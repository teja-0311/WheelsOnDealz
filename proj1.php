<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AutoRent - Rent Your Dream Vehicle</title>
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
        <a href="display_reviews.php">See Reviews</a>
        <a href="cart.php">Cart</a>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
          <!-- User is logged in, show logout button -->
          <a href="logout.php">Logout</a>
          <a href="submit_review.php">Submit Review</a>
        <?php else: ?>
          <!-- User is not logged in, show login button -->
          <a href="login.php">Login</a>
          <a href="signup.html">Sign Up</a>
          
        <?php endif; ?>
      </nav>
    </div>
  </header>
  
  <!-- Banner Section -->
  <div class="banner">
    <h3>Find Your Perfect Vehicle Rental</h3>
  </div>
  
  <!-- Booking Form Section -->
  <div class="booking-section">
    <form id="bookingForm" action="redirect.php" method="POST">
      <div>
        <label for="vehicleType">Vehicle Type</label>
        <select id="vehicleType" name="vehicleType">
          <option value="car">Car</option>
          <option value="bike">Bike</option>
          <option value="bicycle">Bicycle</option>
        </select>
      </div>
     
      <button type="submit">Explore</button>
    </form>
  </div>

  <!-- Featured Cars -->
  <section class="featured-cars">
    <div class="container">
      <h3>Featured Vehicles</h3>
      <div class="car-list">
        <div class="car">
          <img src="https://d2m3nfprmhqjvd.cloudfront.net/blog/20220228143146/559212.jpeg" alt="Luxury Sedan">
          <h4>Mahindra Scorpio</h4>
          <p>$80/day</p>
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a href="car.php" class="btn">View Details</a>
          <?php endif; ?>
        </div>
        <div class="car">
          <img src="https://imgd.aeplcdn.com/664x374/n/cw/ec/51245/meteor-350-right-front-three-quarter.jpeg?q=80" alt="Royal Enfield">
          <h4>Royal Enfield</h4>
          <p>$100/day</p>
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a href="bike.php" class="btn">View Details</a>
          <?php endif; ?>
        </div>
        <div class="car">
          <img src="https://rukminim2.flixcart.com/image/850/1000/kkyc9zk0/cycle/8/f/t/defender-27-5-17-hercules-single-speed-original-imagy6tzdfereguj.jpeg?q=20&crop=false" alt="Bicycle">
          <h4>Hercules Defender</h4>
          <p>$120/day</p>
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a href="bicycle.php" class="btn">View Details</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Why Choose Us -->
  <section class="why-choose">
    <div class="container">
      <h3>Why Choose AutoMobileRent?</h3>
      <p>We offer a seamless rental experience with competitive prices and a wide variety of vehicles.</p>
      <div class="features">
        <div class="feature">
          <h4>24/7 Customer Support</h4>
          <p>Our team is here to help you anytime, day or night.</p>
        </div>
        <div class="feature">
          <h4>Flexible Rentals</h4>
          <p>Rent by the day, week, or month to suit your needs.</p>
        </div>
        <div class="feature">
          <h4>Quality Assured</h4>
          <p>Every vehicle is maintained to the highest standards.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p>&copy; 2023 AutoRent. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
