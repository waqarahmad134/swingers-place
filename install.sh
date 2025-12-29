#!/bin/bash

# ============================================================
# Laravel Deployment Script for DigitalOcean Droplet
# ============================================================
# This script sets up a complete Laravel environment with:
# - Nginx web server
# - PHP 8.2+ with required extensions
# - MySQL database
# - Composer
# - Node.js & npm
# - phpMyAdmin
# - FTP server (vsftpd)
# - Git repository clone/update
# - Automatic deployment (composer install, npm install, build)
# ============================================================

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Clear screen
clear

print_status "Starting Laravel Deployment Script for DigitalOcean..."

# ============================================================
# 1. SYSTEM UPDATE
# ============================================================
echo ""
print_status "Updating system packages..."
sudo apt update
sudo apt upgrade -y
clear

# ============================================================
# 2. GET USER INPUTS
# ============================================================
echo ""
print_status "Gathering configuration information..."

# Get public IP
get_public_ip=$(curl -s ifconfig.me || curl -s ipinfo.io/ip || echo "")
read -p "Server IP address [$get_public_ip]: " server_ip
[[ -z "$server_ip" ]] && server_ip="$get_public_ip"

# Get application details
read -p "Application name (e.g., swingers-place): " app_name
[[ -z "$app_name" ]] && app_name="swingers-place"

read -p "GitHub repository URL [https://github.com/waqarahmad134/swingers-place.git]: " git_repo
[[ -z "$git_repo" ]] && git_repo="https://github.com/waqarahmad134/swingers-place.git"

read -p "Project directory [/var/www/$app_name]: " project_dir
[[ -z "$project_dir" ]] && project_dir="/var/www/$app_name"

# Database configuration
read -p "MySQL root password (leave empty to set later): " mysql_root_password
read -p "Database name [$app_name]: " db_name
[[ -z "$db_name" ]] && db_name="$app_name"
read -p "Database user [$app_name]: " db_user
[[ -z "$db_user" ]] && db_user="$app_name"
read -p "Database password: " db_password

# FTP configuration
read -p "FTP username [$app_name]: " ftp_user
[[ -z "$ftp_user" ]] && ftp_user="$app_name"
read -p "FTP password: " ftp_password

clear

# ============================================================
# 3. INSTALL PHP AND EXTENSIONS
# ============================================================
echo ""
print_status "Installing PHP 8.2 and required extensions..."
sudo apt install -y software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-mysql \
    php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
    php8.2-tokenizer php8.2-json php8.2-intl php8.2-readline

# Verify PHP installation
php -v
clear

# ============================================================
# 4. INSTALL COMPOSER
# ============================================================
echo ""
print_status "Installing Composer..."
if [ ! -f /usr/local/bin/composer ]; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
fi
composer --version
clear

# ============================================================
# 5. INSTALL MYSQL
# ============================================================
echo ""
print_status "Installing MySQL..."
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $mysql_root_password"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $mysql_root_password"
sudo apt install -y mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql

# Create database and user
if [ -n "$mysql_root_password" ] && [ -n "$db_password" ]; then
    print_status "Creating database and user..."
    mysql -u root -p"$mysql_root_password" <<EOF
