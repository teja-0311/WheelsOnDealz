<?php
// Start the session and connect to the database
session_start();

// Database connection
$host = 'localhost';        // Database host
$dbname = '23bcs091';  // Your database name
$username = 'root';    // Your database username
$password = ''; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch the number of active rentals (status = 'NOT AVAILABLE')
$stmt_active = $pdo->query("SELECT COUNT(*) AS active_rentals FROM vehicles WHERE status = 'NOT AVAILABLE'");
$active_rentals = $stmt_active->fetchColumn();

// Fetch the number of available vehicles (status = 'AVAILABLE')
$stmt_available = $pdo->query("SELECT COUNT(*) AS available_vehicles FROM vehicles WHERE status = 'AVAILABLE'");
$available_vehicles = $stmt_available->fetchColumn();

// Fetch the number of overdue returns (vehicles not returned after their end date)

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Automobile Renting Employee Dashboard</title>
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
        <li><a href="rentals.php">Rentals</a></li>
        
      </ul>
    </nav>

    <!-- Main Content -->
    <div class="main">
      <header>
        <h1>Admin Dashboard</h1>
        <div class="profile">
          <span>Welcome!</span>
          <a href="proj1.php"><button>Logout</button></a>
        </div>
      </header>

      <section id="dashboard">
        <h2>Dashboard</h2>
        <div class="cards">
          <div class="card">
            <h3>Active Rentals</h3>
            <p><?php echo $active_rentals; ?></p>
          </div>
          <div class="card">
            <h3>Available Vehicles</h3>
            <p><?php echo $available_vehicles; ?></p>
          </div>
          <div class="card">
            <h3>Overdue Returns</h3>
            <p> 2</p>
          </div>
        </div>
      </section>

      <!-- <section id="vehicles">
        <h2>Vehicles</h2>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Model</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Toyota Corolla</td>
              <td>Available</td>
              <td><button>Edit</button></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Honda Civic</td>
              <td>Rented</td>
              <td><button>Edit</button></td>
            </tr>
          </tbody>
        </table>
      </section> -->

      <footer>
        <p>&copy; 2024 AutoRent. All rights reserved.</p>
      </footer>
    </div>
  </div>
</body>
</html>
