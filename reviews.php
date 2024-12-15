<?php
session_start();
include 'db.php';

// Check if user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $username = $_SESSION['username'];

    // Check if the user has booked any vehicle
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE username = ?");
    $stmt->execute([$username]);
    $hasBooked = $stmt->fetchColumn();

    // Fetch all reviews if the user has booked a vehicle
    if ($hasBooked) {
        $stmt = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC");
        $reviews = $stmt->fetchAll();
    } else {
        $reviews = [];
    }
} else {
    // User is not logged in, show no reviews or message accordingly
    $reviews = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reviews</title>
    <link rel="stylesheet" href="proj1.css">
</head>
<body>

  <!-- Navigation -->
  <header class="header">
    <div class="container">
      <h1 class="logo">AutoMobileRent</h1>
      <nav class="nav">
        <a href="proj1.php">Home</a>
        <a href="about.html">About</a>
        <a href="contact.html">Contact</a>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a href="logout.php">Logout</a>
            <a href="cart.php">Cart</a>

            <!-- Link to submit review if user has booked a vehicle -->
            <?php if ($hasBooked): ?>
                <a href="submit_review.php" class="btn">Submit Review</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.html">Sign Up</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- Reviews Section -->
  <div class="reviews-section">
    <div class="container">
      <h3>All Reviews</h3>

      <?php if ($reviews): ?>
        <?php foreach ($reviews as $review): ?>
          <div class="review">
            <h4><?php echo htmlspecialchars($review['username']); ?></h4>
            <p><?php echo htmlspecialchars($review['review']); ?></p>
            <p>Rating: <?php echo htmlspecialchars($review['rating']); ?> ⭐</p>
            <p>Reviewed on: <?php echo htmlspecialchars($review['created_at']); ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No reviews available. Please book a vehicle and submit a review!</p>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
