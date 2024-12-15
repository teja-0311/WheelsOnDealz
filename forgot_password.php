<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $stmt = $conn->prepare("UPDATE admins SET password = MD5(?) WHERE email = ?");
        $stmt->bind_param("ss", $new_password, $email);

        if ($stmt->execute() && $stmt->affected_rows == 1) {
            header("Location:admin_login.php");
        } else {
            echo "Error resetting password or email not found.";
        }
    } else {
        echo "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #444;
            font-size: 1.8rem;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #74ebd5;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }
        button:hover {
            background: #58c0b7;
        }
        .secondary-button {
            background: #acb6e5;
        }
        .secondary-button:hover {
            background: #929dbb;
        }
        a {
            color: #74ebd5;
            text-decoration: none;
            margin-top: 15px;
            display: inline-block;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="new_password" placeholder="New Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
        
    </div>
</body>
</html>
