<?php
session_start();
include 'connection.php';

$full_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "Guest";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Liham Cafe</title>
    <link rel="stylesheet" href="sample.css" />
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="contact.css">
    <link rel="shortcut icon" href="Image/Logooo.png" sizes="32x32" type="image/x-icon">
    <script src="https://kit.fontawesome.com/effd3867de.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  </head>
  <body>
    <!-- Navigation -->
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
        <p>Get in Touch</p>
        <p>Contact Us</p>

      </div>
    </section>
    <!-- Main Content -->
    <main>
  <div class="contact-section">
    <div class="contact-card">
      <h2><b>Reach Us</b></h2>
      <p><b>Email:</b> <a href="mailto:liham.cafe.inc@outlook.com">liham.cafe.inc@outlook.com</a></p>
      <p><b>Phone:</b> +63 912 345 6789</p>
      <p><b>Address:</b> Barangay I, Sta. Cruz Street, Vinzons, Camarines Norte</p>
      <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3485.518268508266!2d122.90437531015529!3d14.175976286204248!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3398b1ee9136a601%3A0x9036f7a566d11bda!2sLiham%20Cafe!5e1!3m2!1sen!2sph!4v1733057142432!5m2!1sen!2sph" 
        style="border-radius:20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
    <!-- Contact Form -->
    <div class="form-section">
      <h2>Fill the Form*</h2>
      <form action="https://api.web3forms.com/submit" method="POST">
    <input type="hidden" name="access_key" value="34c68be7-62cd-443b-96cc-7de505cea7fb">
    <input type="text" name="name" placeholder="Name*" required>
    <input type="number" name="number" placeholder="Phone*" required>
    <input type="email" name="email" placeholder="Email*" required>
    <input type="text" name="subject" placeholder="Subject" required>
    <textarea name="message" placeholder="Message*" required></textarea>
    <button type="submit">Send Message</button>
</form>

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

    <script src="index.js"></script>
  </body>
</html>
