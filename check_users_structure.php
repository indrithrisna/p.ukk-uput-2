<?php
require_once 'config/Database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Cek struktur tabel users
    $sql = "DESCRIBE users";
    $stmt = $conn->query($sql);
    
    echo "<h3>Struktur Tabel Users:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . ($row['Extra'] ?? '') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Cek apakah tabel users ada
    $sql2 = "SHOW TABLES LIKE 'users'";
    $stmt2 = $conn->query($sql2);
    
    if($stmt2->rowCount() > 0) {
        echo "<br><p style='color: green;'>✅ Tabel users ditemukan</p>";
    } else {
        echo "<br><p style='color: red;'>❌ Tabel users tidak ditemukan</p>";
    }
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
