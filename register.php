<?php
require_once("db.php");

// defining error message
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    // Check for matching passwords
    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check for existing user
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = "player";
            $now = date("Y-m-d H:i:s");

            $insert = $conn->prepare("INSERT INTO users (username, password_hash, email, role, registration_date) VALUES (?, ?, ?, ?, ?)");
            $insert->bind_param("sssss", $username, $hash, $email, $role, $now);

            if ($insert->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Registration failed.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body>
    <div class="auth-container">
        <h1>Create an Account</h1>

        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <label>Confirm Password:</label><br>
            <input type="password" name="confirm" required><br><br>

            <button type="submit">Register</button>
        </form>

        <p class="account">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
