<?php
// Fix passwords - hash ulang semua password yang masih plaintext
require_once 'config/Database.php';

$database = new Database();
$conn = $database->getConnection();

if (!$conn) die("Koneksi database gagal!");

echo "<h2>Fix Passwords</h2>";

// Ambil semua user
$stmt = $conn->query("SELECT id, username, password FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($users)) {
    echo "<p style='color:red'>Tidak ada user di database. Jalankan setup.php dulu.</p>";
    exit;
}

echo "<table border='1' cellpadding='8' style='border-collapse:collapse'>";
echo "<tr><th>Username</th><th>Status Password</th><th>Aksi</th></tr>";

foreach ($users as $user) {
    $isHashed = (strlen($user['password']) === 60 && strpos($user['password'], '$2y$') === 0);
    echo "<tr>";
    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
    echo "<td>" . ($isHashed ? "✓ Sudah di-hash (bcrypt)" : "⚠ Plaintext / tidak valid") . "</td>";
    echo "<td>";
    if (!$isHashed) {
        // Hash password yang masih plaintext
        $newHash = password_hash($user['password'], PASSWORD_BCRYPT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->execute([$newHash, $user['id']]);
        echo "✓ Berhasil di-hash";
    } else {
        echo "-";
    }
    echo "</td></tr>";
}

echo "</table>";

echo "<hr>";
echo "<h3>Reset Password ke Default (password123)</h3>";
echo "<p>Kalau lupa password aslinya, klik tombol di bawah untuk reset semua password ke <strong>password123</strong>:</p>";

if (isset($_POST['reset'])) {
    $newHash = password_hash('password123', PASSWORD_BCRYPT);
    $conn->prepare("UPDATE users SET password = ?")->execute([$newHash]);
    echo "<p style='color:green'>✓ Semua password direset ke: <strong>password123</strong></p>";
}
?>

<form method="POST">
    <button type="submit" name="reset" 
        style="padding:10px 20px;background:#e74c3c;color:white;border:none;border-radius:5px;cursor:pointer"
        onclick="return confirm('Reset semua password ke password123?')">
        Reset Semua Password ke password123
    </button>
</form>

<p><a href="index.php" style="color:#667eea">→ Kembali ke Login</a></p>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
    h2, h3 { color: #667eea; }
    th { background: #667eea; color: white; padding: 8px; }
    td { padding: 8px; }
</style>
