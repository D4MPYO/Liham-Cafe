<?php
// Database connection details
include 'connection.php';

// Check if the form is submitted and the required data is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['full_name'], $_POST['email'], $_POST['password'])) {
    // Form data
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Check if email already exists
    $email_check_query = "SELECT * FROM user WHERE email = '$email'";
    $result = $conn->query($email_check_query);

    if ($result->num_rows > 0) {
        // Email already exists
        $error_message = "Error: This email is already registered. Please use a different email.";
    } else {
        // Insert query
        $sql = "INSERT INTO user (full_name, email, password) VALUES ('$full_name', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "Registered successfully!";
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
} else {
    $error_message = "";
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Liham Cafe - Signup</title>
    <link rel="stylesheet" href="globals.css">
    <script src="https://kit.fontawesome.com/effd3867de.js" crossorigin="anonymous"></script>
    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  </head>
  <body>
  <nav>
      <a href="" class="logo">
        <img src="./Images/Logooo.png" alt="" />
        <p>Liham Cafe</p>
      </a>

      <div style="display: flex; align-items: center; gap: 1rem;">
            <h3>Signup</h3>
        </div>
    </nav>
    <div class="signup-container">
    <?php if (!empty($error_message)): ?>
    <div class="error-message">
        <p><?php echo $error_message; ?></p>
    </div>
    <?php endif; ?>

      <div class="card">
        <h1 class="title">Sign Up</h1>
        <form action="signup.php" method="POST">
          <div class="form-group">
            <label for="full_name" class="label">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required class="input-field">
          </div>

          <div class="form-group">
            <label for="email" class="label">Email:</label>
            <input type="email" id="email" name="email" required class="input-field">
          </div>

          <div class="form-group">
            <label for="password" class="label">Password:</label>
            <input type="password" id="password" name="password" required class="input-field">
          </div>

          <div class="form-group" style="text-align:center;">
            <input type="submit" value="Sign Up" class="submit-button">

            <label for="login" class="label">Already had a Account?</label><br>
            <a href="Login.php">Login</a>
          </div>
        </form>
      </div>
    </div>

    <script src="./index.js"></script>
  </body>
</html>
<style>
  /* Global Styles */
body {
    font-family: 'Arial', sans-serif;
    background-image: url("./Images/Labas.jpg");
  background-position: center;
  background-size: cover;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    color: #333;
}

/* Signup Section Styling */
.signup-container {
    width: 100%;
    max-width: 400px;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.4);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    backdrop-filter: blur(10px); /* Add blur effect */
    -webkit-backdrop-filter: blur(10px);
    text-align: center;
}

.title {
    font-size: 1.8rem;
    margin-bottom: 20px;
    color: #4CAF50;
}

/* Error Message Styling */
.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    font-size: 0.9rem;
}

.error-message p {
    margin: 0;
}

/* Form Group Styling */
.form-group {
    margin-bottom: 20px;
    text-align: left;
    font-size: 1rem;
}

.label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
    color: #333;
}

.input-field {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    margin-bottom: 10px;
}

.input-field:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Button Styling */
.submit-button {
    width: 100%;
    padding: 12px;
    background-color: #4CAF50;
    color: white;
    border: none;
    font-size: 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.submit-button:hover {
    background-color: #45a049;
}

/* Link Styling */
a {
    display: inline-block;
    margin-top: 5px;
    font-size: 20px;
    color: black;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 480px) {
    .signup-container {
        padding: 15px;
    }

    .title {
        font-size: 1.5rem;
    }

    .submit-button {
        padding: 10px;
    }

    .input-field {
        padding: 8px;
    }
}

</style>