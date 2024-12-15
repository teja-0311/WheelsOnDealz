<?php
// Database connection details
$host = 'localhost';
$dbname = '23bcs091';
$username = 'root';
$password = '';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate if the passwords match
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Check if the username exists
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update the password
            $update_sql = "UPDATE users SET password = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $hashed_password, $username);

            if ($update_stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                echo "Error updating password.";
            }
        } else {
            echo "Username not found.";
        }

        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
