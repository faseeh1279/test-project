# Deployment & Server Setup

Everything needed to provision a production server and deploy {{Project Name}}. Written for **Ubuntu 24.04 LTS**; adjust for other distros.

## 1. Server Requirements

| Resource | Minimum        | Recommended     |
| -------- | -------------- | --------------- |
| CPU      | 1 vCPU         | 2+ vCPU         |
| RAM      | 2 GB           | 4 GB+           |
| Disk     | 20 GB SSD      | 40 GB+ SSD      |
| OS       | Ubuntu 22.04+  | Ubuntu 24.04 LTS |

**Software checklist**

- [ ] Nginx
- [ ] PHP 8.3 + FPM + extensions
- [ ] Composer 2
- [ ] Node.js 20 (only if building assets on the server)
- [ ] MySQL 8 / PostgreSQL
- [ ] Redis
- [ ] Supervisor (queue workers)
- [ ] Certbot (SSL)
- [ ] Git
- [ ] UFW firewall + Fail2ban

## 2. Initial Server Hardening

```bash
# As root
apt update && apt upgrade -y

adduser deploy
usermod -aG sudo deploy

# Copy your SSH key to the deploy user, then disable root/password login
rsync --archive --chown=deploy:deploy ~/.ssh /home/deploy
nano /etc/ssh/sshd_config
#   PermitRootLogin no
#   PasswordAuthentication no
systemctl restart ssh

# Firewall
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw enable

apt install -y fail2ban
```

Log in as `deploy` for everything below.

## 3. Install Software Stack

### PHP 8.3 and extensions

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl \
  php8.3-redis php8.3-opcache unzip git
```

### Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Nginx

```bash
sudo apt install -y nginx
```

### MySQL

```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation

sudo mysql <<'SQL'
CREATE DATABASE {{db_name}} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER '{{db_user}}'@'localhost' IDENTIFIED BY '{{strong_password}}';
GRANT ALL PRIVILEGES ON {{db_name}}.* TO '{{db_user}}'@'localhost';
FLUSH PRIVILEGES;
SQL
```

### Redis

```bash
sudo apt install -y redis-server
sudo systemctl enable redis-server
```

### Node.js (only if building assets on the server)

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### Supervisor and Certbot

```bash
sudo apt install -y supervisor certbot python3-certbot-nginx
```

## 4. Application Setup

```bash
sudo mkdir -p /var/www/{{project-folder}}
sudo chown deploy:www-data /var/www/{{project-folder}}
cd /var/www/{{project-folder}}

git clone {{repo-url}} .
git checkout main

composer install --no-dev --optimize-autoloader
npm ci && npm run build        # or upload built assets from CI

cp .env.example .env
php artisan key:generate
nano .env                      # fill in production values (see section 5)

php artisan migrate --force
php artisan storage:link
```

### Permissions

```bash
sudo chown -R deploy:www-data /var/www/{{project-folder}}
sudo find /var/www/{{project-folder}} -type f -exec chmod 644 {} \;
sudo find /var/www/{{project-folder}} -type d -exec chmod 755 {} \;
sudo chmod -R 775 storage bootstrap/cache
```

## 5. Production `.env`

```env
APP_NAME="{{Project Name}}"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://{{your-domain.com}}

