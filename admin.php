<!-- admin.php -->
<?php
$conn = new mysqli("localhost", "root", "", "23bcs091");
$query = "SELECT * FROM booked_vehicles";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Unavailable Vehicles</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="admin-container">
        <h2>Unavailable Vehicles</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Year</th>
                <th>Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['vehicle_id']; ?></td>
                <td><?php echo htmlspecialchars($row['brand']); ?></td>
                <td><?php echo htmlspecialchars($row['model']); ?></td>
                <td>$<?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <form action="return_vehicle.php" method="POST">
                        <input type="hidden" name="vehicle_id" value="<?php echo $row['vehicle_id']; ?>">
                        <button type="submit">Return</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
