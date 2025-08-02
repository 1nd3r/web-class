<?php
// SparkNest Services - Contact Form Handler
// This file processes contact form submissions and booking requests

require_once 'config/database.php';

// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    // Get the form type from the request
    $formType = $_POST['form_type'] ?? '';
    
    switch ($formType) {
        case 'contact':
            handleContactForm();
            break;
        case 'booking':
            handleBookingForm();
            break;
        case 'quick_contact':
            handleQuickContact();
            break;
        default:
            throw new Exception('Invalid form type');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred while processing your request',
        'message' => $e->getMessage()
    ]);
}

function handleContactForm() {
    // Validate required fields
    $requiredFields = ['contactFirstName', 'contactLastName', 'contactEmail', 'contactSubject', 'contactMessage'];
    $data = validateFormData($_POST, $requiredFields);
    
    // Validate email
    if (!filter_var($data['contactEmail'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    
    // Save to database
    $db = new SparkNestDB();
    $success = $db->saveContactMessage(
        $data['contactFirstName'] . ' ' . $data['contactLastName'],
        $data['contactEmail'],
        $data['contactSubject'],
        $data['contactMessage'],
        $data['contactPhone'] ?? null
    );
    
    if (!$success) {
        throw new Exception('Failed to save contact message');
    }
    
    // Send email notification (optional)
    sendContactNotification($data);
    
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your message! We will get back to you within 2 hours.'
    ]);
}

function handleBookingForm() {
    // Validate required fields
    $requiredFields = [
        'firstName', 'lastName', 'email', 'phone', 'address', 
        'serviceType', 'preferredDate'
    ];
    $data = validateFormData($_POST, $requiredFields);
    
    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    
    // Validate date
    $preferredDate = new DateTime($data['preferredDate']);
    $today = new DateTime();
    if ($preferredDate < $today) {
        throw new Exception('Preferred date cannot be in the past');
    }
    
    // Save to database
    $db = new SparkNestDB();
    $success = $db->saveBooking($data);
    
    if (!$success) {
        throw new Exception('Failed to save booking request');
    }
    
    // Send email notification (optional)
    sendBookingNotification($data);
    
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your booking request! We will confirm your appointment within 2 hours.'
    ]);
}

function handleQuickContact() {
    // Validate required fields
    $requiredFields = ['quickName', 'quickEmail'];
    $data = validateFormData($_POST, $requiredFields);
    
    // Validate email
    if (!filter_var($data['quickEmail'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    
    // Save to database as contact message
    $db = new SparkNestDB();
    $success = $db->saveContactMessage(
        $data['quickName'],
        $data['quickEmail'],
        'Quick Contact Request',
        'Service Type: ' . ($data['quickProject'] ?? 'Not specified') . 
        ' | Timeline: ' . ($data['quickTimeline'] ?? 'Not specified'),
        null
    );
    
    if (!$success) {
        throw new Exception('Failed to save quick contact request');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your inquiry! We will get back to you soon.'
    ]);
}

function validateFormData($postData, $requiredFields) {
    $data = [];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (empty($postData[$field])) {
            $missingFields[] = $field;
        } else {
            $data[$field] = trim($postData[$field]);
        }
    }
    
    if (!empty($missingFields)) {
        throw new Exception('Missing required fields: ' . implode(', ', $missingFields));
    }
    
    // Add optional fields
    $optionalFields = ['contactPhone', 'propertyType', 'urgency', 'preferredTime', 
                      'propertySize', 'frequency', 'specialRequirements', 'accessInstructions', 
                      'additionalServices', 'quickProject', 'quickTimeline'];
    
    foreach ($optionalFields as $field) {
        if (isset($postData[$field])) {
            $data[$field] = trim($postData[$field]);
        }
    }
    
    return $data;
}

function sendContactNotification($data) {
    // Email configuration
    $to = 'hello@sparknest.co.ke';
    $subject = 'New Contact Message - SparkNest Services';
    
    $message = "New contact message received:\n\n";
    $message .= "Name: " . $data['contactFirstName'] . " " . $data['contactLastName'] . "\n";
    $message .= "Email: " . $data['contactEmail'] . "\n";
    $message .= "Phone: " . ($data['contactPhone'] ?? 'Not provided') . "\n";
    $message .= "Subject: " . $data['contactSubject'] . "\n";
    $message .= "Message: " . $data['contactMessage'] . "\n";
    $message .= "\nReceived at: " . date('Y-m-d H:i:s');
    
    $headers = "From: " . $data['contactEmail'] . "\r\n";
    $headers .= "Reply-To: " . $data['contactEmail'] . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email (uncomment to enable)
    // mail($to, $subject, $message, $headers);
}

function sendBookingNotification($data) {
    // Email configuration
    $to = 'hello@sparknest.co.ke';
    $subject = 'New Booking Request - SparkNest Services';
    
    $message = "New booking request received:\n\n";
    $message .= "Customer: " . $data['firstName'] . " " . $data['lastName'] . "\n";
    $message .= "Email: " . $data['email'] . "\n";
    $message .= "Phone: " . $data['phone'] . "\n";
    $message .= "Address: " . $data['address'] . "\n";
    $message .= "Service Type: " . $data['serviceType'] . "\n";
    $message .= "Preferred Date: " . $data['preferredDate'] . "\n";
    $message .= "Preferred Time: " . ($data['preferredTime'] ?? 'Not specified') . "\n";
    $message .= "Property Type: " . ($data['propertyType'] ?? 'Not specified') . "\n";
    $message .= "Property Size: " . ($data['propertySize'] ?? 'Not specified') . "\n";
    $message .= "Urgency: " . ($data['urgency'] ?? 'Not specified') . "\n";
    $message .= "Frequency: " . ($data['frequency'] ?? 'One-time') . "\n";
    
    if (!empty($data['specialRequirements'])) {
        $message .= "Special Requirements: " . $data['specialRequirements'] . "\n";
    }
    
    if (!empty($data['accessInstructions'])) {
        $message .= "Access Instructions: " . $data['accessInstructions'] . "\n";
    }
    
    $message .= "\nReceived at: " . date('Y-m-d H:i:s');
    
    $headers = "From: " . $data['email'] . "\r\n";
    $headers .= "Reply-To: " . $data['email'] . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email (uncomment to enable)
    // mail($to, $subject, $message, $headers);
}
?>
// Commit 9 - Add professional cleaning equipment details
// Commit 19 - Add sample data for services and testimonials
// Commit 29 - Update page titles and descriptions
