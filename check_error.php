<?php
// File untuk cek error PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Checking PHP Configuration</h2>";

// Test database connection
echo "<h3>1. Testing Database Connection</h3>";
try {
    require_once 'config/Database.php';
    $database = new Database();
    $conn = $database->getConnection();
    
    if($conn) {
        echo "<p style='color: green;'>✓ Database connection successful!</p>";
        
        // Check tables
        echo "<h3>2. Checking Tables</h3>";
        $tables = ['users', 'ps', 'peminjaman'];
        foreach($tables as $table) {
            try {
                $stmt = $conn->query("SELECT COUNT(*) FROM $table");
                $count = $stmt->fetchColumn();
                echo "<p style='color: green;'>✓ Table '$table' exists with $count records</p>";
            } catch(PDOException $e) {
                echo "<p style='color: red;'>✗ Table '$table' error: " . $e->getMessage() . "</p>";
            }
        }
    } else {
        echo "<p style='color: red;'>✗ Database connection failed!</p>";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

// Test models
echo "<h3>3. Testing Models</h3>";
try {
    require_once 'models/User.php';
    echo "<p style='color: green;'>✓ User model loaded</p>";
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ User model error: " . $e->getMessage() . "</p>";
}

try {
    require_once 'models/PS.php';
    echo "<p style='color: green;'>✓ PS model loaded</p>";
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ PS model error: " . $e->getMessage() . "</p>";
}

try {
    require_once 'models/Peminjaman.php';
    echo "<p style='color: green;'>✓ Peminjaman model loaded</p>";
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Peminjaman model error: " . $e->getMessage() . "</p>";
}

// Test controllers
echo "<h3>4. Testing Controllers</h3>";
try {
    require_once 'controllers/AuthController.php';
    echo "<p style='color: green;'>✓ AuthController loaded</p>";
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ AuthController error: " . $e->getMessage() . "</p>";
}

try {
    require_once 'controllers/UserController.php';
    echo "<p style='color: green;'>✓ UserController loaded</p>";
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ UserController error: " . $e->getMessage() . "</p>";
}

try {
    require_once 'controllers/PSController.php';
    echo "<p style='color: green;'>✓ PSController loaded</p>";
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ PSController error: " . $e->getMessage() . "</p>";
}

try {
    require_once 'controllers/PeminjamanController.php';
    echo "<p style='color: green;'>✓ PeminjamanController loaded</p>";
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ PeminjamanController error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>PHP Info</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>PDO Available: " . (extension_loaded('pdo') ? 'Yes' : 'No') . "</p>";
echo "<p>PDO MySQL Available: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "</p>";

echo "<hr>";
echo "<p><a href='index.php'>Go to Login Page</a></p>";
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
    h2, h3 { color: #667eea; }
    p { line-height: 1.6; }
    a { color: #667eea; text-decoration: none; font-weight: bold; }
    a:hover { text-decoration: underline; }
</style>
