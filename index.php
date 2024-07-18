<?php
session_start();

// Include your configuration file
require 'config.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: Auth/login.php");
    exit;
}

// Fetch products from the database
try {
    $sql = "SELECT * FROM products";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PINKY STORE</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url("bacgroundshop.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: #333;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 10px 20px;
            z-index: 1000;
        }

        .navbar-brand {
            color: #fa8072 !important;
            font-weight: 700;
            font-size: 1.75rem;
        }

        .navbar-brand:hover {
            color: #e07b6e !important;
        }

        .navbar-nav .nav-link {
            color: #333 !important;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: #fa8072 !important;
        }

        .container {
            padding-top: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .card-img-top {
            border-bottom: 5px solid #fa8072;
            transition: transform 0.3s ease-in-out;
        }

        .card-img-top:hover {
            transform: scale(1.1);
            filter: brightness(0.8);
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .card-title {
            color: #333;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card-text {
            color: #666;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #fa8072;
            border-color: #fa8072;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #e07b6e;
            border-color: #e07b6e;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .container h1 {
            color: #333;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 30px;
            font-weight: 700;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">TOKO SEPATU</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Order/cart.php">Cart</a>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="Auth/logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="Auth/login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Products</h1>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="image/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="card-text"><?php echo htmlspecialchars(number_format($product['price'], 2, ',', '.')); ?> RP</p>
                        <a href="Order/cart.php?action=add&id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-primary">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="mt-4 text-center">
        <a href="Order/cart.php" class="btn btn-secondary">View Cart</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
