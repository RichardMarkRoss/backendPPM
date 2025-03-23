# 🚀 Backend Progress (Laravel API)

## ✅ Authentication (Completed)

-   ✔ User Register, Login, Logout with Bearer Token (Sanctum)
-   ✔ GET `/api/me` - Fetch authenticated user details

## ✅ Debit Card System (Completed)

-   ✔ Created `DebitCard` model, migration, and controller
-   ✔ Implemented CRUD operations
-   ✔ Restricted access to only the authenticated user's debit cards

## ✅ Transactions System (Completed)

-   ✔ Create `Transaction` model & migration
-   ✔ Implement transactions for deposits, withdrawals, and purchases
-   ✔ Ensure access control (users can only manage their own transactions)

### Loan System (Completed)

-   ✔ Create `Loan` model & migration
-   ✔ Implement loan repayments & balance tracking
-   ✔ API endpoints for loan application, payments, and schedule

# 🚀 Full Stack PPM

## 📖 Overview

This project is a **Full Stack PPM** handling:

-   ✅ **User Authentication** (Laravel Sanctum)
-   ✅ **Debit Card Management**
-   ✅ **Transactions** (Deposits, Withdrawals, Purchases)
-   ✅ **Loan Management & Repayments**

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/RichardMarkRoss/backendPPM
cd project_folder
```

### 2️⃣ Install Dependencies

```bash
composer install
npm install
```

### 3️⃣ Set Up Environment Variables

Rename .env.example to .env:

```bash
cp .env.example .env
```

Then update .env with your database details:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4️⃣ Generate Application Key

```bash
php artisan key:generate
```

### 5️⃣ Run Migrations & Seed Database\

```bash
php artisan migrate --seed
```

### 6️⃣ Start the Server
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan serve
```

Your API will run at: http://127.0.0.1:8000

