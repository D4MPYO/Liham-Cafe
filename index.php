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
    <link rel="stylesheet" href="index.css" />
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
    <script src="https://kit.fontawesome.com/effd3867de.js"crossorigin="anonymous"></script>
    <!-- AOS Library -->
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
        <p>Write it.</p>
        <p>Believe it.</p>
        <p>Be it.</p>
        <p>Liham para sa Hinaharap</p>
      </div>
    </section>

    <main>
      <section data-aos="fade-left" data-aos-delay="300">
        <div class="section-1-image-mobile">
          <img src="Images/DSCF8992.jpg" alt="" />
        </div>
        <div>
          <h2>What makes Liham Cafe unique?</h2>
          <p>
            Well, besides our awesome espresso and milk-based drinks, we've got
            a soft spot for nostalgia and connection. Ever thought about writing
            a letter to your future self or a loved one? We've got you covered.
            Scribble down your thoughts, and we'll make sure it gets to the
            right place - whether that's your doorstep or our cafe after a year.
          </p>
          <a href="about.php">
            <button>MORE ABOUT US</button>
          </a>
        </div>
        <div class="section-1-image">
          <img src="Images/DSCF8992.jpg" alt="" />
        </div>
      </section>

      <section data-aos="fade-right" data-aos-delay="300">
        <div>
          <img src="Images/image (4).png" alt="" />
        </div>
        <div>
          <h2>What is Liham para sa Hinaharap?</h2>
          <p>
            "Liham para sa hinaharap" is a unique concept of Liham Cafe that
            allows its customers to write a letter to their future selves or to
            anyone else. These letters, sealed with wax, are then stored in
            envelope boxes at Liham Cafe and can be picked up or mailed to the
            provided address on the desired date next year. Let's say you write
            a letter on 14 February 2024, your letter can be picked up or mailed
            on or before 31 December 2025.
          </p>
          <a href="about.php">
            <button>MORE ABOUT US</button>
          </a>
        </div>
      </section>
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
  </body>
</html>
<style>
@media (max-width: 768px) {
  .welcome-message {
    display: none;
}

}
    </style>
