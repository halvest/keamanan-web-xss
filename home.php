<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION["user_id"])) {
  header("Location: index.php");
  exit;
}

// Simpan ulasan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user_id = $_SESSION["user_id"];
  $message = $_POST["message"];

  $stmt = $conn->prepare("INSERT INTO comments (user_id, message) VALUES (?, ?)");
  $stmt->bind_param("is", $user_id, $message);
  $stmt->execute();
  $stmt->close();
}

// Ambil semua ulasan
$query = "
SELECT comments.message, users.username, comments.created_at
FROM comments
JOIN users ON comments.user_id = users.id
ORDER BY comments.id DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Produk - Amikom Store</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-100 to-blue-50 min-h-screen font-sans">

  <!-- Top Bar -->
  <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center shadow-md">
    <h1 class="text-lg font-bold">🛍️ Promo Spesial 5.5!</h1>
    <div class="space-x-4">
      <span>Halo, <?= htmlspecialchars($_SESSION["username"]) ?> 👋</span>
      <a href="logout.php" class="bg-white text-blue-600 px-3 py-1 rounded hover:bg-blue-100 font-medium">Logout</a>
    </div>
  </div>

  <!-- Container -->
  <div class="max-w-4xl mx-auto p-6 space-y-10">

    <!-- Produk -->
    <div class="bg-white rounded-xl shadow p-6 flex flex-col md:flex-row gap-6 items-center">
      <img src="https://s3.bukalapak.com/img/34884333892/s-463-463/data.jpeg.webp" alt="Produk Gambar" class="w-60 h-auto rounded-xl border">
      <div class="flex-1 space-y-4">
        <h2 class="text-2xl font-bold text-gray-800">Jam Tangan Digital RGB</h2>
        <p class="text-gray-600">
          Jam tangan futuristik dengan lampu RGB, cocok untuk nongkrong, ngoding, atau pura-pura sibuk.
          Dilengkapi fitur waktu dan gaya.
        </p>
        <p class="text-xl font-semibold text-green-600">Rp 299.000</p>
        <div class="flex flex-wrap gap-3 pt-2">
          <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🛒 Tambah ke Keranjang</button>
          <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">💸 Beli Sekarang</button>
          <button class="border border-gray-400 text-gray-700 px-4 py-2 rounded hover:bg-gray-100">❤️ Simpan</button>
        </div>
      </div>
    </div>

    <!-- Ulasan -->
    <div class="bg-white rounded-xl shadow p-6">
      <h3 class="text-xl font-bold text-gray-800 mb-4">📝 Ulasan Produk</h3>
      <form method="post" class="space-y-4">
        <textarea name="message" placeholder="Tulis ulasanmu di sini !" required class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim Ulasan</button>
      </form>

      <!-- Daftar Komentar -->
      <div class="mt-6 space-y-4" id="comments">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="bg-gray-100 rounded p-4 shadow-sm">
            <div class="flex justify-between items-center text-sm text-gray-600 mb-1">
              <strong><?= htmlspecialchars($row['username']) ?></strong>
              <span><?= $row['created_at'] ?></span>
            </div>
            <div class="text-gray-800">
              <?= $row['message'] ?> <!-- XSS rentan di sini -->
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>

  </div>
</body>
</html>
