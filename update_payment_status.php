<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $bookingId = $data['bookingId'];
    $paymentId = $data['paymentId'];

    // You would need to verify the payment using Razorpay API
    // For this example, we'll assume the payment is successful
    $conn = new mysqli("localhost", "root", "", "23bcs091");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $update_query = "UPDATE bookings SET payment_status = 'PAID', payment_id = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $paymentId, $bookingId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Payment verification failed']);
    }

    $stmt->close();
    $conn->close();
}
?>
