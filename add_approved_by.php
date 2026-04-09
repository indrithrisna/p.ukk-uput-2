<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/Database.php';

echo "<h2>Add Approved By Column</h2>";

$database = new Database();
$conn = $database->getConnection();

if(!$conn) {
    die("Database connection failed!");
}

// Add approved_by column
echo "<h3>Adding approved_by column to peminjaman table...</h3>";
try {
    $sql = "ALTER TABLE peminjaman ADD COLUMN approved_by INT NULL AFTER status";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Column approved_by added successfully!</p>";
} catch(PDOException $e) {
    if(strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>⚠ Column approved_by already exists!</p>";
    } else {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
}

// Add approved_at column
echo "<h3>Adding approved_at column to peminjaman table...</h3>";
try {
    $sql = "ALTER TABLE peminjaman ADD COLUMN approved_at DATETIME NULL AFTER approved_by";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Column approved_at added successfully!</p>";
} catch(PDOException $e) {
    if(strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>⚠ Column approved_at already exists!</p>";
    } else {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
}

// Verify structure
echo "<h3>Verifying table structure...</h3>";
try {
    $stmt = $conn->query("DESCRIBE peminjaman");
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $highlight = ($row['Field'] == 'approved_by' || $row['Field'] == 'approved_at') ? 'background: #ffffcc;' : '';
        echo "<tr style='$highlight'>";
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

echo "<hr>";
echo "<h3>✓ Done!</h3>";
echo "<p>Kolom approved_by dan approved_at sudah ditambahkan ke tabel peminjaman.</p>";
echo "<p>Sekarang sistem akan mencatat siapa petugas yang menyetujui peminjaman.</p>";
echo "<hr>";
echo "<p><a href='index.php?page=dashboard' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Go to Dashboard</a></p>";
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 1000px; margin: 0 auto; }
    h2, h3 { color: #667eea; }
    table { margin: 20px 0; background: white; }
    th { background: #667eea; color: white; padding: 10px; text-align: left; }
    td { padding: 10px; border-bottom: 1px solid #ddd; }
    p { line-height: 1.8; }
</style>
