<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "You must log in first.";
    header('Location: login.php');
    exit();
}

$sql = "SELECT id, name, description, price, category, image, created_at FROM menu_items ORDER BY category, name";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $item_id = (int)$_POST['item_id'];
    addToCart($item_id);
    header("Location: menu.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        $orderOption = isset($_SESSION['order_option']) ? $_SESSION['order_option'] : 'Dine In'; 
        $user_id = $_SESSION['user_id'];
        $totalAmount = 0;
        foreach ($_SESSION['cart'] as $item_id => $item) {
            $quantity = $item['quantity'];
            $price = $item['price'];
            $total = $price * $quantity;
            $totalAmount += $total;
        }

        $tipAmount = isset($_POST['tip_amount']) ? (float)$_POST['tip_amount'] : 0;

        $totalAmount += ($totalAmount * $tipAmount / 100);
        $conn->begin_transaction();
        try {
            $order_date = date('Y-m-d H:i:s');
            $status = 'Pending';
            $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, total_amount, order_option, status, tip_amount) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isdsdd", $user_id, $order_date, $totalAmount, $orderOption, $status, $tipAmount);
            $stmt->execute();
            $order_id = $stmt->insert_id; 
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO cart_items (user_id, item_id, quantity, price, total, order_id) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($_SESSION['cart'] as $item_id => $item) {
                $quantity = $item['quantity'];
                $price = $item['price'];
                $total = $price * $quantity;

                $stmt->bind_param("iiidid", $user_id, $item_id, $quantity, $price, $total, $order_id);
                $stmt->execute();
            }
            $stmt->close();

            $conn->commit();
            unset($_SESSION['cart']); 

            header("Location: details.php?order_id=$order_id");
            exit();
            } catch (Exception $e) {
                $conn->rollback();
                echo "Error: " . $e->getMessage();
            }
        } else {
            header("Location: menu.php?checkout=empty");
            exit();
        }
    }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_option'])) {
            $_SESSION['order_option'] = $_POST['order_option']; 
            header("Location: menu.php"); 
            exit();
        }

        function addToCart($item_id) {
            global $conn;

            if (!isset($_SESSION['cart'][$item_id])) {
                $sql = "SELECT id, name, price, image FROM menu_items WHERE id = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param("i", $item_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $_SESSION['cart'][$item_id] = $row;
                    $_SESSION['cart'][$item_id]['quantity'] = 1; 
                    $_SESSION['cart'][$item_id]['addon_price'] = 0; 
                }
            } else {
                $_SESSION['cart'][$item_id]['quantity'] += 1; 
            }
        }

            $full_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "Guest";

            // Update cart quantity
            if (isset($_POST['update_id']) && isset($_POST['new_quantity'])) {
                $update_id = $_POST['update_id'];
                $new_quantity = (int)$_POST['new_quantity'];

                if ($new_quantity > 0 && isset($_SESSION['cart'][$update_id])) {
                    $_SESSION['cart'][$update_id]['quantity'] = $new_quantity;
                }
                echo json_encode(['message' => 'Quantity updated']);
                exit();
            }

            // Remove item from cart
            if (isset($_POST['remove_id'])) {
                $remove_id = (int)$_POST['remove_id'];
                unset($_SESSION['cart'][$remove_id]);

                echo "<script>
                        alert('Item removed from cart');
                        window.location.href = ('menu.php');
                    </script>";
                exit();
            }
