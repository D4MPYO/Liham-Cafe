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
    <link rel="stylesheet" href="about.css" />
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
    <script
      src="https://kit.fontawesome.com/effd3867de.js"
      crossorigin="anonymous"
    ></script>
    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  </head>
  <body>
    <!-- Nav  -->
    <nav>
      <a href="/" class="logo">
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
    <main>
      <!-- Hero section -->
      <section class="hero-section"></section>

      <div data-aos="zoom-in" class="about-container">
        <h1 class="about-title">ABOUT LIHAM CAFE</h1>
        <div class="content-grid">
          <div class="image-container">
            <img
              src="Images/DSCF8992.jpg"
              alt="Coffee and letter writing setup at Liham Cafe"
              class="cafe-image"
            />
            <img
              src="Images/DSCF8969.jpg"
              alt="Wall of letter boxes at Liham Cafe"
              class="cafe-image"
            />
          </div>
          <div class="text-content">
            <p>
              Welcome to Liham Cafe, your cozy spot in the heart of Vinzons,
              Camarines Norte! At Liham, we're all about bringing you fantastic
              coffee and a one-of-a-kind experience.
            </p>
            <p>
              Well, besides our awesome espresso and milk-based drinks, we've
              got a soft spot for nostalgia and connection. Ever thought about
              writing a letter to your future self or a loved one? We've got you
              covered. Scribble down your thoughts, and we'll make sure it gets
              to the right place - whether that's your doorstep or our cafe
              after a year.
            </p>
            <p>
              Liham Cafe isn't just about great coffee; it's about creating
              memories and preserving messages. Swing by for a sip, a smile, and
              maybe a letter that'll make you grin a year from now. Cheers to
              coffee and connection at Liham Cafe!
            </p>
          </div>
        </div>
      </div>

      <div data-aos="zoom-in-up" data-aos-delay="300" class="about-container">
        <h1 class="about-title">WHAT IS LIHAM PARA SA HINAHARAP</h1>
        <div class="content-grid">
          <div class="text-content">
            <p>
              "Liham para sa hinaharap" is a unique concept of Liham Cafe that
              allows its customers to write a letter to their future selves or
              to anyone else. These letters, sealed with wax, are then stored in
              envelope boxes at Liham Cafe and can be picked up or mailed to the
              provided address on the desired date next year. Let's say you
              write a letter on 14 February 2024, your letter can be picked up
              or mailed on or before 31 December 2025.
            </p>
            <h3>WHAT TO WRITE?</h3>
            <p>
              <b>Your current situation.</b> Kumusta ka? Anong pinagkakaabalahan
              mo? Isulat mo lahat ng gustong mong isulat na nararamdaman mo o
              naiisip mo ngayon. Let the words flow. Breathe as you open up and
              share your feelings.
            </p>

            <p>
              <b>Things you are grateful for.</b> Anong mga bagay o event o tao
              sa buhay mo na gusto mong pasalamatan? Ito ay makakatulong upang
              mas mag-focus ka sa blessings na iyong natanggap.
            </p>
            <p>
              <b>Long-term goals.</b> Anong goal na gusto mong tuparin sa loob
              ng mahigit isang taon? Gusto mong maging CPA, Chef, Doctor,
              Lawyer, Architect, Seaman? You can turn your dreams into reality
              as long as you set your mind to it.
            </p>
          </div>
          <div class="image-container">
            <img
              src="Images/image (4).png"
              alt="Coffee and letter writing setup at Liham Cafe"
              class="cafe-image-2"
            />
            <div class="text-content">
              <p>
                <b>Short-term goals.</b> Anong goal mo na gusto mong tuparin sa
                loob ng isang taon para ma-achieve mo ang long- term goals mo?
                Take it one step at a time.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="guide-container">
        <div class="guide-title">
          <h1>STEPS GUIDES FOR</h1>
          <h2>Liham para sa Hinaharap</h2>
        </div>

        <div class="timeline">
          <div class="step" data-aos="fade-left" data-aos-delay="500">
            <div class="step-content">
              <div class="step-number">STEP 1</div>
              <div class="step-text">
                Buy a Liham Set (includes postcards, wax seal beads, and
                envelope).
              </div>
            </div>
            <div class="step-dot"></div>
            <img
              src="Images/Envi-Set2.jpg"
              alt="Liham Set with postcards and wax seals"
              class="step-image"
            />
          </div>

          <div class="step" data-aos="fade-right" data-aos-delay="500">
            <div class="step-content">
              <div class="step-number">STEP 2</div>
              <div class="step-text">
                Write anything on the postcards which can be addressed to
                yourself, a family member, a relative, or anyone else.
              </div>
            </div>
            <div class="step-dot"></div>
            <img
              src="Images/Envi-Write.jpg"
              alt="Writing on a postcard"
              class="step-image"
            />
          </div>

          <div class="step" data-aos="fade-left" data-aos-delay="500">
            <div class="step-content">
              <div class="step-number">STEP 3</div>
              <div class="step-text">
                Insert postcards into the envelope and seal it in the sealing
                station.
              </div>
            </div>
            <div class="step-dot"></div>
            <img
              src="Images/image (4).png"
              alt="Sealing an envelope with wax"
              class="step-image"
            />
          </div>

          <div class="step" data-aos="fade-right" data-aos-delay="500">
            <div class="step-content">
              <div class="step-number">STEP 4</div>
              <div class="step-text">
                Write the following on the envelope: Back: Name and complete
                address; Front: Desired mailing or pick up date.
              </div>
            </div>
            <div class="step-dot"></div>
            <img
              src="Images/image (7).png"
              alt="Sealing an envelope with wax"
              class="step-image"
            />
          </div>

          <div class="step" data-aos="fade-left" data-aos-delay="500">
            <div class="step-content">
              <div class="step-number">STEP 5</div>
              <div class="step-text">
                Fill out the registration slip with your contact details. Put
                the envelope in the box with the desired mailing or pick up date
                next year.
              </div>
            </div>
            <div class="step-dot"></div>
            <img src="Images/DSCF8973.jpg" alt="" class="step-image" />
          </div>
        </div>

        <p class="guide-footer">
          And you are done! You can come back to Liham Cafe to pick up your
          letter or have it mailed to the address provided.
        </p>
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
  </body>
</html>
<style>
            @media (max-width: 768px) {
  .welcome-message {
    display: none;
}

}
    </style>