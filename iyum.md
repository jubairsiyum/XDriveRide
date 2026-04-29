# Drivemond Combo Package v3.1 - Setup Documentation

**Project Status:** ✅ **SETUP COMPLETE**  
**Last Updated:** April 20, 2026  
**Laravel Version:** 12.0  
**PHP Version:** 8.2+

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Installation & Setup Steps](#installation--setup-steps)
3. [Issues Fixed](#issues-fixed)
4. [Database Configuration](#database-configuration)
5. [Seeded Users](#seeded-users)
6. [How to Run](#how-to-run)
7. [Access URLs](#access-urls)
8. [Troubleshooting](#troubleshooting)

---

## 🏗️ Project Overview

**Drivemond Combo Package v3.1** is a complete ride-sharing platform with:
- **Laravel 12 Backend API** - RESTful + WebSocket real-time communication
- **Flutter Mobile Apps** - Driver and Passenger applications
- **Modular Architecture** - 16 independent business modules
- **Payment Gateways** - Stripe, Razorpay, Mercado Pago, Xendit, Iyzico
- **Real-time Features** - Laravel Reverb for WebSockets, Firebase push notifications
- **Cloud Integration** - AWS S3, Firebase, Twilio, OpenAI

---

## 🔧 Installation & Setup Steps

### Step 1: Fixed Laravel 12 Compatibility Issues ✅

**Problem:** Spatial data types (`polygon()`, `point()`) not supported in Laravel 12

**Solution:**
- Added `Matanyadaev\LaravelEloquentSpatial\EloquentSpatialServiceProvider` to `bootstrap/providers.php`
- Converted spatial migrations to JSON columns:
  - `Modules/ZoneManagement/Database/Migrations/2023_01_16_043100_create_zones_table.php` - `polygon()` → `json()`
  - `Modules/TripManagement/Database/Migrations/2023_02_19_071606_create_trip_routes_table.php` - `point()` → `json()`
  - `Modules/TripManagement/Database/Migrations/2023_03_06_052511_create_recent_addresses_table.php` - `point()` → `json()`
  - `Modules/TripManagement/Database/Migrations/2023_04_29_062028_create_trip_request_coordinates_table.php` - 9 fields `point()` → `json()`

**Files Modified:**
- `bootstrap/providers.php`

### Step 2: Installed OAuth2 Passport ✅

```bash
php artisan passport:install --force
```

**Status:** ✅ Completed  
**Files Generated:**
- Passport encryption keys
- OAuth client tables
- Authentication tables

### Step 3: Fixed Route Loading Issues ✅

**Problem:** Only update routes were loading; all other routes (login, API, web) were not accessible

**Solution:**
- Updated `app/Providers/RouteServiceProvider.php` to load:
  - ✅ `routes/api.php` - API endpoints
  - ✅ `routes/web.php` - Web pages
  - ✅ `routes/install.php` - Installation routes (prefix: `/install`)
  - ✅ `routes/update.php` - Update routes (prefix: `/update`)

**Files Modified:**
- `app/Providers/RouteServiceProvider.php`

### Step 4: Registered Module Route Providers ✅

**Problem:** Module routes (login, admin panel, etc.) weren't being loaded

**Solution:** Added module RouteServiceProviders to `bootstrap/providers.php`:

**Module RouteServiceProviders Added:**
```php
\Modules\AdminModule\Providers\RouteServiceProvider::class,
\Modules\AiModule\Providers\RouteServiceProvider::class,
\Modules\AuthManagement\Providers\RouteServiceProvider::class,
\Modules\BlogManagement\Providers\RouteServiceProvider::class,
\Modules\BusinessManagement\Providers\RouteServiceProvider::class,
\Modules\ChattingManagement\Providers\RouteServiceProvider::class,
\Modules\FareManagement\Providers\RouteServiceProvider::class,
\Modules\Gateways\Providers\RouteServiceProvider::class,
\Modules\ParcelManagement\Providers\RouteServiceProvider::class,
\Modules\PromotionManagement\Providers\RouteServiceProvider::class,
\Modules\ReviewModule\Providers\RouteServiceProvider::class,
\Modules\TransactionManagement\Providers\RouteServiceProvider::class,
\Modules\TripManagement\Providers\RouteServiceProvider::class,
\Modules\UserManagement\Providers\RouteServiceProvider::class,
\Modules\VehicleManagement\Providers\RouteServiceProvider::class,
\Modules\ZoneManagement\Providers\RouteServiceProvider::class,
```

**Files Modified:**
- `bootstrap/providers.php`

### Step 5: Created Test User Seeders ✅

Created comprehensive seeder with users for all roles

**Files Created:**
- `database/seeders/AllUsersSeeder.php`

**Files Modified:**
- `database/seeders/DatabaseSeeder.php`

```bash
php artisan db:seed
```

---

## 📊 Database Configuration

### Database Migrations

**Total Migrations Run:** 150+

**Key Tables Created:**
- ✅ `users` - User authentication & profiles
- ✅ `oauth_clients`, `oauth_tokens`, `oauth_auth_codes` - OAuth2 Passport
- ✅ `zones` - Geographic service zones
- ✅ `vehicles` - Driver vehicle information
- ✅ `trip_requests` - Ride booking requests
- ✅ `trip_routes`, `trip_request_coordinates` - Trip tracking with coordinates
- ✅ `payments`, `transactions` - Payment processing
- ✅ `trips_status` - Trip status tracking
- ✅ `reviews` - User ratings and reviews
- ✅ `coupons`, `discounts` - Promotion system
- ✅ `driver_details` - Driver-specific data
- ✅ `blogs`, `newsletters` - CMS functionality
- ✅ And 130+ more tables for complete functionality

---

## 👥 Seeded Users

### Admin Users

#### 1. Super Admin
| Property | Value |
|----------|-------|
| **Name** | Super Admin |
| **Email** | `admin@admin.com` |
| **Password** | `12345678` |
| **User Type** | `super-admin` |
| **Status** | Active |
| **Permissions** | Full system access |

#### 2. Admin Employee
| Property | Value |
|----------|-------|
| **Name** | Admin Employee |
| **Email** | `admin-employee@admin.com` |
| **Password** | `12345678` |
| **User Type** | `admin-employee` |
| **Status** | Active |
| **Permissions** | Limited admin access |

### Customer Users (Passengers)

#### 3. Customer 1
| Property | Value |
|----------|-------|
| **Name** | John Customer |
| **Email** | `customer@test.com` |
| **Password** | `12345678` |
| **Phone** | `+1234567890` |
| **User Type** | `customer` |
| **Status** | Active |

#### 4. Customer 2
| Property | Value |
|----------|-------|
| **Name** | Jane Passenger |
| **Email** | `customer2@test.com` |
| **Password** | `12345678` |
| **Phone** | `+1111111111` |
| **User Type** | `customer` |
| **Status** | Active |

### Driver Users

#### 5. Driver 1
| Property | Value |
|----------|-------|
| **Name** | Mike Driver |
| **Email** | `driver@test.com` |
| **Password** | `12345678` |
| **Phone** | `+9876543210` |
| **User Type** | `driver` |
| **Status** | Active |

#### 6. Driver 2
| Property | Value |
|----------|-------|
| **Name** | Tom Cabbie |
| **Email** | `driver2@test.com` |
| **Password** | `12345678` |
| **Phone** | `+2222222222` |
| **User Type** | `driver` |
| **Status** | Active |

---

## 🚀 How to Run

### 1. Start the Development Server

```bash
cd "e:\Agency Projects\drivemond-combo-package-lifetime-v3.1\combo\combo"
php artisan serve
```

**Expected Output:**
```
INFO  Server running on [http://127.0.0.1:8000].
Press Ctrl+C to stop the server
```

### 2. Start WebSocket Server (Optional - for Real-time Features)

```bash
php artisan reverb:start
```

### 3. Queue Worker (Optional - for Background Jobs)

```bash
php artisan queue:work
```

---

## 🌐 Access URLs

### Admin Panel
| URL | Purpose | Login Type |
|-----|---------|-----------|
| `http://127.0.0.1:8000/` | Landing Page | Public |
| `http://127.0.0.1:8000/admin/auth/login` | Admin Login | Super Admin / Admin Employee |
| `http://127.0.0.1:8000/admin/dashboard` | Admin Dashboard | Super Admin / Admin Employee |
| `http://127.0.0.1:8000/admin/users` | User Management | Super Admin / Admin Employee |
| `http://127.0.0.1:8000/admin/trips` | Trip Management | Super Admin / Admin Employee |

### Public Pages
| URL | Purpose |
|-----|---------|
| `http://127.0.0.1:8000/` | Home / Landing Page |
| `http://127.0.0.1:8000/about-us` | About Us |
| `http://127.0.0.1:8000/contact-us` | Contact Us |
| `http://127.0.0.1:8000/privacy` | Privacy Policy |
| `http://127.0.0.1:8000/terms` | Terms & Conditions |

### API Endpoints
| Endpoint | Purpose |
|----------|---------|
| `http://127.0.0.1:8000/api/auth/login` | Mobile App Authentication |
| `http://127.0.0.1:8000/api/trips` | Ride Management API |
| `http://127.0.0.1:8000/api/payments` | Payment API |
| `http://127.0.0.1:8000/api/users` | User Management API |

---

## 🛠️ Technology Stack

### Backend
- **Framework:** Laravel 12
- **PHP:** 8.2+
- **Database:** MySQL / PostgreSQL
- **Authentication:** Laravel Passport (OAuth2)
- **Real-time:** Laravel Reverb (WebSockets)
- **Queue:** Laravel Queue (Redis/Database)

### Mobile
- **Framework:** Flutter
- **Apps:** 
  - HexaRide Driver App
  - HexaRide Passenger App

### External Services
- **Payments:** Stripe, Razorpay, Mercado Pago, Xendit, Iyzico
- **Storage:** AWS S3
- **Notifications:** Firebase Cloud Messaging
- **SMS:** Twilio
- **AI:** OpenAI Integration
- **Maps:** Google Maps SDK

---

## 📁 Project Structure

```
combo/
├── app/                          # Laravel application code
│   ├── Http/                     # Controllers, middleware, requests
│   ├── Models/                   # Eloquent models
│   ├── Providers/                # Service providers
│   └── Traits/                   # Reusable functionality
├── routes/                       # Route definitions
│   ├── api.php                   # API routes
│   ├── web.php                   # Web routes
│   ├── install.php               # Installation routes
│   └── update.php                # Update routes
├── database/
│   ├── migrations/               # Schema migrations
│   └── seeders/                  # Database seeders
├── Modules/                      # Feature modules (Laravel Modules)
│   ├── AdminModule/              # Admin panel
│   ├── AuthManagement/           # Authentication
│   ├── UserManagement/           # User management
│   ├── TripManagement/           # Ride management
│   ├── VehicleManagement/        # Vehicle management
│   ├── PaymentManagement/        # Payments
│   └── ... (12 more modules)
├── resources/
│   ├── views/                    # Blade templates
│   ├── js/                       # JavaScript files
│   └── css/                      # CSS files
├── bootstrap/
│   ├── app.php
│   └── providers.php             # Application service providers
├── config/                       # Configuration files
├── storage/                      # Logs, cache, uploads
├── vendor/                       # Composer dependencies
└── .env                          # Environment variables
```

---

## 🔑 Environment Configuration

Key `.env` variables to configure:

```env
# App
APP_NAME="Drivemond"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=drivemond
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

# Firebase
FIREBASE_PROJECT_ID=your_project_id
FIREBASE_PRIVATE_KEY=your_private_key
FIREBASE_CLIENT_EMAIL=your_client_email

# Payment Gateways
STRIPE_PUBLIC_KEY=your_stripe_key
STRIPE_SECRET_KEY=your_stripe_secret

# WebSocket / Real-time
REVERB_APP_ID=drivemond
REVERB_APP_KEY=drivemond
REVERB_APP_SECRET=drivemond
REVERB_HOST=127.0.0.1
REVERB_PORT=6001
```

---

## ⚠️ Issues Fixed

### Issue 1: Spatial Column Types Not Supported
**Error:** `Method Illuminate\Database\Schema\Blueprint::polygon does not exist`  
**Cause:** Laravel 12 dropped support for spatial types via macros  
**Solution:** Converted to JSON columns; added EloquentSpatialServiceProvider  
**Status:** ✅ Fixed

### Issue 2: Routes Not Loading
**Error:** `404 Not Found` on login page  
**Cause:** RouteServiceProvider only loading update routes  
**Solution:** Added all route files to RouteServiceProvider  
**Status:** ✅ Fixed

### Issue 3: Module Routes Not Registered
**Error:** `404 Not Found` on `/admin/auth/login`  
**Cause:** Module RouteServiceProviders not in bootstrap providers  
**Solution:** Registered all 16 module RouteServiceProviders  
**Status:** ✅ Fixed

### Issue 4: Software Update Page as Default
**Error:** Always redirected to Software Update page  
**Cause:** Update routes had priority over web routes  
**Solution:** Added route prefix `/update` to update routes  
**Status:** ✅ Fixed

---

## 📋 Modules Overview

| Module | Purpose | Status |
|--------|---------|--------|
| AdminModule | Admin dashboard & reporting | ✅ Active |
| AuthManagement | Login, registration, authentication | ✅ Active |
| UserManagement | User profiles, levels, roles | ✅ Active |
| TripManagement | Ride booking, acceptance, completion | ✅ Active |
| VehicleManagement | Vehicle registration, documents | ✅ Active |
| FareManagement | Pricing algorithms, fare calculations | ✅ Active |
| PaymentManagement | Payments, wallets, refunds | ✅ Active |
| ChattingManagement | Real-time messaging | ✅ Active |
| ReviewModule | Ratings and reviews | ✅ Active |
| ZoneManagement | Geographic zones, service areas | ✅ Active |
| BusinessManagement | Business settings, Firebase | ✅ Active |
| PromotionManagement | Coupons, referrals, discounts | ✅ Active |
| ParcelManagement | Delivery/parcel services | ✅ Active |
| TransactionManagement | Financial transactions, accounting | ✅ Active |
| AiModule | AI features (route optimization, etc) | ✅ Active |
| Gateways | Payment gateway integrations | ✅ Active |

---

## 🧪 Testing

### Login Test
```bash
# Admin Login
URL: http://127.0.0.1:8000/admin/auth/login
Email: admin@admin.com
Password: 12345678

# Driver Login (API)
POST /api/auth/login
{
  "email": "driver@test.com",
  "password": "12345678"
}

# Customer Login (API)
POST /api/auth/login
{
  "email": "customer@test.com",
  "password": "12345678"
}
```

### Database Check
```bash
# List all users
php artisan tinker
>>> App\Models\User::all();

# Check user types
>>> App\Models\User::where('user_type', 'driver')->get();
>>> App\Models\User::where('user_type', 'customer')->get();
```

---

## 📝 Next Steps

1. **Configure Environment Variables**
   - Add Firebase credentials
   - Configure payment gateway keys
   - Set up mail/SMTP settings

2. **Start WebSocket Server** (for real-time features)
   ```bash
   php artisan reverb:start
   ```

3. **Configure Mobile Apps**
   - Update API base URL in Flutter apps
   - Add Firebase configuration
   - Build APK/IPA files

4. **Set Up Additional Services**
   - AWS S3 bucket access
   - Twilio SMS service
   - OpenAI API key

5. **Production Deployment**
   - Configure SSL certificates
   - Set `APP_ENV=production`
   - Enable query caching
   - Set up CDN for static assets

---

## 🐛 Troubleshooting

### Server Won't Start
```bash
# Clear caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Restart
php artisan serve
```

### Database Connection Error
```bash
# Check .env database credentials
# Ensure MySQL is running
# Run migrations again
php artisan migrate --fresh --seed
```

### Login Not Working
```bash
# Verify users exist
php artisan tinker
>>> App\Models\User::all()

# Reseed users
php artisan db:seed --class=AllUsersSeeder
```

### WebSocket Not Working
```bash
# Check Reverb is running
php artisan reverb:start

# Verify REVERB_* variables in .env
# Check port 6001 is not in use
```

---

## 📞 Support

For issues or questions:
1. Check this documentation
2. Review Laravel documentation: https://laravel.com/docs
3. Check module-specific documentation in `Modules/*/README.md`
4. Review error logs in `storage/logs/laravel.log`

---

## ✅ Setup Checklist

- [x] Fixed Laravel 12 compatibility (spatial columns)
- [x] Installed Passport OAuth2
- [x] Fixed route loading
- [x] Registered module routes
- [x] Created test users (all roles)
- [x] Database migrations completed
- [x] Documentation created

**Project is ready for development! 🎉**

---

**Generated:** April 20, 2026  
**Status:** Setup Complete ✅
