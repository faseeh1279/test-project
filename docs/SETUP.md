# ⚙️ Environment Setup (`test-project`)

This guide covers everything needed to install, run, test, and reset your local Laravel 13 sandbox environment.

## 📋 Prerequisites

Ensure the following are installed on your machine:

* **PHP:** `^8.3` (with standard Laravel extensions: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `sqlite3`/`pdo_mysql`)
* **Composer:** `2.x`
* **Node.js & NPM:** For Vite frontend asset compilation
* **Database:** SQLite (default/quickest for sandbox testing) or MySQL/PostgreSQL

---

## ⚡ Automated Setup (Recommended)

Your `composer.json` includes a custom `setup` script that handles dependencies, `.env` creation, app key generation, migrations, and asset builds automatically:

```bash
git clone <your-repo-url> test-project
cd test-project
composer setup
```

> **Note:** If you are using SQLite and `database/database.sqlite` does not exist yet before running `composer setup`, create it first:
> ```bash
> touch database/database.sqlite
> ```

---

## 🔧 Manual Setup (Step-by-Step)

If you prefer setting up each step manually or troubleshooting an issue:

1. **Install PHP dependencies:**
   ```bash
   composer install
   ```

2. **Create environment file & generate key:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure database in `.env`:**
   For zero-config local testing with SQLite:
   ```env
   DB_CONNECTION=sqlite
   ```
   *(Or configure `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` for MySQL/PostgreSQL.)*

4. **Run database migrations:**
   ```bash
   php artisan migrate
   ```

5. **Install & build frontend assets:**
   ```bash
   npm install
   npm run build
   ```

---

## 🖥️ Running & Developing

Use the custom scripts defined in `composer.json` or standard Artisan commands:

* **Start the dev environment:**
  ```bash
  composer dev
  ```
  *(Runs `php artisan dev` as configured in `composer.json`.)*

* **Tail real-time application logs (`laravel/pail`):**
  ```bash
  php artisan pail
  ```

* **Run the test suite (`phpunit/phpunit`):**
  ```bash
  composer test
  ```

* **Format code style (`laravel/pint`):**
  ```bash
  ./vendor/bin/pint
  ```

---

## 🧹 Resetting & Cleaning Up After Experiments

When an experiment gets messy and you want a clean slate:

* **Wipe database and re-run all migrations & seeders:**
  ```bash
  php artisan migrate:fresh --seed
  ```

* **Clear all compiled views, routes, config, and cache:**
  ```bash
  php artisan optimize:clear
  ```

* **Reset all uncommitted code changes and delete untracked files:**
  ```bash
  git reset --hard && git clean -fd
  ```