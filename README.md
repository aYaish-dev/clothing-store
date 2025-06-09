# 👕 Clothing Store (PHP + MySQL)

This is a complete web-based clothing store project built using **PHP**, **MySQL**, and **Bootstrap**.  
It supports both frontend and admin functionalities, along with size-based inventory management and cart handling.

---

## 📦 Features

### 🛍️ For Customers
- Browse products by category (Men, Women, Kids)
- Select size for each product
- Add to cart with size-specific stock control
- View cart with real-time quantity updates (via AJAX)
- Secure checkout with order summary

### 🔐 Admin Panel
- Login system for admins
- Add / Edit / Delete products
- Manage stock for each size separately
- View orders and user details

---

## 🗃️ Technologies Used

- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend**: PHP 8
- **Database**: MySQL (via phpMyAdmin)
- **AJAX**: Dynamic cart updates

---

## 🛠️ Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/clothing-store.git
   ```

2. Create the MySQL database and import the schema:
   - Create a database named `clothing_store`.
   - Import `clothing_store.sql` using phpMyAdmin or the command line:
     ```bash
     mysql -u root -p clothing_store < clothing_store.sql
     ```

3. Configure PHP:
   - Update `db.php` with your MySQL credentials if they differ from the defaults.
   - Ensure the `mysqli` extension is enabled in `php.ini`.
   - Verify `file_uploads` is `On` and adjust `upload_max_filesize` if needed for product images.
