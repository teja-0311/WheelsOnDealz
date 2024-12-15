<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $vehicle_id = $_POST['vehicle_id'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];

    // Insert the review into the database
    $stmt = $pdo->prepare("INSERT INTO reviews (user_name, vehicle_id, review, rating) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_name, $vehicle_id, $review, $rating])) {
        echo "Review submitted successfully!";
    } else {
        echo "Failed to submit review.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Submit Review</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the external CSS -->
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
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

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        button {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #1c85e8;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #0066cc;
            transform: translateY(-2px);
        }

        /* Slider Styling */
        .slider-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .slider-container p {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #555;
        }

        .slider-container input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 12px;
            background: #ddd;
            border-radius: 10px;
            outline: none;
            transition: background 0.3s ease;
        }

        .slider-container input[type="range"]:hover {
            background: #ccc;
        }

        .slider-container input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: #1c85e8;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease;
        }

        .slider-container input[type="range"]::-webkit-slider-thumb:hover {
            background: #0066cc;
        }

        .slider-container input[type="range"]::-moz-range-thumb {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: #1c85e8;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease;
        }

        .slider-container input[type="range"]::-moz-range-thumb:hover {
            background: #0066cc;
        }

        .slider-container span {
            font-size: 18px;
            color: #1c85e8;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submit a Review</h1>
        <form method="POST" action="">
            <label for="user_name">Your Name</label>
            <input type="text" id="user_name" name="user_name" placeholder="Enter your name" required>

            <label for="vehicle_id">Vehicle ID</label>
            <input type="number" id="vehicle_id" name="vehicle_id" placeholder="Enter vehicle ID" required>

            <label for="review">Your Review</label>
            <textarea id="review" name="review" placeholder="Write your review here..." required></textarea>

            <div class="slider-container">
                <p>Rate the Vehicle: <span id="rating-value">3</span> ⭐</p>
                <input type="range" id="rating" name="rating" min="0.5" max="5" step="0.5" value="3">
            </div>

            <button type="submit">Submit Review</button>
        </form>
    </div>

    <script>
        // Update the displayed rating value
        const slider = document.getElementById('rating');
        const ratingValue = document.getElementById('rating-value');

        slider.addEventListener('input', () => {
            ratingValue.textContent = slider.value;
        });
    </script>
</body>
</html>
