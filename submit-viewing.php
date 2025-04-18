<?php
/**
 * Submit Viewing Request for Love View Estate
 */

// Include database connection
require_once('includes/db.php');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $propertyId = isset($_POST['property_id']) ? intval($_POST['property_id']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $preferredDate = isset($_POST['preferred_date']) && !empty($_POST['preferred_date']) ? $_POST['preferred_date'] : null;
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Basic validation
    if ($propertyId <= 0 || empty($name) || empty($email) || empty($phone)) {
        // Redirect back with error
        header('Location: property-details.php?id=' . $propertyId . '&error=1');
        exit;
    }
    
    // Insert viewing request into database
    $stmt = $conn->prepare("INSERT INTO viewing_requests (property_id, name, email, phone, preferred_date, message, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("isssss", $propertyId, $name, $email, $phone, $preferredDate, $message);
    
    if ($stmt->execute()) {
        // Get property details for email
        $propertyQuery = "SELECT title FROM properties WHERE id = ?";
        $propertyStmt = $conn->prepare($propertyQuery);
        $propertyStmt->bind_param("i", $propertyId);
        $propertyStmt->execute();
        $propertyResult = $propertyStmt->get_result();
        $propertyTitle = $propertyResult->fetch_assoc()['title'];
        
        // Send email notification
        $to = "info@loveviewestates.co.uk"; // Change to your email
        $subject = "New Viewing Request: " . $propertyTitle;
        $emailMessage = "A new viewing request has been submitted:\n\n";
        $emailMessage .= "Property: " . $propertyTitle . "\n";
        $emailMessage .= "Name: " . $name . "\n";
        $emailMessage .= "Email: " . $email . "\n";
        $emailMessage .= "Phone: " . $phone . "\n";
        
        if ($preferredDate) {
            $emailMessage .= "Preferred Date: " . date('d-m-Y', strtotime($preferredDate)) . "\n";
        }
        
        if (!empty($message)) {
            $emailMessage .= "Message: " . $message . "\n";
        }
        
        $headers = "From: website@loveviewestates.co.uk";
        
        mail($to, $subject, $emailMessage, $headers);
        
        // Redirect with success message
        header('Location: property-details.php?id=' . $propertyId . '&success=1');
        exit;
    } else {
        // Redirect with error
        header('Location: property-details.php?id=' . $propertyId . '&error=2');
        exit;
    }
} else {
    // If not POST request, redirect to home
    header('Location: index.php');
    exit;
}