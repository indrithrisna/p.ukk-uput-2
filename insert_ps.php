<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/Database.php';

echo "<h2>Insert Data PS</h2>";

$database = new Database();
$conn = $database->getConnection();

if(!$conn) {
    die("Database connection failed!");
}

// Data PS yang akan diinsert
$ps_data = [
    ['nama_ps' => 'PS 1', 'tipe' => 'PS5', 'status' => 'tersedia', 'harga_per_jam' => 15000],
    ['nama_ps' => 'PS 2', 'tipe' => 'PS5', 'status' => 'tersedia', 'harga_per_jam' => 15000],
    ['nama_ps' => 'PS 3', 'tipe' => 'PS4', 'status' => 'tersedia', 'harga_per_jam' => 10000],
    ['nama_ps' => 'PS 4', 'tipe' => 'PS4', 'status' => 'tersedia', 'harga_per_jam' => 10000],
    ['nama_ps' => 'PS 5', 'tipe' => 'PS4', 'status' => 'tersedia', 'harga_per_jam' => 10000],
    ['nama_ps' => 'PS 6', 'tipe' => 'PS3', 'status' => 'tersedia', 'harga_per_jam' => 8000],
    ['nama_ps' => 'PS 7', 'tipe' => 'PS3', 'status' => 'tersedia', 'harga_per_jam' => 8000],
    ['nama_ps' => 'PS 8', 'tipe' => 'PS3', 'status' => 'tersedia', 'harga_per_jam' => 8000],
];

echo "<h3>Inserting PS Data...</h3>";

try {
    // Cek apakah sudah ada data
    $stmt = $conn->query("SELECT COUNT(*) FROM ps");
    $count = $stmt->fetchColumn();
    
    if($count > 0) {
        echo "<p style='color: orange;'>⚠ Database sudah memiliki $count PS.</p>";
        echo "<p>Apakah Anda ingin menambah data PS lagi?</p>";
        echo "<form method='POST'>";
        echo "<button type='submit' name='action' value='add' style='padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 5px;'>Ya, Tambahkan</button>";
        echo "<button type='submit' name='action' value='reset' style='padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 5px;'>Reset & Insert Ulang</button>";
        echo "<a href='index.php?page=ps&action=index' style='padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>Batal</a>";
        echo "</form>";
    }
    
    if(isset($_POST['action'])) {
        if($_POST['action'] == 'reset') {
            // Hapus semua data PS
            $conn->exec("DELETE FROM ps");
            echo "<p style='color: green;'>✓ Semua data PS berhasil dihapus</p>";
        }
        
        // Insert data PS
        $query = "INSERT INTO ps (nama_ps, tipe, status, harga_per_jam) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        $success = 0;
        foreach($ps_data as $ps) {
            if($stmt->execute([$ps['nama_ps'], $ps['tipe'], $ps['status'], $ps['harga_per_jam']])) {
                $success++;
                echo "<p style='color: green;'>✓ {$ps['nama_ps']} ({$ps['tipe']}) - Rp " . number_format($ps['harga_per_jam'], 0, ',', '.') . "/jam berhasil ditambahkan</p>";
            }
        }
        
        echo "<hr>";
        echo "<h3>✓ Berhasil menambahkan $success PS!</h3>";
        echo "<p><a href='index.php?page=ps&action=index' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Lihat Data PS</a></p>";
    }
    
    if($count == 0 && !isset($_POST['action'])) {
        // Auto insert jika belum ada data
        $query = "INSERT INTO ps (nama_ps, tipe, status, harga_per_jam) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        $success = 0;
        foreach($ps_data as $ps) {
            if($stmt->execute([$ps['nama_ps'], $ps['tipe'], $ps['status'], $ps['harga_per_jam']])) {
                $success++;
                echo "<p style='color: green;'>✓ {$ps['nama_ps']} ({$ps['tipe']}) - Rp " . number_format($ps['harga_per_jam'], 0, ',', '.') . "/jam berhasil ditambahkan</p>";
            }
        }
        
        echo "<hr>";
        echo "<h3>✓ Berhasil menambahkan $success PS!</h3>";
        echo "<p><a href='index.php?page=ps&action=index' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Lihat Data PS</a></p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

// Tampilkan data PS yang ada
echo "<hr>";
echo "<h3>Data PS Saat Ini:</h3>";
try {
    $stmt = $conn->query("SELECT * FROM ps ORDER BY id ASC");
    $count = $stmt->rowCount();
    
    if($count > 0) {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nama PS</th><th>Tipe</th><th>Status</th><th>Harga/Jam</th></tr>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td><strong>" . $row['nama_ps'] . "</strong></td>";
            echo "<td>" . $row['tipe'] . "</td>";
            echo "<td><span style='padding: 5px 10px; background: " . ($row['status'] == 'tersedia' ? '#28a745' : '#ffc107') . "; color: white; border-radius: 3px;'>" . ucfirst($row['status']) . "</span></td>";
            echo "<td>Rp " . number_format($row['harga_per_jam'], 0, ',', '.') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p>Total: <strong>$count PS</strong></p>";
    } else {
        echo "<p style='color: red;'>Belum ada data PS</p>";
    }
} catch(PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='index.php?page=dashboard' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>Dashboard</a></p>";
echo "<p><a href='index.php?page=ps&action=index' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>Kelola PS</a></p>";
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 1000px; margin: 0 auto; }
    h2, h3 { color: #667eea; }
    table { margin: 20px 0; background: white; }
    th { background: #667eea; color: white; padding: 10px; text-align: left; }
    td { padding: 10px; border-bottom: 1px solid #ddd; }
    p { line-height: 1.8; }
</style>
