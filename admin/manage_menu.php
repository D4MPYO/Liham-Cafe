<?php
session_start();
include '../connection.php';

// Initialize session arrays for undo/redo
if (!isset($_SESSION['deleted_items'])) {
    $_SESSION['deleted_items'] = [];
}
if (!isset($_SESSION['redo_items'])) {
    $_SESSION['redo_items'] = [];
}

// Fetch menu items and add-ons from the database
$sql = "SELECT m.*, GROUP_CONCAT(a.addon_name) AS addons 
        FROM menu_items m
        LEFT JOIN add_ons a ON m.id = a.menu_item_id
        GROUP BY m.id";

$result = $conn->query($sql);

// Check for errors in the query execution
if (!$result) {
    die("Error executing query: " . $conn->error); // Display error if the query fails
}

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $deleted_item_result = $stmt->get_result();

    if ($deleted_item_result->num_rows > 0) {
        $deleted_item = $deleted_item_result->fetch_assoc();
        $_SESSION['deleted_items'][] = $deleted_item;

        $stmt_delete = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
        $stmt_delete->bind_param("i", $id);
        if ($stmt_delete->execute()) {
            $_SESSION['message'] = "Menu item deleted successfully!";
        } else {
            $_SESSION['error'] = "Error: Unable to delete menu item.";
        }
        $stmt_delete->close();
    } else {
        $_SESSION['error'] = "Menu item not found.";
    }
    $stmt->close();
    header("Location: manage_menu.php");
    exit;
}

if (isset($_GET['best_selling_id'])) {
    $id = intval($_GET['best_selling_id']);
    $stmt = $conn->prepare("UPDATE menu_items SET best_selling = !best_selling WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Best Selling status updated successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to update Best Selling status.";
    }
    $stmt->close();
    header("Location: manage_menu.php");
    exit;
}

// Handle undo operation
if (isset($_GET['undo']) && !empty($_SESSION['deleted_items'])) {
    $last_deleted_item = array_pop($_SESSION['deleted_items']);
    $_SESSION['redo_items'][] = $last_deleted_item;

    $stmt_undo = $conn->prepare("INSERT INTO menu_items (id, name, description, price, category, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_undo->bind_param(
        "issdss",
        $last_deleted_item['id'],
        $last_deleted_item['name'],
        $last_deleted_item['description'],
        $last_deleted_item['price'],
        $last_deleted_item['category'],
        $last_deleted_item['image']
    );
    if ($stmt_undo->execute()) {
        $_SESSION['message'] = "Undo successful!";
    } else {
        $_SESSION['error'] = "Error: Unable to undo.";
    }
    $stmt_undo->close();
    header("Location: manage_menu.php");
    exit;
}

// Handle redo operation
if (isset($_GET['redo']) && !empty($_SESSION['redo_items'])) {
    $last_redo_item = array_pop($_SESSION['redo_items']);
    $_SESSION['deleted_items'][] = $last_redo_item;

    $stmt_redo = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt_redo->bind_param("i", $last_redo_item['id']);
    if ($stmt_redo->execute()) {
        $_SESSION['message'] = "Redo successful!";
    } else {
        $_SESSION['error'] = "Error: Unable to redo.";
    }
    $stmt_redo->close();
    header("Location: manage_menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - Liham Cafe</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../globals.css">
    <script src="https://kit.fontawesome.com/effd3867de.js" crossorigin="anonymous"></script>
</head>
<body>
<nav>
      <a href="index.php" class="logo">
        <img src="./Images/Logooo.png" alt="" />
      </a>

      <div style="display: flex; align-items: center; gap: 1rem;">
            <h3>Manage Menu</h3>
        </div>
    </nav>
<div class="dashboard-content">
        <!-- Sidebar -->
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

    <!-- Admin Dashboard Content -->
    <main class="admin-main">
        <div class="dashboard-content" style="margin-top: 130px;">
            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='success-message'>{$_SESSION['message']}</div>";
                unset($_SESSION['message']);
            }
            if (isset($_SESSION['error'])) {
                echo "<div class='error-message'>{$_SESSION['error']}</div>";
                unset($_SESSION['error']);
            }
            ?>  
            <!-- Add Undo and Redo Buttons -->
            <div class="action-buttons">
                <form action="manage_menu.php" method="get" style="display:inline;">
                    <button type="submit" name="undo" <?php echo empty($_SESSION['deleted_items']) ? 'disabled' : ''; ?>>Undo</button>
                </form>
                <form action="manage_menu.php" method="get" style="display:inline;">
                    <button type="submit" name="redo" <?php echo empty($_SESSION['redo_items']) ? 'disabled' : ''; ?>>Redo</button>
                </form>
            </div>


            <!-- Add New Menu Item Form -->
            <div class="add-item-forms">
                <form action="add_menu.php" method="get">
                    <button type="submit">Add New Menu</button>
                </form>
            </div>

            <!-- Menu Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Best Selling</th>
                        <th>Image</th> 
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($menu_item = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $menu_item['id'] . "</td>";
                            echo "<td>" . $menu_item['name'] . "</td>";
                            echo "<td>" . $menu_item['description'] . "</td>";
                            echo "<td>" . $menu_item['price'] . "</td>";
                            echo "<td>" . $menu_item['category'] . "</td>";
                            echo "<td>
                                <a href='manage_menu.php?best_selling_id=" . $menu_item['id'] . "' style='color:" . ($menu_item['best_selling'] ? 'green' : 'gray') . ";'>
                                    " . ($menu_item['best_selling'] ? 'Best Seller' : 'Not Best Seller') . "
                                </a>
                            </td>";
                            echo "<td><img src='" . $menu_item['image'] . "' alt='" . $menu_item['name'] . "' width='70' height='70'></td>";
                            echo "<td>
                                 <a href='edit_menu.php?id=" . $menu_item['id'] . "' style='color:blue;'>Edit</a> | 
                                    <a href='manage_menu.php?delete_id=" . $menu_item['id'] . "' style='color:red;' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No menu items found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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
    margin: 5px;
    gap: 4px;
    font-size: 1.3rem;
    font-weight: 700;
    color: white;
    text-decoration: none;
  }
  
  .logo img {
    width: 130px;
    height: 130px;
  }
  .add-item-forms {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.add-item-forms button {
    background-color: #4CAF50; /* Green background */
    color: white; /* White text */
    border: none; /* Remove default border */
    padding: 12px 24px; /* Padding for the button */
    text-align: center; /* Center the text */
    text-decoration: none; /* Remove underline from text */
    display: inline-block;
    font-size: 16px; /* Button text size */
    border-radius: 8px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s ease; /* Smooth transition on hover */
}

.add-item-forms button:hover {
    background-color: #45a049; /* Darker green on hover */
}

.add-item-forms button:focus {
    outline: none; /* Remove default focus outline */
}

</style>