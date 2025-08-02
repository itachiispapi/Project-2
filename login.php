<?php
session_start();
require_once("db.php");

// defining error message
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT user_id, password_hash, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hash, $role);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;
            header("Location: fifteen.html");
            exit;
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "User not found";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body>
  <div class="auth-container">
    <h1>Login to Play</h1>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
      <label>Username:</label><br>
      <input type="text" name="username" required><br><br>

      <label>Password:</label><br>
      <input type="password" name="password" required><br><br>

      <button type="submit">Login</button>
    </form>

    <p class="account">Don't have an account? <a href="register.php">Register here</a>.</p>
  </div>
</body>
</html>