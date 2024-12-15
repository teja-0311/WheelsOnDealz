<?php
if (isset($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];
    $conn = new mysqli("localhost", "root", "", "23bcs091");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM vehicles WHERE id = ? AND status = 'AVAILABLE'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();
    } else {
        echo "<div class='error-message'>Vehicle not available or already booked.</div>";
        exit;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "<div class='error-message'>No vehicle selected.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Vehicle</title>
    <style>
        /* General Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; line-height: 1.6; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .container h2 { text-align: center; color: #333; margin-bottom: 20px; font-size: 24px; font-weight: bold; border-bottom: 2px solid #f9a826; padding-bottom: 10px; }
        form { display: flex; flex-direction: column; gap: 15px; }
        form label { font-size: 16px; font-weight: bold; color: #555; }
        form input[type="date"], form input[type="submit"] { width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; background-color: #f9f9f9; transition: border-color 0.3s, background-color 0.3s; }
        form input[type="date"]:focus { border-color: #f9a826; background-color: #fff; outline: none; }
        form input[type="submit"] { background-color: #f9a826; color: #fff; border: none; font-weight: bold; cursor: pointer; transition: background-color 0.3s; }
        form input[type="submit"]:hover { background-color: #d8881f; }
        form input[type="checkbox"] { margin-right: 10px; transform: scale(1.2); }
        form label[for="extras"] { font-size: 18px; color: #333; margin-bottom: 10px; display: block; }
        form br { margin-bottom: 10px; }
        .success-message, .error-message { text-align: center; font-weight: bold; margin-top: 20px; }
        .success-message { color: green; }
        .error-message { color: red; }
    </style>
    <script>
        function confirmBooking(event) {
            event.preventDefault(); 
            var confirmed = confirm("Are you sure you want to book this vehicle?");
            if (confirmed) {
                document.getElementById("bookingForm").submit();
            }
        }

        // Add client-side validation for dates
        function validateDates(event) {
            const startDate = new Date(document.getElementById("start_date").value);
            const endDate = new Date(document.getElementById("end_date").value);
            const today = new Date();

            if (startDate < today) {
                alert("Start date cannot be in the past.");
                event.preventDefault();
                return false;
            }
            if (endDate <= startDate) {
                alert("End date must be after the start date.");
                event.preventDefault();
                return false;
            }
            return confirmBooking(event);
        }
    </script>
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model'] . ' - $' . number_format($vehicle['price'], 2)); ?></h2>
        <form id="bookingForm" action="process_booking.php" method="POST" onsubmit="validateDates(event);">
            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
            
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>
            
            <label for="extras">Extras:</label><br>
            <input type="checkbox" name="gps" value="10"> GPS ($10)<br>
            <input type="checkbox" name="insurance" value="20"> Insurance ($20)<br>
            
            <input type="submit" value="Proceed to Confirm Booking">
        </form>
    </div>
</body>
</html>
