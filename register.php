<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"]; // tidak di-hash, vuln!

  $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $stmt->close();

  header("Location: index.php");
  exit;
}
?>

<!-- HTML register -->
<!DOCTYPE html>
<html>
<head><title>Register</title><link rel="stylesheet" href="style.css"></head>
<body>
  <div class="container">
    <h1>Daftar Akun</h1>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Daftar</button>
      <p><a href="index.php">Login</a></p>
    </form>
  </div>
</body>
</html>
