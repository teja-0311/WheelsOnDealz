<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_name = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['decrease_item_id'])) {
        $decrease_item_id = $_POST['decrease_item_id'];

        // Decrease the quantity by 1
        $update_sql = "UPDATE cart SET quantity = quantity - 1 
                       WHERE id = ? AND username = ? AND quantity > 1";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$decrease_item_id, $user_name]);

        // If quantity becomes 0, remove the item
        $delete_sql = "DELETE FROM cart WHERE id = ? AND username = ? AND quantity <= 1";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->execute([$decrease_item_id, $user_name]);

        header("Location: cart.php");
        exit();
    }
}

$sql = "SELECT vehicles.*, cart.quantity FROM cart 
        JOIN vehicles ON cart.id = vehicles.id 
        WHERE cart.username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_name]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - AutoRent</title>
    <link rel="stylesheet" href="proj1.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">AutoMobileRent</h1>
            <nav class="nav">
                <a href="proj1.php">Home</a>
                <a href="about.html">About</a>
                <a href="contact.html">Contact</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="cart-container">
        <h2>Your Cart</h2>
        <?php if (count($cart_items) > 0): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= $item['brand'] . ' ' . $item['model'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="decrease_item_id" value="<?= $item['id'] ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-footer">
                <p>Total Price: $<?= number_format($total_price, 2) ?></p>
                <form action="booking.php" method="GET">
                    <?php if (count($cart_items) === 1): ?>
                        <input type="hidden" name="vehicle_id" value="<?= $cart_items[0]['id'] ?>">
                    <?php elseif (count($cart_items) > 1): ?>
                        <select name="vehicle_id" required>
                            <option value="">Select a vehicle to book</option>
                            <?php foreach ($cart_items as $item): ?>
                                <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['brand'] . ' ' . $item['model']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                    <button type="submit">Proceed to Booking</button>
                </form>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 AutoMobileRent. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
