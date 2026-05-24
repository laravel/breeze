<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>





<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php">
  <img src="https://img.shields.io/badge/Breeze-Authentication-success?style=for-the-badge">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge">
</p>

<p align="center">
  Minimal and elegant authentication scaffolding for Laravel applications using Laravel Breeze.
</p>

---

## 📖 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Screenshots](#screenshots)
- [Project Structure](#project-structure)
- [Available Commands](#available-commands)
- [Troubleshooting](#troubleshooting)
- [License](#license)

---

## 🚀 Overview

Laravel Breeze is a lightweight authentication starter kit for Laravel.

It provides:

- Login
- Registration
- Password Reset
- Email Verification
- Profile Management
- Tailwind CSS UI

Perfect for developers who need clean authentication scaffolding without unnecessary complexity.

---

## ✨ Features

✅ Authentication System  
✅ Clean Tailwind UI  
✅ Blade / Vue / React Support  
✅ Secure Password Reset  
✅ Email Verification  
✅ Laravel Best Practices  
✅ Super Admin & User Roles (first registered user becomes super admin)

---

## 📋 Requirements

Before installation, ensure you have:

| Requirement | Version |
|------------|---------|
| PHP | 8.1+ |
| Composer | Latest |
| Node.js | 18+ |
| NPM | Latest |
| Laravel | 10+ |

---

## ⚙️ Installation

### 1. Create Laravel Project

```bash
composer create-project laravel/laravel my-app
cd my-app
```

---

### 2. Install Breeze

```bash
composer require laravel/breeze --dev
```

---

### 3. Install Authentication Scaffolding

### Blade
```bash
php artisan breeze:install
```

### Vue
```bash
php artisan breeze:install vue
```

### React
```bash
php artisan breeze:install react
```

### API
```bash
php artisan breeze:install api
```

---

### 4. Install Dependencies

```bash
npm install
npm run dev
```

---

### 5. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

---

### 6. Setup Database

Update `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_breeze
DB_USERNAME=root
DB_PASSWORD=
```

---

### 7. Run Migration

```bash
php artisan migrate
```

---

### 8. Start Server

```bash
php artisan serve
```

Visit:

```arduino
http://127.0.0.1:8000
```




## 📁 Project Structure

```bash
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
```

---

## 🛠 Available Commands

| Command | Description |
|---------|-------------|
| `php artisan serve` | Start local server |
| `npm run dev` | Run Vite dev server |
| `php artisan migrate` | Run database migrations |
| `php artisan test` | Run tests |

---

## 🐞 Troubleshooting

### Clear Cache

```bash
php artisan optimize:clear
```

### Rebuild Frontend

```bash
npm install && npm run build
```

### Reset Database

```bash
php artisan migrate:fresh
```

---

## 🔐 Super Admin

When a new application is scaffolded with Breeze, the package will mark the very first registered user as a super admin by setting an `is_super_admin` boolean on the `users` table. This makes it easy to bootstrap an admin account during initial setup.

If your `users` table doesn't include the column yet, add a migration like this:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->boolean('is_super_admin')->default(false)->after('remember_token');
    });
  }

  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('is_super_admin');
    });
  }
};
```

Then run:

```bash
php artisan migrate
```

In your application you can check the role with `auth()->user()->is_super_admin`.


## 🤝 Contributing

Pull requests are welcome.

For major changes, please open an issue first.

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Author

**Your Name**

GitHub: [@yourusername](https://github.com/IrfanGhuori)

---

<p align="center">
Made with ❤️ using Laravel Breeze
</p>
