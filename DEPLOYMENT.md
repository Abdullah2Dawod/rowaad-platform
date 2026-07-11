# 🚀 Deploying Rowaad Platform to Hostinger

Full guide for pushing this Laravel + Vue + Filament app to a Hostinger hosting account, with automatic deploys on every `git push`.

---

## 📋 Prerequisites

Before starting, make sure you have:

- **Hostinger plan** with SSH access (Business, Cloud, or VPS — SSH is required for Composer)
- **PHP 8.3+** (Hostinger's default is 8.2; enable 8.3/8.4 from hPanel → PHP Configuration)
- **A MySQL database** (create in hPanel → Databases)
- **A GitHub / GitLab repo** (we'll create this)
- **Node.js** installed locally for building assets

---

## 🎯 One-time setup (~30 min)

### Step 1 — Push to GitHub

On your local machine, from the project root:

```bash
cd "D:/Laravel-Projects/rowaad-platform"

# Initialize git if not already
git init
git add .
git commit -m "Initial commit — Rowaad Platform"

# Create a NEW repo on github.com (e.g. rowaad-platform, PRIVATE)
# then link it:
git remote add origin https://github.com/YOUR_USERNAME/rowaad-platform.git
git branch -M main
git push -u origin main
```

### Step 2 — Set up MySQL on Hostinger

1. Log into **hPanel** → **Databases** → **Management**
2. Click **Create Database**:
   - Database name: `rowaad`
   - Username: `rowaad` (a random `u000000000_` prefix is added — copy the full name)
   - Password: pick a strong one — **copy it, you'll need it in Step 4**
3. Note the full DB name, username, and password.

### Step 3 — Get SSH access

1. hPanel → **Advanced** → **SSH Access** → **Enable**
2. Note down:
   - SSH IP address
   - SSH port (usually 65002)
   - SSH username
3. Test locally:
   ```bash
   ssh -p 65002 u000000000@YOUR_IP
   ```

### Step 4 — Upload the app

**Option A: git clone (recommended)**

Via SSH on Hostinger:

```bash
cd ~
mkdir -p domains/rowaad.org
cd domains/rowaad.org

# If public_html doesn't exist yet, it's created automatically by Hostinger
# when you add the domain. Remove any placeholder files:
rm -rf public_html/*

# Clone the app (use a personal access token for private repos)
git clone https://github.com/YOUR_USERNAME/rowaad-platform.git laravel
cd laravel

# Composer install
composer install --no-dev --prefer-dist --optimize-autoloader

# Environment
cp .env.hostinger.example .env
nano .env    # Fill in DB_*, MAIL_*, APP_URL, APP_KEY

# Generate app key
php artisan key:generate

# Migrate + storage link
php artisan migrate --force
php artisan storage:link

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Option B: SFTP upload**

If your plan doesn't have SSH, upload the project via FileZilla / Hostinger's file manager (excluding `node_modules`, `vendor`, `.env`). Then use hPanel → **Terminal** for the commands above.

### Step 5 — Wire public_html to Laravel

Copy the Hostinger-specific entry files into `public_html`:

```bash
# On the server:
cd ~/domains/rowaad.org

# Copy the customized index.php
cp laravel/deployment/hostinger-index.php public_html/index.php

# Copy .htaccess
cp laravel/deployment/hostinger-htaccess public_html/.htaccess

# Copy the built assets, images, favicon, robots.txt, etc.
cp -r laravel/public/* public_html/
# (Don't overwrite the index.php you just placed!)
cp laravel/deployment/hostinger-index.php public_html/index.php
```

**Or symlink** (cleaner, needs SSH):
```bash
# Rename the auto-created public_html folder
mv public_html public_html.backup
ln -s laravel/public public_html
```

### Step 6 — Build assets locally + upload

Vite assets should be built locally then uploaded:

```bash
# On your local machine:
npm run build
```

This creates `public/build/`. Upload the entire `public/build/` folder into `public_html/build/` on Hostinger.

Or run `npm run build` on the server if Node is available (Business plans usually have it).

---

## 🤖 Automatic deploys via GitHub Actions

Now the fun part — every `git push` triggers a live deploy.

### Step 7 — Add repository secrets

On GitHub → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**:

| Secret name          | Value                                                |
|----------------------|------------------------------------------------------|
| `HOSTINGER_HOST`     | Your SSH IP (from Step 3)                            |
| `HOSTINGER_USER`     | Your SSH username (e.g. `u000000000`)               |
| `HOSTINGER_PORT`     | `65002` (or whatever Hostinger shows)               |
| `HOSTINGER_SSH_KEY`  | The **private key** (see Step 8)                    |
| `HOSTINGER_APP_PATH` | `/home/u000000000/domains/rowaad.org/laravel`       |

### Step 8 — Generate SSH deploy key

On your local machine:

```bash
ssh-keygen -t ed25519 -f rowaad_deploy -N ""
```

This creates two files: `rowaad_deploy` (private) and `rowaad_deploy.pub` (public).

- **Paste the contents of `rowaad_deploy.pub`** into hPanel → SSH → **Manage SSH Keys** → **Add Key**.
- **Paste the contents of `rowaad_deploy`** (private key) into the GitHub secret `HOSTINGER_SSH_KEY`.

### Step 9 — Test the workflow

Make a tiny change, commit, and push:

```bash
git commit --allow-empty -m "test deploy"
git push
```

Go to your GitHub repo → **Actions** tab → watch the deploy run in real-time.

If it succeeds, your changes are live at https://rowaad.org 🎉

---

## 🔧 Ongoing workflow

From now on, every code change follows this loop:

1. Edit locally.
2. Test with `php artisan serve` + `npm run dev`.
3. Commit and push:
   ```bash
   git add -A
   git commit -m "your change"
   git push
   ```
4. GitHub Actions builds and deploys automatically (~2 min).
5. Visit https://rowaad.org — your change is live.

---

## 🆘 Troubleshooting

### "500 Internal Server Error"

- Check the Laravel log: `~/domains/rowaad.org/laravel/storage/logs/laravel.log`
- Verify `.env` has correct DB credentials.
- Ensure `storage/` and `bootstrap/cache/` are `chmod 775`.
- Check PHP version matches (hPanel → PHP Configuration → 8.3 or 8.4).

### "Vite manifest not found"

- Assets weren't built. Run `npm run build` locally and upload `public/build/` to `public_html/build/`.

### "Class not found"

- Composer autoload cache is stale:
  ```bash
  composer dump-autoload -o
  php artisan optimize:clear
  php artisan config:cache
  ```

### Storage images 404

- Storage symlink is missing:
  ```bash
  cd ~/domains/rowaad.org/laravel
  php artisan storage:link
  # If symlink fails on shared hosting, manually copy:
  cp -r storage/app/public/* ~/domains/rowaad.org/public_html/storage/
  ```

### Session issues after deploy

- Sessions stored on disk get invalidated. Users may need to log in again — this is normal.

---

## 📞 Support

If a deploy fails or something is unclear, share:
1. The GitHub Actions log
2. `storage/logs/laravel.log` (last 100 lines)

Good luck! 🚀