?>



    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liham Cafe Menu</title>
        <link rel="stylesheet" href="menu.css">
        <link rel="stylesheet" href="globals.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
        <script src="https://kit.fontawesome.com/effd3867de.js" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
                <p>Liham Cafe</p>
                <p>Menu</p>
            </div>
        </section>
        <main>
    <div class="product-section">     
    <div class="categories-container">
        <div class="categories-links">
            <a href="#dessert/snack" class="categories-link">Dessert/Snack</a>
            <a href="#espresso" class="categories-link">Espresso</a>
            <a href="#fruit-tea" class="categories-link">Fruit Tea</a>
            <!-- <a href="#liham-set" class="categories-link">Liham Set</a> -->
            <a href="#non-coffee" class="categories-link">Non-Coffee</a>
            <a href="#rice-meals" class="categories-link">Rice Meals</a>
            <a href="#yakult-series" class="categories-link">Yakult Series</a>
        </div>
    </div>
        
        <div class="menu-grid">
            <?php
            // Query for best sellers
            $bestSellersSql = "SELECT id, name, description, price, category, image FROM menu_items WHERE best_selling = 1 ORDER BY name";
            $bestSellersResult = $conn->query($bestSellersSql);
            if ($bestSellersResult->num_rows > 0) {
                $currentCategory = '';
                while ($row = $bestSellersResult->fetch_assoc()) {
                    $imageSrc = htmlspecialchars($row['image']);
                    if ($currentCategory !== $row['category']) {
                        if ($currentCategory !== '') echo "</div></section>";
                        $currentCategory = $row['category'];
                        echo "<section id='" . strtolower(str_replace(' ', '-', $currentCategory)) . "'>";
                        echo "<h3 class='menu-category-title'>" . htmlspecialchars($currentCategory) . "</h3>";
                        echo "<div class='menu-grid'>";
                    }
                    echo "<div class='menu-item'>";
                    echo " <h3 class='menu-category-title'>Best Seller!</h3>";
                    echo "<img class='menu-item-image' src='" . (!empty($imageSrc) ? $imageSrc : 'admin/uploads/default-image.png') . "' alt='" . htmlspecialchars($row['name']). "' width='100%' height='200px'>";
                    echo "<div class='menu-item-details'>";
                    echo "<h4 class='item-title'>" . htmlspecialchars($row['name']) . "</h4>";
                    echo "<p class='item-description'>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p class='price'>₱" . number_format($row['price'], 2) . "</p>";
                    echo "<form action='menu.php' method='POST'>
                            <input type='hidden' name='item_id' value='" . $row['id'] . "'>
                            <button class='add-button' name='add_to_cart'>Add to Cart</button>
                        </form>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div></section>";
            } else {
                echo "<p class='no-menu-message'>No best sellers available.</p>";
            }
            ?>
        </div>
        <?php
        $currentCategory = '';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imageSrc = htmlspecialchars($row['image']);
                if ($currentCategory !== $row['category']) {
                    if ($currentCategory !== '') echo "</div></section>";
                    $currentCategory = $row['category'];
                    echo "<section id='" . strtolower(str_replace(' ', '-', $currentCategory)) . "'>";
                    echo "<h3 class='menu-category-title'>" . htmlspecialchars($currentCategory) . "</h3>";
                    echo "<div class='menu-grid'>";
                }
                echo "<div class='menu-item'>";
                echo "<img class='menu-item-image' src='" . (!empty($imageSrc) ? $imageSrc : 'admin/uploads/default-image.png') . "' alt='" . htmlspecialchars($row['name']). "' width='100%' height='200px'>";
                echo "<div class='menu-item-details'>";
                echo "<h4 class='item-title'>" . htmlspecialchars($row['name']) . "</h4>";
                echo "<p class='item-description'>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p class='price'>₱" . number_format($row['price'], 2) . "</p>";
                echo "<form action='menu.php' method='POST'>
                        <input type='hidden' name='item_id' value='" . $row['id'] . "'>
                        <button class='add-button' name='add_to_cart'>Add to Cart</button>
                    </form>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div></section>";
        } else {
            echo "<p class='no-menu-message'>No menu items available.</p>";
        }
        ?>
    </div>
       <div class="cart" id="cart">
        <h2 class="cart-title">Your Order</h2>
        <form method="POST" action="menu.php">
            <select class="pickup-select" name="order_option" onchange="this.form.submit()">
                <option value="Dine In" <?= isset($_SESSION['order_option']) && $_SESSION['order_option'] == 'Dine In' ? 'selected' : ''; ?>>Dine in</option>
                <option value="Take Out" <?= isset($_SESSION['order_option']) && $_SESSION['order_option'] == 'Take Out' ? 'selected' : ''; ?>>Take out</option>
            </select>
        </form>
        <div class="cart-items" id="cartItems">
        <?php   
$totalAmount = 0;

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $item_id => $item) {
        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
        $itemTotal = $item['price'] * $quantity;
        $totalAmount += $itemTotal;

       // Display cart item
       echo "<div class='cart-item'>
       <p class='name'>" . htmlspecialchars($item['name']) . "</p>
       <p>₱" . number_format($item['price'], 2) . "</p>
       <p>Quantity: 
           <input type='number' name='quantity' value='{$quantity}' min='1' 
               data-product-id='{$item_id}' class='quantity-input'/>
       </p>
       <div class='cart-actions'>
           <!-- Update Total SVG -->
           <svg class='update-quantity' onclick='updateCart({$item_id})' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' width='24' height='24'>
               <path d='M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z'/>
           </svg>
           
           <!-- Remove Cart SVG in a button -->
           <form method='POST' class='cancel-form' style='display:inline;'>
               <input type='hidden' name='remove_id' value='{$item_id}' />
               <button type='submit' class='cancel-button' style='background: none; border: none; padding: 0; cursor: pointer;'>
                   <svg xmlns='http://www.w3.org/2000/svg' class='icon' viewBox='0 0 448 512' width='24' height='24'>
                       <path d='M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z'/>
                   </svg>
               </button>
           </form>
       </div>
       <p class='addons'></p>
     </div>";
}
} else {
echo "<p>Your cart is empty.</p>";
}
?>
        <div>
            <p>Tip</p>
            <div class="tip-options">
                <button class="tip-button selected" onclick="setTip(0)">0%</button>
                <button class="tip-button" onclick="setTip(10)">10%</button>
                <button class="tip-button" onclick="setTip(20)">20%</button>
                <button class="tip-button" onclick="showCustomTipInput()">Other</button>
            </div>
            <div id="customTip" style="display: none;">
                <input type="number" id="customTipInput" placeholder="Enter custom tip" />
                <button onclick="setCustomTip()">Set Tip</button>
            </div>
        </div>

        <div class="selected-tip">
    <p>Amount of Tip: <strong><span id="tipAmount">₱0.00</span></strong></p>
        </div>

        <div class="total">
            <span>TOTAL</span>
            <span id="totalAmount">₱<?php echo number_format($totalAmount, 2); ?></span>
        </div>

        <input type="hidden" id="final_total_amount" name="total_amount" value="<?php echo $totalAmount; ?>">


        <!-- JavaScript section -->
