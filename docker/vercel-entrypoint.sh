#!/bin/sh
set -eu

# Vercel containers are stateless. Runtime secrets/configuration must come
# from Vercel Environment Variables, not from the image.
if [ -z "${APP_KEY:-}" ]; then
    echo "ERROR: APP_KEY is missing. Add APP_KEY to Vercel Environment Variables." >&2
    exit 1
fi

mkdir -p /app/storage/framework/cache \
         /app/storage/framework/sessions \
         /app/storage/framework/views \
         /app/storage/logs \
         /app/bootstrap/cache

exec frankenphp run --config /etc/frankenphp/Caddyfile
