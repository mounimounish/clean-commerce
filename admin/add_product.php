<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
include '../includes/db.php';

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // Upload the image to the 'images' folder
    move_uploaded_file($_FILES['image']['tmp_name'], "../images/$image");

    // Insert product details into the database
    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $image]);

    echo "Product added successfully!";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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
            --add-color: #16a34a; /* Green for adding */
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
            max-width: 500px;
            margin: 2.5rem auto;
            padding: 2.5rem;
            background-color: #fff;
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

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-color-dark);
            margin-bottom: 0.5rem;
        }

        input, textarea {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(91, 33, 182, 0.2);
        }
        
        button {
            background-color: var(--add-color);
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #15803d;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 600;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: #4c1d95;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" required>
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            <label for="image">Image:</label>
            <input type="file" name="image" id="image" required>
            <button type="submit" name="add_product">Add Product</button>
        </form>
        <div class="back-link">
            <a href="manage_products.php">Back to Manage Products</a>
        </div>
    </div>
</body>
</html>

