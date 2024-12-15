<?php
// Start session to track user login status
session_start();

// Database connection parameters (adjust these for your setup)
$host = "localhost";
$dbname = "23bcs091";
$username = "root";
$password = "";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if form data is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the submitted username, password, and vehicle type
        $user_username = $_POST['username'];
        $user_password = $_POST['password'];
        $vehicle_type = isset($_POST['vehicleType']) ? $_POST['vehicleType'] : null;


        // Prepare SQL query to select the user by username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $user_username);
        $stmt->execute();
        
        // Check if the user exists
        if ($stmt->rowCount() == 1) {
            // Fetch the user record
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if (password_verify($user_password, $user['password'])) {
                // Store user info in session
                $_SESSION['username'] = $user['username'];
                $_SESSION['loggedin'] = true;

                // Redirect to the respective vehicle page
                switch ($vehicle_type) {
                    case 'car':
                        header("Location: car.php");
                        break;
                    case 'bike':
                        header("Location: bike.php");
                        break;
                    case 'bicycle':
                        header("Location: bicycle.php");
                        break;
                    default:
                        header("Location: proj1.php"); // Redirect to home if invalid type
                        break;
                }
                exit();
            } else {
                $error = "Invalid password. Please try again.";
            }
        } else {
            $error = "User not found. Please try again.";
        }
    }
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoMobileRent - Login Error</title>
</head>
<body>
    <h1>Sign In</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <a href="login.php">Go back to Login</a>
</body>
</html>
