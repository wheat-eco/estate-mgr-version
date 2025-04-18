<?php
// Enable full error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration - update these with your actual database details
$db_host = 'localhost';
$db_user = 'root'; // Change to your database username
$db_pass = ''; // Change to your database password
$db_name = 'love_view_estate';

echo "<h1>PHP Error Diagnostic</h1>";

// Check PHP version
echo "<h2>PHP Version</h2>";
echo "PHP Version: " . phpversion();

// Check if required extensions are loaded
echo "<h2>Required Extensions</h2>";
$required_extensions = ['mysqli', 'gd', 'fileinfo', 'session'];
foreach ($required_extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "Loaded" : "Not Loaded") . "<br>";
}

// Check database connection
echo "<h2>Database Connection</h2>";
try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($conn->connect_error) {
        echo "Database connection failed: " . $conn->connect_error;
    } else {
        echo "Database connection successful!";
        
        // Check if tables exist
        echo "<h3>Database Tables</h3>";
        $tables = ['users', 'properties', 'regions', 'areas', 'property_images', 'property_features', 'property_documents', 'viewing_requests'];
        
        foreach ($tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            echo "$table: " . ($result->num_rows > 0 ? "Exists" : "Does not exist") . "<br>";
        }
        
        $conn->close();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Check directory permissions
echo "<h2>Directory Permissions</h2>";
$directories = ['.', 'admin', 'uploads', 'includes'];
foreach ($directories as $dir) {
    if (file_exists($dir)) {
        echo "$dir: " . substr(sprintf('%o', fileperms($dir)), -4) . "<br>";
    } else {
        echo "$dir: Directory does not exist<br>";
    }
}

// Check if admin files exist
echo "<h2>Admin Files</h2>";
$admin_files = [
    'admin/index.php',
    'admin/login.php',
    'admin/includes/header.php',
    'admin/includes/footer.php',
    'admin/includes/db.php',
    'admin/includes/auth.php'
];

foreach ($admin_files as $file) {
    echo "$file: " . (file_exists($file) ? "Exists" : "Does not exist") . "<br>";
}
?>