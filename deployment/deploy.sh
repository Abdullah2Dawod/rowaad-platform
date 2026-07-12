#!/usr/bin/env bash
# ═══════════════════════════════════════════════════════════════════════
# Rowaad Deploy — Hostinger-compatible
# Runs on the server via GitHub Actions after every `git push` to main.
#
# Hostinger constraints:
#   • proc_open disabled → some artisan commands skipped (storage:link, up/down)
#   • composer post-install scripts skipped
#   • PHP 8.4 CLI available (adjust path if it changes)
# ═══════════════════════════════════════════════════════════════════════
set -e

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PUBLIC_HTML="$(cd "$APP_DIR/../public_html" && pwd)"
cd "$APP_DIR"

# Force PHP 8.4 (Hostinger CLI default may be 8.3)
if [ -x /opt/alt/php84/usr/bin/php ]; then
    export PATH="/opt/alt/php84/usr/bin:$PATH"
    alias php='/opt/alt/php84/usr/bin/php'
fi

echo "═══ Rowaad Deploy ═══════════════════════════════════════════"
echo "📁 App:         $APP_DIR"
echo "🌐 public_html: $PUBLIC_HTML"
echo "🕐 Started:     $(date)"
echo "🐘 PHP version: $(php -v | head -1)"
echo ""

# 1. Pull latest code
echo "📥 Pulling latest code from GitHub..."
git fetch --all
git reset --hard origin/main

# 2. Install PHP dependencies (skip scripts — Hostinger has proc_open disabled)
echo "📦 Installing composer dependencies..."
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

# 3. Regenerate autoload (needed because we skipped scripts)
echo "🔄 Regenerating autoload..."
composer dump-autoload -o --no-scripts

# 4. Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# 5. Ensure storage symlinks exist (manual — storage:link uses exec which is disabled)
echo "🔗 Ensuring storage symlinks..."
if [ ! -L "$APP_DIR/public/storage" ]; then
    ln -sf "$APP_DIR/storage/app/public" "$APP_DIR/public/storage"
fi
if [ ! -L "$PUBLIC_HTML/storage" ]; then
    ln -sf "$APP_DIR/storage/app/public" "$PUBLIC_HTML/storage"
fi

# 6. Copy built frontend assets to public_html (Vite outputs to laravel/public/build)
echo "🎨 Copying build assets to public_html..."
if [ -d "$APP_DIR/public/build" ]; then
    rm -rf "$PUBLIC_HTML/build"
    cp -r "$APP_DIR/public/build" "$PUBLIC_HTML/build"
fi

# 7. Copy static assets (images, favicon, robots) that changed
echo "🖼️  Syncing static assets..."
cp -rf "$APP_DIR/public/images" "$PUBLIC_HTML/" 2>/dev/null || true
cp -f  "$APP_DIR/public/favicon.ico" "$PUBLIC_HTML/" 2>/dev/null || true
cp -f  "$APP_DIR/public/robots.txt"  "$PUBLIC_HTML/" 2>/dev/null || true

# 8. Ensure index.php and .htaccess in public_html are current
echo "⚙️  Updating public_html entry files..."
cp -f "$APP_DIR/deployment/hostinger-index.php" "$PUBLIC_HTML/index.php"
cp -f "$APP_DIR/deployment/hostinger-htaccess"  "$PUBLIC_HTML/.htaccess"

# 9. Rebuild optimized caches
echo "⚡ Rebuilding caches..."
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 10. Publish Filament assets (may fail silently — non-critical)
php artisan filament:assets 2>/dev/null || true

# 11. Fix permissions
echo "🔐 Fixing permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "═══ ✅ Deploy finished at $(date) ══════════════════════════════"
