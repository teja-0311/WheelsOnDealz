<?php
// Start the session and connect to the database
session_start();

// Database connection
$host = 'localhost';        // Database host
$dbname = '23bcs091';       // Your database name
$username = 'root';         // Your database username
$password = '';             // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch rental details from the 'booked' table without start_date and end_date
$stmt_rentals = $pdo->query("
    SELECT b.id, b.vehicle_id, b.brand, b.model, b.price
    FROM booked_vehicles b
    ORDER BY b.id DESC
");

$rentals = $stmt_rentals->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rental Details</title>
  <link rel="stylesheet" href="dummy.css">
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <nav class="sidebar">
      <h2>AutoRent</h2>
      <ul>
        <li><a href="dummy.php">Dashboard</a></li>
        <li><a href="base.php">Vehicles</a></li>
        <li><a href="#rentals">Rentals</a></li>
        
      </ul>
    </nav>

    <!-- Main Content -->
    <div class="main">
      <header>
        <h1>Rental Details</h1>
        <div class="profile">
          <span>Welcome!</span>
          <a href="proj1.php"><button>Logout</button></a>
        </div>
      </header>

      <section id="rentals">
        <h2>All Rentals</h2>
        <table>
          <thead>
            <tr>
              <th>Rental ID</th>
              <th>Vehicle ID</th>
              <th>Brand</th>
              <th>Model</th>
              <th>Price</th>
              
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rentals as $rental): ?>
              <tr>
                <td><?php echo htmlspecialchars($rental['id']); ?></td>
                <td><?php echo htmlspecialchars($rental['vehicle_id']); ?></td>
                <td><?php echo htmlspecialchars($rental['brand']); ?></td>
                <td><?php echo htmlspecialchars($rental['model']); ?></td>
                <td><?php echo htmlspecialchars($rental['price']); ?></td>
            
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </section>

      <footer>
        <p>&copy; 2024 AutoRent. All rights reserved.</p>
      </footer>
    </div>
  </div>
</body>
</html>
