<?php
session_start();

// Redirect to home page if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: proj1.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoMobileRent - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }

        /* Container */
        .container {
            width: 80%;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background-color: #333;
            color: #fff;
            padding: 1em 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 24px;
            color: #f9a826;
            font-weight: bold;
            text-align: left;
        }

        .nav {
            text-align: left;
        }

        .nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav a:hover {
            color: #f9a826;
        }

        /* Login Container */
        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 40px auto;
            padding: 40px;
            background-color: #ffffffd9;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(10, 10, 10, 0.1);
            text-align: center;
        }

        .login-title {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
            
        }

        .input-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #555555;
            margin-bottom: 8px;
            font-family: Arial, sans-serif;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #f9a826;
            outline: none;
        }

        .login-button {
            padding: 12px;
            background-color: #f9a826;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #f9a826;
        }

        .forgot-password {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #f9a826;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        /* Sign Up Link */
        .sign-up {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #f9a826;
            text-decoration: none;
            transition: color 0.3s;
        }

        .sign-up:hover {
            text-decoration: underline;
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            .login-container {
                padding: 20px;
            }
            .login-title {
                font-size: 24px;
            }
        }

        .why-choose {
            background-color: hsl(0, 2%, 82%);
            padding: 50px 0;
            text-align: center;
        }
        
        .why-choose h3 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        
        .features {
            display: flex;
            justify-content: space-around;
        }
        
        .feature {
            width: 30%;
            padding: 10px;
        }
        
        .feature h4 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .feature p {
            color: #666;
        }
        
        /* Footer */
        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        
        .footer p {
            font-size: 14px;
        }
    </style>
</head>
<body>
    
    <header class="header">
        <div class="container">
            <h1 class="logo">AutoMobileRent</h1>
            <nav class="nav">
                <a href="proj1.php">Home</a>
                <a href="about.html">About</a>
                <a href="contact.html">Contact</a>
                <a href="signup.html">Sign Up</a>
        
            </nav>
        </div>
    </header>

    <div class="login-container">
        <h1 class="login-title">Sign In</h1>
        <form action="login_check.php" method="POST" class="login-form">            <div class="input-group">
                <label for="username"><h4>Username</h4></label>
                <input type="username" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password"><h4>Password</h4></label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">Sign In</button>
        </form>
        <a href="forgotpass.html" class="forgot-password"><h4>Forgot Password?</h4></a>
        <a href="signup.html" class="sign-up"><h4>Don't have an account? Sign Up</h4></a> <!-- Sign Up link added here -->
    </div>

    <section class="why-choose">
        <div class="container">
          <h3>Why Choose AutoMobileRent?</h3>
          <p>We offer a seamless rental experience with competitive prices and a wide variety of vehicles.</p>
          <div class="features">
            <div class="feature">
              <h4>24/7 Customer Support</h4>
              <p>Our team is here to help you anytime, day or night.</p>
            </div>
            <div class="feature">
              <h4>Flexible Rentals</h4>
              <p>Rent by the day, week, or month to suit your needs.</p>
            </div>
            <div class="feature">
              <h4>Quality Assured</h4>
              <p>Every vehicle is maintained to the highest standards.</p>
            </div>
          </div>
        </div>
      </section>
      
    <footer class="footer">
        <div class="container">
          <p>&copy; 2023 AutoRent. All rights reserved.</p>
        </div>
      </footer>
</body>
</html>
