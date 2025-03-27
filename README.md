# 🚀 Full Stack PPM Backend (Laravel API)

## 📖 Overview
This is the backend API for **Full Stack PPM**, handling:

- ✅ **User Authentication** (Laravel Sanctum)
- ✅ **Role-Based Access Control (RBAC)** (Admin, Manager, User)
- ✅ **Debit Card Management**
- ✅ **Transactions** (Deposits, Withdrawals, Purchases)
- ✅ **Loan Management & Repayments**

---

## 📂 Project Structure
```
backendPPM/
│── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── UserController.php
│   │   │   ├── RoleController.php
│   │   │   ├── PermissionController.php
│   │   │   ├── DebitCardController.php
│   │   │   ├── TransactionController.php
│   │   │   ├── LoanController.php
│   │   │   ├── RepaymentController.php
│   │   │   ├── StructureController.php
│   │── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Permission.php
│   │   ├── DebitCard.php
│   │   ├── Transaction.php
│   │   ├── Loan.php
│   │   ├── Repayment.php
│── database/
│   ├── migrations/
│   ├── seeders/
│   │   ├── RolesSeeder.php
│   │   ├── PermissionsSeeder.php
│   ├── factories/
│── routes/
│   ├── api.php
│── .env
│── README.md
│── composer.json
│── package.json
│── artisan
│── server.php
```

---

## 🛠 Installation & Setup
### 1️⃣ Clone the Repository
```bash
git clone https://github.com/RichardMarkRoss/backendPPM.git
cd backendPPM
```

### 2️⃣ Install Dependencies
```bash
composer install
npm install
```

### 3️⃣ Set Up Environment Variables
Rename `.env.example` to `.env` and configure your database:
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

### 5️⃣ Run Migrations & Seed Database
```bash
php artisan migrate --seed
```

### 6️⃣ Start the Laravel Server
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan serve
```

The API will now be running at: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🔑 Authentication & Security
- **JWT-Based Authentication** using Laravel Sanctum.
- **Role-Based Access Control (RBAC)**
  - **Admin:** Full access.
  - **Manager:** Limited access.
  - **User:** Basic access.

#### 🔐 User Authentication Endpoints
| Method | Route          | Description |
|--------|--------------|-------------|
| `POST` | `/api/register` | Register new user |
| `POST` | `/api/login` | User login (returns token) |
| `POST` | `/api/logout` | Logout and revoke token |
| `GET`  | `/api/me` | Get authenticated user info |

Example Response:
```json
{
    "message": "User registered successfully",
    "user": {
        "name": "Admin User",
        "email": "admin@example.com",
        "role": {
            "id": 1,
            "name": "Admin",
            "permissions": ["create_user", "edit_user", "delete_user"]
        }
    },
    "token": "2|F15pDaB541cteFR0AX3NFA8PHknaPw4kT30n9lZcdff4bbe3"
}
```

---

## 💳 Debit Card Management
| Method | Route            | Description |
|--------|----------------|-------------|
| `GET`  | `/api/debit-cards` | List all debit cards |
| `POST` | `/api/debit-cards` | Create a new debit card |
| `GET`  | `/api/debit-cards/{id}` | Get details of a debit card |
| `PUT`  | `/api/debit-cards/{id}` | Update a debit card |
| `DELETE` | `/api/debit-cards/{id}` | Delete a debit card |

---

## 💸 Transactions
| Method | Route          | Description |
|--------|--------------|-------------|
| `GET`  | `/api/transactions` | Get all transactions |
| `POST` | `/api/transactions` | Create a new transaction |
| `GET`  | `/api/transactions/{id}` | Get transaction details |

**Example Payload**
```json
{
    "debit_card_id": 2,
    "amount": 500,
    "type": "purchase"
}
```

---

## 🏦 Loan Management
| Method | Route       | Description |
|--------|-----------|-------------|
| `GET`  | `/api/loans` | Get all loans |
| `POST` | `/api/loans` | Apply for a loan |
| `GET`  | `/api/loans/{id}` | Get loan details |

**Example Payload**
```json
{
    "amount": 3000,
    "term": 3
}
```

---

## 🛠 Tools & Technologies
- **Laravel 10** (Backend)
- **Sanctum** (Authentication)
- **MySQL** (Database)
- **Eloquent ORM**
- **RBAC** (Role-Based Access Control)
- **RESTful API**
- **Seeder & Factory for Data**

---

## 👥 Contributors
- **Richard Ross** - _Lead Developer_
- **Team PPM** - _Review & QA_

---

## ✅ Next Steps
- **🚀 Deploy Backend to AWS, Heroku, or DigitalOcean**
- **🔗 Connect to Frontend (React)**
- **📊 Build an Admin Panel for Role & Permission Management**

---

🚀 **Happy Coding!** 🎉
