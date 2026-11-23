# Development Guide

Everything a new developer needs to go from a fresh machine to a running local app. Target time: **under 30 minutes**.

## 1. Prerequisites

| Tool       | Version | Check with          |
| ---------- | ------- | ------------------- |
| PHP        | 8.3+    | `php -v`            |
| Composer   | 2.x     | `composer -V`       |
| Node.js    | 20 LTS  | `node -v`           |
| npm        | 10+     | `npm -v`            |
| MySQL      | 8.0+    | `mysql --version`   |
| Redis      | 7+ (optional) | `redis-cli ping` |
| Git        | any     | `git --version`     |

**Required PHP extensions:** `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `zip`

Check installed extensions with `php -m`.

> **Tip:** On Windows use Laravel Herd or WSL2. On macOS use Herd or Valet. On Linux install via your package manager or use Laravel Sail (Docker).

## 2. Get the Code

```bash
git clone {{repo-url}}
cd {{project-folder}}
git checkout develop      # default working branch
```

## 3. Install Dependencies

```bash
composer install
npm install
```

## 4. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set at minimum:

```env
APP_NAME="{{Project Name}}"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE={{db_name}}
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
MAIL_MAILER=log
```

### Environment variables explained

| Variable            | What it does                                   | Where to get it |
| ------------------- | ---------------------------------------------- | --------------- |
| `{{THIRD_PARTY_KEY}}` | Used for {{service}}                         | Ask {{person}} / team vault |
| `{{ANOTHER_KEY}}`   | Used for {{service}}                           | {{where}}       |

Never commit `.env`. Share secrets through the team password manager.

## 5. Database Setup

```bash
# Create the database first
mysql -u root -e "CREATE DATABASE {{db_name}} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

php artisan migrate --seed
```

Seeded accounts:

| Role  | Email               | Password   |
| ----- | ------------------- | ---------- |
| Admin | admin@example.com   | password   |
| User  | user@example.com    | password   |

## 6. Storage & Permissions

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache     # Linux/macOS
```

## 7. Run the App

```bash
php artisan serve       # backend at http://localhost:8000
npm run dev             # Vite dev server with hot reload
php artisan queue:work  # only if you work with jobs
```

If you see a blank page or missing styles, make sure `npm run dev` is running.

## 8. Verify Your Setup

- [ ] Home page loads at http://localhost:8000
- [ ] You can log in with the seeded admin account
- [ ] `php artisan test` passes
- [ ] `npm run build` completes without errors

## 9. Daily Workflow

### Branching

| Branch        | Purpose                     |
| ------------- | --------------------------- |
| `main`        | Production                  |
| `develop`     | Integration / staging       |
| `feature/xyz` | New work, branched from `develop` |
| `hotfix/xyz`  | Urgent fixes, branched from `main` |

### Commit messages

Use the format `type: short description` (`feat`, `fix`, `refactor`, `docs`, `test`, `chore`).

### Pull requests

1. Branch from `develop`.
2. Run `./vendor/bin/pint` and `php artisan test` before pushing.
3. Open a PR into `develop` and request at least one review.
4. Squash-merge after approval.

## 10. Testing

```bash
php artisan test                      # all tests
php artisan test --filter=UserTest    # single test class
php artisan test --coverage           # needs Xdebug or PCOV
```

Tests use a separate database (see `phpunit.xml`). Add new tests under `tests/Feature` or `tests/Unit`.

## 11. Code Style & Conventions

- Formatter: Laravel Pint (`./vendor/bin/pint`)
- Static analysis: {{Larastan level X / none}}
- Business logic lives in `app/Services`, controllers stay thin
- Use Form Requests for validation
- Use Eloquent API Resources for JSON responses

## 12. Useful Tools

- **Debugging:** Laravel Telescope / Debugbar (local only)
- **Mail testing:** Mailpit / Mailtrap
- **API testing:** Postman collection at `docs/postman.json`

## 13. Troubleshooting

| Problem | Fix |
| ------- | --- |
| `No application encryption key has been specified` | Run `php artisan key:generate` |
| `SQLSTATE[HY000] [1045] Access denied` | Check DB credentials in `.env`, then `php artisan config:clear` |
| Changes to `.env` not applied | `php artisan config:clear` |
| `Vite manifest not found` | Run `npm run dev` or `npm run build` |
| Permission denied on `storage/logs` | `chmod -R 775 storage bootstrap/cache` |
| Class not found after pulling | `composer dump-autoload` |
| Stale routes, views or config | `php artisan optimize:clear` |

## 14. Getting Help

Ask in {{channel}}, or contact {{name}}. When asking, include the error message, what you ran, and your OS/PHP version.
