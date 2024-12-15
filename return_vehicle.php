<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = $_POST['vehicle_id'];

    $conn = new mysqli("localhost", "root", "", "23bcs091");

    $update_query = "UPDATE vehicles SET status = 'AVAILABLE' WHERE id = $vehicle_id";
    if ($conn->query($update_query)) {
        $delete_query = "DELETE FROM booked_vehicles WHERE vehicle_id = $vehicle_id";
        $conn->query($delete_query);
        echo "Vehicle returned successfully.";
    } else {
        echo "Error in returning vehicle.";
    }

    $conn->close();
    header("Location: admin.php");
    exit();
}
?>
