<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/Database.php';

echo "<h2>Check Users in Database</h2>";

$database = new Database();
$conn = $database->getConnection();

if(!$conn) {
    die("Database connection failed!");
}

// Check users
echo "<h3>Users in Database:</h3>";
try {
    $stmt = $conn->query("SELECT id, username, nama, role FROM users");
    $count = $stmt->rowCount();
    
    if($count > 0) {
        echo "<p>Found <strong>$count</strong> users:</p>";
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Nama</th><th>Role</th></tr>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td><strong>" . $row['username'] . "</strong></td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>" . ucfirst($row['role']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>⚠ No users found! Please run setup.php first.</p>";
        echo "<p><a href='setup.php' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>Run Setup</a></p>";
    }
} catch(PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Check PS
echo "<h3>PS in Database:</h3>";
try {
    $stmt = $conn->query("SELECT id, nama_ps, tipe, status, harga_per_jam FROM ps");
    $count = $stmt->rowCount();
    
    if($count > 0) {
        echo "<p>Found <strong>$count</strong> PS:</p>";
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nama PS</th><th>Tipe</th><th>Status</th><th>Harga/Jam</th></tr>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td><strong>" . $row['nama_ps'] . "</strong></td>";
            echo "<td>" . $row['tipe'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>Rp " . number_format($row['harga_per_jam'], 0, ',', '.') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠ No PS found! Please add PS data.</p>";
    }
} catch(PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Actions:</h3>";
echo "<p><a href='setup.php' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>Setup Users</a></p>";
echo "<p><a href='index.php' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>Go to Login</a></p>";
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 1000px; margin: 0 auto; }
    h2, h3 { color: #667eea; }
    table { margin: 20px 0; background: white; }
    th { background: #667eea; color: white; padding: 10px; text-align: left; }
    td { padding: 10px; border-bottom: 1px solid #ddd; }
</style>
