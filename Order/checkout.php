<?php
require '../config.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Retrieve cart items for the current user
$sql = "SELECT products.name, products.price, cart.quantity FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('bacgroundshop.jpg'); /* Ensure this path is correct */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .container {
            padding: 30px;
            margin: 0 auto;
            max-width: 900px; /* Center and limit the width of the container */
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            margin-top: 50px; /* Margin for better spacing */
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8d7da; /* Light salmon color for table headers */
            color: #721c24;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Zebra striping for table rows */
        }

        .btn-primary {
            background-color: #fa8072; /* Salmon color for primary buttons */
            border-color: #fa8072;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1.1rem;
            font-weight: 700;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #e07b6e; /* Darker salmon color for hover effect */
            border-color: #e07b6e;
        }

        .btn-secondary {
            background-color: #f08080; /* Light salmon color for secondary buttons */
            border-color: #f08080;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1.1rem;
            font-weight: 700;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #e57373; /* Darker salmon color for hover effect */
            border-color: #e57373;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            background-color: #fa8072; /* Salmon color for card header */
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            border-bottom: 1px solid #ddd;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .card-body {
            padding: 20px;
        }

        .card-body h5 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Checkout</h1>
        <div class="card">
            <div class="card-header">
                Your Cart Items
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo number_format($item['price'], 2, ',', '.'); ?> RP</td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td><?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?> RP</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h3 class="text-center">Total: <?php
                $total = 0;
                foreach ($cart_items as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                echo number_format($total, 2, ',', '.'); ?> RP
                </h3>
                <div class="text-center mt-4">
                    <a href="confirm_checkout.php" class="btn btn-primary btn-confirm">Confirm Purchase</a>
                    <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
