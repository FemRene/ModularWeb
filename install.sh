#!/bin/bash

set -e

PROJECT_DIR="/var/www/MW"
REPO_URL="https://github.com/FemRene/ModularWeb.git"

echo "🚀 Starting Laravel project installation..."

# Step 0: Check & install required packages
echo "🔍 Checking for required packages..."

REQUIRED_PKG=("git" "composer" "php" "php-cli" "php-mbstring" "php-xml" "php-bcmath" "php-curl" "php-mysql" "php-tokenizer" "php-fileinfo" "php-fpm" "php-zip" "php-common")

for pkg in "${REQUIRED_PKG[@]}"; do
    if ! dpkg -s "$pkg" &>/dev/null; then
        echo "📦 Installing missing package: $pkg"
        sudo apt-get update
        sudo apt-get install -y "$pkg"
    else
        echo "✅ $pkg is already installed."
    fi
done

# Ensure PHP version is at least 8.1
PHP_VERSION=$(php -r "echo PHP_VERSION;")
REQUIRED_VERSION="8.1"

if dpkg --compare-versions "$PHP_VERSION" "lt" "$REQUIRED_VERSION"; then
    echo "❌ PHP $REQUIRED_VERSION or higher is required. You have $PHP_VERSION."
    exit 1
fi

# Step 1: Ensure project directory exists
if [ ! -d "$PROJECT_DIR" ]; then
    echo "📁 Creating project directory at $PROJECT_DIR..."
    sudo mkdir -p "$PROJECT_DIR"
    sudo chown -R "$USER:www-data" "$PROJECT_DIR"
fi

cd "$PROJECT_DIR" || exit

# Step 2: Clone the repository
if [ ! -d ".git" ]; then
    echo "🔄 Cloning repository..."
    git clone "$REPO_URL" . || { echo "❌ Git clone failed."; exit 1; }
else
    echo "✅ Repository already cloned."
fi


# Step 3: Prompt for database details and configure .env
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file..."

    read -p "📛 Enter database name: " DB_NAME
    read -p "👤 Enter database username: " DB_USER
    read -s -p "🔑 Enter database password: " DB_PASS
    echo
    read -p "🖥️  Enter database host (default: 127.0.0.1): " DB_HOST
    DB_HOST=${DB_HOST:-127.0.0.1}

    cp .env.example .env

    sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|" .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|" .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|" .env
    sed -i "s|DB_HOST=.*|DB_HOST=${DB_HOST}|" .env

    echo "📦 Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
    php artisan key:generate
else
    echo "✅ .env already exists."
fi

# Step 5: Set permissions
echo "🔐 Setting permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Step 6: Run database migrations
echo "🧱 Running database migrations..."
php artisan migrate --force

# Step 7: Cache config, routes, and views
echo "📦 Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 8: Create storage symlink
php artisan storage:link

# Step 9: Done message and Caddy instructions
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
