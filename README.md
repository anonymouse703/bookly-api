**Bookly API**

Bookly API is a Laravel 12 backend application that serves as both an API source and an admin panel.
It uses Livewire and Filament to provide a modern, reactive admin interface for managing the system.

This project is not just an API-only backend — it includes a fully functional admin panel for internal management.

🚀 Tech Stack

- Laravel 12

- Livewire

- Filament Admin Panel

- MySQL / PostgreSQL (configurable)

- REST API

✨ Features

- RESTful API for frontend or mobile app consumption

- Admin dashboard built with Filament

- Authentication & authorization

- Service and booking management

- Clean and scalable Laravel architecture

📦 Requirements

- PHP 8.2+

- Composer

- Node.js & npm (for assets)

- MySQL / PostgreSQL

**Installation**
```
git clone https://github.com/your-username/bookly-api.git
cd bookly-api
```
**Install PHP dependencies:**
```
composer install
```

**Install frontend dependencies:**
```
npm install && npm run build
```

**Copy environment file:**
```
cp .env.example .env
```

**Generate application key:**
```
php artisan key:generate
```

**Run migrations:**
```
php artisan migrate
```

**(Optional) Seed data:**
```
php artisan db:seed --class="SpecificClass"
```
▶️ Running the Application
```
php artisan serve
```

**API base URL:** http://localhost:8000/api

**Admin panel:** http://localhost:8000/admin

🔐 Admin Panel (Filament)

The admin panel is built using Filament and Livewire, allowing admins to:

- Manage services

- Manage bookings

- Manage users

- View system data in a clean UI

📡 API Usage

This project exposes REST API endpoints that can be consumed by:

- Web frontends (Vue, React, etc.)

- Mobile applications

- Third-party services

- Authentication is handled using Laravel’s built-in auth (or tokens, if configured).

🛠️ Development Notes

- Business logic is kept in services and controllers

- Filament resources handle admin CRUD operations

- API routes are separated from admin routes

📄 License

This project is proprietary / internal use only.
(Or replace with MIT if applicable.)
