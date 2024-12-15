<?php
// Database connection details
$servername = "localhost"; // Or your host (e.g., 127.0.0.1)
$username = "root";        // Your MySQL username
$password = "";            // Your MySQL password (empty by default)
$dbname = "23bcs091"; // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the vehicle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    
    // SQL query to delete the vehicle
    $sql = "DELETE FROM vehicles WHERE id = '$delete_id'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "<div class='message'>Vehicle deleted successfully.</div>";
    } else {
        echo "<div class='message'>Error: " . $conn->error . "</div>";
    }
}

// Handle the vehicle addition via POST method
$vehicle_added = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = mysqli_real_escape_string($conn, $_POST['nameOfVehicle']); // vechi -> type
    $brand = mysqli_real_escape_string($conn, $_POST['brandOfVehicle']); // brandi -> brand
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']); // descriptioni -> description
    $vehicleType = mysqli_real_escape_string($conn, $_POST['vehicleType']); // type -> vechi
    
    // SQL query to insert the new vehicle, with default status as 'available'
    $sql = "INSERT INTO vehicles (type, brand, model, price, description, vechi, status) 
            VALUES ('$type', '$brand', '$model', '$price', '$description', '$vehicleType', 'available')";

    if ($conn->query($sql) === TRUE) {
        $vehicle_added = true; // Mark as added
    } else {
        echo "<div class='message'>Error: " . $conn->error . "</div>";
    }
}

// Handle the status update (mark as rented or available)
if (isset($_GET['update_status'])) {
    $update_id = mysqli_real_escape_string($conn, $_GET['update_status']);
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);
    
    $sql = "UPDATE vehicles SET status = '$new_status' WHERE id = '$update_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='message'>Status updated successfully.</div>";
    } else {
        echo "<div class='message'>Error: " . $conn->error . "</div>";
    }
}

// Fetch all vehicles from the database
$sql = "SELECT * FROM vehicles";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Inventory</title>
    <link rel="stylesheet" href="base_styles.css"> <!-- Linking external CSS -->
</head>
<body>
    <div class="container">
        <h2>Vehicle Inventory</h2>

        <!-- Show vehicles list if not adding new vehicle -->
        <?php if (!$vehicle_added): ?>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle Type</th> <!-- vechi -->
                        <th>Brand</th> <!-- brandi -->
                        <th>Model</th>
                        <th>Price</th>
                        <th>Description</th> <!-- descriptioni -->
                        <th>Type</th> <!-- type -->
                        <th>Status</th> <!-- Status Column -->
                        <th>Action</th>
                    </tr>

                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['type']; ?></td> <!-- vechi -->
                            <td><?php echo $row['brand']; ?></td> <!-- brandi -->
                            <td><?php echo $row['model']; ?></td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['description']; ?></td> <!-- descriptioni -->
                            <td><?php echo $row['vechi']; ?></td> <!-- type -->
                            <td><?php echo $row['status']; ?></td> <!-- Display Status -->
                            <td>
                                <a href="base.php?delete_id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this vehicle?')">Delete  || </a>
                                <?php if ($row['status'] == 'available'): ?>
                                    <a href="base.php?update_status=<?php echo $row['id']; ?>&status=NOT AVAILABLE"> Mark as Rented</a>
                                <?php else: ?>
                                    <a href="base.php?update_status=<?php echo $row['id']; ?>&status=AVAILABLE"> Mark as Available</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p class="no-data">No vehicles found in the inventory.</p>
            <?php endif; ?>

            <!-- Button to Add New Vehicle -->
            <a href="base.php?add_vehicle=true"><button id="addVehicleButton">Add Vehicle</button></a>
        <?php else: ?>
            <!-- Success message after adding a vehicle -->
            <div class="message">New vehicle added successfully!</div>
            <!-- Button to go back to the vehicle list -->
            <a href="base.php">
                <button id="backButton">Go Back to Vehicle Details</button>
            </a>
        <?php endif; ?>

        <!-- Form to Add New Vehicle -->
        <?php if (isset($_GET['add_vehicle']) && $_GET['add_vehicle'] == 'true'): ?>
            <form action="base.php" method="POST">
                <label for="nameOfVehicle">Vehicle Name :</label>
                <input type="text" name="nameOfVehicle" id="nameOfVehicle" placeholder="Enter vehicle type" required>

                <label for="brandOfVehicle">Brand of Vehicle:</label>
                <input type="text" name="brandOfVehicle" id="brandOfVehicle" placeholder="Enter brand of vehicle" required>

                <label for="model">Model:</label>
                <input type="text" name="model" id="model" placeholder="Enter vehicle model" required>

                <label for="price">Price ($):</label>
                <input type="number" name="price" id="price" placeholder="Enter price" required>

                <label for="description">Description:</label>
                <input type="text" name="description" id="description" placeholder="Enter description" required>

                <label for="vehicleType">Vehicle Type:</label>
                <input type="text" name="vehicleType" id="vehicleType" placeholder="Car, Bicycle, etc." required>

                <button type="submit">Save Vehicle</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
