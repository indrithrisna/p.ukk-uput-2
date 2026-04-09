<?php
// File untuk membuat tabel activity_logs
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/Database.php';

echo "<h2>Setup Activity Log Table</h2>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<p>✅ Koneksi database berhasil</p>";
    
    // Drop table jika sudah ada (untuk testing)
    // $conn->exec("DROP TABLE IF EXISTS activity_logs");
    // echo "<p>⚠️ Tabel lama dihapus</p>";
    
    // Buat tabel baru
    $sql = "CREATE TABLE IF NOT EXISTS activity_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        action VARCHAR(50) NOT NULL,
        description TEXT NOT NULL,
        ip_address VARCHAR(45) NULL,
        user_agent TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_action (action),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($sql);
    
    echo "<p style='color: green; font-weight: bold;'>✅ Tabel activity_logs berhasil dibuat!</p>";
    
    // Verifikasi tabel sudah ada
    $check = $conn->query("SHOW TABLES LIKE 'activity_logs'");
    if($check->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Verifikasi: Tabel activity_logs ditemukan di database</p>";
        
        // Tampilkan struktur tabel
        $describe = $conn->query("DESCRIBE activity_logs");
        echo "<h3>Struktur Tabel:</h3>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr style='background: #f0f0f0;'><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while($row = $describe->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra'] ?? '') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<br><p><a href='index.php?page=activity_log' style='background: #ec4899; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>➡️ Lihat Log Aktivitas</a></p>";
        echo "<p><a href='index.php?page=dashboard' style='background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>➡️ Kembali ke Dashboard</a></p>";
    } else {
        echo "<p style='color: red;'>❌ Error: Tabel tidak ditemukan setelah dibuat</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Detail Error:</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    background: #f5f5f5;
}
h2 {
    color: #ec4899;
}
table {
    background: white;
    width: 100%;
}
th {
    text-align: left;
    padding: 8px;
}
td {
    padding: 8px;
}
</style>
