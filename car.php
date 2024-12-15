<?php
session_start();
include 'db.php'; // Include your DB connection file

if (!isset($_SESSION['username'])) {
    // Redirect if the user is not logged in
    header('Location: login.php');
    exit();
}

// Database connection setup (assuming $pdo is your PDO instance)
$host = "localhost";
$dbname = "23bcs091";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize $vehicles as an empty array
    $vehicles = [];

    // Check if a filter for max price is applied
    $maxPrice = isset($_POST['max_price']) ? $_POST['max_price'] : null;

    // Prepare the query to fetch only cars
    $query = "SELECT * FROM vehicles WHERE VECHI = 'car'"; // Add filter for type = 'car'

    if ($maxPrice) {
        $query .= " AND price <= :maxPrice";
    }

    $stmt = $pdo->prepare($query);

    // Bind parameters if maxPrice is set
    if ($maxPrice) {
        $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);
    }

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle Add to Cart action
    if (isset($_POST['add_to_cart']) && isset($_SESSION['username'])) {
        $user_name = $_SESSION['username']; // Assuming username is stored in session
        $id = $_POST['id']; // Get the vehicle ID from the form

        // Check if the vehicle is already in the cart
        $checkQuery = "SELECT * FROM cart WHERE username = :username AND id = :id";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->execute([':username' => $user_name, ':id' => $id]);
        $cartItem = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($cartItem) {
            // If the vehicle is already in the cart, update the quantity
            $updateQuery = "UPDATE cart SET quantity = quantity + 1 WHERE username = :username AND id = :id";
            $updateStmt = $pdo->prepare($updateQuery);
            try {
                $updateStmt->execute([':username' => $user_name, ':id' => $id]);
            } catch (PDOException $e) {
                echo "Error executing update: " . $e->getMessage();
            }
        } else {
            // Insert new vehicle into the cart
            $insertQuery = "INSERT INTO cart (username, id, quantity) VALUES (:username, :id, 1)";
            $insertStmt = $pdo->prepare($insertQuery);
            try {
                $insertStmt->execute([':username' => $user_name, ':id' => $id]);
            } catch (PDOException $e) {
                echo "Error executing insert: " . $e->getMessage();
            }
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cars</title>
    <link rel="stylesheet" href="proj1.css">
</head>
<style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
}

/* Header Styles */
header.header {
    background-color: #333;
    color: #fff;
    padding: 15px 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

header .containerte {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

header .logo {
    font-size: 24px;
    font-weight: bold;
    color: #f9a826;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

header .nav {
    display: flex;
    gap: 20px;
}

header .nav a {
    font-size: 16px;
    color: #000000;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s, background-color 0.3s;
    padding: 10px 15px;
    border-radius: 5px;
}

header .nav a:hover {
    color: #333;
    background-color: #f9a826;
}

/* Main Container */
.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

/* Filter Form */
.filters {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    gap: 10px;
}

.filters input {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    transition: border-color 0.3s;
    flex: 1;
    max-width: 300px;
}

.filters input:focus {
    border-color: #f9a826;
    outline: none;
}

.filters button {
    padding: 10px 20px;
    background-color: #f9a826;
    color: #000000;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.filters button:hover {
    background-color: #d8881f;
}

/* Vehicle List */
.vehicle-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.vehicle {
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.vehicle:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.vehicle h3 {
    margin-bottom: 10px;
    font-size: 20px;
    color: #333;
}

.vehicle p {
    margin: 5px 0;
    color: #555;
}

/* Buttons */
.button {
    display: inline-block;
    padding: 10px 15px;
    background-color: #007BFF;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
    font-weight: bold;
}

.button:hover {
    background-color: #0056b3;
}

.not-available {
    color: red;
    font-weight: bold;
}

.no-results {
    color: #666;
    font-style: italic;
    text-align: center;
    margin-top: 20px;


}
/* Background Video */
.background-video {
    position: fixed;
    right: 0;
    bottom: 0;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    z-index: -1;
        background-size: cover;}

        </style>
<body>
<video autoplay muted loop class="background-video">
        <source src="bikevid.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <header class="header">
        <div class="container">
            <h1 class="logo">AutoMobileRent</h1>
            <nav class="nav">
                <a href="proj1.php">Home</a>
                <a href="about.html">About</a>
                <a href="contact.html">Contact</a>
                <a href="cart.php">Cart</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1>Available Cars</h1>

        <!-- Filter Form -->
        <form method="POST" action="car.php" class="filters">
            <input type="number" name="max_price" placeholder="Max Price" value="<?php echo htmlspecialchars($maxPrice); ?>">
            <button type="submit">Filter</button>
        </form>

        <!-- Vehicle List Section -->
        <div class="vehicle-list">
            <?php if (count($vehicles) > 0): ?>
                <?php foreach ($vehicles as $vehicle): ?>
                    <div class="vehicle">
                        <h3><?php echo htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model'] . ' - $' . number_format($vehicle['price'], 2)); ?></h3>
                        <p>Type: <?php echo htmlspecialchars($vehicle['vechi']); ?></p>
                        <p>Description: <?php echo htmlspecialchars($vehicle['description']); ?></p>

                        <!-- Select Button for Available Vehicles -->
                        <?php if ($vehicle['status'] == 'AVAILABLE'): ?>
                            <a href="booking.php?vehicle_id=<?php echo $vehicle['id']; ?>" class="button">Select</a>
                            <form method="POST" action="car.php">
                                <input type="hidden" name="id" value="<?php echo $vehicle['id']; ?>">
                                <button type="submit" name="add_to_cart" class="button">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <p class="not-available">Not Available</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-results">No vehicles found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
