<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/Database.php';

echo "<h2>Add Pengembalian & Denda Columns</h2>";

$database = new Database();
$conn = $database->getConnection();

if(!$conn) {
    die("Database connection failed!");
}

// Add kondisi_ps column
echo "<h3>Adding kondisi_ps column...</h3>";
try {
    $sql = "ALTER TABLE peminjaman ADD COLUMN kondisi_ps ENUM('baik', 'rusak') NULL AFTER tanggal_kembali";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Column kondisi_ps added successfully!</p>";
} catch(PDOException $e) {
    if(strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>⚠ Column kondisi_ps already exists!</p>";
    } else {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
}

// Add denda column
echo "<h3>Adding denda column...</h3>";
try {
    $sql = "ALTER TABLE peminjaman ADD COLUMN denda INT DEFAULT 0 AFTER kondisi_ps";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Column denda added successfully!</p>";
} catch(PDOException $e) {
    if(strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>⚠ Column denda already exists!</p>";
    } else {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
}

// Add keterangan column
echo "<h3>Adding keterangan column...</h3>";
try {
    $sql = "ALTER TABLE peminjaman ADD COLUMN keterangan TEXT NULL AFTER denda";
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Column keterangan added successfully!</p>";
} catch(PDOException $e) {
    if(strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>⚠ Column keterangan already exists!</p>";
    } else {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
}

// Verify structure
echo "<h3>Verifying table structure...</h3>";
try {
    $stmt = $conn->query("DESCRIBE peminjaman");
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $highlight = in_array($row['Field'], ['kondisi_ps', 'denda', 'keterangan']) ? 'background: #ffffcc;' : '';
        echo "<tr style='$highlight'>";
        echo "<td><strong>" . $row['Field'] . "</strong></td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
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
echo "<p>Kolom kondisi_ps, denda, dan keterangan sudah ditambahkan.</p>";
echo "<ul>";
echo "<li><strong>kondisi_ps:</strong> Kondisi PS saat dikembalikan (baik/rusak)</li>";
echo "<li><strong>denda:</strong> Jumlah denda jika kondisi rusak</li>";
echo "<li><strong>keterangan:</strong> Keterangan kerusakan atau catatan lainnya</li>";
echo "</ul>";
echo "<hr>";
echo "<p><a href='index.php?page=dashboard' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Go to Dashboard</a></p>";
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 1000px; margin: 0 auto; }
    h2, h3 { color: #667eea; }
    table { margin: 20px 0; background: white; }
    th { background: #667eea; color: white; padding: 10px; text-align: left; }
    td { padding: 10px; border-bottom: 1px solid #ddd; }
    p, li { line-height: 1.8; }
</style>
