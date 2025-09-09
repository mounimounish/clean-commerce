<?php
include('../includes/db.php');  // Include the database connection
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        $_SESSION['user_id'] = $user['id']; // Store user ID in session
        header("Location: ../index.php"); // Redirect to the main page
        exit();
    } else {
        // Invalid login
        $error_message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* --- Minimalistic Design with CSS Variables --- */
        :root {
            --primary-color: #5b21b6;
            --text-color-dark: #1f2937;
            --text-color-light: #6b7280;
            --background-light: #f9fafb;
            --border-color: #d1d5db;
            --shadow-color: rgba(0, 0, 0, 0.05);
            --error-color: #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-light);
            color: var(--text-color-dark);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .login-container {
            background-color: #fff;
            padding: 2.5rem; /* Use rem for better scaling */
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px var(--shadow-color), 0 2px 4px -1px var(--shadow-color);
            width: 100%;
            max-width: 400px;
        }
        
        h2 {
            text-align: center;
            color: var(--text-color-dark);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }
        
        label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-color-dark);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(91, 33, 182, 0.2);
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        button:hover {
            background-color: #4c1d95;
        }
        
        .error-message {
            color: var(--error-color);
            font-size: 0.9rem;
            text-align: center;
            margin-top: 1rem;
            padding: 0.75rem;
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
