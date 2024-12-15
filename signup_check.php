<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "23bcs091";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $driving_license_number = mysqli_real_escape_string($conn, $_POST['driving_license_number']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // Calculate the age
    $dob_date = new DateTime($dob); // Create a DateTime object from the DOB
    $current_date = new DateTime(); // Current date
    $age = $current_date->diff($dob_date)->y; // Calculate the age in years

    if ($age < 18) {
        // If the user is below 18, show an error and exit
        echo "Error: You must be at least 18 years old to sign up.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert data into database
        $sql = "INSERT INTO users (username, fullname, email, phone, password, date_of_birth, driving_license_number, gender)
                VALUES ('$username', '$fullname', '$email', '$phone', '$hashed_password', '$dob', '$driving_license_number', '$gender')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
