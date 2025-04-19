<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST["username"] ?? '';
  $password = $_POST["password"] ?? '';

  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $_SESSION["user_id"] = $user_id;
    $_SESSION["username"] = $username;
    header("Location: home.php");
    exit;
  } else {
    $error = "Login gagal!";
  }

  $stmt->close();
}
?>


<!DOCTYPE html>
<html>
<head><title>Login</title><link rel="stylesheet" href="style.css"></head>
<body>
  <div class="container">
    <h1>Login</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
      <p><a href="register.php">Daftar</a></p>
    </form>
  </div>
</body>
</html>
