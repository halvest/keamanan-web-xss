1. Buat Database

CREATE DATABASE vuln_web;

USE vuln_web;

-- Tabel user
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100),
  password VARCHAR(100)
);

-- Tabel komentar (XSS vuln)
CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

2. Script XSS

<script>
  alert("🔥 Aku berhasil XSS!");
  document.body.innerHTML = "<h1 style='color:red;text-align:center;'>Buat Teks disini</h1>";
</script>

