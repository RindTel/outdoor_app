<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'outdoor_app';

echo "<h2>Outdoor App Database Setup</h2>";


$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>");
}

echo "<p style='color: green;'>✓ Connected to MySQL server</p>";


$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Database '$dbname' created or already exists</p>";
} else {
    echo "<p style='color: red;'>Error creating database: " . $conn->error . "</p>";
    $conn->close();
    exit;
}

$conn->select_db($dbname);

$schemaFile = __DIR__ . '/database/schema.sql';
if (!file_exists($schemaFile)) {
    die("<p style='color: red;'>Schema file not found: $schemaFile</p>");
}

$schema = file_get_contents($schemaFile);

// Split by semicolons and execute each statement
$statements = array_filter(array_map('trim', explode(';', $schema)));

$successCount = 0;
$errorCount = 0;

foreach ($statements as $statement) {
    if (empty($statement) || strpos($statement, '--') === 0) {
        continue; // Skip comments and empty statements
    }
    
    if (stripos($statement, 'CREATE DATABASE') !== false || 
        stripos($statement, 'USE ') !== false) {
        continue; // Skip database creation and USE statements
    }
    
    if ($conn->query($statement) === TRUE) {
        $successCount++;
    } else {
        $errorCount++;
        if (strpos($conn->error, 'already exists') === false) {
            echo "<p style='color: orange;'>Warning: " . $conn->error . "</p>";
        }
    }
}

echo "<p style='color: green;'>✓ Executed $successCount SQL statements successfully</p>";
if ($errorCount > 0) {
    echo "<p style='color: orange;'>⚠ $errorCount statements had warnings (may be due to existing data)</p>";
}

// Verify tables
$tables = ['users', 'places', 'place_details', 'contact_messages'];
echo "<h3>Verifying tables:</h3>";
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        $count = $conn->query("SELECT COUNT(*) as count FROM $table")->fetch_assoc()['count'];
        echo "<p style='color: green;'>✓ Table '$table' exists ($count rows)</p>";
    } else {
        echo "<p style='color: red;'>✗ Table '$table' not found</p>";
    }
}

$conn->close();

echo "<hr>";
echo "<h3 style='color: green;'>Setup Complete!</h3>";
echo "<p>You can now:</p>";
echo "<ul>";
echo "<li><a href='Login/login.php'>Go to Login Page</a></li>";
echo "<li><a href='Pages/Home/home.php'>Go to Home Page</a></li>";
echo "</ul>";
echo "<p><strong>Note:</strong> Delete or protect this setup.php file for security.</p>";
?>
