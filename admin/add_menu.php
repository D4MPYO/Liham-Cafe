<?php
session_start();
include '../connection.php';

// Check if the form is submitted to add a new menu item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_menu_item'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // File upload handling
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/user/uploads/"; // Absolute path to the uploads directory
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
    }
    $file_name = uniqid() . "-" . basename($_FILES["image"]["name"]); // Ensure unique file name
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // Get file extension

    if (in_array($imageFileType, ["jpg", "jpeg", "png"])) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Save relative path in database for HTTP usage
            $imagePathForDb = "/user/uploads/" . $file_name;

            // Prepare the SQL statement with placeholders
            $stmt = $conn->prepare("INSERT INTO menu_items (name, description, price, category, image) 
                                    VALUES (?, ?, ?, ?, ?)");

            // Bind the parameters
            $stmt->bind_param("ssdss", $name, $description, $price, $category, $imagePathForDb);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['message'] = "Menu item added successfully!";
                header("Location: manage_menu.php");
            } else {
                $_SESSION['error'] = "Error: " . $stmt->error;
            }

            $stmt->close(); // Close the prepared statement
        } else {
            $_SESSION['error'] = "Sorry, there was an error uploading your file.";
        }
    } else {
        $_SESSION['error'] = "Sorry, only JPG, JPEG, and PNG files are allowed.";
    }
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM menu_items WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Menu item deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }

    header("Location: manage_menu.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item</title>
    <link rel="stylesheet" href="add_menu.css">
</head>
<body>
<div class="add-item-form">
    <h3>Add New Menu Item</h3>
    <form action="add_menu.php" method="POST" enctype="multipart/form-data">
        <label for="name">Item Name</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" required></textarea>

        <label for="price">Price</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="category">Category</label>
            <select name="category" id="category" required>
            <option value="" disabled selected>Select a Category</option>
            <option value="Espresso">Espresso</option>
            <option value="Fruit Tea">Fruit Tea</option>
            <option value="Non-Coffee">Non-Coffee</option>
            <option value="Add On">Add On</option>
            <option value="Add New Category">Add New Category</option>
        </select>
        <!-- Input field for new category (hidden by default) -->
        <div id="newCategoryContainer" style="display: none; margin-top: 10px;">
            <label for="newCategory">New Category</label>
            <input type="text" id="newCategory" placeholder="Enter new category">
            <button type="button" onclick="addNewCategory()">Add</button>
        </div>

        <label for="image">Item Image</label>
        <input type="file" name="image" id="image" accept="image/jpeg, image/png" required onchange="previewImage(event)">

        <button type="submit" name="add_menu_item">Add Item</button>
    </form>

    <div class="form">
        <!-- Cancel button form -->
        <form action="manage_menu.php" method="get">
            <button type="submit">Cancel</button>
        </form>
    </div>
</div>

<script>
    const categorySelect = document.getElementById('category');
    const newCategoryContainer = document.getElementById('newCategoryContainer');
    const newCategoryInput = document.getElementById('newCategory');

    // Show new category input when "Add New Category" is selected
    categorySelect.addEventListener('change', () => {
        if (categorySelect.value === 'Add New Category') {
            newCategoryContainer.style.display = 'block';
        } else {
            newCategoryContainer.style.display = 'none';
        }
    });

    // Add new category to the select list
    function addNewCategory() {
        const newCategory = newCategoryInput.value.trim();
        if (newCategory) {
            // Add new category as an option
            const newOption = document.createElement('option');
            newOption.value = newCategory;
            newOption.textContent = newCategory;
            categorySelect.insertBefore(newOption, categorySelect.lastElementChild);

            // Select the newly added category and hide the input
            categorySelect.value = newCategory;
            newCategoryContainer.style.display = 'none';
            newCategoryInput.value = '';
        } else {
            alert('Please enter a valid category name.');
        }
    }

    document.getElementById("category").addEventListener("change", function() {
        var newCategoryContainer = document.getElementById("newCategoryContainer");
        if (this.value === "Add New Category") {
            newCategoryContainer.classList.add("active");
        } else {
            newCategoryContainer.classList.remove("active");
        }
    });

    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function() {
            imagePreview.innerHTML = `<img src="${reader.result}" alt="Preview">`;
        };
        
        if (file) {
            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = `<p>No image selected.</p>`;
        }
    }
</script>
</body>
</html>
