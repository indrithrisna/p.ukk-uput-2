<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/Database.php';

echo "<h2>Fix Database Structure</h2>";

$database = new Database();
$conn = $database->getConnection();

if(!$conn) {
    die("Database connection failed!");
}

// Drop and recreate peminjaman table
echo "<h3>Step 1: Drop existing peminjaman table (if exists)</h3>";
try {
    $conn->exec("DROP TABLE IF EXISTS peminjaman");
    echo "<p style='color: green;'>✓ Old peminjaman table dropped</p>";
} catch(PDOException $e) {
    echo "<p style='color: orange;'>⚠ " . $e->getMessage() . "</p>";
}

// Create peminjaman table
echo "<h3>Step 2: Create peminjaman table</h3>";
try {
    $sql = "CREATE TABLE peminjaman (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        ps_id INT NOT NULL,
        tanggal_pinjam DATETIME NOT NULL,
        tanggal_kembali DATETIME NULL,
        durasi_jam INT NOT NULL,
        total_harga INT NOT NULL,
        status ENUM('pending', 'disetujui', 'ditolak', 'selesai') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (ps_id) REFERENCES ps(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Peminjaman table created successfully!</p>";
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

// Verify table structure
echo "<h3>Step 3: Verify table structure</h3>";
try {
    $stmt = $conn->query("DESCRIBE peminjaman");
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td><strong>" . $row['Field'] . "</strong></td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p style='color: green;'>✓ Table structure verified!</p>";
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Done!</h3>";
echo "<p><a href='index.php'>Go to Login Page</a></p>";
echo "<p><a href='index.php?page=peminjaman&action=index'>Go to Peminjaman Page</a></p>";
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: 0 auto; }
    h2, h3 { color: #667eea; }
    table { margin: 20px 0; background: white; width: 100%; }
    th { background: #667eea; color: white; padding: 10px; text-align: left; }
    td { padding: 8px; border-bottom: 1px solid #ddd; }
    a { color: #667eea; text-decoration: none; font-weight: bold; padding: 10px 20px; background: #f0f0f0; border-radius: 5px; display: inline-block; margin: 5px; }
    a:hover { background: #667eea; color: white; }
</style>
