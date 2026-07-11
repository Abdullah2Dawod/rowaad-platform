#!/usr/bin/env bash
# ═══════════════════════════════════════════════════════════════════════
# Deploy script — run this ON THE HOSTINGER SERVER via SSH after pushing
# updates to git. Also called by GitHub Actions.
#
# Usage:
#   cd ~/domains/rowaad.org/laravel
#   ./deployment/deploy.sh
# ═══════════════════════════════════════════════════════════════════════
set -e

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_DIR"

echo "═══ Rowaad Deploy ═══════════════════════════════════════════"
echo "📁 App directory: $APP_DIR"
echo "🕐 Started at: $(date)"
echo ""

# 1. Enter maintenance mode (users see a friendly page during deploy)
echo "🔧 Enabling maintenance mode..."
php artisan down --render="errors::503" --retry=15 || true

# 2. Pull latest code
echo "📥 Pulling latest code from git..."
git fetch --all
git reset --hard origin/main
git pull origin main --ff-only

# 3. Install PHP dependencies (production only, optimized)
echo "📦 Installing composer dependencies..."
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 4. Run migrations (non-interactive)
echo "🗄️  Running database migrations..."
php artisan migrate --force

# 5. Publish storage symlink (idempotent)
echo "🔗 Ensuring storage symlink..."
php artisan storage:link || true

# 6. Rebuild optimized caches
echo "⚡ Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:cache-components || true

# 7. Clear old temp caches
echo "🧹 Clearing session/temp..."
php artisan optimize:clear >/dev/null 2>&1 || true
php artisan config:cache

# 8. Set correct permissions
echo "🔐 Fixing permissions..."
chmod -R 775 storage bootstrap/cache

# 9. Exit maintenance mode
echo "✅ Bringing app back up..."
php artisan up

echo ""
echo "═══ Deploy finished at $(date) ══════════════════════════════"
