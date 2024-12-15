<?php
if (isset($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $gps = isset($_GET['gps']) ? $_GET['gps'] : 0;
    $insurance = isset($_GET['insurance']) ? $_GET['insurance'] : 0;

    $conn = new mysqli("localhost", "root", "", "23bcs091");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch vehicle details for insertion
    $query = "SELECT * FROM vehicles WHERE id = $vehicle_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();

        // Insert into booked_vehicles
        $stmt = $conn->prepare("INSERT INTO booked_vehicles (vehicle_id, brand, model, price, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issds", $vehicle_id, $vehicle['brand'], $vehicle['model'], $vehicle['price'], $status);
        $status = 'BOOKED';

        if ($stmt->execute()) {
            // Update the vehicle status to NOT AVAILABLE
            $updateStmt = $conn->prepare("UPDATE vehicles SET status = 'NOT AVAILABLE' WHERE id = ?");
            $updateStmt->bind_param("i", $vehicle_id);
            $updateStmt->execute();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $conn->close();
}
?>
