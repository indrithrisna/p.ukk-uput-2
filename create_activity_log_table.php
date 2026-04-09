<?php
require_once 'config/Database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Cek apakah tabel sudah ada
    $check = $conn->query("SHOW TABLES LIKE 'activity_logs'");
    if($check->rowCount() > 0) {
        echo "⚠️ Tabel activity_logs sudah ada!<br>";
        echo "<a href='index.php?page=activity_log'>Lihat Log Aktivitas</a>";
        exit;
    }
    
    $sql = "CREATE TABLE activity_logs (
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
    
    echo "✅ Tabel activity_logs berhasil dibuat!<br>";
    echo "<a href='index.php?page=activity_log'>Lihat Log Aktivitas</a>";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
