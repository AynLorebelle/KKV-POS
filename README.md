# KKV POS System



A modern, premium Point of Sale (POS) and Inventory Management system built with **Laravel 11** and **Laravel Breeze**. This application features a robust backend with role-based access control, a high-performance Vue/Blade frontend, and a beautiful, responsive user interface following a strict five-color design system.

---

## Table of Contents
1. [Project Overview](#project-overview)
2. [Technical Stack](#technical-stack)
3. [System Features](#system-features)
4. [Step-by-Step Setup Guide (Instructor Evaluation)](#step-by-step-setup-guide)
5. [Database Architecture](#database-architecture)
6. [System Flow](#system-flow)

---

##  Project Overview
The KKV POS System is designed to handle core retail operations, including product management, stock tracking, and secure transaction processing. It serves both internal staff (Admins/Cashiers) through an advanced Dashboard and end-customers through a unified Customer Portal.

---

##  Technical Stack
- **Framework:** Laravel 11.x
- **Authentication:** Laravel Breeze
- **Database:** MySQL
- **Frontend Styling:** Vanilla CSS with custom design tokens, Blade Templates
- **Icons & Assets:** Custom SVG, Google Fonts (Outfit/Inter)
- **Local Server Environment:** XAMPP / Laravel Valet / Artisan Serve

---

## System Features

### 1. Role-Based Access Control (RBAC)
- **Admin(`role: admin`)**: Full access to POS operations, inventory management, transaction history,add staff like cashier/admin and sales reporting where the admin can edit/delete/add/update products and transactions.
**Cashier (`role: cashier`)**: Can access to POS operations but  limited to checkout and update product management only.
- **Customer (`role: customer`)**: Limited to view products only.

### 2. POS Operations
- Real-time cart calculation (Total, VAT, Change).
- Dynamic, animated thermal receipt generation upon checkout.
- Barcode scanning compatibility.

### 3. Inventory Management
- Full CRUD operations for Products.
- Stock level monitoring.
- Product barcodes, pricing, and automated status updates.

### 4. Advanced UI/UX
- Premium dynamic design system with smooth micro-animations.
- High-contrast custom aesthetic reflecting the "KKV" brand.
- Fully responsive layouts utilizing CSS Grid and Flexbox.

---

## 🚀 Step-by-Step Setup Guide

This section provides a strict, step-by-step guide to run this project in a local environment (e.g., XAMPP, Laragon) for evaluation purposes.

### Prerequisites
- PHP 8.2 or higher
- Composer 2.x
- Node.js (v18+) & npm
- MySQL Server (via XAMPP)

### Step 1: Clone or Extract the Project
Extract the project archive into your local server's web root (e.g., `C:\xampp\htdocs\CAVAN_FINAL_PROJECT`).

```bash
cd C:\xampp\htdocs\CAVAN_FINAL_PROJECT
```

### Step 2: Install PHP Dependencies
Run Composer to install all Laravel vendor packages.
```bash
composer install
```

### Step 3: Install Node Dependencies
Install frontend assets required by Laravel Breeze.
```bash
npm install
npm run build
```
*(Use `npm run dev` if you wish to run the Vite development server)*

### Step 4: Environment Configuration
Copy the `.env.example` file to create a `.env` file.
```bash
copy .env.example .env
```
Generate the application key:
```bash
php artisan key:generate
```

### Step 5: Database Setup
1. Open XAMPP Control Panel and start **MySQL** and **Apache**.
2. Open phpMyAdmin (`http://localhost/phpmyadmin`).
3. Create a new database named **`KKV_DB`**.
4. Update your `.env` file to match the database configuration:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=KKV_DB
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Step 6: Run Migrations
Execute the database migrations to create the required tables.
```bash
php artisan migrate
```
*(Note: A `db.sql` file is also provided in the root directory for manual import or schema review if preferred.)*

### Step 7: Start the Application
Start the Laravel development server:
```bash
php artisan serve
```
The application will be available at `http://127.0.0.1:8000`.

### Step 8: Testing the Application
1. **Register** a new account via `http://127.0.0.1:8000/register`. By default, newly registered users are assigned the `customer` role.
2. **Promote to Admin (Optional):** To test POS features, open phpMyAdmin, go to the `users` table, and change the `role` column of your user from `customer` to `admin`.
3. Log in to access the respective dashboards.

---

## 🗄 Database Architecture

The system utilizes an optimized relational schema consisting of the following primary tables:

1. **`users`**: Manages authentication and roles (`admin`, `cashier`,`customer`).
2. **`products`**: Central repository for store inventory (barcodes, names, pricing, stock levels).
3. **`transactions`**: Records of sale (invoice numbers, cashier details, tax computations, cash tendered).
4. **`transaction_items`**: Pivot/Details table linking `transactions` to `products`, tracking the quantity, unit price, and subtotal per item at the time of purchase.

Relationships:
- A `Transaction` **has many** `TransactionItems`.
- A `Product` **has many** `TransactionItems`.

*(Refer to `db.sql` for the explicit Data Definition Language (DDL) queries and constraints).*

---

## 🔄 System Flow

1. **Authentication:** User logs in via the Breeze auth scaffold. Middleware intercepts the request and routes `admin` users to the Admin Dashboard, `cashier` users to the Cashier Dashboard and `customer` users to the Customer Dashboard.
2. **Inventory Sync:** Admins add products with barcodes and initial stock. 
3. **Point of Sale:** Cashier scans/selects products. The system dynamically updates the cart total and VAT computations via interactive JS/Frontend logic.
4. **Checkout:** Upon tender, the system writes to the `transactions` and `transaction_items` tables, simultaneously decrementing product `stock`.
5. **Receipt:** A dynamic thermal receipt is presented for printing.

---
*Developed by Ayn Lorebelle Cavan