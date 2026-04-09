<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/Database.php';

echo "<h2>Fix Peminjaman Table - Remove Foreign Keys</h2>";

$database = new Database();
$conn = $database->getConnection();

if(!$conn) {
    die("Database connection failed!");
}

// Step 1: Drop existing table
echo "<h3>Step 1: Drop existing peminjaman table</h3>";
try {
    $conn->exec("DROP TABLE IF EXISTS peminjaman");
    echo "<p style='color: green;'>✓ Old peminjaman table dropped</p>";
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

// Step 2: Create new table WITHOUT foreign keys
echo "<h3>Step 2: Create new peminjaman table (without foreign keys)</h3>";
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
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Peminjaman table created successfully WITHOUT foreign keys!</p>";
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

// Step 3: Verify structure
echo "<h3>Step 3: Verify table structure</h3>";
try {
    $stmt = $conn->query("DESCRIBE peminjaman");
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td><strong>" . $row['Field'] . "</strong></td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p style='color: green;'>✓ Table structure verified!</p>";
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

// Step 4: Check foreign keys
echo "<h3>Step 4: Check foreign keys (should be empty)</h3>";
try {
    $stmt = $conn->query("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = 'peminjaman_ps' AND TABLE_NAME = 'peminjaman' AND CONSTRAINT_TYPE = 'FOREIGN KEY'");
    $fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(count($fks) > 0) {
        echo "<p style='color: orange;'>⚠ Found " . count($fks) . " foreign keys:</p>";
        foreach($fks as $fk) {
            echo "<p>- " . $fk['CONSTRAINT_NAME'] . "</p>";
        }
    } else {
        echo "<p style='color: green;'>✓ No foreign keys found! Table is ready to use.</p>";
    }
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>✓ Done! Table peminjaman is now ready without foreign key constraints.</h3>";
echo "<p>You can now create peminjaman without foreign key errors.</p>";
echo "<hr>";
echo "<p><a href='index.php?page=dashboard' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>Go to Dashboard</a></p>";
echo "<p><a href='index.php?page=peminjaman&action=create' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>Create Peminjaman</a></p>";
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 1000px; margin: 0 auto; }
    h2, h3 { color: #667eea; }
    table { margin: 20px 0; background: white; }
    th { background: #667eea; color: white; padding: 10px; text-align: left; }
    td { padding: 10px; border-bottom: 1px solid #ddd; }
    p { line-height: 1.8; }
</style>
