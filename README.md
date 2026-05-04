<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Laravel Breeze 

Laravel Breeze provides a minimal and simple implementation of authentication for Laravel, including login, registration, password reset, and email verification.


## Requirements
Before installing Laravel Breeze, make sure you have:

- PHP >= 8.1
- Composer
- Node.js & NPM
- Laravel installed

## Installation Steps
Create a New Laravel Project

```bash
composer create-project laravel/laravel my-app
cd my-app
```

## Install Laravel Breeze

```bash
composer require laravel/breeze --dev
```

## Install Breeze Scaffolding
Blade (default)
```bash
php artisan breeze:install
```
## Vue

```bash
php artisan breeze:install vue
```

## React

```bash
php artisan breeze:install react
```

## API Only

```bash
php artisan breeze:install api
```

## Install Frontend Dependencies

```bash
npm install
npm run dev
```

## Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

## **Configure Database**

```bash
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Run Migrations

```bash
php artisan migrate
```

## **Start Development Server**

```bash
php artisan serve
```

## Features Included
- Login & Registration
- Password Reset
- Email Verification
- CSRF Protection
- Tailwind CSS UI

## Project Structure

```bash
app/
resources/views/
routes/
database/
```

## Testing

```bash
php artisan test
```

## Notes
- Breeze is ideal for simple authentication scaffolding.
- You can customize UI and logic easily.
- Works well with Blade, Vue, and React.

## Contributing
Feel free to fork this repo and submit pull requests.

## vLicense

This project is open-source and available under the MIT License.
