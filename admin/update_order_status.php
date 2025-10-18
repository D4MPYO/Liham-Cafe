<?php
// Start session
session_start();

// Include database connection
require_once 'config.php';

// Check if the order_id and status are set
if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Validate the status (optional, to make sure it's one of the allowed values)
    $allowed_statuses = ['Pending', 'Processing', 'Completed', 'Cancel'];
    if (!in_array($status, $allowed_statuses)) {
        die('Invalid status');
    }

    // Update the status in the database
    $query = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        $stmt->bind_param("si", $status, $order_id); // s = string, i = integer
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            header("Location: view_orders.php?status=success");
        } else {
            header("Location: view_orders.php?status=error");
        }
    } else {
        die("Error preparing statement: " . $mysqli->error);
    }
} else {
    header("Location: view_orders.php?status=error");
}
?>
