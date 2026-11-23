# 🧪 Laravel Test Project (Sandbox)

Welcome to **test-project** — a dedicated Laravel 13 sandbox environment built for experimenting, studying complex theories, troubleshooting bugs, and testing packages without cluttering or risking production codebases.

## 🎯 Purpose

Trying out unfamiliar packages, authentication flows, complex database queries, or experimental architecture inside a real project often leaves behind stray files, messy migrations, and unnecessary dependencies.

This repository serves as a **zero-consequence playground** to:

* **Test Theories & Concepts:** Explore Eloquent relationships, queues, events, service containers, and design patterns.
* **Experiment with Auth & Access Control:** Compare and test token flows (`laravel/sanctum` vs. `php-open-source-saver/jwt-auth`) alongside role/permission setups (`spatie/laravel-permission`).
* **Prototype & Document APIs:** Generate and verify OpenAPI/Swagger specs using `darkaonline/l5-swagger`.
* **Reproduce & Solve Problems:** Isolate tricky bugs or edge cases in a clean environment before applying fixes to live applications.

## 📚 Documentation

* [**Environment Setup (`SETUP.md`)**](SETUP.md) — Prerequisites, one-command Composer setup, dev server scripts, and sandbox reset commands.
* [**Configurations & Packages (`CONFIGURATIONS.md`)**](CONFIGURATIONS.md) — Package-specific setup notes for Sanctum, JWT Auth, Spatie Permission, L5-Swagger, and custom `.env` tweaks.

## 🛠️ Current Stack (from `composer.json`)

* **Core:** PHP `^8.3` | Laravel Framework `^13.17`
* **Authentication & Authorization:** `laravel/sanctum` (`^4.0`), `php-open-source-saver/jwt-auth` (`^2.10`), `spatie/laravel-permission` (`^8.5`)
* **API Documentation:** `darkaonline/l5-swagger` (`^11.2`)
* **Dev & Debugging Tools:** `laravel/pail` (`^1.2.5`), `laravel/pao` (`^1.0.6`), `laravel/pint` (`^1.27`), `laravel/tinker` (`^3.0`), `phpunit/phpunit` (`^12.5.12`)

## 🚀 Quick Start

Bootstrap the project in one step using the built-in Composer script:

```bash
composer setup
```

Start the development environment:

```bash
composer dev
```

*(See [SETUP.md](SETUP.md) for manual steps, SQLite configuration, and cleanup commands.)*

## 📝 Experiment Log

Keep a quick running log of what you are testing or have already explored:

* [ ] *Current Experiment:* (e.g., Comparing Sanctum SPA/token auth vs. JWT guards)
* [ ] *Planned:* (e.g., Role/permission caching behavior with `spatie/laravel-permission`)
* [x] *Completed:* Initial Laravel 13 sandbox setup with Auth & Swagger packages

> **Note:** Code inside this repository is experimental and not intended for production deployment.