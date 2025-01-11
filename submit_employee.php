<?php
require_once 'db_functions.php';

header('Content-Type: application/json');

// Initialize response
$response = [
    'success' => false,
    'message' => ''
];

try {
    // Check required fields
    $required = ['name', 'email', 'department', 'phone'];
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Please fill in all fields");
        }
    }
    
    // Basic validation
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address");
    }
    
    if (!preg_match("/^[0-9+\-\s()]*$/", $_POST['phone'])) {
        throw new Exception("Invalid phone number");
    }
    
    // Clean input
    $name = trim(strip_tags($_POST['name']));
    $email = trim($_POST['email']);
    $department = (int)$_POST['department'];
    $phone = trim(strip_tags($_POST['phone']));
    
    // Save to database
    addEmployee($name, $email, $department, $phone);
    
    $response['success'] = true;
    $response['message'] = "Employee added successfully!";
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);