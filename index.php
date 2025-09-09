<?php
session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
include 'includes/db.php'; // Include the database connection

// Fetch products from the database
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <style>
        /* --- Minimalistic Design with CSS Variables --- */
        :root {
            --primary-color: #5b21b6;
            --accent-color: #fca5a5;
            --text-color-dark: #1f2937;
            --text-color-light: #6b7280;
            --background-light: #f9fafb;
            --background-dark: #f3f4f6;
            --border-color: #d1d5db;
            --shadow-color: rgba(0, 0, 0, 0.05);
            --error-color: #ef4444;
        }

        /* --- Global Styles & Typography --- */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-light);
            color: var(--text-color-dark);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        h1, h2, h3 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            color: var(--text-color-dark);
            margin-top: 0;
        }

        /* --- Header & Navigation Bar --- */
        header {
            background-color: #fff;
            box-shadow: 0 1px 3px var(--shadow-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            letter-spacing: -0.02em;
        }

        nav {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        nav a, .logout-button {
            color: var(--text-color-light);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s ease-in-out;
        }

        nav a:hover {
            color: var(--primary-color);
        }

        .logout-button {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        .logout-button:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .cart-link {
            color: var(--text-color-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s ease-in-out;
        }

        .cart-link:hover {
            color: var(--primary-color);
        }
        
        .cart-icon {
            width: 1.5rem;
            height: 1.5rem;
            filter: invert(30%) sepia(0%) saturate(2000%) hue-rotate(180deg) brightness(80%);
            transition: filter 0.2s ease-in-out;
        }

        .cart-link:hover .cart-icon {
            filter: invert(21%) sepia(74%) saturate(2250%) hue-rotate(242deg) brightness(97%) contrast(97%);
        }

        /* --- Main Content & Product Grid --- */
        .main-container {
            padding: 2rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h2 {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }
        
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .product {
            background-color: #fff;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            overflow: hidden;
            text-align: left;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .product:hover {
            transform: translateY(-0.25rem);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
            border-bottom: 1px solid var(--border-color);
        }

        .product-info {
            padding: 1.25rem;
        }

        .product h3 {
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .product p {
            font-size: 0.9rem;
            color: var(--text-color-light);
            margin-bottom: 1rem;
        }

        .add-to-cart-button {
            background-color: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            box-shadow: none;
        }

        .add-to-cart-button:hover {
            background-color: #4c1d95;
        }

        .no-products {
            text-align: center;
            color: var(--text-color-light);
            font-size: 1rem;
            padding: 2rem;
            background-color: var(--background-dark);
            border-radius: 0.5rem;
        }

        /* --- Footer --- */
        footer {
            background-color: var(--background-dark);
            color: var(--text-color-light);
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 3rem;
            font-size: 0.8rem;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <a href="#" class="logo">Wassup Buddy,</a>
            <nav>
                <?php if ($is_logged_in) : ?>
                    <a href="pages/cart.php" class="cart-link">
                        <img src="images/cart-icon.png" alt="Cart" class="cart-icon">
                        Cart
                    </a>
                    <form method="POST" style="display: inline;">
                        <button type="submit" name="logout" class="logout-button">Logout</button>
                    </form>
                <?php else : ?>
                    <a href="pages/login.php">Login</a>
                    <a href="pages/register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="main-container">
        <main>
            <h2>Products</h2>
            <div class="product-list">
                <?php if (empty($products)) : ?>
                    <p class="no-products">No products available.</p>
                <?php else : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="product">
                            <?php if (!empty($product['image'])) : ?>
                                <img src="images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image">
                            <?php endif; ?>
                            <div class="product-info">
                                <h3><?= htmlspecialchars($product['name']); ?></h3>
                                <p>Price: $<?= number_format($product['price'], 2); ?></p>
                                <p><?= htmlspecialchars($product['description']); ?></p>
                                <form method="POST" action="pages/cart.php">
                                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                    <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <footer>
        <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
    </footer>
</body>
</html>
