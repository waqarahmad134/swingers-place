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