# CampusKart 🛒🎓
### A Peer-to-Peer Marketplace for University Students

CampusKart is a dedicated peer-to-peer marketplace platform built specifically for university students. It facilitates buying, selling, and exchanging academic resources — books, electronics, accessories — within a trusted campus community, moderated by admins who verify every user and listing before it goes live.

> Built as a course project for **Web Technologies** at **American International University-Bangladesh (AIUB)**.

---

## 🖼️ Screenshots

> Signup: <img width="1863" height="870" alt="image" src="https://github.com/user-attachments/assets/b2c94f4d-7bce-4588-a29c-c700f9eed89e" />
> Login: <img width="1864" height="874" alt="image" src="https://github.com/user-attachments/assets/719e2ea6-f2aa-4bad-8b91-ebfd31cf3d59" />
> Admin Dashboard: <img width="1862" height="873" alt="image" src="https://github.com/user-attachments/assets/6825b899-8100-4d76-8b60-43c0aafb9696" />
> User List: <img width="1843" height="871" alt="image" src="https://github.com/user-attachments/assets/8486f13e-7bba-4995-af64-d6d6762184bc" />
> User Search(AJAX): <img width="1862" height="872" alt="image" src="https://github.com/user-attachments/assets/e101b0d8-af06-4c46-a802-7d24efe0db5b" />
> User Requests: <img width="1864" height="872" alt="image" src="https://github.com/user-attachments/assets/d2782842-941e-4dc3-9af9-bd014eae194d" />
> Product Requests: <img width="1854" height="848" alt="image" src="https://github.com/user-attachments/assets/1e831d16-b095-4da5-bf18-ded8bd520ae4" />
> Create Admin Page: <img width="1850" height="859" alt="image" src="https://github.com/user-attachments/assets/a543d9cb-04cc-4690-8d76-3e94bead7b8f" />
> Product List: <img width="1845" height="869" alt="image" src="https://github.com/user-attachments/assets/504f4b77-1504-46d6-b04f-4e4ad619b302" />
> Product Search(AJAX): <img width="1864" height="873" alt="image" src="https://github.com/user-attachments/assets/e8322d90-d0ac-46c0-9fe9-012904833d18" />
> Student Dashboard: <img width="1843" height="869" alt="image" src="https://github.com/user-attachments/assets/cc70d570-61d8-4760-b747-d7f1bb95b4c0" />
> Filter Product(Min-Max Price Range): <img width="1863" height="870" alt="image" src="https://github.com/user-attachments/assets/624de944-5391-4c10-b3d2-a5ae4f1a751d" />
> Search By Product Name: <img width="1863" height="874" alt="image" src="https://github.com/user-attachments/assets/888d80fd-a982-4565-b947-34c889a0e878" />
> Message Page(Real Time Messaging by Product): <img width="1842" height="873" alt="image" src="https://github.com/user-attachments/assets/1e67786d-5865-49e5-9a22-57405bccc19e" />
> View Product Details Page: <img width="1863" height="870" alt="image" src="https://github.com/user-attachments/assets/60ac8dbb-10ae-413a-9a6b-e2bd668327c4" />
> View Product Details Page(After press "I'm Interested" & The Buyer's phone number is sent to the seller ): <img width="1864" height="873" alt="image" src="https://github.com/user-attachments/assets/39404bbd-e68c-401b-b5d2-1f3eea12c4c1" />
> View Product Details Page(After Clicking on "Send Message" Button): <img width="1861" height="874" alt="image" src="https://github.com/user-attachments/assets/2a6501fd-919b-437a-9cb1-6e20382ee8b2" />
> Notification Tab: <img width="1864" height="875" alt="image" src="https://github.com/user-attachments/assets/2011e298-f75f-4e7d-9ee8-6a47033db918" />
> Sell Post Page: <img width="1838" height="872" alt="image" src="https://github.com/user-attachments/assets/180d1565-c154-4a18-b4fb-dd320a6be3fa" />
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
| 🛡️ Admin | `admin@campuskart.aiub` | `123456` |
| 👨‍🎓 Student | `23-50636-1@aiub.edu` | `123456` |

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
