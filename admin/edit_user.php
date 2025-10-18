<?php
session_start();
include '../connection.php';

$user_id = (int)$_GET['user_id'];  // Cast to integer for security

// Fetch the user details
$sql = "SELECT * FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();
} else {
  echo "User not found.";
  exit();
}

// Fetch the orders for the user
$order_sql = "SELECT * FROM orders WHERE user_id = ?";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit();
  }

  $update_sql = "UPDATE user SET full_name = ?, email = ? WHERE user_id = ?";
  $update_stmt = $conn->prepare($update_sql);
  $update_stmt->bind_param("ssi", $full_name, $email, $user_id);

  if ($update_stmt->execute()) {
    echo "User updated successfully!";
    header("Location: admin_dashboard.php");
    exit();
  } else {
    // Log the error with user ID for better debugging
    error_log("Error updating user (ID: $user_id): " . $conn->error);
    echo "Error updating user. Please try again.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Liham Cafe</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/effd3867de.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Admin Dashboard Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2>Liham Cafe Admin</h2>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Admin Dashboard Content -->
    <main class="admin-main">
        <div class="dashboard-header">
            <h1>Edit User: <?php echo htmlspecialchars($user['full_name']); ?></h1>
        </div>

        <div class="dashboard-content">
            <form action="edit_user.php?user_id=<?php echo $user['user_id']; ?>" method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <button type="submit" class="update-btn">Update User</button>
            </form>
        </div>

        <!-- User's Orders Section -->
        <div class="user-orders">
            <h2>User's Orders</h2>
            <?php if ($order_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $order_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $order['order_id']; ?></td>
                                <td><?php echo $order['order_date']; ?></td>
                                <td><?php echo $order['total_amount']; ?></td>
                                <td><?php echo $order['status']; ?></td>
                                <td>
                                    <a href="view_orders.php?order_id=<?php echo $order['order_id']; ?>" class="view-btn">View</a>
                                    <!-- You can add other actions like update, cancel, etc. -->
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders found for this user.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
<style>
  form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-width: 500px;
    margin: 0 auto;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

label {
    font-weight: bold;
    color: #555;
}

input[type="text"], input[type="email"] {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    color: #333;
    width: 100%;
    box-sizing: border-box;
}

input[type="text"]:focus, input[type="email"]:focus {
    border-color: #0066cc;
    outline: none;
}

/* Button Styling */
button.update-btn {
    background-color: #0066cc;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    box-sizing: border-box;
    transition: background-color 0.3s ease;
}

button.update-btn:hover {
    background-color: #005bb5;
}
</style>
