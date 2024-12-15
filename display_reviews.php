<?php
include 'db.php';

// Fetch reviews from the database
$stmt = $pdo->query("SELECT user_name, vehicle_id, review, rating, created_at FROM reviews ORDER BY id DESC");
$reviews = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reviews</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .review-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 15px;
            background: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .review-card h3 {
            font-size: 20px;
            color: #1c85e8;
        }

        .review-card p {
            font-size: 16px;
            color: #555;
        }

        .rating {
            font-size: 18px;
            color: #f9a826;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Reviews</h1>

        <?php if ($reviews): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <h3><?= htmlspecialchars($review['user_name']) ?></h3>
                    <p><strong>Vehicle ID:</strong> <?= htmlspecialchars($review['vehicle_id']) ?></p>
                    <p><?= htmlspecialchars($review['review']) ?></p>
                    <p class="rating">Rating: <?= htmlspecialchars($review['rating']) ?> ⭐</p>
                    <p><small>Reviewed on: <?= htmlspecialchars(date('F j, Y, g:i a', strtotime($review['created_at']))) ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews yet. Be the first to leave a review!</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>&copy; <?= date('Y') ?> AutoRent. All rights reserved.</p>
    </div>
</body>
</html>
