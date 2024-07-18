<?php
// Database configuration
$host = getenv('DB_HOST') ?: 'localhost';
$db = getenv('DB_NAME') ?: 'dewtoko';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';

// Create a PDO instance with error handling
try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4"; // Added charset for better handling of Unicode
    $pdo = new PDO($dsn, $user, $pass);
    
    // Set PDO attributes
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception mode for errors
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Disable emulation of prepared statements for better security

} catch (PDOException $e) {
    // Log error message to a file
    error_log("Database connection error: " . $e->getMessage(), 3, '/var/log/my_app_errors.log'); // Log to a specific file
    
    // Display a user-friendly message
    die("Could not connect to the database. Please try again later.");
}
?>
