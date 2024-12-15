<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // User not logged in
    header("Location: login.php");
    exit;
}

// Get the selected vehicle type
$vehicleType = $_POST['vehicleType'] ?? '';

// Redirect to the respective vehicle type page
switch ($vehicleType) {
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
        header("Location: proj1.php"); // Redirect to home if no valid option
        break;
}
exit;
?>
