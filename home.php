<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION["user_id"])) {
  header("Location: index.php");
  exit;
}

// Simpan komentar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user_id = $_SESSION["user_id"];
  $message = $_POST["message"];

  $stmt = $conn->prepare("INSERT INTO comments (user_id, message) VALUES (?, ?)");
  $stmt->bind_param("is", $user_id, $message);
  $stmt->execute();
  $stmt->close();
}

// Ambil semua komentar
$query = "
SELECT comments.message, users.username, comments.created_at
FROM comments
JOIN users ON comments.user_id = users.id
ORDER BY comments.id DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Halaman Komentar</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Hai, <?= htmlspecialchars($_SESSION["username"]) ?> 👋</h1>
    <a href="logout.php">Logout</a>

    <form method="post">
      <textarea name="message" placeholder="Tulis komentar di sini" required></textarea>
      <button type="submit">Kirim</button>
    </form>

    <h2>Komentar Terbaru</h2>
    <div id="comments">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="comment">
          <strong><?= htmlspecialchars($row['username']) ?></strong>
          <small><?= $row['created_at'] ?></small><br>
          <?= $row['message'] ?> <!-- tidak disanitasi -->
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
