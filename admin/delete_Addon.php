<?php
session_start();
include '../connection.php';

// Check if the delete request has been made
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the add-on from the database
    $sql = "DELETE FROM add_ons WHERE add_on_id = $id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Add-on deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }

    // Redirect back to the manage add-ons page
    header("Location: manage_addons.php");
    exit();
}
?>
