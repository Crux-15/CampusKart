# CampusKart 🛒🎓
### A Peer-to-Peer Marketplace for University Students

CampusKart is a dedicated peer-to-peer marketplace platform built specifically for university students. It facilitates buying, selling, and exchanging academic resources — books, electronics, accessories — within a trusted campus community, moderated by admins who verify every user and listing before it goes live.

> Built as a course project for **Web Technologies** at **American International University-Bangladesh (AIUB)**.

---

## 🖼️ Screenshots

> *(Add screenshots here after running the project locally)*

---

## 🚀 Key Features

### 👨‍🎓 For Students
- **Secure Authentication** — Registration with a "Security Question" based password recovery system
- **Real-Time Messaging** — Built-in AJAX chat to negotiate prices privately without page reloads
- **Smart Search & Filtering** — Filter products by price, category, and keyword dynamically
- **Ad Management** — List items, edit listings, and mark items as "Sold"
- **Approval Workflow** — New accounts and product listings require Admin approval before going live

### 🛡️ For Admins
- **Moderation Dashboard** — Live counters for pending users and pending products
- **Student ID Verification** — Admins verify Student IDs before granting platform access
- **Content Control** — Full CRUD to delete inappropriate listings or suspend users
- **AJAX Search** — Instantly search through user records without page reloads

---

## 🛠️ Technology Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP (Custom MVC Architecture) |
| Database Access | PDO (Prepared Statements) |
| Frontend | HTML5, CSS3, Vanilla JavaScript + AJAX |
| Database | MySQL / MariaDB |
| Server | Apache (XAMPP) |

---

## 📂 Project Architecture (MVC)

This project follows a strict MVC pattern — no spaghetti code, no mixed concerns.

```
CampusKart/
├── app/
│   ├── config/
│   │   └── database.php          # DB credentials & PDO connection
│   ├── controllers/
│   │   ├── AdminController.php   # Admin dashboard logic
│   │   ├── PagesController.php   # Homepage & search
│   │   ├── ProductsController.php# Listings CRUD
│   │   └── UsersController.php   # Auth, profile, messages
│   ├── models/
│   │   ├── User.php              # User DB queries
│   │   ├── Product.php           # Product DB queries
│   │   ├── Message.php           # Chat DB queries
│   │   └── campuskart_db.sql     # ← Full database dump (import this)
│   └── views/
│       ├── admin/                # Admin panel templates
│       ├── pages/                # Homepage
│       ├── products/             # Listings, add, edit, details
│       └── users/                # Login, register, profile, chat
└── public/
    ├── index.php                 # Entry point & custom router
    ├── .htaccess                 # URL rewriting rules
    ├── css/                      # Page-specific stylesheets
    ├── js/                       # AJAX & interactive scripts
    └── img/                      # Uploaded product images
```

**How the router works:** Every request hits `public/index.php`, which parses the URL (e.g. `/products/show/5`) and dynamically dispatches to the correct Controller and Method — no framework needed.

---

## ⚙️ Local Setup Guide

Follow these steps to run CampusKart on your machine using XAMPP.

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (includes Apache + MySQL + PHP 8.x)
- A web browser

---

### Step 1 — Clone or Download the Project

```bash
git clone https://github.com/YOUR_USERNAME/CampusKart.git
```

Or download the ZIP and extract it.

---

### Step 2 — Move to XAMPP's Web Root

Place the project folder inside XAMPP's `htdocs` directory:

```
C:\xampp\htdocs\CampusKart\       ← Windows
/Applications/XAMPP/htdocs/CampusKart/   ← macOS
```

The final structure should look like:
```
htdocs/
└── CampusKart/
    ├── app/
    ├── public/
    └── .htaccess
```

---

### Step 3 — Import the Database

1. Start **Apache** and **MySQL** from the XAMPP Control Panel
2. Open your browser and go to: `http://localhost/phpmyadmin`
3. Click **"New"** in the left sidebar
4. Create a database named exactly: **`campuskart_db`**
5. Select the new database, click the **"Import"** tab
6. Click **"Choose File"** and select:
   ```
   CampusKart/app/models/campuskart_db.sql
   ```
7. Click **"Go"** — all tables and sample data will be imported

The database contains these tables: `users`, `products`, `messages`, `interests`, `orders`

---

### Step 4 — Configure the URL

Open `public/index.php` and make sure line 5 matches your setup:

```php
define('URLROOT', 'http://localhost/CampusKart');
```

> If you renamed the folder, update `CampusKart` to match your folder name.

The database credentials in `app/config/database.php` use XAMPP defaults (`root` / no password) — no changes needed for a standard XAMPP install.

---

### Step 5 — Run the Project

Open your browser and go to:

```
http://localhost/CampusKart
```

You should see the login page. Use the demo accounts below to explore.

---

## 🔑 Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| 🛡️ Admin | `admin@aiub.edu` | `123456` |
| 👨‍🎓 Student | `23-50636-1@student.aiub.edu` | `123456` |

> ⚠️ These are demo credentials for local testing only. Change passwords before any real deployment.

---

## 🗺️ Key Routes

| URL | Description |
|-----|-------------|
| `/users/login` | Login page |
| `/users/register` | Student registration |
| `/pages/index` | Homepage & product feed |
| `/products/show/{id}` | Product detail & chat |
| `/products/add` | List a new item for sale |
| `/products/listings` | Your active listings |
| `/users/messages` | Inbox |
| `/admin/index` | Admin dashboard *(admin only)* |
| `/admin/pendingUsers` | Approve new students *(admin only)* |
| `/admin/pendingProducts` | Approve new listings *(admin only)* |

---

## 🧑‍💻 Author

**SADMAN SAKIB**
Student, B.Sc. in Computer Science
American International University-Bangladesh (AIUB)

[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-blue?logo=linkedin)](https://www.linkedin.com/in/sadmaan-sakib/)
[![GitHub](https://img.shields.io/badge/GitHub-Follow-black?logo=github)](https://github.com/Crux-15)

---

## 📄 License & Copyright

© 2026 **SADMAN SAKIB**. All Rights Reserved.

This project was developed for academic purposes as part of the Web Technologies course at AIUB. The codebase, design, and all associated assets are the sole intellectual property of the author.

**Unauthorized use is strictly prohibited.** You may not copy, reproduce, distribute, modify, or use any part of this project — in whole or in part — for commercial purposes without explicit written permission from the author.

Any form of plagiarism, code theft, or commercial exploitation will be treated as a copyright violation and pursued accordingly under applicable intellectual property law.

> If you'd like to use or reference this project, please reach out via LinkedIn or GitHub first.
