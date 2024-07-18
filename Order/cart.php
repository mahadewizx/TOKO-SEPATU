<?php
require '../config.php';
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize the cart_items variable
$cart_items = [];

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $cart_id = $_GET['id']; // Get cart.id instead of product_id
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?"; // Use cart.id for deletion
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cart_id, $user_id]);

    // Check if deletion was successful
    if ($stmt->rowCount() > 0) {
        echo "<p class='text-success text-center'>Product deleted from cart.</p>";
    } else {
        echo "<p class='text-danger text-center'>Failed to delete product from cart.</p>";
    }
}

// Handle add action
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $product_id, 1]);

    echo "<p class='text-success text-center'>Product added to cart.</p>";
}

// Handle update action
if (isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['id']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    // Ensure quantity is at least 1
    $quantity = max(1, (int)$quantity);

    $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$quantity, $cart_id, $user_id]);

    echo "<p class='text-success text-center'>Cart updated.</p>";
}

// Retrieve cart items for the current user
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT cart.id, products.name, products.price, cart.quantity FROM cart 
            JOIN products ON cart.product_id = products.id 
            WHERE cart.user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url("bacgroundshop.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }

        .container {
            padding-top: 50px;
            max-width: 900px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.9); /* Slightly opaque white background */
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 700;
        }

        .table {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th,
        .table td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: 600;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .btn-checkout,
        .btn-secondary {
            margin-top: 20px;
            padding: 12px 25px;
            font-size: 1.1rem;
        }

        .btn-primary {
            background-color: #fa8072;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #e07b6e;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            border-radius: 20px;
            font-weight: 700;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 5px;
            margin: 0 5px;
        }

        .btn-increase,
        .btn-decrease {
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            margin: 0;
            cursor: pointer;
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Your Cart</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cart_items)): ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo number_format($item['price'], 0, ',', '.'); ?> RP</td>
                            <td>
                                <form action="cart.php" method="POST" class="d-inline">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                    <button type="submit" name="quantity" value="<?php echo max($item['quantity'] - 1, 1); ?>"
                                        class="btn btn-decrease btn-secondary btn-sm">-</button>
                                    <input type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>"
                                        class="quantity-input" min="1">
                                    <button type="submit" name="quantity" value="<?php echo $item['quantity'] + 1; ?>"
                                        class="btn btn-increase btn-secondary btn-sm">+</button>
                                </form>
                            </td>
                            <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> RP</td>
                            <td>
                                <a href="cart.php?action=delete&id=<?php echo htmlspecialchars($item['id']); ?>"
                                    class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Your cart is empty.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-center">
            <a href="checkout.php" class="btn btn-primary btn-checkout">Proceed to Checkout</a>
            <a href="../index.php" class="btn btn-secondary">Back to Products</a>
        </div>
    </div>
</body>

</html>
