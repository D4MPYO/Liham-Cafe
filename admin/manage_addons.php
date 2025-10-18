<?php
session_start();
include '../connection.php';

// Check if the form is submitted to add a new add-on
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_add_on'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Insert the new add-on into the database
    $sql = "INSERT INTO add_ons (name, price, description) 
            VALUES ('$name', '$price', '$description')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Add-on added successfully!";
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }

    // Redirect to manage add-ons page
    header("Location: manage_addons.php");
    exit();
}

// Fetch existing add-ons from the database
$sql = "SELECT * FROM add_ons";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Add-ons</title>
    <link rel="stylesheet" href="manage_addons.css">
</head>
<body>
    <div class="container">
        <h1>Manage Add-ons</h1>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Form to add a new add-on -->
        <h3>Add New Add-on</h3>
        <form action="manage_addons.php" method="POST">
            <label for="name">Add-on Name</label>
            <input type="text" name="name" id="name" required>

            <label for="price">Price</label>
            <input type="number" name="price" id="price" step="0.01" required>

            <label for="description">Description</label>
            <textarea name="description" id="description" required></textarea>

            <button type="submit" name="add_add_on">Add Add-on</button>
        </form>

        <!-- List of existing add-ons -->
        <h3>Existing Add-ons</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <a href="delete_addon.php?id=<?php echo $row['add_on_id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
