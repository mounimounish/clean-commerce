<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* --- Minimalistic Design with CSS Variables --- */
        :root {
            --primary-color: #5b21b6;
            --text-color-dark: #1f2937;
            --text-color-light: #6b7280;
            --background-light: #f9fafb;
            --background-dark: #f3f4f6;
            --border-color: #d1d5db;
            --shadow-color: rgba(0, 0, 0, 0.05);
            --logout-color: #dc2626;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-light);
            color: var(--text-color-dark);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px var(--shadow-color), 0 2px 4px -1px var(--shadow-color);
        }

        h2 {
            text-align: center;
            color: var(--text-color-dark);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1.5rem;
        }
        
        nav {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-top: 2rem;
            align-items: center;
        }

        nav a, .logout-button {
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease, transform 0.2s ease;
            width: 100%;
            text-align: center;
            border: 1px solid var(--primary-color);
            background-color: white;
            color: var(--primary-color);
        }

        nav a:hover, .logout-button:hover {
            transform: translateY(-0.125rem);
            background-color: var(--primary-color);
            color: white;
        }
        
        .logout-button {
            border-color: var(--logout-color);
            color: var(--logout-color);
            background-color: white;
        }
        
        .logout-button:hover {
            background-color: var(--logout-color);
            color: white;
        }

        footer {
            text-align: center;
            margin-top: 3rem;
            font-size: 0.8rem;
            color: var(--text-color-light);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <nav>
            <a href="add_product.php">Add Product</a>
            <a href="manage_products.php">Manage Products</a>
            <form method="POST" style="width: 100%; text-align: center;">
               
                <button type="submit" name="logout" class="logout-button">Logout</button>
            </form>
        </nav>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Admin Dashboard. All rights reserved.</p>
    </footer>
</body>
</html>
