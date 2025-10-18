<?php
session_start();
include 'connection.php';

// Fetch user data (if logged in)
$full_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "Guest";

// Fetch images from the database
$query = "SELECT id, image_name, description, image_path FROM gallery_images";
$result = mysqli_query($conn, $query);

// Error handling for DB query
if (!$result) {
    die('Error fetching images: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Liham Cafe - Gallery</title>
    <link rel="stylesheet" href="gallery.css" />
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
    <script src="https://kit.fontawesome.com/effd3867de.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>
<nav>
      <a href="index.php" class="logo">
        <img src="Images/Logooo.png" alt="" />
        <p>Liham Cafe</p>
      </a>

      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact Us</a></li>
      </ul>

      <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="accounts.php" style="color: white; text-decoration: none;" aria-label="User Account">
                <i class="fa-solid fa-user" aria-hidden="true"></i>
                <span class="welcome-message">Welcome, <?php echo htmlspecialchars($full_name); ?>!</span>
            </a>
            <i id="menu" class="fa-solid fa-bars" aria-hidden="true" style="cursor: pointer;"></i>
        </div>

      <aside class="sidebar">
        <i id="closeBtn" class="fa-solid fa-xmark"></i>
        
        <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        </ul>
        
      </aside>
    </nav>
    <section class="hero-section">
        <div class="hero-title">
            <p>Liham cafe</p>
            <p>Image Gallery</p>
        </div>
    </section>

    <main>
        <div class="gallery-container">
            <div class="gallery-grid">
            <?php
            // Check if there are rows in the result
            if (mysqli_num_rows($result) > 0) {
                // Loop through the result set
                while ($row = mysqli_fetch_assoc($result)) { 
                    $image_name = htmlspecialchars($row['image_name']); // Sanitize the image name
                    $image_path = "uploads/" . htmlspecialchars($row['image_path']); // Correct image path
                    $description = htmlspecialchars($row['description']); // Sanitize the description
                ?>
                    <div class="gallery-item" data-aos="fade-up" data-aos-duration="1000">
                        <img src="<?= $image_path; ?>" alt="<?= $image_name; ?>">
                        <div class="description">
                            <p><?php echo $description; ?></p>
                        </div>
                    </div>
                <?php 
                }
            } else {
                echo "<p>No images found in the gallery.</p>";
            }
            ?>

            </div>
        </div>
    </main>
    <!-- Footer Section -->
    <footer class="footer">
      <div class="footer__container">
        <!-- Quick Links Section -->
        <div class="footer__content">
          <h3 class="footer__title">QUICK LINKS</h3>
          <ul class="footer__links">
            <li><a href="index.php" class="footer__link">Home</a></li>
            <li><a href="menu.php" class="footer__link">Menu</a></li>
            <li><a href="gallery.php" class="footer__link">Gallery</a></li>
            <li><a href="about.php" class="footer__link">About Us</a></li>
            <li><a href="contact.php" class="footer__link">Contact Us</a></li>
          </ul>
        </div>

        <!-- Store Hours Section -->
        <div class="footer__content">
          <h3 class="footer__title">STORE HOURS</h3>
          <ul class="footer__links">
            <li>
              <span class="footer__link">Mon - Sat: 10:00am - 7:00pm</span>
            </li>
            <li><span class="footer__link">Sunday - Closed</span></li>
          </ul>
        </div>

        <!-- Location Section -->
        <div class="footer__content">
          <h3 class="footer__title">LOCATION</h3>
          <ul class="footer__links">
            <li>
              <a
                href="https://maps.app.goo.gl/CqAnR19cAf9aoQ1j8"
                class="footer__link"
                >Barangay I, Sta, Cruz Street, Vinzons, Camarines Norte,
                Philippines</a
              >
            </li>
          </ul>
        </div>

        <!-- Contact Section -->
        <div class="footer__content">
          <h3 class="footer__title">CONTACT US</h3>
          <ul class="footer__links">
          <style>.mailto-link { color: inherit; text-decoration: none; } .mailto-link:hover {text-decoration: underline; } </style>
          <li>
              <a href="mailto:liham.cafe.inc@outlook.com" class="mailto-link">liham.cafe.inc@outlook.com</a>
          </li>
            </li>
          </ul>

          <br />
          <!-- Follow Section -->
          <h3 class="footer__title">FOLLOW US</h3>
          <div class="footer__social">
            <a
              href="https://www.facebook.com/lihamcafe"
              class="footer__social-link"
              target="_blank"
              ><i class="bx bxl-facebook-circle"></i
            ></a>
            <a
              href="https://www.tiktok.com/@lihamcafeph"
              class="footer__social-link"
              target="_blank"
              ><i class="bx bxl-tiktok"></i
            ></a>
            <a
              href="https://instagram.com/lihamcafe"
              class="footer__social-link"
              target="_blank"
              ><i class="bx bxl-instagram"></i
            ></a>
          </div>
        </div>
      </div>

      <p class="footer__copy">
        &#169; Liham Cafe. All rights reserved. <br>Website developed by Devengers.
      </p>
    </footer>
    <script src="./index.js"></script>
    <script src="./menu.js"></script>
</body>
</html>
