# 🛠️ Configurations & Package Notes (`test-project`)

This file documents the package-specific configurations, published assets, and environment variables used in this sandbox so you don't have to re-configure them from scratch between experiments.

---

## 🔑 1. Laravel Sanctum (`laravel/sanctum ^4.0`)

Used for testing SPA cookie-based authentication and simple API token issuance.

* **Publish config & migrations (if not yet published):**
  ```bash
  php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
  php artisan migrate
  ```
* **Key Notes:**
  * Ensure your `User` model uses the `Laravel\Sanctum\HasApiTokens` trait.
  * For SPA testing on localhost, configure `SANCTUM_STATEFUL_DOMAINS` and `SESSION_DOMAIN` in `.env`.
  * Config file location: `config/sanctum.php`.

---

## 🎟️ 2. JWT Authentication (`php-open-source-saver/jwt-auth ^2.10`)

Used for testing stateless JSON Web Token authentication, refresh tokens, and custom claims.

* **Publish config & generate JWT secret:**
  ```bash
  php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
  php artisan jwt:secret
  ```
* **Key Notes:**
  * Adds `JWT_SECRET=...` to your `.env` file.
  * Your `User` model must implement `PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject` (`getJWTIdentifier()` and `getJWTCustomClaims()`).
  * To use JWT on the `api` guard, update `config/auth.php`:
    ```php
    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],
    ```
  * Config file location: `config/jwt.php`.

---

## 🛡️ 3. Roles & Permissions (`spatie/laravel-permission ^8.5`)

Used for testing RBAC (Role-Based Access Control), guards, teams, and permission caching.

* **Publish config & migrations:**
  ```bash
  php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
  php artisan migrate
  ```
* **Key Notes:**
  * Add the `Spatie\Permission\Traits\HasRoles` trait to your `User` model.
  * When testing with multiple guards (`web`, `api`, `jwt`), always verify `guard_name` matches the active auth guard.
  * Clear cached permissions if manual DB edits don't reflect immediately:
    ```bash
    php artisan permission:cache-reset
    ```
  * Config file location: `config/permission.php`.

---

## 📖 4. OpenAPI / Swagger Docs (`darkaonline/l5-swagger ^11.2`)

Used for testing OpenAPI annotations/attributes and interactive API documentation.

* **Publish config & views:**
  ```bash
  php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
  ```
* **Generate Swagger JSON/YAML documentation:**
  ```bash
  php artisan l5-swagger:generate
  ```
* **Key Notes:**
  * Default UI endpoint: `/api/documentation` (configurable in `config/l5-swagger.php`).
  * Set `L5_SWAGGER_GENERATE_ALWAYS=true` in `.env` during active API experiments so docs regenerate automatically on page reload.
  * For JWT or Sanctum Bearer testing inside Swagger UI, configure `securityDefinitions` / `OpenApi` security schemes in `config/l5-swagger.php` or your base controller attributes.

---

## 🧪 5. Custom `.env` Variables Reference

Keep track of custom environment flags you toggle during experiments:

```env
# Database (SQLite default for fast sandbox resets)
DB_CONNECTION=sqlite

# JWT Auth
JWT_SECRET=your_generated_secret_here
JWT_TTL=60

# L5-Swagger
L5_SWAGGER_GENERATE_ALWAYS=true

# Debugging / Logs
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

---

## 📌 Active Experiment Config Notes

*(Jot down temporary tweaks here—e.g., custom middleware aliases in `bootstrap/app.php`, queue driver changes, or custom service container bindings—so you remember what you modified.)*

* **`bootstrap/app.php`:** (No custom overrides logged yet)
* **`config/auth.php`:** (Document active default guard here when switching between Sanctum and JWT)