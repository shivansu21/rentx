# RentX — Car & Bike Rental Management System

A PHP + MySQL car/bike rental platform with a customer-facing site and an
admin panel.

**See `SETUP_GUIDE.md` for installation steps.**

## Login credentials (after running setup.sql / setup.php)

| Role  | URL                          | Username/Email | Password  |
|-------|------------------------------|-----------------|-----------|
| Admin | `/admin/login.php`           | `admin`         | `admin123`|
| Customer | `/login.php`               | (register your own account via `/register.php`) | |

Change the admin password before going live — see `SETUP_GUIDE.md`.

## Project structure

```
RentX_Fixed/
├── setup.sql                  ← Import this in phpMyAdmin to create the DB
├── setup.php                  ← Optional one-click installer (delete after use)
├── SETUP_GUIDE.md             ← Full setup instructions
├── index.php                  ← Home page (car/bike listings + search)
├── search.php                 ← Search results page
├── vehicle_details.php        ← Single vehicle detail + "Book Now"
├── booking.php                ← Booking form + fare calculator
├── booking_history.php        ← Customer's past/current bookings
├── payment.php / invoice.php  ← Dummy payment + printable invoice
├── login.php / register.php / forgot_password.php
├── change_password.php / edit_profile.php / profile.php
├── contact.php / about.php
├── includes/
│   ├── config.php             ← Database connection settings
│   ├── header.php / footer.php
├── admin/
│   ├── login.php / logout.php
│   ├── dashboard.php
│   ├── add_vehicle.php / edit_vehicle.php / manage_vehicles.php / delete_vehicle.php
│   ├── manage_bookings.php / approve_booking.php / reject_booking.php
│   ├── contact_messages.php
│   ├── partials_sidebar.php / partials_end.php   ← shared admin layout
├── user/
│   └── dashboard.php          ← Customer dashboard
├── css/style.css              ← All site + admin panel styling
├── js/script.js
└── uploads/                   ← Vehicle photos, licence uploads
```

## Notes

- Uses plain `mysqli` (no framework/ORM) with prepared statements on all
  write operations added during the bug-fix pass.
- Passwords are hashed with PHP's `password_hash()`/`password_verify()`
  everywhere (register, login, forgot password, change password).
- `test.php` and `admin_password.php` are leftover dev helper files —
  safe to delete, not used by the app.
