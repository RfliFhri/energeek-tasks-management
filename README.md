a<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Energeek Task Management Application

Energeek Task Management Application adalah aplikasi manajemen tugas yang dibangun menggunakan Laravel. Aplikasi ini memungkinkan pengguna untuk membuat, mengelola, dan menghapus to-do list secara efisien.

## Prerequisites

-   PHP 8.3
-   Composer
-   Node.js & NPM
-   Laravel 11.x
-   PostgreSQL
-   Laragon

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/RfliFhri/energeek-task-management.git
cd energeek-task-management
composer install
npm install
npm run dev
```

### 3. Configure the `.env` file

Copy the `.env.example` to `.env` and update the following variables to match your environment:
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

## Running the Application

### Start the Laravel development server:

```bash
php artisan serve
http://energeek.test:8080/
```

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
