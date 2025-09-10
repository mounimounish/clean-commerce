# Clean Commerce

_A Modern E-commerce Platform Built with PHP and MySQL_

---

## 🚀 Project Overview
Clean Commerce is a robust and modern e-commerce application designed to provide a seamless shopping experience for users and an intuitive administration panel for store owners. It features a clean, minimalistic user interface and a secure, database-driven backend.

---

## ✨ Key Features
- **Modern UI:** Clean and responsive design built with a custom CSS framework, ensuring an aesthetic and mobile-friendly experience.
- **User Authentication:** Secure login and registration for users, with password hashing for enhanced security.
- **Product Catalog:** Products are fetched from a MySQL database and displayed in an organized, categorized format on the homepage.
- **Shopping Cart:** Persistent shopping cart system that allows users to add, update, and remove products.
- **Admin Panel:** Dedicated, secure dashboard for administrators to manage products.
- **CRUD Functionality:** Full Create, Read, Update, and Delete functionality for products, enabling seamless management of the store catalog.

---

## 🛠️ Technologies Used
- **PHP:** Core server-side scripting language for all backend logic.
- **MySQL:** Relational database used to store all product, user, and cart data.
- **HTML & CSS:** Semantic markup and a custom, modern stylesheet for a clean frontend.
- **XAMPP:** Local server environment used for development, including Apache and MySQL.
- **Git & GitHub:** For version control and collaborative development.

---

## 🏁 Getting Started
Follow these instructions to get a copy of the project up and running on your local machine.

### Prerequisites
- A local server environment like **XAMPP** (recommended) or any other AMP stack.
- A web browser.

### Installation
#### 1. Clone the repository
```bash
git clone https://github.com/your-username/your-repo-name.git
```

#### 2. Set up the database
- Start **Apache** and **MySQL** in your XAMPP control panel.
- Open **phpMyAdmin** in your browser.
- Create a new database named `ecommerce`.
- Import the `ecommerce.sql` file (found in the root of the project) to create the necessary tables.

#### 3. Place the project files
- Move the cloned project folder into your XAMPP `htdocs` directory.

#### 4. Configure the database connection
- Open `includes/db.php`.
- Update the database credentials if necessary (default XAMPP settings should work).

#### 5. Run the application
- Open your web browser and navigate to:
```
http://localhost/your-project-folder-name/
```

---

## 📦 Usage
### For Users
- Browse the homepage to view products.
- Click on a product to view details.
- Log in to add items to your cart and proceed to checkout.

### For Admins
- Navigate to `/admin_login.php` to access the admin dashboard.
- Use the dashboard to manage all products, including adding new ones and editing or deleting existing items.

---

## 🤝 Contributing
We welcome contributions! If you would like to improve this project, please follow these steps:
1. Fork the repository.
2. Create a new branch for your feature:
	```bash
	git checkout -b feature/your-feature-name
	```
3. Commit your changes:
	```bash
	git commit -am 'Add new feature'
	```
4. Push to the branch:
	```bash
	git push origin feature/your-feature-name
	```
5. Create a new Pull Request.

---

## 📄 License
This project is licensed under the MIT License. See the `LICENSE.md` file for details.
