<?php
session_start();
include 'connection.php'; 
$admin_email = "admin@lihamscafe.com";
$admin_password = "admin123";  // if nasa real prod this should be hashed na

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == $admin_email && $password == $admin_password) {
        // Store admin info in session variables
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $username;
        header("Location: admin/admin_dashboard.php");
        exit();
    } else {
        $error_message = "Invalid email or password. Please try again.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT user_id, full_name FROM user WHERE email = ? AND password = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $full_name);

        if ($stmt->fetch()) {
            // Store the user ID and full name in session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['full_name'] = $full_name;

            // Redirect to the home page after successful login
            header("Location: index.php");
            exit();
        } else {
            // Handle login error (invalid username/password)
            $error_message = "Invalid username or password.";
        }

        $stmt->close();
    } else {
        die("Error preparing query: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liham Cafe Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="globals.css">
    <script src="https://kit.fontawesome.com/effd3867de.js" crossorigin="anonymous"></script>
</head>
<body>
<nav>
      <a href="" class="logo">
        <img src="./Images/Logooo.png" alt="" />
        <p>Liham Cafe</p>
      </a>

      <div style="display: flex; align-items: center; gap: 1rem;">
            <h3>Login</h3>
        </div>
    </nav>
    <section class="login-section">
        <div class="login-container">
            <h2>Login to Liham Cafe</h2>

            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Email:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit">Login</button><br>
                <a href="signup.php">Create Account</a>
            </form>
        </div>
    </section>
</body>
</html>

