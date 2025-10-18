<?php
session_start();
include '../connection.php';

// Fetch the menu item if ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM menu_items WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $menu_item = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Menu item not found.";
        header("Location: manage_menu.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid menu item ID.";
    header("Location: manage_menu.php");
    exit;
}

// Handle form submission for updating the menu item
if (isset($_POST['update_menu_item'])) {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $image_path = $_POST['current_image']; // Default to existing image

    // Handle file upload if a new image is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp = $image['tmp_name'];
        $image_size = $image['size'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpeg', 'jpg', 'png'];
        if (in_array($image_ext, $allowed_ext)) {
            if ($image_size <= 5000000) { // 5MB limit
                $image_new_name = uniqid('', true) . '.' . $image_ext;
                $image_target_dir = $_SERVER['DOCUMENT_ROOT'] . "/user/uploads/";
                $image_target_path = $image_target_dir . $image_new_name;

                // Ensure the directory exists
                if (!is_dir($image_target_dir)) {
                    mkdir($image_target_dir, 0777, true);
                }

                if (move_uploaded_file($image_tmp, $image_target_path)) {
                    $image_path = "/user/uploads/" . $image_new_name; // Save relative path
                } else {
                    $_SESSION['error'] = "Error uploading the image.";
                    header("Location: edit_menu.php?id=$id");
                    exit;
                }
            } else {
                $_SESSION['error'] = "Image size exceeds the limit of 5MB.";
                header("Location: edit_menu.php?id=$id");
                exit;
            }
        } else {
            $_SESSION['error'] = "Only JPEG and PNG images are allowed.";
            header("Location: edit_menu.php?id=$id");
            exit;
        }
    }

    // Update the menu item in the database
    $sql = "UPDATE menu_items 
            SET name = '$name', description = '$description', price = '$price', 
                category = '$category', image = '$image_path' 
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Menu item updated successfully!";
        header("Location: manage_menu.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating record: " . $conn->error;
        header("Location: edit_menu.php?id=$id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item - Liham Cafe</title>
    <link rel="stylesheet" href="edit_menu.css">
</head>
<body>
<main class="admin-main">
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
    <div class="menu-actions">
        <h3>Edit Item</h3>
        <form action="edit_menu.php?id=<?php echo $menu_item['id']; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $menu_item['id']; ?>">
            <input type="hidden" name="current_image" value="<?php echo $menu_item['image']; ?>">

            <label for="name">Item Name</label>
            <input type="text" name="name" id="name" value="<?php echo $menu_item['name']; ?>" required>
            
            <label for="description">Description</label>
            <textarea name="description" id="description"><?php echo $menu_item['description']; ?></textarea>
            
            <label for="price">Price</label>
            <input type="number" name="price" id="price" value="<?php echo $menu_item['price']; ?>" step="0.01" required>
            
            <label for="category">Category</label>
            <input type="text" name="category" id="category" value="<?php echo $menu_item['category']; ?>" required>
            
            <label for="image">Item Image</label>
            <input type="file" name="image" id="image" accept="image/jpeg, image/png">
            <input type="hidden" name="menu_item_id" value="<?= $menu_item['id'] ?>">
            <button type="submit" name="update_menu_item">Update Item</button>
        </form>
        <div class="cancel-action">
            <form action="manage_menu.php" method="get">
                <button type="submit">Cancel</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>
