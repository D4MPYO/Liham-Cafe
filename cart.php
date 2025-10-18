<?php
session_start();
include 'connection.php'; // Make sure connection.php is included for database connection

// Check if 'product_id' and 'quantity' are set in the POST request
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Fetch product from the database
    $sql = "SELECT id, name, description, price, category, image, created_at FROM menu_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        // Add product to cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] += $quantity; // Increase quantity if the product is already in the cart
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'description' => $product['description'], 
                'price' => $product['price'],
                'quantity' => $quantity,
                'image_url' => $product['image'],
                'category' => $product['category'], // Added category
            ];
        }

        echo json_encode(['message' => 'Item added to cart']);
    } else {
        echo json_encode(['message' => 'Product not found']);
    }
} else {
    echo json_encode(['message' => 'Product ID and Quantity are required']);
}
?>