<script>
   // Get total amount from PHP
let currentTip = 0; // Default to 0% tip

// Function to set the tip when a button is clicked
function setTip(percentage) {
    // Set the selected tip percentage
    currentTip = percentage;

    // Calculate the tip amount
    let tipAmount = (currentTip / 100) * totalAmount;

    // Update the displayed tip amount
    document.getElementById("tipAmount").textContent = "₱" + tipAmount.toFixed(2);

    // Optionally, update the total amount with the tip
    let newTotal = totalAmount + tipAmount;
    document.getElementById("totalAmount").textContent = "₱" + newTotal.toFixed(2);
}

// Function to allow the user to input a custom tip percentage
function showCustomTipInput() {
    let customTip = prompt("Enter your custom tip percentage:");
    if (customTip !== null && !isNaN(customTip) && customTip >= 0) {
        setTip(parseFloat(customTip)); // Update the tip with the custom value
    }
}

</script>

        <form method="POST" action="menu.php">
            <input type="hidden" name="order_option" value="<?= isset($_SESSION['order_option']) ? $_SESSION['order_option'] : 'Dine In'; ?>">
            <input type="hidden" id="tip_amount" name="tip_amount" value="0">
            <button type="submit" name="checkout" class="checkout-button">Checkout</button>
        </form>

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

        </body>
    </html>

        <script>

    document.querySelector('.checkout-button').addEventListener('click', function(e) {
        if (document.querySelectorAll('.cart-item').length === 0) {
            e.preventDefault();
            swal('Error!', 'Your cart is empty.', 'error');
        }
    });


            function updateCart(productId) {
                const newQuantity = document.querySelector(`.quantity-input[data-product-id='${productId}']`).value;
                const data = new FormData();
                data.append('update_id', productId);
                data.append('new_quantity', newQuantity);

                fetch('menu.php', {
                    method: 'POST',
                    body: data,
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
            }

            function removeFromCart(productId) {
                const data = new FormData();
                data.append('remove_id', productId);

                fetch('menu.php', {
                    method: 'POST',
                    body: data,
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
            }
            document.addEventListener('DOMContentLoaded', function() {
        let selectedTip = 0;

        // Function to set the tip based on user input
        function setTip(tip) {
            if (tip === 'custom') {
                const customTip = parseFloat(prompt("Enter custom tip percentage:"));
                if (!isNaN(customTip) && customTip >= 0) {
                    selectedTip = customTip;
                } else {
                    alert('Please enter a valid custom tip percentage.');
                    selectedTip = 0; // Reset if invalid input
                }
            } else {
                selectedTip = tip;
            }

            // Update the total amount after selecting a tip
            updateTotalAmount();

            // Update the hidden input field for the tip amount
            document.getElementById('tip_amount').value = selectedTip;
        }

        // Function to update the total amount displayed
        function updateTotalAmount() {
            let totalAmount = <?php echo $totalAmount; ?>;
            totalAmount += (totalAmount * selectedTip / 100);
            document.getElementById('totalAmount').innerText = '₱' + totalAmount.toFixed(2);
        }
        const buttons = document.querySelectorAll('.tip-button');
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                buttons.forEach(btn => btn.classList.remove('selected'));
                button.classList.add('selected');
                if (button.textContent === 'Other') {
                    setTip('custom');
                } else {
                    const tipPercent = parseInt(button.textContent, 10);
                    setTip(tipPercent);
                }
            });
        });
    });

        </script>
        <script>
        const totalAmount = <?php echo $totalAmount; ?>;
    </script>

        <style>
                @media (max-width: 768px) {
    .welcome-message {
        display: none;
    }

    }
        </style>

    <script src="index.js"></script>
    <script src="menu.js"></script>
