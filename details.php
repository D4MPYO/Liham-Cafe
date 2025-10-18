<?php
session_start();
include('connection.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; 
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null; 

if (!$order_id) {
    echo "Order ID is missing.";
    exit();
}

$userQuery = "SELECT full_name FROM user WHERE user_id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userResult = $stmt->get_result();

$userFullName = $userResult->num_rows > 0 ? $userResult->fetch_assoc()['full_name'] : "Guest";
$stmt->close();

$orderQuery = "SELECT order_option, tip_amount, total_amount FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$orderResult = $stmt->get_result();

if ($orderResult->num_rows === 0) {
    echo "No order found.";
    exit();
}

$orderDetails = $orderResult->fetch_assoc();
$orderOption = $orderDetails['order_option'];
$tipPercentage = $orderDetails['tip_amount'];  // Tip stored as percentage
$totalAmount = $orderDetails['total_amount'];  // Total amount including tip

// Calculate the actual tip amount (assuming total_amount includes the tip)
$tipAmount = ($tipPercentage / 100) * ($totalAmount / (1 + $tipPercentage / 100));  // Remove the tip from total amount

$stmt->close();

// Fetch items related to the order
$itemsQuery = "
    SELECT ci.quantity, mi.name, mi.price, mi.category
    FROM cart_items ci
    JOIN menu_items mi ON ci.item_id = mi.id
    WHERE ci.order_id = ?
";
$stmt = $conn->prepare($itemsQuery);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$itemsResult = $stmt->get_result();

$orderItems = [];
while ($itemRow = $itemsResult->fetch_assoc()) {
    $orderItems[] = $itemRow;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="details.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="globals.css">
    <script src="https://kit.fontawesome.com/effd3867de.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
</head>
<body>
    <nav>
        <a href="index.php" class="logo">
            <img src="./Images/Logooo.png" alt="Logo">
            <p>Liham Cafe</p>
        </a>
        <ul>
            <li><a href="./index.php">Home</a></li>
            <li><a href="./menu.php">Menu</a></li>
            <li><a href="./gallery.php">Gallery</a></li>
            <li><a href="./about.php">About Us</a></li>
            <li><a href="./contact.php">Contact Us</a></li>
        </ul>
    </nav>

    <div class="order-details" id="order-details">
    <img src="./Images/Logooo.png" alt="Liham Cafe Logo" width="300" height="200">
    <h1>LIHAM CAFE</h1>
    <p>Here are your order details. Please take a screenshot. Show the ordered item below to the counter to place your order.</p>
    <table>
        <tr>
            <th>QTY</th>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
        </tr>
        <?php if (!empty($orderItems)): ?>
            <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['quantity']) ?>x</td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>₱<?= number_format($item['price'], 2) ?></td>
                    <td><?= htmlspecialchars($item['category']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No items in the order.</td>
            </tr>
        <?php endif; ?>
    </table>
    <div class="total">
        <p>Customer Name: <?= htmlspecialchars($userFullName) ?></p>
        <p>TIP: ₱<?= number_format($tipAmount, 2) ?></p>
        <p>TOTAL AMOUNT: ₱<?= number_format($totalAmount, 2) ?></p>
        <p>Order Option: <?= htmlspecialchars($orderOption) ?></p>
    </div>

    <!-- Display Order ID at the bottom with larger font -->
    <div class="order-id-container">
        <p class="order-id">ORDER ID: <?= htmlspecialchars($order_id) ?></p>
    </div>
</div>

    <button id="downloadPdfBtn" class="btn btn-info">Download PDF</button>
    <script>
        document.getElementById('downloadPdfBtn').addEventListener('click', function () {
            const element = document.getElementById('order-details');
            const opt = {
                margin: 0.5,
                filename: 'Liham_Cafe_Order_Details.pdf',
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save().then(() => {
                window.location.href = 'menu.php';
            });
        });
    </script>
</body>
</html>
