<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
include '../includes/db.php';
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
            --edit-color: #16a34a; /* Green for edit */
            --delete-color: #dc2626; /* Red for delete */
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
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1000px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            font-size: 0.9rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: var(--background-dark);
            color: var(--text-color-dark);
            font-weight: 600;
        }
        
        td img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.25rem;
            border: 1px solid var(--border-color);
        }

        .actions {
            white-space: nowrap;
        }

        .actions a, .btn-back {
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: background-color 0.2s, color 0.2s;
            border: 1px solid;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .actions .edit-btn {
            color: var(--edit-color);
            border-color: var(--edit-color);
        }

        .actions .edit-btn:hover {
            background-color: var(--edit-color);
            color: white;
        }

        .actions .delete-btn {
            color: var(--delete-color);
            border-color: var(--delete-color);
        }

        .actions .delete-btn:hover {
            background-color: var(--delete-color);
            color: white;
        }
        
        .btn-back {
            display: block;
            margin: 2rem auto 0;
            text-align: center;
            background-color: white;
            color: var(--primary-color);
            border-color: var(--primary-color);
            max-width: 200px;
        }

        .btn-back:hover {
            background-color: var(--primary-color);
            color: white;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Manage Products</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-color-light);">No products found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?= $product['id']; ?></td>
                        <td><?= htmlspecialchars($product['name']); ?></td>
                        <td>$<?= number_format($product['price'], 2); ?></td>
                        <td><?= htmlspecialchars($product['description']); ?></td>
                        <td><img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="Product Image"></td>
                        <td class="actions">
                            <a href="edit_product.php?id=<?= $product['id']; ?>" class="edit-btn">Edit</a>
                            <a href="delete_product.php?id=<?= $product['id']; ?>" class="delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
</div>

</body>
</html>
