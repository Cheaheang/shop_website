# Laravel + Vue Product CRUD

This repository now contains a Laravel/Vue product CRUD implementation with image upload support.

## Features

- List, create, update, and delete products
- Product image upload
- Uploaded images are stored in `storage/app/public/products`
- Vue 3 frontend UI for managing products

## Main files

- Backend routes: `routes/api.php`
- Controller: `app/Http/Controllers/ProductController.php`
- Model: `app/Models/Product.php`
- Migration: `database/migrations/2026_02_20_000000_create_products_table.php`
- Vue app: `resources/js/components/ProductManager.vue`

## Setup

> Note: The current environment blocks external package downloads, so `composer install` / `npm install` cannot be completed here. Run these commands in an environment with access to package registries.

1. Install dependencies:

```bash
composer install
npm install
```

2. Prepare environment and database:

```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan storage:link
```

3. Run app:

```bash
php artisan serve
npm run dev
```

Then open `http://127.0.0.1:8000`.
