# 🚗 HexaRide/Drivemond - Complete Ride Sharing Platform

## Ayooooooooooo Stalkers!! It's not mistakenly public. 

> A comprehensive, production-ready ride-sharing application with Laravel backend and Flutter mobile applications for drivers and users.

![Version](https://img.shields.io/badge/version-3.1-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg)
![Flutter](https://img.shields.io/badge/Flutter-3.3.4+-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

---

## 📋 Table of Contents

- [Project Overview](#project-overview)
- [Architecture](#architecture)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Mobile Applications](#mobile-applications)
- [Backend Modules](#backend-modules)
- [Quick Start](#quick-start)
- [Features](#features)
- [Development](#development)
- [Deployment](#deployment)

---

## 🎯 Project Overview

**HexaRide/Drivemond** is an advanced ride-sharing platform designed to connect drivers and passengers with a robust backend system and feature-rich mobile applications. The platform supports multiple payment gateways, real-time location tracking, in-app messaging, and comprehensive admin control.

### Key Highlights

✅ **Multi-sided Platform** - Drivers, Users, and Admins  
✅ **Real-time Features** - WebSockets via Reverb & Pusher  
✅ **Payment Integration** - 5+ payment gateways  
✅ **GPS Tracking** - Real-time driver location tracking  
✅ **AI Integration** - OpenAI integration for smart features  
✅ **Modular Architecture** - Laravel Modules for scalability  
✅ **Cross-platform** - iOS, Android, Web support  

---

## 🏗️ Architecture

### System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                      FRONTEND LAYER                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────────┐  ┌──────────────────────┐              │
│  │  Driver Flutter App  │  │  User Flutter App    │              │
│  │  (iOS, Android, Web) │  │  (iOS, Android, Web) │              │
│  │                      │  │                      │              │
│  │ • Real-time Tracking │  │ • Ride Request       │              │
│  │ • Earnings           │  │ • Payment            │              │
│  │ • Profile Mgmt       │  │ • Ratings & Reviews  │              │
│  │ • Rating & Reviews   │  │ • In-app Chat        │              │
│  └──────────────────────┘  └──────────────────────┘              │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                              ▲
                              │ (REST API, WebSocket)
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    API GATEWAY LAYER                             │
│              (Laravel 12 with API Routes)                        │
├─────────────────────────────────────────────────────────────────┤
│                      Authentication                              │
│              (Laravel Passport & Sanctum)                        │
└─────────────────────────────────────────────────────────────────┘
                              ▲
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    BUSINESS LOGIC LAYER                          │
│                  (Modular Laravel System)                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌─────────────┐ ┌──────────────┐ ┌─────────────────┐           │
│  │ Auth Module │ │ User Module  │ │ Trip Module     │           │
│  ├─────────────┤ ├──────────────┤ ├─────────────────┤           │
│  │ • Login     │ │ • Registration│ │ • Create Trip   │           │
│  │ • OTP       │ │ • Profile    │ │ • Accept Trip   │           │
│  │ • Passwords │ │ • Settings   │ │ • Track Trip    │           │
│  └─────────────┘ └──────────────┘ └─────────────────┘           │
│                                                                   │
│  ┌──────────────┐ ┌────────────┐ ┌──────────────────┐           │
│  │ Payment Mgmt │ │ Wallet Mgmt│ │ Chat Module      │           │
│  ├──────────────┤ ├────────────┤ ├──────────────────┤           │
│  │ • Stripe     │ │ • Balance  │ │ • Real-time Msgs │           │
│  │ • Razorpay   │ │ • Recharge │ │ • Notifications  │           │
│  │ • + 3 more   │ │ • Withdraw │ │ • Blocking Users │           │
│  └──────────────┘ └────────────┘ └──────────────────┘           │
│                                                                   │
│  ┌──────────────┐ ┌────────────┐ ┌──────────────────┐           │
│  │ Promotion Mgmt│ │ Review Mgmt│ │ Parcel Module   │           │
│  ├──────────────┤ ├────────────┤ ├──────────────────┤           │
│  │ • Coupons    │ │ • Ratings  │ │ • Parcel Booking │           │
│  │ • Discounts  │ │ • Comments │ │ • Parcel Pricing │           │
│  │ • Referrals  │ │ • Reports  │ │ • Parcel Tracking│           │
│  └──────────────┘ └────────────┘ └──────────────────┘           │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                              ▲
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    DATA LAYER                                    │
├─────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌───────────────┐  ┌────────────────┐        │
│  │   MySQL DB   │  │   Redis Cache │  │   File Storage │        │
│  │ (Eloquent)   │  │  (Sessions)   │  │   (AWS S3)     │        │
│  └──────────────┘  └───────────────┘  └────────────────┘        │
└─────────────────────────────────────────────────────────────────┘
                              ▲
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                  EXTERNAL SERVICES                               │
├─────────────────────────────────────────────────────────────────┤
│  Firebase  │  Pusher  │  Stripe  │  Google Maps  │  Twilio     │
│  Razorpay  │  Xendit  │  Mercado │  OpenAI      │  AWS         │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔧 Tech Stack

### Backend
- **Framework**: Laravel 12.0
- **Language**: PHP 8.2+
- **Database**: MySQL
- **Cache**: Redis
- **Queue**: Laravel Queues
- **Real-time**: Laravel Reverb + Pusher
- **Authentication**: Laravel Passport & Sanctum
- **Package Manager**: Composer

### Frontend (Mobile)
- **Framework**: Flutter 3.3.4+
- **Language**: Dart
- **State Management**: GetX
- **Local Storage**: Shared Preferences
- **Maps**: Google Maps Flutter
- **Push Notifications**: Firebase Cloud Messaging
- **Package Manager**: Pub

### External Integrations
| Service | Purpose |
|---------|---------|
| **Firebase** | Authentication, Cloud Messaging |
| **Google Maps** | Location, Mapping, Directions |
| **Stripe** | Payment Processing |
| **Razorpay** | Payment Gateway |
| **Xendit** | Payment Processing |
| **Mercado Pago** | Payment Gateway |
| **Iyzico** | Payment Processing |
| **Twilio** | SMS Notifications |
| **OpenAI** | AI Features |
| **AWS** | File Storage (S3) |
| **Pusher** | Real-time Broadcasting |

---

## 📁 Project Structure

### Overall Directory Layout

```
drivemond-combo-package-lifetime-v3.1/
│
├── combo/
│   ├── app/                           # Laravel Application Code
│   │   ├── Http/                      # Controllers, Middleware
│   │   ├── Models/                    # Eloquent Models
│   │   ├── Jobs/                      # Queue Jobs
│   │   ├── Events/                    # Application Events
│   │   ├── Listeners/                 # Event Listeners
│   │   ├── Providers/                 # Service Providers
│   │   ├── Repositories/              # Data Access Layer
│   │   ├── Service/                   # Business Logic Layer
│   │   ├── Lib/                       # Helper Libraries
│   │   └── Traits/                    # PHP Traits
│   │
│   ├── Modules/                       # Laravel Modules (Modular Architecture)
│   │   ├── AdminModule/
│   │   ├── AuthManagement/
│   │   ├── UserManagement/
│   │   ├── SubscriptionManagement/
│   │   ├── TripManagement/
│   │   ├── FareManagement/
│   │   ├── PaymentManagement/
│   │   ├── VehicleManagement/
│   │   ├── ParcelManagement/
│   │   ├── ReviewModule/
│   │   ├── TransactionManagement/
│   │   ├── ZoneManagement/
│   │   ├── PromotionManagement/
│   │   ├── ChattingManagement/
│   │   ├── BusinessManagement/
│   │   └── Other Modules...
│   │
│   ├── config/                        # Configuration Files
│   ├── database/                      # Migrations & Seeders
│   ├── resources/                     # Views, Localization
│   ├── routes/                        # API & Web Routes
│   ├── storage/                       # Application Storage
│   ├── tests/                         # Test Suite
│   ├── public/                        # Public Assets
│   ├── bootstrap/                     # Application Bootstrap
│   │
│   ├── composer.json                  # PHP Dependencies
│   ├── artisan                        # Laravel CLI
│   ├── phpunit.xml                    # Testing Configuration
│   ├── webpack.mix.js                 # Build Configuration
│   └── .env                           # Environment Variables
│
├── HexaRide-Driver-app-release-3.1/   # Flutter Driver Application
│   ├── lib/
│   │   ├── main.dart
│   │   ├── features/
│   │   │   ├── auth/                  # Authentication Feature
│   │   │   ├── home/                  # Home Screen
│   │   │   ├── map/                   # Map & Ride Tracking
│   │   │   ├── profile/               # Driver Profile
│   │   │   ├── wallet/                # Earnings & Wallet
│   │   │   ├── ride/                  # Ride Management
│   │   │   ├── review/                # Ratings & Reviews
│   │   │   ├── chat/                  # Messaging
│   │   │   └── ... more features
│   │   ├── common_widgets/            # Reusable Widgets
│   │   ├── data/                      # Data Layer
│   │   ├── helper/                    # Helper Functions
│   │   └── util/                      # Utilities
│   │
│   ├── android/                       # Android Native Code
│   ├── ios/                           # iOS Native Code
│   ├── web/                           # Web Build
│   ├── pubspec.yaml                   # Flutter Dependencies
│   └── analysis_options.yaml
│
├── HexaRide-User-app-release-3.1/     # Flutter User Application
│   ├── lib/
│   │   ├── main.dart
│   │   ├── features/
│   │   │   ├── auth/                  # Authentication
│   │   │   ├── home/                  # Home Dashboard
│   │   │   ├── booking/               # Ride Booking
│   │   │   ├── address/               # Address Management
│   │   │   ├── map/                   # Map & Tracking
│   │   │   ├── wallet/                # Payment & Wallet
│   │   │   ├── parcel/                # Parcel Delivery
│   │   │   ├── chat/                  # Messaging
│   │   │   ├── review/                # Reviews & Ratings
│   │   │   └── ... more features
│   │   ├── common_widgets/
│   │   ├── data/
│   │   ├── helper/
│   │   └── util/
│   │
│   ├── android/
│   ├── ios/
│   ├── web/
│   ├── pubspec.yaml
│   └── analysis_options.yaml
│
└── README.md (this file)
```

---

## 📱 Mobile Applications

### Driver Application (HexaRide-Driver-app-release-3.1)

A comprehensive driver-focused mobile application for ride-sharing platform.

**Platform Support**: iOS, Android, Web

**Key Features**:
- 🔐 **Authentication** - OTP & Password-based login
- 🗺️ **Real-time Mapping** - Live location tracking, route visualization
- 📍 **Ride Management** - Accept/reject rides, track passengers
- 💰 **Earnings Dashboard** - Daily, weekly, monthly earnings
- 👤 **Profile Management** - Personal info, vehicle details, documents
- 💬 **In-app Messaging** - Real-time communication with passengers
- ⭐ **Ratings & Reviews** - Track driver ratings and feedback
- 🎯 **Referral Program** - Earn by referring other drivers
- 📊 **Statistics** - Trips completed, ratings, earnings
- 🔔 **Push Notifications** - Real-time ride requests
- 💳 **Wallet Management** - Check balance, view transactions

**Technology Stack**:
```yaml
Flutter: 3.3.4+
State Management: GetX 4.7.3
Maps: Google Maps Flutter 2.14.2
Location: Geolocator 14.0.2
Notifications: Firebase Messaging 16.1.1
Network: HTTP 1.6.0
UI Libraries: Flutter SVG, Shimmer, Spinkit
```

**App Structure**:
```
lib/
├── features/
│   ├── auth/              # Login, Signup, OTP verification
│   ├── home/              # Driver dashboard
│   ├── map/               # Map and ride tracking
│   ├── ride/              # Ride details and management
│   ├── profile/           # Profile & documents
│   ├── wallet/            # Earnings and transactions
│   ├── review/            # Ratings and feedback
│   ├── chat/              # Messaging with passengers
│   ├── leaderboard/       # Driver rankings
│   ├── refer_and_earn/    # Referral program
│   ├── help_and_support/  # Support tickets
│   ├── location/          # Location access
│   ├── splash/            # App initialization
│   └── settings/          # App settings
├── common_widgets/        # Reusable UI components
├── data/                  # API integration
├── helper/                # DI, routes, notifications
└── util/                  # Constants, dimensions, images
```

---

### User Application (HexaRide-User-app-release-3.1)

A feature-rich user/passenger mobile application for ride-sharing and parcel delivery.

**Platform Support**: iOS, Android, Web

**Key Features**:
- 🔐 **Authentication** - Multi-method signup/login
- 🏠 **Home Dashboard** - Quick access to rides and services
- 🚗 **Ride Booking** - Search, select, and book rides
- 📍 **Address Management** - Save favorite locations
- 🗺️ **Live Tracking** - Track driver in real-time
- 💳 **Multiple Payments** - Cards, wallet, digital wallets
- 📦 **Parcel Delivery** - Send parcels with drivers
- 💬 **Chat** - Communicate with drivers
- ⭐ **Reviews & Ratings** - Rate drivers and rides
- 🎟️ **Discounts & Coupons** - Apply promotional codes
- 📊 **History** - View all rides and transactions
- 🔔 **Smart Notifications** - Real-time updates
- 👥 **Referral System** - Earn credits by inviting friends

**Technology Stack**:
```yaml
Flutter: 3.3.4+
State Management: GetX 4.7.3
Maps: Google Maps Flutter 2.14.2
Location: Geolocator 14.0.2
Notifications: Firebase Messaging 16.1.1
Network: HTTP 1.6.0
Date Picker: Syncfusion 32.2.4
UI Libraries: Flutter SVG, Shimmer, Carousel
```

**App Structure**:
```
lib/
├── features/
│   ├── auth/              # Login, Signup, Verification
│   ├── home/              # User dashboard
│   ├── booking/           # Ride booking interface
│   ├── map/               # Map and tracking
│   ├── address/           # Address book
│   ├── wallet/            # Payment methods & wallet
│   ├── parcel/            # Parcel delivery
│   ├── ride/              # Ride details
│   ├── chat/              # Messaging
│   ├── review/            # Ratings
│   ├── my_offer/          # Active discounts
│   ├── notification/      # Notifications
│   ├── onboard/           # Onboarding
│   ├── settings/          # App settings
│   └── splash/            # App startup
├── common_widgets/        # Shared UI components
├── data/                  # API integration
├── helper/                # Utilities and DI
└── util/                  # Constants and helpers
```

---

## 🏢 Backend Modules

The Laravel backend uses a modular architecture with `nwidart/laravel-modules` package for better organization and scalability.

### Module Breakdown

| Module | Purpose | Key Components |
|--------|---------|-----------------|
| **AdminModule** | Admin panel and controls | Dashboard, Users, Analytics |
| **AuthManagement** | Authentication system | Login, Registration, OTP, Password Reset |
| **UserManagement** | User profiles & data | Profile, Documents, Verification |
| **SubscriptionManagement** | Subscription plans | Plans, Billing, Renewals |
| **TripManagement** | Trip/Ride operations | Booking, Tracking, Completion |
| **FareManagement** | Pricing & fare calculation | Fare Rules, Surge Pricing |
| **Gateways** | Payment gateways | Stripe, Razorpay, Xendit, Mercado |
| **ParcelManagement** | Parcel delivery service | Booking, Pricing, Tracking |
| **PromotionManagement** | Coupons & discounts | Promo Codes, Referrals, Offers |
| **ReviewModule** | Ratings & reviews | Trip Reviews, Driver Ratings |
| **ChattingManagement** | Real-time messaging | Messages, Chat Groups, Notifications |
| **VehicleManagement** | Vehicle management | Vehicle Types, Details, Documents |
| **ZoneManagement** | Geographic zones | Zone Setup, Pricing Zones |
| **BusinessManagement** | Business accounts | Corporate Accounts, Bulk Booking |
| **TransactionManagement** | Payment transactions | Ledger, Settlement, Reports |
| **AiModule** | AI features | Chatbot, Predictions, Recommendations |
| **BlogManagement** | Content management | Blog Posts, Articles |

### Module Structure

Each module follows this structure:
```
Modules/
├── ModuleName/
│   ├── Http/
│   │   ├── Controllers/    # Route controllers
│   │   └── Requests/       # Form validation requests
│   │
│   ├── Models/             # Eloquent models
│   ├── Routes/
│   │   ├── api.php         # API routes
│   │   └── web.php         # Web routes
│   │
│   ├── Database/
│   │   ├── Migrations/
│   │   └── Seeders/
│   │
│   ├── Services/           # Business logic
│   ├── Repositories/       # Data access
│   ├── Policies/           # Authorization
│   ├── Events/             # Domain events
│   ├── Listeners/          # Event handlers
│   └── module.json         # Module definition
```

---

## 🚀 Quick Start

### Prerequisites

- **Backend**:
  - PHP 8.2 or higher
  - Composer
  - MySQL 8.0+
  - Redis (for caching and sessions)
  - Node.js 16+ (for asset compilation)

- **Mobile (Flutter)**:
  - Flutter SDK 3.3.4+
  - Dart SDK
  - Android Studio / Xcode
  - Android SDK / iOS SDK

### Backend Setup

```bash
# Navigate to project directory
cd combo

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=hexaride
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed

# Install Node dependencies
npm install

# Compile assets
npm run dev

# Start the development server
php artisan serve

# In another terminal, start queue worker (optional)
php artisan queue:work
```

### Flutter Driver App Setup

```bash
# Navigate to driver app
cd HexaRide-Driver-app-release-3.1

# Get Flutter dependencies
flutter pub get

# Configure API endpoint in lib/util/app_constants.dart
# Update BASE_URL to your backend URL

# Run the app
flutter run

# Or build for specific platform
flutter build apk          # Android
flutter build ios          # iOS
flutter build web          # Web
```

### Flutter User App Setup

```bash
# Navigate to user app
cd HexaRide-User-app-release-3.1

# Get Flutter dependencies
flutter pub get

# Update API endpoint configuration
# Edit lib/util/app_constants.dart

# Run the app
flutter run

# Build commands
flutter build apk          # Android
flutter build ios          # iOS
flutter build web          # Web
```

---

## ✨ Features Overview

### Driver Features
- ✅ Real-time ride acceptance with notifications
- ✅ Live GPS tracking with map navigation
- ✅ Earnings tracking and withdrawal
- ✅ Driver rating and reviews
- ✅ In-app messaging with passengers
- ✅ Vehicle and document management
- ✅ Trip history with detailed analytics
- ✅ Referral program for earning bonuses
- ✅ Multiple payment method support
- ✅ Shift-based work mode

### User Features
- ✅ Instant ride booking with estimated fare
- ✅ Real-time driver tracking
- ✅ Multiple payment options
- ✅ Driver ratings and reviews
- ✅ Parcel delivery service
- ✅ In-app chat with driver
- ✅ Saved locations (home, work, etc.)
- ✅ Promo codes and discounts
- ✅ Ride history and receipts
- ✅ Referral program with rewards

### Admin Features
- ✅ Complete user & driver management
- ✅ Real-time analytics dashboard
- ✅ Payment and transaction management
- ✅ Ride history and reports
- ✅ Promotion and discount management
- ✅ Zone configuration
- ✅ Subscription plan management
- ✅ Support ticket management
- ✅ Revenue analytics
- ✅ System configuration

---

## 🔌 API Integration

### Key API Endpoints

```
BASE_URL: /api/v1

Authentication:
  POST   /auth/login              - User login
  POST   /auth/register           - User registration
  POST   /auth/logout             - Logout
  POST   /auth/refresh            - Refresh token
  POST   /auth/verify-otp         - Verify OTP
  POST   /auth/resend-otp         - Resend OTP

User Profile:
  GET    /profile                 - Get profile
  PUT    /profile                 - Update profile
  GET    /profile/documents       - Get documents
  POST   /profile/documents       - Upload documents

Rides:
  POST   /rides                   - Create ride request
  GET    /rides                   - List rides
  GET    /rides/{id}              - Get ride details
  PUT    /rides/{id}              - Update ride
  GET    /rides/{id}/track        - Track ride

Payments:
  POST   /payments                - Process payment
  GET    /payments/history        - Payment history
  POST   /wallet/topup            - Add funds to wallet
  POST   /wallet/withdraw         - Withdraw from wallet

Chat:
  GET    /messages/{conversation} - Get messages
  POST   /messages                - Send message
  GET    /conversations           - List conversations

Reviews:
  POST   /reviews                 - Create review
  GET    /reviews                 - List reviews

... and many more
```

---

## 📦 Payment Gateways

The system supports multiple payment gateways for flexibility:

1. **Stripe** - Global payments
2. **Razorpay** - Indian payments
3. **Xendit** - Southeast Asian payments
4. **Mercado Pago** - Latin American payments
5. **Iyzico** - Turkish/European payments

Configuration in `config/services.php`

---

## 🗄️ Database Schema

Key models and their relationships:

```
Users
├── Profiles
├── Documents
├── Reviews (as reviewer & reviewee)
├── Trips
├── Wallets
├── Transactions
├── Messages
└── Ratings

Drivers (extends Users)
├── Vehicles
├── DriverDocuments
├── AvailabilitySchedule
├── Earnings
└── Ratings

Trips
├── User (passenger)
├── Driver
├── Route (pickup & dropoff)
├── Fare
├── Payments
├── Reviews
└── Chat

Vehicles
├── VehicleType
├── VehicleDocuments
├── Insurance
└── Registration
```

---

## 🛠️ Development

### Running Commands

```bash
# Laravel Artisan Commands
php artisan make:migration create_table_name
php artisan make:model ModelName
php artisan make:controller ControllerName
php artisan make:job JobName
php artisan migrate
php artisan tinker

# Module Commands
php artisan module:make ModuleName
php artisan module:list
php artisan module:migrate ModuleName

# Queue Processing
php artisan queue:work
php artisan queue:failed
php artisan queue:retry {id}

# Cache Management
php artisan cache:clear
php artisan config:cache
php artisan view:cache

# Flutter Commands
flutter doctor              # Check Flutter setup
flutter pub get             # Install dependencies
flutter pub upgrade         # Upgrade dependencies
flutter format lib/         # Format code
flutter analyze            # Code analysis
flutter test               # Run tests
```

### Code Standards

- Follow PSR-12 for PHP code
- Use Dart conventions for Flutter code
- Write meaningful commit messages
- Add tests for new features
- Document complex logic

### Git Workflow

```bash
# Create feature branch
git checkout -b feature/feature-name

# Commit changes
git commit -m "feat: add feature description"

# Push to remote
git push origin feature/feature-name

# Create Pull Request
```

---

## 🚢 Deployment

### Backend Deployment (Server)

```bash
# Clone repository
git clone <repository-url>
cd combo

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set environment
cp .env.production .env
php artisan key:generate

# Database setup
php artisan migrate --force

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache

# Start services
php artisan serve
php artisan queue:work
```

### Mobile App Deployment

**Android Play Store**:
```bash
cd HexaRide-Driver-app-release-3.1
flutter build appbundle
# Upload to Play Store Console
```

**iOS App Store**:
```bash
cd HexaRide-Driver-app-release-3.1
flutter build ios
# Use Xcode to upload to App Store
```

---

## 📊 Monitoring & Logs

- **Backend Logs**: `storage/logs/laravel.log`
- **Queue Logs**: `storage/logs/queue.log`
- **App Performance Monitoring**: Laravel Telescope (dev environment)
- **Error Tracking**: Sentry integration available

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

---

## 📝 Documentation

- [Backend API Documentation](./docs/api.md)
- [Database Schema](./docs/database.md)
- [Flutter App Development](./docs/flutter.md)
- [Module Development Guide](./docs/modules.md)
- [Deployment Guide](./docs/deployment.md)

---

## 📞 Support

For issues, feature requests, or questions:
- Create an issue in the repository
- Check existing documentation
- Contact the development team

---

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 🙏 Credits

Built with ❤️ using:
- **Laravel** - The PHP Framework
- **Flutter** - Google's UI framework
- **Firebase** - Backend-as-a-Service
- **Google Maps** - Location & Mapping
- And many other amazing open-source libraries

---

## 📈 Version History

- **v3.1** (Current) - Latest stable release with all features
- **v3.0** - Previous version
- See [CHANGELOG.md](./CHANGELOG.md) for detailed changes

---

**Last Updated**: April 2026  
**Maintained by**: Development Team

```
 ____  ____   ___  _   _ _____  _____      _____ _____ _   _ 
|  _ \|  _ \ / _ \| \ | |_   _| |___ \ __ | ____|_   _| | | |
| | | | |_) | | | |  \| | | |   _ __) |_/ |  _|   | | | |_| |
| |_| |  _ <| |_| | |\  | | |  | | __      | |___  | | |  _  |
|____/|_| \_\\___/|_| \_| |_|  |_|_|       |_____| |_| |_| |_|

Happy Coding! 🚀
```

---

*For the latest updates and detailed information, always refer to the official repository.*
