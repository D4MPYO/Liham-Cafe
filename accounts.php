<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'connection.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$userQuery = "SELECT full_name, email FROM user WHERE user_id = ?";
$stmtUser = $conn->prepare($userQuery);
if (!$stmtUser) {
    die("User query preparation failed: " . $conn->error);
}
$stmtUser->bind_param('i', $user_id);
$stmtUser->execute();
$userResult = $stmtUser->get_result();
$userData = $userResult->fetch_assoc();
$stmtUser->close();

if (!$userData) {
    $_SESSION['error_message'] = "User details not found.";
    header('Location: login.php');
    exit;
}

// Handle order deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $deleteQuery = "DELETE FROM orders WHERE order_id = ? AND user_id = ?";
    $stmtDelete = $conn->prepare($deleteQuery);
    if (!$stmtDelete) {
        die("Delete query preparation failed: " . $conn->error);
    }
    $stmtDelete->bind_param('ii', $orderId, $user_id);
    $stmtDelete->execute();

    if ($stmtDelete->affected_rows > 0) {
        $_SESSION['success_message'] = "Order #$orderId has been successfully deleted.";
    } else {
        $_SESSION['error_message'] = "Failed to delete the order or the order does not exist.";
    }
    $stmtDelete->close();
    header('Location: accounts.php');
    exit;
}

// Fetch user order history
$orderHistory = [];
$query = "
    SELECT 
        o.order_id,
        o.order_date,
        o.status,
        ci.quantity,
        ci.price,
        mi.name AS product_name,
        mi.category,
        mi.image
    FROM orders o
    JOIN cart_items ci ON o.order_id = ci.order_id
    JOIN menu_items mi ON ci.item_id = mi.id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC;
";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Order history query preparation failed: " . $conn->error);
}
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orderId = $row['order_id'];
    if (!isset($orderHistory[$orderId])) {
        $orderHistory[$orderId] = [
            'order_date' => $row['order_date'],
            'status' => $row['status'],
            'items' => [],
        ];
    }
    $orderHistory[$orderId]['items'][] = [
        'product_name' => $row['product_name'],
        'category' => $row['category'],
        'image' => $row['image'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'total_amount' => $row['quantity'] * $row['price'],
    ];
}

$stmt->close();
$conn->close();
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account - Order History</title>
    <link rel="stylesheet" href="globals.css">
    <script src="https://kit.fontawesome.com/effd3867de.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>
<nav>
    <a href="index.php" class="logo">
        <img src="./Images/Logooo.png" alt="" />
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
            <span class="welcome-message">Welcome, <?php echo htmlspecialchars($userData['full_name']); ?>!</span>
        </a>
        <a href="logout.php" style="color: white; text-decoration: none;" aria-label="Logout">
            <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
            <span>Logout</span>
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

<main><br><br>
    <h2>Your Order History</h2>
    <?php if (!empty($orderHistory)): ?>
        <?php foreach ($orderHistory as $orderId => $order): ?>
    <div class="order" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; border-radius: 5px;">
        <h3>Order ID: <?php echo htmlspecialchars($orderId); ?> | Order Date: <?php echo htmlspecialchars($order['order_date']); ?></h3>
        <p><strong>Status:</strong> <strong><?php echo htmlspecialchars($order['status']); ?></strong> </p>

        <table>
            <thead>
                <tr>
                    <th>Menu Item Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Item Price</th>
                    <th>Total Amount</th>
                    <th>Item Image</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₱<?php echo number_format($item['price'], 2); ?></td>
                        <td>₱<?php echo number_format($item['total_amount'], 2); ?></td>
                        <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="item-image"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Delete Button -->
        <form action="accounts.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderId); ?>">
            <button type="submit" class="delete-btn">Delete Order</button>
        </form>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
        <p>You have no order history yet.</p>
    <?php endif; ?>
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

<style>
/* Main container styles */
main {
    padding: 50px;
    width: 100%;
    align-items: center;
    margin-bottom: 100px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 22px;
    color: black;
}

/* Order container styles */
.order {
    background: white;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 20px;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.order h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
}

.order p {
    font-size: 16px;
    margin-bottom: 15px;
    color: #555;
}

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    font-size: 16px;
}

table th,
table td {
    padding: 10px;
    text-align: center; /* Center-align text */
    border: 1px solid #ddd;
    vertical-align: middle;
}

table thead {
    background: #e6b325;
    color: white;
}

table tr:nth-child(even) {
    background: #f9f9f9;
}

table tr:hover {
    background: #f0d9a6;
}

table th {
    width: auto; /* Ensures equal spacing across all columns */
    font-weight: bold;
}

table img {
    width: 80px; /* Fixed image size */
    height: auto;
    display: block;
    margin: 0 auto; /* Center the image */
}

/* Delete button styles */
.delete-btn {
    color: white;
    background-color: red;
    padding: 5px 10px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    font-size: 14px;
}

.delete-btn:hover {
    background-color: darkred;
}
</style>



<script src="./index.js"></script>
