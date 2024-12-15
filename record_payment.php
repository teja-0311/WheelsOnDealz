<?php
header("Content-Type: application/json");

// Get data from Razorpay response
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    $conn = new mysqli("localhost", "root", "", "23bcs091");
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'error' => "Database connection failed: " . $conn->connect_error]);
        exit();
    }

    $booking_id = $data['booking_id'];
    $username = $data['username'];
    $vehicle_id = $data['vehicle_id'];
    $vehicle_details = $conn->real_escape_string($data['vehicle_details']);
    $payment_amount = $data['payment_amount'];

    // Insert into `payments` table
    $insert_payment_query = "INSERT INTO payments (booking_id, username, vehicle_id, vehicle_details, payment_amount)
                             VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_payment_query);
    $stmt->bind_param("isiss", $booking_id, $username, $vehicle_id, $vehicle_details, $payment_amount);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => "Error inserting payment: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => "Invalid request data."]);
}
?>
