<?php
session_start();
include '../connection.php';



// Fetch users from the database
$sql = "SELECT * FROM user";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Liham Cafe</title>
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
            <h3>Manage Users</h3>
        </div>
    </nav>
<div class="dashboard-content" >
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
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($user = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $user['user_id'] . "</td>";
                            echo "<td>" . $user['full_name'] . "</td>";
                            echo "<td>" . $user['email'] . "</td>";
                            echo "<td>
                                    <a href='edit_user.php?user_id=" . $user['user_id'] . "'>Edit</a>  
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No users found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
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
    font-size: 1.3rem;
    font-weight: 700;
    color: white;
    text-decoration: none;
    margin: 5px;
  }
  
  .logo img {
    width: 130px;
    height: 130px;
  }
</style>