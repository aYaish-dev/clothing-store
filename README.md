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
- View orders with status management and receive email notifications for new orders

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
   - `db.php` reads MySQL credentials from environment variables. Copy `.env.example` to `.env` and adjust the values of `DB_HOST`, `DB_USER`, `DB_PASS`, and `DB_NAME` as needed (defaults are `localhost`, `root`, an empty password, and `clothing_store`).
   - Ensure the `mysqli` extension is enabled in `php.ini`.
   - Verify `file_uploads` is `On` and adjust `upload_max_filesize` if needed for product images.

4. **Migrating Admin Passwords**:
     - The `users` table now stores hashed passwords. If you are upgrading from an
       older version where the admin password was stored in plain text, run:
       ```bash
       php migrate_admin_password.php your_old_password
       ```
       This will hash the password and update the admin record.

5. Start the development server:
   ```bash
   php -S localhost:8000
   ```
   Then open `http://localhost:8000/index.php` in your browser.

The main stylesheet is located at `assets/css/style.css` if you wish to tweak the look and feel.

## 🧪 Running Tests

This project uses PHPUnit for unit tests.
Install Composer dependencies and run the suite with:

```bash
composer install
./vendor/bin/phpunit
```
