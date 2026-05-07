# Test 5 — Ecommerce

A small ecommerce sandbox built with Laravel 12. Demonstrates basic product
catalog, authentication, and checkout flow.

## Stack

- **PHP** 8.2+
- **Laravel** 12
- **Database** MySQL 8.0+

## Tickets in scope

This project tracks work via [Flexpick](http://localhost:8000/admin/projects/5).
Initial tickets:

- LARA-1 — Install Laravel
- LARA-2 — Configure Laravel
- LARA-3 — Define Entities
- LARA-4 — Define Value Objects
- LARA-5 — Setup Database
- LARA-6 — Setup API Gateway
- LARA-7 — Implement Authentication
- LARA-8 — Implement Payment Gateway

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
