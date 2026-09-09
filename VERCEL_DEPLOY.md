# Deploy POS Hady to Vercel

This project uses Vercel's container deployment with `Dockerfile.vercel`. Vercel builds the image and serves the HTTP server on the `PORT` environment variable.

## 1. Generate the Laravel APP_KEY

Run locally from the project folder:

```bash
php artisan key:generate --show
```

Copy the complete `base64:...` value into Vercel as `APP_KEY`. Do not commit the key to Git.

## 2. Configure the persistent database

Vercel containers are stateless. The POS database must be a managed/external MySQL or MariaDB database. Add these variables in Vercel:

```text
DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

Do not use `127.0.0.1` or `localhost` for the production database.

## 3. Recommended runtime variables

```text
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pos-hady.vercel.app
SESSION_DRIVER=array
CACHE_STORE=array
QUEUE_CONNECTION=sync
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

## 4. Run migrations once

After the production database variables are configured, run migrations against that database from a trusted environment:

```bash
php artisan migrate --force
```

If the database is empty and you intentionally want the demo data, run:

```bash
php artisan migrate --seed --force
```

Do not run `migrate --seed` on every Vercel container startup.

## 5. Deploy

Commit and push the project to the connected `main` branch. Vercel will build `Dockerfile.vercel`.

The `/health` endpoint returns a small JSON response without querying the database:

```text
https://pos-hady.vercel.app/health
```

The root URL `/` redirects to `/login`.

## File uploads

The current `public` filesystem is local to the container. Vercel containers do not provide persistent local storage. For permanent product/store images, use object/blob storage and update the filesystem disk accordingly.
