<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Check if a product ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = $_GET['id'];

// Handle product update form submission
if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    // Handle image upload if a new image is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../images/';
        $image_name = basename($_FILES['image']['name']);
        $target_file = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Update the database with the new image name
            $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
            $stmt->execute([$name, $price, $description, $image_name, $product_id]);
            $success_message = "Product updated successfully!";
        } else {
            $error_message = "Failed to upload image.";
        }
    } else {
        // Update the database without changing the image
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $price, $description, $product_id]);
        $success_message = "Product updated successfully!";
    }
}

// Fetch the current product details from the database
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<p style='text-align:center; color: red;'>Product not found!</p>";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
            --edit-color: #5b21b6; /* Use primary color for update action */
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
            background-color: var(--edit-color);
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
            background-color: #4c1d95;
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
        
        .message {
            text-align: center;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .message.success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #34d399;
        }

        .message.error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Product</h2>

        <?php if (isset($success_message)): ?>
            <p class="message success"><?= htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <p class="message error"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']); ?>" required>

            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($product['price']); ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($product['description']); ?></textarea>

            <label for="current-image">Current Image:</label>
            <img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="Current Product Image" style="max-width: 150px; margin-bottom: 1.5rem;">
            
            <label for="image">Upload New Image (optional):</label>
            <input type="file" name="image" id="image">

            <button type="submit" name="update_product">Update Product</button>
        </form>
        <div class="back-link">
            <a href="manage_products.php">Back to Manage Products</a>
        </div>
    </div>
</body>
</html>
