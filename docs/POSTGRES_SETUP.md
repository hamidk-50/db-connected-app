# PostgreSQL Setup

## 1) Update `.env`

Use these values in `.env`:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_connected_app
DB_USERNAME=app_user
DB_PASSWORD=change_me
```

Then run:

```bash
php artisan config:clear
php artisan migrate:fresh --seed
```

## 2) Suggested Database Roles

- Create one app role with only required privileges for the app schema.
- Keep superuser/admin credentials out of `.env`.
- Use separate DB users for app runtime and maintenance tasks.

## 3) Security Features to Add Next

- Row-level security (RLS) if you introduce tenant separation.
- Audit triggers for sensitive table changes.
- Strict grants (least privilege) and periodic credential rotation.
- TLS for DB connections in hosted environments.
