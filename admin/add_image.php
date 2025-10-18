<?php
session_start();
include '../connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_image'])) {
    $image_name = isset($_POST['image_name']) ? $_POST['image_name'] : '';

    // Check if a file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_error = $_FILES['image']['error'];
        $image_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION)); 
        $image_path = '../uploads/' . uniqid('', true) . '.' . $image_ext; // Use a unique name for the image

        // Validate and sanitize inputs
        if (empty($image_name) || empty($image)) {
            $_SESSION['error'] = "All fields are required.";
        } else {
            // Validate file type (only allow images)
            $allowed_types = ['jpeg', 'jpg', 'png', 'gif'];
            if (in_array($image_ext, $allowed_types)) {
                if ($image_error === 0) {
                    // Check file size (e.g., 5MB limit)
                    if ($image_size <= 5 * 1024 * 1024) {
                        if (move_uploaded_file($image_tmp, $image_path)) {
                            // Insert image into the database without description
                            $sql = $conn->prepare("INSERT INTO gallery_images (image_name, image_path) VALUES (?, ?)"); 
                            $sql->bind_param("ss", $image_name, $image_path);

                            if ($sql->execute()) {
                                $_SESSION['message'] = "Image added successfully!";
                                header("Location: manage_gallery.php");
                                exit();
                            } else {
                                $_SESSION['error'] = "Error: " . $conn->error;
                            }
                        } else {
                            $_SESSION['error'] = "Error uploading the image.";
                        }
                    } else {
                        $_SESSION['error'] = "File size exceeds the maximum limit of 5MB.";
                    }
                } else {
                    $_SESSION['error'] = "Error uploading the image. Please try again.";
                }
            } else {
                $_SESSION['error'] = "Only image files (JPEG, PNG, GIF) are allowed.";
            }
        }
    } else {
        $_SESSION['error'] = "No image uploaded or there was an error with the upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Gallery</title>
    <link rel="stylesheet" href="../globals.css">
    <link rel="stylesheet" href="manage_menu.css">
</head>
<body>
    <!-- Main Content -->
    <div class="dashboard-container">
        <div class="admin-main" style="margin-top: 130px;">
            <div class="container">
                <form method="POST" action="" enctype="multipart/form-data">
                    <h3>Add New Image</h3>
                    <input type="text" name="image_name" placeholder="Image Name" required>
                    <input type="file" name="image" accept="image/jpeg, image/png, image/gif" required>
                    <!-- Removed description textarea -->
                    <button type="submit" name="add_image">Add Image</button>
                    <button type="button" style="background-color: red;" onclick="window.location.href='manage_gallery.php';">Cancel</button>
                </form>
            </div>
        </div>
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
body {
    font-family: Arial, sans-serif;
    background-image: url("../Images/Labas.jpg");
    background-position: center;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}


form {
    background-color: rgba(255, 255, 255, 0.4); /* Semi-transparent background */
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 30px;
    width: 350px;
    height: 400px;
    text-align: center;
    position: relative;
    backdrop-filter: blur(10px); /* Add blur effect */
    -webkit-backdrop-filter: blur(10px); /* For Safari support */
}

form h3 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

form input[type="text"],
form input[type="file"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    background-color: #fff;
    transition: border-color 0.3s;
}

form input[type="text"]:focus,
form input[type="file"]:focus {
    border-color: #4b87c2;
}

form button {
    padding: 12px 25px;
    background-color: #4b87c2;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

form button:hover {
    background-color: #3e6ea2;
}

/* Message Styles */
.message {
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    font-size: 16px;
    font-weight: bold;
}

.message.success {
    background-color: #28a745;
    color: white;
}

.message.error {
    background-color: #dc3545;
    color: white;
}

</style>
