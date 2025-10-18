<?php
// Start session
session_start();

// Include database connection
require_once 'config.php';

// Query to fetch all user orders
$query = "SELECT u.user_id AS user_id, u.full_name, o.order_id, o.order_date, o.total_amount, o.status,
          ci.item_id, ci.quantity, ci.total AS item_total, 
          mi.name AS menu_item_name, mi.description, mi.price AS menu_item_price, 
          mi.category, mi.image AS menu_item_image
          FROM user u
          JOIN orders o ON u.user_id = o.user_id
          JOIN cart_items ci ON o.order_id = ci.order_id
          JOIN menu_items mi ON ci.item_id = mi.id
          ORDER BY u.user_id, o.order_date";

$stmt = $mysqli->prepare($query);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();

    // Organize data by user_id and order_id
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[$row['user_id']][$row['order_id']][] = $row;
    }
} else {
    die("Error preparing statement: " . $mysqli->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View All Orders</title>
    <link rel="stylesheet" href="../globals.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Custom CSS for uniform table layout */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        td {
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            max-width: 150px;
        }

        /* Ensure that all table columns have equal width */
        table th, table td {
            width: 14.28%; /* Distribute evenly for 7 columns */
        }

        /* Style for images in the table */
        td img {
            width: 100px;
            height: auto;
        }

        /* Style for the update status form */
        form {
            display: inline-block;
        }
    </style>
</head>
<body>
<nav>
    <a href="admin_dashboard.php" class="logo">
        <img src="./Images/Logooo.png" alt="" />
    </a>
    <div style="display: flex; align-items: center; gap: 1rem;">
        <h1>Manage Orders</h1>
    </div>
</nav>

<div class="dashboard-content">
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <a href="admin_dashboard.php" class="logo">
                <img src="../Images/Logooo.png" alt="" />
            </a>
        </div>
        <div class="sidebar-nav">
            <ul>
                <li><a href="admin_dashboard.php">Manage User</a></li>
                <li><a href="manage_menu.php">Manage Menu</a></li>
                <li><a href="view_orders.php">Manage Orders</a></li>
                <li><a href="manage_gallery.php">Manage Gallery</a></li>
                <li><a href="#" id="logout-btn" style="color: white; text-decoration: none;">Logout</a></li>
            </ul>
        </div>
    </div>

    <main class="admin-main">
        <div class="dashboard-content" style="margin-top: 130px;">
            <div class="container">
                <?php foreach ($orders as $user_id => $userOrders): ?>
                    <h2>Orders for <?php echo htmlspecialchars($userOrders[array_key_first($userOrders)][0]['full_name']); ?></h2>
                    <?php foreach ($userOrders as $order_id => $orderItems): ?>
                        <h3>
                            Order ID: <?php echo htmlspecialchars($order_id); ?> | 
                            Order Date: <?php echo htmlspecialchars($orderItems[0]['order_date']); ?>
                        </h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Menu Item Name</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Item Price</th>
                                    <th>Total Amount</th>
                                    <th>Item Image</th>
                                    <th>Update Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderItems as $index => $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['menu_item_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                        <td><?php echo "₱" . number_format($row['menu_item_price'], 2); ?></td>
                                        <td><?php echo "₱" . number_format($row['item_total'], 2); ?></td>
                                        <td>
                                            <?php if (!empty($row['menu_item_image'])): ?>
                                                <img src="<?php echo htmlspecialchars($row['menu_item_image']); ?>" alt="<?php echo htmlspecialchars($row['menu_item_name']); ?>">
                                            <?php else: ?>
                                                <p>No image available</p>
                                            <?php endif; ?>
                                        </td>
                                        <?php if ($index === 0): // Display Update Status form only once per order_id ?>
                                            <td rowspan="<?php echo count($orderItems); ?>" style="vertical-align: middle;">
                                                <form action="update_order_status.php" method="POST">
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                                                    <select name="status" required>
                                                        <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="Processing" <?php echo ($row['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                                                        <option value="Completed" <?php echo ($row['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="Cancel" <?php echo ($row['status'] == 'Cancel') ? 'selected' : ''; ?>>Cancel</option>
                                                    </select>
                                                    <button type="submit">Update</button>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>


<script>
    document.getElementById("logout-btn").addEventListener("click", function (e) {
        e.preventDefault(); // Prevent the default link action
        const confirmLogout = confirm("Are you sure you want to log out?");
        if (confirmLogout) {
            // Redirect to the logout PHP script if the user confirms
            window.location.href = "../login.php";
        }
    });
</script>
<style>
    .logo {
    display: flex;
    align-items: center;
    gap: 4px;
    margin: 5px;
    font-size: 1.3rem;
    font-weight: 700;
    color: white;
    text-decoration: none;
  }
  
  .logo img {
    width: 130px;
    height: 130px;
  }
  nav {
  width: 100%;
  padding: 8px 4rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  /* background-color: var(--primary-color); */
  /* background-color: var(--secondary-color); */
  background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));
  /* backdrop-filter: blur(4px); */
  color: white;
  position: fixed;
  top: 0;
  z-index: 99;
}
.status-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #f9f9f9;
    padding: 3px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100px;
}

.status-form select {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
    margin-bottom: 20px;
    background-color: white;
    transition: border-color 0.3s ease;
}

.status-form select:focus {
    border-color: #4CAF50; /* Green border when focused */
    outline: none;
}

.status-form button.update-btn {
    background-color: #4CAF50; /* Green background */
    color: white;
    padding: 10px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
}

.status-form button.update-btn:hover {
    background-color: #45a049; /* Darker green on hover */
}

.status-form button.update-btn:focus {
    outline: none; /* Remove focus outline */
}


</style>
