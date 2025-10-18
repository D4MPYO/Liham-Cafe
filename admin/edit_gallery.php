<?php
session_start();
include '../connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current image data from the database
    $sql = "SELECT * FROM gallery_images WHERE id = '$id'";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $image_name = $row['image_name'];
        $description = $row['description'];
        $image_path = $row['image_path'];
    }
}

// Handle Image Edit
if (isset($_POST['edit_image'])) {
    $image_name = $_POST['image_name'];
    $description = $_POST['description'];

    // Check if a new image is uploaded
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = 'uploads/' . basename($image);

        // Upload the new image and delete the old one
        if (move_uploaded_file($image_tmp, $image_path)) {
            unlink($row['image_path']); // Delete the old image from the server

            // Update the image details in the database
            $update_sql = "UPDATE gallery_images SET image_name = '$image_name', description = '$description', image_path = '$image_path' WHERE id = '$id'";
            if ($conn->query($update_sql) === TRUE) {
                $_SESSION['message'] = "Image updated successfully!";
                header("Location: manage_gallery.php");
            } else {
                $_SESSION['error'] = "Error updating image: " . $conn->error;
            }
        } else {
            $_SESSION['error'] = "Error uploading the new image.";
        }
    } else {
        // Update the details without changing the image
        $update_sql = "UPDATE gallery_images SET image_name = '$image_name', description = '$description' WHERE id = '$id'";
        if ($conn->query($update_sql) === TRUE) {
            $_SESSION['message'] = "Image updated successfully!";
            header("Location: manage_gallery.php");
        } else {
            $_SESSION['error'] = "Error updating image: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gallery Image</title>
</head>
<body>
    <h2>Edit Image</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="edit_gallery.php?id=<?= $id; ?>" enctype="multipart/form-data">
        <label for="image_name">Image Name:</label>
        <input type="text" name="image_name" value="<?= $image_name; ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" required><?= $description; ?></textarea>

        <label for="image">Change Image (Optional):</label>
        <input type="file" name="image">

        <button type="submit" name="edit_image">Update Image</button>
    </form>

    <a href="manage_gallery.php">Back to Gallery</a>
</body>
</html>
