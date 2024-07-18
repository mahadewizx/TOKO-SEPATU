<?php
require '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email, $password]);

    $success_message = "User registered successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url("bacgroundshop.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent background */
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2); /* Darker shadow on hover */
        }

        .card-title {
            text-align: center;
            color: #fa8072; /* Salmon color */
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #fa8072; /* Salmon color */
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 1.1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
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
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .success-message {
            color: #28a745;
            margin-bottom: 1.5rem;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1 class="card-title">Register</h1>
            <?php if (isset($success_message)) : ?>
                <div class="alert alert-success success-message" role="alert">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
            <div class="mt-3 text-center">
                <p>Already have an account? <a href="login.php" style="color: #fa8072; text-decoration: none; font-weight: 500;">Login here</a></p>
            </div>
        </div>
    </div>
</body>

</html>
