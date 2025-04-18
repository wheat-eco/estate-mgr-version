<?php
/**
 * AJAX handler to get areas by region ID
 */
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is logged in
requireLogin();

// Get region ID from query string
$regionId = isset($_GET['region_id']) ? intval($_GET['region_id']) : 0;

if ($regionId > 0) {
    // Get areas for the selected region
    $stmt = $conn->prepare("SELECT id, name FROM areas WHERE region_id = ? ORDER BY name");
    $stmt->bind_param("i", $regionId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $areas = [];
    while ($row = $result->fetch_assoc()) {
        $areas[] = $row;
    }
    
    // Return areas as JSON
    header('Content-Type: application/json');
    echo json_encode($areas);
} else {
    // Return empty array if no region ID provided
    header('Content-Type: application/json');
    echo json_encode([]);
}