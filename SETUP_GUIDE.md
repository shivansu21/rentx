# RentX_Fixed — Setup Guide

This is your RentX project, fixed and renamed so it can run side-by-side with
your existing install without touching it. Folder name: **RentX_Fixed**.
Database name: **rentx_fixed**.

## 1. Place the folder

Extract this zip so you end up with:

```
C:\xampp\htdocs\RentX_Fixed\   (Windows/XAMPP)
```
or
```
/opt/lampp/htdocs/RentX_Fixed/   (Linux/XAMPP)
```

The folder must be named `RentX_Fixed` (or update `.htaccess`'s two
`ErrorDocument` lines and this guide's URLs if you rename it).

## 2. Create the database — pick ONE option

### Option A — phpMyAdmin (recommended)
1. Open `http://localhost/phpmyadmin`
2. Click **Import**
3. Choose the file `setup.sql` (inside the RentX_Fixed folder)
4. Click **Go**

This creates a new database called `rentx_fixed` with every table the app
needs, plus a default admin account.

### Option B — One-click web installer
1. Start Apache + MySQL in XAMPP
2. Visit `http://localhost/RentX_Fixed/setup.php` in your browser
3. It will create the database and tables automatically
4. **Delete `setup.php` from the server once it says success** — it should
   never stay live on a real server

## 3. Check the database credentials

Open `includes/config.php`. By default it's set up for a stock XAMPP/WAMP
install:

```php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "rentx_fixed";
```

If your MySQL root user has a password, or you're using a different DB name,
update it here (and in `setup.php` if you used Option B).

## 4. Log in

- **Website:** `http://localhost/RentX_Fixed/index.php`
- **Admin panel:** `http://localhost/RentX_Fixed/admin/login.php`
  - Username: `admin`
  - Password: `admin123`
  - **Change this password immediately** — there's no in-app admin password
    change screen yet, so update it directly in the `admin` table (use
    PHP's `password_hash()` to generate the new hash, don't store it as
    plain text).

## 5. Before going live (production checklist)

- [ ] Change the admin password
- [ ] Set a real MySQL user/password in `includes/config.php` instead of
      blank root
- [ ] Delete `test.php` and `admin_password.php` — leftover dev files, not
      used by the app
- [ ] Delete `setup.php` after first run
- [ ] In `php.ini`, set `display_errors = Off` so visitors never see raw
      PHP error text
- [ ] Make sure `uploads/vehicles/`, `uploads/licences/`, and `uploads/`
      are writable by the web server

## What's different from the old `RentX` folder

- Folder renamed `RentX` → `RentX_Fixed`
- Database renamed `rentx` → `rentx_fixed`
- All the bug fixes from earlier (Add Vehicle crash, admin logout crash,
  search.php crash, change-password account corruption, missing
  `pickup_location` → real `city`/`pickup_address`/`service_radius`
  columns, XSS hardening, new admin/user dashboard styling) are included

Your original `RentX` folder and `rentx` database are untouched — this is
a separate, independent copy.
