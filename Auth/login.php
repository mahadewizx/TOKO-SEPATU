<?php
require '../config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../index.php");
        exit(); // Ensure that script execution stops after redirection
    } else {
        $error_message = "Invalid email or password."; // Display an error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('bacgroundshop.jpg'); /* Ensure this path is correct */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 400px;
            width: 100%;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            overflow: hidden;
        }

        .card-header {
            background-color: #fa8072; /* Salmon color */
            color: #ffffff;
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .card-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #fa8072; /* Salmon color */
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 1.1rem;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background-color: #e07b6e; /* Slightly darker salmon color for hover effect */
            transform: scale(1.05);
        }

        .text-center {
            font-size: 0.9rem;
            color: #666;
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 1rem;
            text-align: center;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #ffffff;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <header class="card-header">
                <h4 class="card-title">Login</h4>
            </header>
            <article class="card-body">
                <?php if (isset($error_message)) : ?>
                    <div class="alert alert-danger error-message" role="alert">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                </form>
            </article>
            <div class="footer">
                Don't have an account? <a href="register.php">Register</a>
            </div>
        </div>
    </div>
</body>

</html>
