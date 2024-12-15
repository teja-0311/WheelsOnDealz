<?php
session_start();
include 'db.php';

// Check if the form was submitted and retrieve form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $gps = isset($_POST['gps']) ? $_POST['gps'] : 0;
    $insurance = isset($_POST['insurance']) ? $_POST['insurance'] : 0;
    $username = $_SESSION['username']; // Assuming the user is logged in

    $conn = new mysqli("localhost", "root", "", "23bcs091");

    // Fetch vehicle details
    $query = "SELECT * FROM vehicles WHERE id = $vehicle_id";
    $result = $conn->query($query);
    $vehicle = $result->fetch_assoc();

    if (!$vehicle) {
        echo "Vehicle not found.";
        exit;
    }

    // Calculate number of days
    $date1 = new DateTime($start_date);
    $date2 = new DateTime($end_date);
    $days = $date1->diff($date2)->days;

    // Calculate total price
    $total_price = ($days * $vehicle['price']) + $gps + $insurance;

    // Insert booking into the bookings table
    $insert_booking_query = "INSERT INTO bookings (vehicle_id, start_date, end_date, total_price, username) 
                             VALUES ($vehicle_id, '$start_date', '$end_date', $total_price, '$username')";
    if ($conn->query($insert_booking_query)) {
        $booking_id = $conn->insert_id;  // Get the last inserted booking ID

        // Insert payment details into the payments table (but payment isn't completed yet)
        $insert_payment_query = "INSERT INTO payments (booking_id, username, vehicle_id, vehicle_details, payment_amount, payment_date) 
                                 VALUES ($booking_id, '$username', $vehicle_id, '" . $vehicle['brand'] . " " . $vehicle['model'] . "', $total_price, NOW())";
        if ($conn->query($insert_payment_query)) {
            // Update the vehicle's status to NOT AVAILABLE after successful payment insertion
            $update_vehicle_status_query = "UPDATE vehicles SET status = 'NOT AVAILABLE' WHERE id = $vehicle_id";
            if ($conn->query($update_vehicle_status_query)) {
                // Razorpay API integration (client-side)
                ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Booking Confirmation</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f9;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            max-width: 600px;
                            margin: 50px auto;
                            background: #fff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                            text-align: center;
                        }
                        h2 {
                            font-size: 24px;
                            margin-bottom: 20px;
                            color: #333;
                        }
                        p {
                            font-size: 18px;
                            margin: 10px 0;
                            color: #555;
                        }
                        button {
                            background-color: #007bff;
                            color: #fff;
                            border: none;
                            padding: 10px 15px;
                            font-size: 16px;
                            border-radius: 5px;
                            cursor: pointer;
                            transition: background-color 0.3s;
                        }
                        button:hover {
                            background-color: #0056b3;
                        }
                    </style>
                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                    <script>
                        const handlePayment = async (amount, product) => {
                            try {
                                const response = await fetch("http://localhost:4000/api/payment/order", {
                                    method: "POST",
                                    headers: { "Content-Type": "application/json" },
                                    body: JSON.stringify({ amount })
                                });
                                const data = await response.json();

                                const options = {
                                    key: "<?php echo getenv('REACT_APP_RAZORPAY_KEY_ID'); ?>",
                                    amount: data.data.amount,
                                    currency: data.data.currency,
                                    name: "Devknus",
                                    description: "Test Mode",
                                    order_id: data.data.id,
                                    handler: async function (response) {
                                        const verifyResponse = await fetch("http://localhost:4000/api/payment/verify", {
                                            method: "POST",
                                            headers: { "Content-Type": "application/json" },
                                            body: JSON.stringify(response)
                                        });
                                        const verifyData = await verifyResponse.json();
                                        if (verifyData.message) {
                                            alert(verifyData.message);
                                        }
                                    },
                                    theme: { color: "#5f63b8" }
                                };
                                const rzp1 = new Razorpay(options);
                                rzp1.open();
                            } catch (error) {
                                console.error("Payment failed", error);
                            }
                        };

                        // Automatically trigger the Razorpay payment
                        window.onload = function () {
                            handlePayment(<?php echo $total_price * 100; ?>, 'Booking Payment');
                        };
                    </script>
                </head>
                <body>
                    <div class="container">
                        <h2>Your booking is confirmed!</h2>
                        <p>Vehicle ID: <?php echo $vehicle_id; ?></p>
                        <p>Number of Days: <?php echo $days; ?></p>
                        <p>Total Price: ₹<?php echo $total_price; ?></p>
                        <p>Your payment is being processed. Please wait...</p>
                    </div>
                </body>
                </html>
                <?php
            } else {
                echo "<p>Error: Unable to update vehicle status. Please try again later.</p>";
            }
        } else {
            echo "<p>Error: Unable to process payment details. Please try again later.</p>";
        }
    } else {
        echo "<p>Error: Unable to process booking. Please try again later.</p>";
    }

    // Close the database connection
    $conn->close();
}
?>
