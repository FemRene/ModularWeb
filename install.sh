#!/bin/bash

PROJECT_DIR="/var/www/MW"
REPO_URL="https://github.com/FemRene/ModularWebsite.git"

echo "🚀 Starting Laravel project installation..."

# Step 0: Ensure project directory exists
if [ ! -d "$PROJECT_DIR" ]; then
    echo "📁 Creating project directory at $PROJECT_DIR..."
    sudo mkdir -p "$PROJECT_DIR"
    sudo chown -R $USER:www-data "$PROJECT_DIR"
fi

cd "$PROJECT_DIR" || exit

# Step 1: Clone the repository
if [ ! -d ".git" ]; then
    echo "🔄 Cloning repository..."
    git clone "$REPO_URL" . || { echo "❌ Git clone failed."; exit 1; }
else
    echo "✅ Repository already cloned."
fi

# Step 2: Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Step 3: Create .env if it doesn't exist
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    php artisan key:generate
else
    echo "✅ .env already exists."
fi

# Step 4: Set permissions
echo "🔐 Setting permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Step 5: Run migrations
echo "🧱 Running database migrations..."
php artisan migrate --force

# Step 7: Cache config, routes, views
echo "📦 Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 8: Storage link
php artisan storage:link

echo "✅ Laravel project setup completed at $PROJECT_DIR!"
echo
echo "📄 NEXT STEP: Caddy Setup Instructions"
echo "----------------------------------------"
echo "1. Edit your Caddyfile (usually at /etc/caddy/Caddyfile or /usr/local/etc/Caddyfile):"
echo
echo "   yourdomain.com {"
echo "       root * $PROJECT_DIR/public"
echo "       php_fastcgi unix//run/php/php8.2-fpm.sock"
echo "       file_server"
echo "   }"
echo
echo "2. Reload Caddy:"
echo "   sudo systemctl reload caddy"
echo
echo "3. Make sure DNS for 'yourdomain.com' points to this server."
echo "4. Done! 🎉 Your Laravel site should be live."
