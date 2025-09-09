<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id']; 

// Handle Add to Cart with Quantity
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; 

    // Check if product is already in the user's cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        // Update quantity if the product is already in the cart
        $new_quantity = $cart_item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$new_quantity, $user_id, $product_id]);
    } else {
        // Add new product to the cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }
}

// Handle Product Removal from Cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
}

// Handle Quantity Update
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Update the quantity in the cart
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$quantity, $user_id, $product_id]);
}

// Fetch the user's cart items
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_cost = 0; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
/* --- Minimalistic Design with CSS Variables --- */
:root {
    --primary-color: #5b21b6; /* Consistent with previous file */
    --accent-color: #fca5a5;
    --text-color-dark: #1f2937;
    --text-color-light: #6b7280;
    --background-light: #f9fafb;
    --background-dark: #f3f4f6;
    --border-color: #d1d5db;
    --shadow-color: rgba(0, 0, 0, 0.05);
}

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--background-light);
    color: var(--text-color-dark);
    margin: 0;
    padding: 2rem 1rem;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
}

.container {
    width: 100%;
    max-width: 900px;
    background-color: #fff;
    padding: 2rem;
    border-radius: 0.75rem;
    border: 1px solid var(--border-color); /* Subtle border for clean lines */
    box-shadow: 0 4px 6px -1px var(--shadow-color), 0 2px 4px -1px var(--shadow-color);
}

h2 {
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-color-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-color); /* Lighter, more minimal line */
}

/* --- Cart Item Styling --- */
.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    margin-bottom: 1rem;
    background-color: var(--background-dark);
    border-radius: 0.5rem;
    border: 1px solid var(--border-color);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.cart-item:hover {
    transform: translateY(-0.25rem); /* Less dramatic hover effect */
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.cart-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 0.5rem;
    margin-right: 1.5rem;
    border: 1px solid var(--border-color);
}

.item-details {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.item-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-color-dark);
}

.item-price {
    font-size: 0.9rem;
    color: var(--text-color-light);
    font-weight: 500;
}

.item-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.item-actions form {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* --- Form Elements and Buttons --- */
.quantity {
    width: 4rem; /* Use rem for consistent sizing */
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: 0.25rem;
    text-align: center;
    font-size: 0.9rem;
    transition: border-color 0.2s;
}

.quantity:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(91, 33, 182, 0.2);
}

.btn {
    padding: 0.65rem 1rem;
    border: 1px solid transparent; /* Use transparent border for a cleaner look */
    border-radius: 0.5rem;
    cursor: pointer;
    font-weight: 600;
    transition: background-color 0.2s ease, transform 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.update-btn {
    background-color: var(--primary-color);
    color: white;
}

.update-btn:hover {
    background-color: #4c1d95;
}

.remove-btn {
    background-color: transparent;
    border-color: #ef4444;
    color: #ef4444; /* Use an error color for removal */
}

.remove-btn:hover {
    background-color: #ef4444;
    color: white;
}

/* --- Cart Summary & Call to Action --- */
.total-cost {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-color-dark);
    text-align: right;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.cart-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 1.5rem;
}

.cart-actions a {
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: 0.5rem;
    transition: background-color 0.2s ease, transform 0.2s ease;
}

.back-to-shop {
    background-color: var(--background-dark);
    color: var(--text-color-dark);
    border: 1px solid var(--border-color);
}

.back-to-shop:hover {
    background-color: var(--border-color);
    transform: translateY(-2px);
}

.proceed-to-checkout {
    background-color: var(--primary-color);
    color: white;
}

.proceed-to-checkout:hover {
    background-color: #4c1d95;
    transform: translateY(-2px);
}

.empty-cart {
    text-align: center;
    font-size: 1.1rem;
    color: var(--text-color-light);
    padding: 2rem;
    background-color: var(--background-dark);
    border-radius: 0.5rem;
    border: 1px solid var(--border-color);
    margin-top: 1rem;
}

</style>
</head>
<body>
    <div class="container">
        <h2>Your Cart</h2>
        <?php
        if (empty($cart_items)) {
            echo "<p class='empty-cart'>Your cart is empty.</p>";
        } else {
            // Fetch product details for each cart item
            $product_ids = array_column($cart_items, 'product_id');
            $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
            $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
            $stmt->execute($product_ids);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as $product) {
                $quantity = 0;
                foreach ($cart_items as $cart_item) {
                    if ($cart_item['product_id'] == $product['id']) {
                        $quantity = $cart_item['quantity'];
                        break;
                    }
                }
                $total_cost += $product['price'] * $quantity; 

                echo "<div class='cart-item'>
                        <img src='../images/{$product['image']}' alt='{$product['name']}' class='item-image'>
                        <div class='item-details'>
                            <div class='item-name'>{$product['name']}</div>
                            <div class='item-price'>\${$product['price']} x $quantity</div>
                        </div>
                        <div class='item-actions'>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$product['id']}'>
                                <input type='number' name='quantity' value='$quantity' class='quantity' min='1' required>
                                <button type='submit' name='update_quantity' class='btn update-btn'>Update</button>
                            </form>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$product['id']}'>
                                <button type='submit' name='remove_from_cart' class='btn remove-btn'>Remove</button>
                            </form>
                        </div>
                    </div>";
            }
        }
        ?>
        <?php if (!empty($cart_items)) : ?>
            <div class="total-cost">
                Total: $<?= number_format($total_cost, 2); ?>
            </div>
        <?php endif; ?>
        <div class="cart-actions">
            <a href="../index.php" class="btn back-to-shop">Back to Shop</a>
            <a href="checkout.php" class="btn proceed-to-checkout">Proceed to Checkout</a>
        </div>
    </div>
</body>
</html>
