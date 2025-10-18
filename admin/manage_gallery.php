<?php
session_start();
include '../connection.php';

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add new image to the gallery
if (isset($_POST['add_image'])) {
    $image_name = $_POST['image_name'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = '../uploads/' . basename($image); // Corrected the path
    $upload_time = date("Y-m-d g:i:s a");  // Get the current date and time in 12-hour format with lowercase am/pm


    // Validate and sanitize inputs
    if (empty($image_name) || empty($description) || empty($image)) {
        $_SESSION['error'] = "All fields are required.";
    } else {
        // Validate the file type (only allow images)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            // Upload the image to the 'uploads' directory
            if (move_uploaded_file($image_tmp, $image_path)) {
                // Insert image details into the database
                $sql = $conn->prepare("INSERT INTO gallery_images (image_name, description, image_path) VALUES (?, ?, ?)");
                $sql->bind_param("sss", $image_name, $description, $image_path);

                if ($sql->execute()) {
                    $_SESSION['message'] = "Image added successfully!";
                } else {
                    $_SESSION['error'] = "Error: " . $conn->error;
                }
            } else {
                $_SESSION['error'] = "Error uploading the image.";
            }
        } else {
            $_SESSION['error'] = "Only image files (JPEG, PNG, GIF) are allowed.";
        }
    }
}

// Delete image from gallery
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Fetch image path from the database
    $sql = $conn->prepare("SELECT image_path FROM gallery_images WHERE id = ?");
    $sql->bind_param("i", $delete_id);
    $sql->execute();
    $result = $sql->get_result();

    // Check if image exists in the database
    if ($result && $row = $result->fetch_assoc()) {
        $image_path = $row['image_path'];

        // Ensure the file exists before attempting to delete
        if (file_exists($image_path)) {
            // Delete the image file
            if (unlink($image_path)) {
                // Delete the record from the database
                $delete_sql = $conn->prepare("DELETE FROM gallery_images WHERE id = ?");
                $delete_sql->bind_param("i", $delete_id);
                if ($delete_sql->execute()) {
                    $_SESSION['message'] = "Image deleted successfully!";
                } else {
                    $_SESSION['error'] = "Error deleting image from database: " . $conn->error;
                }
            } else {
                $_SESSION['error'] = "Error deleting the image file.";
            }
        } else {
            $_SESSION['error'] = "Image file does not exist.";
        }
    } else {
        $_SESSION['error'] = "Image not found in database.";
    }
}

// Fetch gallery images from the database
$sql = "SELECT id, image_name, description, image_path, upload_time FROM gallery_images";
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
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
</head>
<body>
<nav>
      <a href="admin_dashboard.php" class="logo">
        <img src="./Images/Logooo.png" alt="" />
      </a>

      <div style="display: flex; align-items: center; gap: 1rem;">
            <h1>Manage Gallery</h1>
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

        <!-- Main Content -->
        <div class="dashboard-container" >
        <div class="admin-main" style="margin-top: 100px;">

            <div class="container">
                <div class="add-item-forms">
                    <form method="POST" action="add_image.php" enctype="multipart/form-data">
                    <button type="submit" name="add_image">Add Image</button>
                </form>
                </div>

            <h3>Gallery Images</h3>
            <!-- Gallery Images Table -->
            <table>
                <thead>
                    <tr>
                        <th>Image Name</th>
                        <th>Image</th>
                        <th>Upload Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['image_name']); ?></td>
                            <td><img src="<?= htmlspecialchars($row['image_path']); ?>" alt="<?= htmlspecialchars($row['image_name']); ?>" width="100"></td>
                            <td><?= htmlspecialchars($row['upload_time']); ?></td>
                            <td>
                                <a href="manage_gallery.php?delete_id=<?= $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this image?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
    
    <script>
        document.getElementById("logout-btn").addEventListener("click", function (e) {
            e.preventDefault();
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = "../login.php";
            }
        });
    </script>
</body>
</html>
<style>
       /* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Chakra Petch", sans-serif;
    scroll-behavior: smooth;
}

/* Logo Styles */
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

/* Add New Image Form */
form {
    background-color: #f9f9f9;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 0px;
    width: 100%;
    max-width: 200px;
}

form h3 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
}

form input[type="text"],
form input[type="file"],
form textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    background-color: #fff;
}

form input[type="text"]:focus,
form input[type="file"]:focus,
form textarea:focus {
    border-color: #4a90e2;
    outline: none;
}

form textarea {
    resize: vertical;
    min-height: 120px;
}

/* Submit Button */
form button[type="submit"] {
    width: 100%;
    padding: 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

form button[type="submit"]:hover {
    background-color: #45a049;
}

/* Table Styling for Gallery Images */
table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

table th, table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #f2f2f2;
    font-weight: 600;
}

table img {
    max-width: 100px;
    height: auto;
}

table .edit, table .delete {
    text-decoration: none;
    color: #4CAF50;
    padding: 5px 10px;
    border-radius: 4px;
}

table .edit:hover {
    background-color: #f0f0f0;
}

table .delete {
    color: #f44336;
}

table .delete:hover {
    background-color: #f2dede;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        margin-left: 0;
    }

    form {
        width: 100%;
        padding: 15px;
    }

    form h3 {
        font-size: 20px;
    }

    form input[type="text"],
    form input[type="file"],
    form textarea {
        font-size: 14px;
        padding: 10px;
    }

    form button[type="submit"] {
        padding: 12px;
    }
}

</style>
<?php
// Close the database connection
$conn->close();
?>
