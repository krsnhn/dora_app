# 🌏 DORA — Digital Tourism Connector

A full-stack web application connecting travelers with local travel agencies, built with **Laravel 11**, **MySQL**, and **Blade** templating.

---

## ✨ Features

### For Travelers
- Browse 22+ local & international destinations
- Live weather data via OpenWeatherMap API
- Google Maps integration
- Save favorites, upload travel memories
- Backpack checklist
- Inquiry system with email notifications

### For Travel Agencies
- Agency registration with document upload (Cloudinary)
- Dashboard: manage tour packages & inquiries
- Receive email alerts for new inquiries

### For Admins
- User management (approve/suspend accounts)
- Agency verification workflow
- Destination approval
- Feedback moderation

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11 |
| Language | PHP 8.3+ |
| Database | MySQL 8.0+ |
| Templating | Blade |
| Auth | Laravel Breeze |
| Images | Cloudinary |
| Email | Brevo (Sendinblue) SMTP |
| Weather | OpenWeatherMap API |
| Maps | Google Maps Embed API |

---

## 🚀 Installation

### Prerequisites
- PHP 8.3+
- Composer
- MySQL 8.0+
- Node.js 18+ & npm

---

### Step 1 — Clone / Extract the Project

```bash
# If from ZIP:
unzip dora_app.zip -d dora_app
cd dora_app
```

---

### Step 2 — Install PHP Dependencies

```bash
composer install
```

> If you encounter memory issues: `COMPOSER_MEMORY_LIMIT=-1 composer install`

---

### Step 3 — Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and update:

```env
APP_NAME=DORA
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dora_app_db
DB_USERNAME=root
DB_PASSWORD=your_password

# OpenWeatherMap (https://openweathermap.org/api)
OPENWEATHERMAP_API_KEY=your_key_here

# Google Maps (https://console.cloud.google.com)
GOOGLE_MAPS_API_KEY=your_key_here

# Cloudinary (https://cloudinary.com)
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret

# Brevo / Sendinblue SMTP (https://brevo.com)
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_login
MAIL_PASSWORD=your_brevo_smtp_key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="DORA"
```

---

### Step 4 — Create Database

```sql
CREATE DATABASE dora_app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

### Step 5 — Run Migrations & Seed

```bash
php artisan migrate
php artisan db:seed
```

This creates:
- ✅ **Admin:** `admin@dora.app` / `password`
- ✅ **Agency:** `agency@dora.app` / `password`
- ✅ **Traveler:** `traveler@dora.app` / `password`
- ✅ 22 destinations with tour packages

---

### Step 6 — Storage Link

```bash
php artisan storage:link
```

---

### Step 7 — Build Frontend Assets (optional)

```bash
npm install
npm run build
```

> The app works without this step — all styles are embedded in Blade templates.

---

### Step 8 — Start Development Server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 📁 Project Structure

```
dora_app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── Agency/         # Agency controllers
│   │   │   ├── Auth/           # Breeze auth controllers
│   │   │   └── ...             # Traveler controllers
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── AgencyMiddleware.php
│   ├── Models/                 # Eloquent models
│   └── Services/
│       ├── WeatherService.php  # OpenWeatherMap integration
│       ├── CloudinaryService.php
│       └── EmailService.php    # Brevo email
├── database/
│   ├── migrations/             # 4 migration files
│   └── seeders/
│       └── DatabaseSeeder.php  # 22 destinations + demo users
├── resources/views/
│   ├── layouts/app.blade.php   # Main layout (design system)
│   ├── home.blade.php
│   ├── destinations/
│   ├── traveler/               # Favorites, Memories, Backpack, Inquiries
│   ├── agency/                 # Agency dashboard & packages
│   ├── admin/                  # Admin panel
│   ├── auth/                   # Login, Register, Password reset
│   ├── emails/                 # Email templates
│   └── pages/                  # About, Terms, Privacy
├── routes/
│   ├── web.php
│   └── auth.php
└── config/                     # App configuration
```

---

## 🎨 Design System

| Color | Hex | Usage |
|---|---|---|
| Deep Earth | `#2C1810` | Primary headings, navbar |
| Forest Green | `#2C5F2D` | Primary buttons, accents |
| Sunset Orange | `#FF7F4F` | CTAs, highlights |
| Platinum Beige | `#E8DCC0` | Backgrounds, cards |
| Ocean Blue | `#1E4A6D` | Links, badges |

**Fonts:** Cormorant Garamond (headings) · Jost (body) — loaded from Google Fonts

---

## 🔑 API Setup Guides

### OpenWeatherMap
1. Register at https://openweathermap.org
2. Go to API Keys → Generate key
3. Add to `.env`: `OPENWEATHERMAP_API_KEY=xxx`
4. Weather data is cached for 1 hour

### Google Maps
1. Go to https://console.cloud.google.com
2. Enable **Maps Embed API**
3. Create an API key (restrict to your domain)
4. Add to `.env`: `GOOGLE_MAPS_API_KEY=xxx`

### Cloudinary
1. Register at https://cloudinary.com
2. Dashboard → API Keys
3. Add cloud name, key, and secret to `.env`

### Brevo (Email)
1. Register at https://brevo.com
2. SMTP & API → Generate SMTP key
3. Add credentials to `.env` mail settings

---

## 🔒 Roles & Permissions

| Role | Access |
|---|---|
| `traveler` | Browse, favorites, memories, backpack, inquiries |
| `agency` | Dashboard, packages, inquiries (requires admin approval) |
| `admin` | Full platform oversight |

---

## 🧪 Running Tests

```bash
php artisan test
```

---

## 📝 License

This project is for educational/portfolio use. All destination photos are from [Unsplash](https://unsplash.com).

---

## 🙏 Credits

Built with ❤️ using Laravel, Blade, and the DORA color palette.
