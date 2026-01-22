# CampusKart 🛒🎓

**CampusKart** is a dedicated peer-to-peer marketplace platform designed specifically for university students. It facilitates the buying, selling, and exchanging of academic resources (books, electronics, accessories) & also the Home-Rental ad within a trusted campus community.

The project is built using a custom **MVC (Model-View-Controller) Framework** in native PHP, ensuring a secure, scalable, and organized codebase.

---

## 🚀 Key Features

### 👨‍🎓 For Students (Users)
* **Secure Authentication:** Registration with email verification and "Security Question" based password recovery.
* **Real-Time Messaging:** Built-in chat system using AJAX to negotiate prices privately without page reloads.
* **Smart Search & Filtering:** Filter products by Price, Category, and specific keywords dynamically.
* **Ad Management:** Students can list items, edit their ads, and mark items as "Sold".
* **Security:** New accounts and product listings require Admin approval before going live.

### 🛡️ For Admins (Moderators)
* **Moderation Dashboard:** Real-time counters for "Pending Users" and "Pending Products".
* **User Verification:** Admins verify Student IDs before approving access to the platform.
* **Content Control:** Full CRUD capabilities to delete inappropriate listings or ban users.
* **AJAX Search:** Instantly search through thousands of user records without reloading the page.

---

## 🛠️ Technology Stack

* **Backend:** PHP (Native MVC Architecture), PDO (Database Security)
* **Frontend:** HTML5, CSS3, JavaScript (Vanilla + AJAX)
* **Database:** MySQL
* **Server:** Apache (XAMPP)

---

## 📂 Project Architecture (MVC)

This project avoids "Spaghetti Code" by strictly following the MVC pattern:

```text
/app
  ├── /config          # Database & URL Constants
  ├── /controllers     # Logic (Users, Products, Admin)
  ├── /models          # Database Queries (User.php, Product.php)
  ├── /views           # HTML Templates (Login, Dashboard, Chat)
/public
  ├── /css             # Stylesheets
  ├── /js              # AJAX & Interactive Scripts
  ├── /img             # Product Images
