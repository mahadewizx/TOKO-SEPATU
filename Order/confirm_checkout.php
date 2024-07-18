<?php
require '../config.php';
session_start();

// Retrieve cart items for the current user
$sql = "SELECT products.name, products.price, cart.quantity FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Clear the cart after checkout
$sql_clear_cart = "DELETE FROM cart WHERE user_id = ?";
$stmt_clear_cart = $pdo->prepare($sql_clear_cart);
$stmt_clear_cart->execute([$_SESSION['user_id']]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .bg-image {
            background-image: url('bacgroundshop.jpg'); /* Ensure this path is correct */
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            filter: brightness(70%);
        }

        .container {
            padding: 40px;
            margin: 5% auto;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 500px;
        }

        h1 {
            color: #fa8072; /* Salmon color */
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
        }

        h3 {
            color: #333;
            font-size: 1.5rem;
            margin-top: 20px;
            text-align: center;
        }

        p {
            text-align: center;
            font-size: 1.2rem;
            color: #555;
            margin: 20px 0;
        }

        .btn-primary {
            background-color: #fa8072;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1.1rem;
            text-transform: uppercase;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background-color: #e07b6e;
            transform: scale(1.05);
        }

        .btn-home {
            display: block;
            width: 100%;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-top: 1px solid #ddd;
        }

        .footer p {
            margin: 0;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="bg-image"></div>
    <div class="container">
        <h1>Checkout Confirmed</h1>
        <p>Your order has been successfully placed.</p>
        <h3>Total: <?php echo number_format($total, 2, ',', '.'); ?> RP</h3>
        <a href="../index.php" class="btn btn-primary btn-home">Back to Home</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 Your Company Name. All rights reserved.</p>
    </div>
</body>

</html>
