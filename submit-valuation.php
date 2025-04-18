<?php
/**
 * Submit Sales Valuation Request for Love View Estate
 */

// Include database connection
require_once('includes/db.php');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $postcode = isset($_POST['postcode']) ? trim($_POST['postcode']) : '';
    $bedrooms = isset($_POST['bedrooms']) ? trim($_POST['bedrooms']) : '';
    $propertyType = isset($_POST['property_type']) ? trim($_POST['property_type']) : '';
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $moveDate = isset($_POST['move_date']) ? trim($_POST['move_date']) : '';
    $marketingConsent = isset($_POST['marketing_consent']) ? 1 : 0;
    $termsConsent = isset($_POST['terms_consent']) ? 1 : 0;
    
    // Basic validation
    if (empty($postcode) || empty($bedrooms) || empty($propertyType) || empty($fullName) || empty($email) || empty($phone) || empty($moveDate)) {
        // Redirect back with error
        header('Location: sales-valuation.php?error=1');
        exit;
    }
    
    // Insert valuation request into database
    $stmt = $conn->prepare("INSERT INTO valuation_requests (
        postcode, bedrooms, property_type, full_name, email, phone, 
        move_date, marketing_consent, terms_consent, valuation_type, status, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'sale', 'pending', NOW())");
    
    $stmt->bind_param("sssssssii", $postcode, $bedrooms, $propertyType, $fullName, $email, $phone, $moveDate, $marketingConsent, $termsConsent);
    
    if ($stmt->execute()) {
        // Send email notification
        $to = "info@loveviewestates.co.uk"; // Change to your email
        $subject = "New Sales Valuation Request";
        $message = "A new sales valuation request has been submitted:\n\n";
        $message .= "Name: " . $fullName . "\n";
        $message .= "Email: " . $email . "\n";
        $message .= "Phone: " . $phone . "\n";
        $message .= "Postcode: " . $postcode . "\n";
        $message .= "Bedrooms: " . $bedrooms . "\n";
        $message .= "Property Type: " . $propertyType . "\n";
        $message .= "Move Date: " . $moveDate . "\n";
        $message .= "Marketing Consent: " . ($marketingConsent ? 'Yes' : 'No') . "\n";
        
        $headers = "From: website@loveviewestates.co.uk";
        
        mail($to, $subject, $message, $headers);
        
        // Redirect with success message
        header('Location: valuation-thank-you.php');
        exit;
    } else {
        // Redirect with error
        header('Location: sales-valuation.php?error=2');
        exit;
    }
} else {
    // If not POST request, redirect to home
    header('Location: index.php');
    exit;
}