LOG_CHANNEL=daily
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE={{db_name}}
DB_USERNAME={{db_user}}
DB_PASSWORD={{strong_password}}

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_MAILER=smtp
MAIL_HOST={{smtp_host}}
MAIL_PORT=587
MAIL_USERNAME={{user}}
MAIL_PASSWORD={{password}}
MAIL_FROM_ADDRESS=no-reply@{{your-domain.com}}
```

`APP_DEBUG` must be `false` in production. Never commit this file.

## 6. Nginx Configuration

`/etc/nginx/sites-available/{{project-folder}}`:

```nginx
server {
    listen 80;
    server_name {{your-domain.com}} www.{{your-domain.com}};
    root /var/www/{{project-folder}}/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;
    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/{{project-folder}} /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### SSL

```bash
sudo certbot --nginx -d {{your-domain.com}} -d www.{{your-domain.com}}
```

Certbot installs auto-renewal. Verify with `sudo certbot renew --dry-run`.

## 7. Queue Workers (Supervisor)

`/etc/supervisor/conf.d/{{project-folder}}-worker.conf`:

```ini
[program:{{project-folder}}-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/{{project-folder}}/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=deploy
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/{{project-folder}}/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start {{project-folder}}-worker:*
```

## 8. Scheduler (Cron)

```bash
crontab -e
```

```
* * * * * cd /var/www/{{project-folder}} && php artisan schedule:run >> /dev/null 2>&1
```

## 9. PHP Tuning (optional)

In `/etc/php/8.3/fpm/php.ini`:

```ini
upload_max_filesize = 20M
post_max_size = 20M
memory_limit = 256M
opcache.enable = 1
opcache.validate_timestamps = 0
```

Restart with `sudo systemctl restart php8.3-fpm`. If you set `validate_timestamps = 0`, restart PHP-FPM on every deploy.

## 10. Deploying Updates

Save as `deploy.sh` in the project root and run `./deploy.sh`:

```bash
#!/usr/bin/env bash
set -e

cd /var/www/{{project-folder}}

php artisan down --retry=60

git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build

php artisan migrate --force
php artisan optimize:clear
php artisan optimize          # caches config, routes, views, events

php artisan queue:restart
sudo systemctl reload php8.3-fpm

php artisan up
echo "Deployed successfully"
```

## 11. Rollback

```bash
php artisan down
git log --oneline -5                 # find the last good commit
git checkout <commit-hash>
composer install --no-dev --optimize-autoloader
php artisan migrate:rollback --step=1    # only if the last deploy ran migrations
php artisan optimize
php artisan queue:restart
php artisan up
```

Always take a database backup before deploying migrations.

## 12. Backups

```bash
# Daily DB backup (add to cron)
mysqldump -u {{db_user}} -p'{{password}}' {{db_name}} | gzip > /var/backups/{{db_name}}_$(date +\%F).sql.gz
```

Also back up `storage/app` and the `.env` file. Store copies off-server (S3, Backblaze, etc.) and test restores periodically.

## 13. Monitoring & Logs

| What          | Where                                      |
| ------------- | ------------------------------------------ |
| Laravel logs  | `storage/logs/laravel-*.log`               |
| Nginx errors  | `/var/log/nginx/error.log`                 |
| PHP-FPM       | `/var/log/php8.3-fpm.log`                  |
| Queue workers | `storage/logs/worker.log`                  |

Consider: Sentry / Flare for error tracking, UptimeRobot for uptime checks.

## 14. Go-Live Checklist

- [ ] `APP_ENV=production`, `APP_DEBUG=false`
- [ ] Unique `APP_KEY` generated on the server
- [ ] HTTPS working and HTTP redirects to HTTPS
- [ ] `php artisan optimize` run
- [ ] Queue workers running (`supervisorctl status`)
- [ ] Cron scheduler configured
- [ ] Mail sending tested
- [ ] Backups scheduled and restore tested
- [ ] Firewall enabled, root SSH login disabled
- [ ] Error monitoring connected
- [ ] `.env` and `.git` not publicly accessible

## 15. Troubleshooting

| Problem | Fix |
| ------- | --- |
| 502 Bad Gateway | Check PHP-FPM: `sudo systemctl status php8.3-fpm` and the socket path in Nginx |
| 500 error with no details | Check `storage/logs/laravel.log` and permissions on `storage/` |
| Config changes ignored | `php artisan config:clear && php artisan optimize` |
| Jobs not processing | `sudo supervisorctl status`, then `php artisan queue:restart` |
| Assets 404 | Re-run `npm run build`, confirm `public/build` exists |
| Uploads fail | Check `client_max_body_size` (Nginx) and `upload_max_filesize` (PHP) |

## 16. Server Info

| Item | Value |
| ---- | ----- |
| Provider | {{DigitalOcean / AWS / Hetzner}} |
| IP address | {{x.x.x.x}} |
| SSH | `ssh deploy@{{your-domain.com}}` |
| Access managed by | {{name}} |
