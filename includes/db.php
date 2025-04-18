<?php
/**
 * Database connection for Love View Estate
 */

// Database configuration
$db_host = 'srv1216.hstgr.io';
$db_user = 'u368036953_muizdev'; // Change to your database username
$db_pass = 'Adeniyi2020#'; // Change to your database password
$db_name = 'u368036953_loveview';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");