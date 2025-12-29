# Laravel Project – Setup & Deployment Guide

This README provides a step-by-step guide for installing the project locally and deploying it on Hostinger.

------------------------------------------------------------
LOCAL DEVELOPMENT SETUP
------------------------------------------------------------

1. Clone the Project
git clone https://github.com/waqarahmad134/swingers-place.git
cd your-project

2. Install PHP Dependencies
composer install
npm run dev / install

3. Create Environment File
cp .env.example .env
Update .env with local DB credentials.

4. Generate Application Key
php artisan key:generate

5. Link Storage
php artisan storage:link

6. Run Database Migrations
php artisan migrate

7. Seed the Database (Optional)
php artisan db:seed

8. Start Local Server
php artisan serve
App runs at: http://localhost:8000

------------------------------------------------------------
HOSTINGER DEPLOYMENT
------------------------------------------------------------

1. Upload Your Laravel Project (FTP or File Manager)

2. Move index.php and .htaccess from:
project-root/public/
Into:
public_html/

3. Edit public_html/index.php paths:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

4. Update .env with:
APP_URL
DB_*
MAIL_*

5. Create Storage Link
php artisan storage:link
If not allowed: manually map
public_html/storage -> storage/app/public

6. Run Migrations (SSH recommended)
php artisan migrate --force

7. Seed Database (optional)
php artisan db:seed --force

------------------------------------------------------------
DEVELOPMENT INDEX FILE
------------------------------------------------------------

Use this index.php if developing from root:

<?php
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());

------------------------------------------------------------
DONE
------------------------------------------------------------




Credientails :


===============
FTP (Hostinger)
===============
server: ftp.swingers.place
username: u756937133.swingersplace
password: AAaa11@@AAopen2code.com
server-dir: /public_html 


=================
Email (Hostinger)
=================

https://mail.hostinger.com/v2/mailboxes/INBOX
Mail : contact@swingers.place
Host : smtp.hostinger.com
Port : 465
encryption : ssl
username : contact@swingers.place
password: 1112223344@Wsx

====
DB
===
AAaa11@@AAopen2code.com
u756937133_crm

===
SSH
===
ssh -p 65002 u756937133@82.29.80.213
AAaa11@@AAopen2code.com 

====================
Storage Link Command 
====================

php artisan storage:link


else  

# 1. Go inside your CURRENT storage folder
cd /home/u756937133/domains/swingers.place/public_html/storage

# 2. Create a "profiles" shortcut that points to your uploads
# This tells the server: "When someone asks for 'profiles', look in 'app/public/profiles'"
ln -s app/public/profiles profiles
ln -s app/public/cover_photos cover_photos

# 3. Fix permissions so the images can load
chmod -R 755 profiles
chmod -R 755 cover_photos
===========================


Depoly 
=======

in root create .htaccess , if allow laravel to work from public


<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
# Block access to Dotfiles (like .env)
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

# Block access to Composer files
<FilesMatch "(^composer\.json|^composer\.lock)">
    Order allow,deny
    Deny from all
</FilesMatch>

# Block access to package logs
<FilesMatch "(^package\.json|^package-lock\.json)">
    Order allow,deny
    Deny from all
</FilesMatch>
# Disable Directory Browsing
Options -Indexes



------------------------------------------------------------
DEPLOYMENT ROUTES (No SSH Required)
------------------------------------------------------------

You can now deploy without SSH access using web routes:

1. Admin Panel Deployment (Requires Admin Login)
   URL: https://swingers.place/admin/deployment
   - Access via admin panel sidebar
   - Click individual commands or "Run Full Deployment"
   - Full deployment runs: composer install → migrate → seed → optimize

2. Public Deployment Route (For CI/CD)
   URL: POST https://swingers.place/deploy/{token}?action=all
   
   Setup:
   - Add to .env: DEPLOYMENT_TOKEN=your-secret-token-here
   - Use the token in the URL: /deploy/your-secret-token-here
   
   Actions:
   - action=all (runs all commands)
   - action=composer (only composer install)
   - action=migrate (only migrations)
   - action=seed (only database seed)
   - action=optimize (only optimize)
   
   Example:
   curl -X POST "https://swingers.place/deploy/your-secret-token-here?action=all"
   
   Note: Change the default token in .env for security!

3. PHP and Composer Path Configuration
   
   ✅ WORKS ON BOTH: Windows (Local) AND Linux Shared Hosting (Hostinger)
   
   The deployment routes work on both platforms automatically!
   
   If you get "php is not recognized" or "composer is not recognized" errors:
   
   For Windows (XAMPP):
   - Add to .env: PHP_PATH=C:\xampp\php\php.exe
   - Install Composer globally: https://getcomposer.org/download/
   - Or download composer.phar to project root
   
   For Linux Shared Hosting (Hostinger):
   - PHP is usually auto-detected (uses PHP_BINARY)
   - Composer: Download composer.phar to project root (recommended)
   - Or add to .env if composer is in custom location: COMPOSER_PATH=/path/to/composer
   
   Auto-Detection:
   - PHP: Uses PHP_BINARY (the PHP running Laravel) - works on both platforms
   - Composer: Checks project root, system PATH, and common locations
   
   Shared Hosting Best Practice:
   - Download composer.phar to your project root:
     curl -sS https://getcomposer.org/installer | php
   - The system will automatically find and use it



============================
Search with filters like swingers 
Cron job online user auto
Like for dummy user 1-1000
