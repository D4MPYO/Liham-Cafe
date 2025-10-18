<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'your_database_name');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from POST
$addons = $_POST['addons'];

// Insert order into the database
$sql = "INSERT INTO orders (addons, status) VALUES ('$addons', 'Pending')";

if ($conn->query($sql) === TRUE) {
    echo "Order saved successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: menu.php?error=not_logged_in");
        exit();
    }

    if (!empty($_SESSION['cart'])) {
        $user_id = $_SESSION['user_id'];
        $totalAmount = array_reduce($_SESSION['cart'], function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Insert order
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, order_date) VALUES (?, ?, 'Pending', NOW())");
            $stmt->bind_param("id", $user_id, $totalAmount);
            $stmt->execute();
            $order_id = $stmt->insert_id;

            // Insert cart items
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($_SESSION['cart'] as $item_id => $item) {
                $stmt->bind_param("iiid", $order_id, $item_id, $item['quantity'], $item['price']);
                $stmt->execute();
            }

            $conn->commit();
            unset($_SESSION['cart']);
            header("Location: menu.php?checkout=success");
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    } else {
        header("Location: menu.php?checkout=empty");
    }
    exit();
}
$conn->close();
?>