CREATE DATABASE IF NOT EXISTS \`$db_name\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$db_user'@'localhost' IDENTIFIED BY '$db_password';
GRANT ALL PRIVILEGES ON \`$db_name\`.* TO '$db_user'@'localhost';
FLUSH PRIVILEGES;
EOF
    print_status "Database '$db_name' and user '$db_user' created successfully."
else
    print_warning "Skipping database creation. Please create manually later."
fi
clear

# ============================================================
# 6. INSTALL NGINX
# ============================================================
echo ""
print_status "Installing Nginx..."
sudo apt install -y nginx
sudo systemctl start nginx
sudo systemctl enable nginx
clear

# ============================================================
# 7. INSTALL NODE.JS AND NPM
# ============================================================
echo ""
print_status "Installing Node.js 18.x..."
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
clear

# ============================================================
# 8. INSTALL GIT
# ============================================================
echo ""
print_status "Installing Git..."
sudo apt install -y git
git --version
clear

# ============================================================
# 9. CLONE/UPDATE GIT REPOSITORY
# ============================================================
echo ""
print_status "Setting up project directory..."

# Create project directory
sudo mkdir -p "$project_dir"
sudo chown -R $USER:$USER "$project_dir"

# Clone or update repository
if [ -d "$project_dir/.git" ]; then
    print_status "Repository exists. Pulling latest changes..."
    cd "$project_dir"
    git pull origin main || git pull origin master
else
    print_status "Cloning repository..."
    git clone "$git_repo" "$project_dir"
    cd "$project_dir"
fi

clear

# ============================================================
# 10. INSTALL COMPOSER DEPENDENCIES
# ============================================================
echo ""
print_status "Installing Composer dependencies..."
cd "$project_dir"
composer install --no-dev --optimize-autoloader
clear

# ============================================================
# 11. INSTALL NPM DEPENDENCIES AND BUILD
# ============================================================
echo ""
print_status "Installing npm dependencies..."
npm install
print_status "Building assets..."
npm run build
clear

# ============================================================
# 12. SET UP LARAVEL ENVIRONMENT
# ============================================================
echo ""
print_status "Setting up Laravel environment..."

cd "$project_dir"

# Create .env if it doesn't exist
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
        print_status ".env file created from .env.example"
    else
        print_warning ".env.example not found. Creating basic .env file..."
        cat > .env <<EOF
APP_NAME="$app_name"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://$server_ip

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=$db_name
DB_USERNAME=$db_user
DB_PASSWORD=$db_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
EOF
    fi
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    print_status "Generating application key..."
    php artisan key:generate --force
fi

# Update database credentials in .env
if [ -n "$db_name" ] && [ -n "$db_user" ] && [ -n "$db_password" ]; then
    print_status "Updating database credentials in .env..."
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$db_name/" .env
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$db_user/" .env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$db_password/" .env
fi

# Update APP_URL
sed -i "s|APP_URL=.*|APP_URL=http://$server_ip|" .env

clear

# ============================================================
# 13. SET PERMISSIONS
# ============================================================
echo ""
print_status "Setting proper permissions..."
sudo chown -R www-data:www-data "$project_dir"
sudo chmod -R 755 "$project_dir"
sudo chmod -R 775 "$project_dir/storage"
sudo chmod -R 775 "$project_dir/bootstrap/cache"
clear

# ============================================================
# 14. CREATE STORAGE LINK
# ============================================================
echo ""
print_status "Creating storage symbolic link..."
cd "$project_dir"
php artisan storage:link || print_warning "Storage link creation failed (may already exist)"
clear

# ============================================================
# 15. RUN MIGRATIONS
# ============================================================
echo ""
read -p "Run database migrations now? (y/n) [y]: " run_migrations
[[ -z "$run_migrations" ]] && run_migrations="y"
if [ "$run_migrations" = "y" ]; then
    print_status "Running database migrations..."
    php artisan migrate --force || print_warning "Migrations failed. Please run manually later."
fi
clear

# ============================================================
# 16. CONFIGURE NGINX
# ============================================================
echo ""
print_status "Configuring Nginx..."

sudo tee /etc/nginx/sites-available/$app_name > /dev/null <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name $server_ip;
    root $project_dir/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 100M;
}
EOF

# Enable site
sudo ln -sf /etc/nginx/sites-available/$app_name /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test and reload Nginx
sudo nginx -t
sudo systemctl reload nginx

print_status "Nginx configured successfully!"
clear

# ============================================================
# 17. INSTALL AND CONFIGURE PHPMYADMIN
# ============================================================
echo ""
print_status "Installing phpMyAdmin..."

sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $mysql_root_password"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $mysql_root_password"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $mysql_root_password"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect nginx"

sudo apt install -y phpmyadmin

# Configure phpMyAdmin for Nginx
if [ ! -f /etc/nginx/snippets/phpmyadmin.conf ]; then
    sudo tee /etc/nginx/snippets/phpmyadmin.conf > /dev/null <<'EOF'
location /phpmyadmin {
    root /usr/share/;
    index index.php index.html index.htm;
    location ~ ^/phpmyadmin/(.+\.php)$ {
        root /usr/share/;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $request_filename;
        include fastcgi_params;
    }
    location ~* ^/phpmyadmin/(.+\.(jpg|jpeg|gif|css|png|js|ico|html|xml|txt))$ {
        root /usr/share/;
    }
}
EOF
fi

# Add phpMyAdmin to Nginx config
sudo sed -i '/location ~ \.php$/a\    include /etc/nginx/snippets/phpmyadmin.conf;' /etc/nginx/sites-available/$app_name

# Secure phpMyAdmin
sudo tee -a /etc/nginx/sites-available/$app_name > /dev/null <<EOF

# phpMyAdmin Security
location /phpmyadmin {
    allow 127.0.0.1;
    allow ::1;
    # Uncomment and add your IP for remote access
    # allow YOUR_IP_ADDRESS;
    deny all;
    include /etc/nginx/snippets/phpmyadmin.conf;
}
EOF

sudo systemctl reload nginx
print_status "phpMyAdmin installed! Access at: http://$server_ip/phpmyadmin (localhost only by default)"
clear

# ============================================================
# 18. INSTALL AND CONFIGURE FTP (VSFTPD)
# ============================================================
echo ""
print_status "Installing FTP server (vsftpd)..."

sudo apt install -y vsftpd

# Backup original config
sudo cp /etc/vsftpd.conf /etc/vsftpd.conf.backup

# Configure vsftpd
sudo tee /etc/vsftpd.conf > /dev/null <<EOF
listen=NO
listen_ipv6=YES
anonymous_enable=NO
local_enable=YES
write_enable=YES
local_umask=022
dirmessage_enable=YES
use_localtime=YES
xferlog_enable=YES
connect_from_port_20=YES
chroot_local_user=YES
secure_chroot_dir=/var/run/vsftpd/empty
pam_service_name=vsftpd
rsa_cert_file=/etc/ssl/certs/ssl-cert-snakeoil.pem
rsa_private_key_file=/etc/ssl/private/ssl-cert-snakeoil.key
ssl_enable=NO
allow_writeable_chroot=YES
pasv_enable=YES
pasv_min_port=40000
pasv_max_port=50000
userlist_enable=YES
userlist_file=/etc/vsftpd.userlist
userlist_deny=NO
EOF

# Create FTP user if it doesn't exist
if ! id "$ftp_user" &>/dev/null; then
    print_status "Creating FTP user: $ftp_user"
    sudo useradd -m -d "$project_dir" -s /bin/bash "$ftp_user"
    echo "$ftp_user:$ftp_password" | sudo chpasswd
    
    # Add user to www-data group
    sudo usermod -aG www-data "$ftp_user"
    
    # Set proper ownership
    sudo chown -R "$ftp_user":www-data "$project_dir"
    sudo chmod -R 775 "$project_dir"
fi

# Add user to allowed list
echo "$ftp_user" | sudo tee -a /etc/vsftpd.userlist

# Restart vsftpd
sudo systemctl restart vsftpd
sudo systemctl enable vsftpd

print_status "FTP server configured! User: $ftp_user, Directory: $project_dir"
clear

# ============================================================
# 19. CONFIGURE FIREWALL
# ============================================================
echo ""
print_status "Configuring firewall (UFW)..."

if command -v ufw &> /dev/null; then
    sudo ufw allow 22/tcp
    sudo ufw allow 80/tcp
    sudo ufw allow 443/tcp
    sudo ufw allow 21/tcp
    sudo ufw allow 40000:50000/tcp
    sudo ufw --force enable
    print_status "Firewall configured!"
else
    print_warning "UFW not installed. Please configure firewall manually."
fi
clear

# ============================================================
# 20. OPTIMIZE LARAVEL
# ============================================================
echo ""
print_status "Optimizing Laravel..."
cd "$project_dir"
php artisan config:cache
php artisan route:cache
php artisan view:cache
clear

# ============================================================
# 21. FINAL SUMMARY
# ============================================================
echo ""
echo "============================================================"
echo -e "${GREEN}           DEPLOYMENT COMPLETE!${NC}"
echo "============================================================"
echo ""
echo "Application Details:"
echo "  - Project Directory: $project_dir"
echo "  - Application URL: http://$server_ip"
echo "  - phpMyAdmin: http://$server_ip/phpmyadmin (localhost only)"
echo ""
echo "Database:"
echo "  - Database Name: $db_name"
echo "  - Database User: $db_user"
echo "  - Database Password: $db_password"
echo ""
echo "FTP Access:"
echo "  - FTP User: $ftp_user"
echo "  - FTP Password: $ftp_password"
echo "  - FTP Directory: $project_dir"
echo "  - FTP Server: $server_ip"
echo ""
echo "SSH Access:"
echo "  - You already have SSH access (you're using it now!)"
echo ""
echo "Next Steps:"
echo "  1. Update .env file with your specific settings"
echo "  2. Run 'php artisan migrate' if migrations weren't run"
echo "  3. Run 'php artisan db:seed' to seed database (optional)"
echo "  4. Configure domain name in Nginx if you have one"
echo "  5. Set up SSL certificate with Let's Encrypt (optional)"
echo ""
echo "Useful Commands:"
echo "  - View logs: tail -f $project_dir/storage/logs/laravel.log"
echo "  - Restart Nginx: sudo systemctl restart nginx"
echo "  - Restart PHP-FPM: sudo systemctl restart php8.2-fpm"
echo "  - Clear cache: cd $project_dir && php artisan cache:clear"
echo ""
echo "============================================================"
echo ""
