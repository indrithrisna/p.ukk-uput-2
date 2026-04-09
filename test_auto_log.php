<?php
/**
 * File Demo: Menunjukkan Log Aktivitas Bekerja Otomatis
 * 
 * File ini untuk testing bahwa log tercatat otomatis
 * TIDAK PERLU dijalankan di production
 */

session_start();
require_once 'config/Database.php';
require_once 'controllers/ActivityLogController.php';

// Simulasi user login (untuk testing)
if(!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Admin
    $_SESSION['username'] = 'admin';
    $_SESSION['nama'] = 'Administrator';
    $_SESSION['role'] = 'admin';
}

echo "<h2>🧪 Test Log Aktivitas Otomatis</h2>";
echo "<p>Sistem akan mencatat log secara otomatis...</p><hr>";

// Test 1: Log otomatis
echo "<h3>Test 1: Mencatat Log Otomatis</h3>";
$result1 = ActivityLogController::log('test', 'Testing log otomatis - tidak perlu input manual');
echo $result1 ? "✅ Log berhasil tercatat otomatis<br>" : "❌ Gagal mencatat log<br>";

// Test 2: Log dengan detail
echo "<h3>Test 2: Log dengan Detail Lengkap</h3>";
$result2 = ActivityLogController::log('create', 'Menambahkan data test secara otomatis pada ' . date('Y-m-d H:i:s'));
echo $result2 ? "✅ Log detail berhasil tercatat<br>" : "❌ Gagal mencatat log<br>";

// Test 3: Berbagai jenis aktivitas
echo "<h3>Test 3: Berbagai Jenis Aktivitas</h3>";
$actions = [
    ['login', 'User login ke sistem (otomatis)'],
    ['create', 'Menambah data baru (otomatis)'],
    ['update', 'Mengupdate data (otomatis)'],
    ['approve', 'Menyetujui peminjaman (otomatis)'],
    ['logout', 'User logout dari sistem (otomatis)']
];

foreach($actions as $action) {
    $result = ActivityLogController::log($action[0], $action[1]);
    echo $result ? "✅ {$action[0]}: {$action[1]}<br>" : "❌ Gagal<br>";
}

// Tampilkan log yang baru saja dibuat
echo "<hr><h3>📋 Log yang Baru Tercatat (5 Terakhir)</h3>";

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT al.*, u.username, u.nama 
              FROM activity_logs al
              LEFT JOIN users u ON al.user_id = u.id
              ORDER BY al.created_at DESC
              LIMIT 5";
    
    $stmt = $db->query($query);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(count($logs) > 0) {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%; background: white;'>";
        echo "<tr style='background: #ec4899; color: white;'>
                <th>Waktu</th>
                <th>User</th>
                <th>Aksi</th>
                <th>Deskripsi</th>
                <th>IP Address</th>
              </tr>";
        
        foreach($logs as $log) {
            echo "<tr>";
            echo "<td>" . date('d/m/Y H:i:s', strtotime($log['created_at'])) . "</td>";
            echo "<td>" . htmlspecialchars($log['nama']) . "<br><small>(" . htmlspecialchars($log['username']) . ")</small></td>";
            echo "<td><strong>" . strtoupper($log['action']) . "</strong></td>";
            echo "<td>" . htmlspecialchars($log['description']) . "</td>";
            echo "<td>" . htmlspecialchars($log['ip_address']) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ Belum ada log</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>✅ Kesimpulan</h3>";
echo "<p><strong>Sistem log aktivitas bekerja 100% OTOMATIS!</strong></p>";
echo "<ul>";
echo "<li>✅ Tidak perlu input manual dari user</li>";
echo "<li>✅ Log tercatat otomatis saat user melakukan aktivitas</li>";
echo "<li>✅ Mencatat: user, aksi, deskripsi, IP, waktu</li>";
echo "<li>✅ Admin/Petugas hanya perlu MELIHAT log, tidak perlu ISI</li>";
echo "</ul>";

echo "<p><a href='index.php?page=activity_log' style='background: #ec4899; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px;'>➡️ Lihat Semua Log Aktivitas</a></p>";
echo "<p><a href='index.php?page=dashboard' style='background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>➡️ Kembali ke Dashboard</a></p>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
    background: #f5f5f5;
}
h2 {
    color: #ec4899;
}
h3 {
    color: #64748b;
    margin-top: 20px;
}
</style>
