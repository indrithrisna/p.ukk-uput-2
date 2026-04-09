<?php
require_once 'config/Database.php';

$database = new Database();
$conn = $database->getConnection();

try {
    // Update nama user yang benar
    $queries = [
        "UPDATE users SET nama = 'Administrator' WHERE username = 'admin' AND role = 'admin'",
        "UPDATE users SET nama = 'Petugas Satu' WHERE username = 'petugas1' AND role = 'petugas'",
        "UPDATE users SET nama = 'Peminjam Satu' WHERE username = 'peminjam1' AND role = 'peminjam'"
    ];
    
    foreach($queries as $query) {
        $stmt = $conn->prepare($query);
        $stmt->execute();
    }
    
    echo "✅ Berhasil memperbaiki nama user!<br><br>";
    
    // Tampilkan data user sekarang
    $query = "SELECT id, username, nama, role FROM users ORDER BY id";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    echo "<h3>Data User Sekarang:</h3>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Nama</th><th>Role</th></tr>";
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['nama'] . "</td>";
        echo "<td>" . $row['role'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "<br><br>";
    echo "<a href='index.php'>Kembali ke Login</a>";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
