<?php
require_once '../functions.php';
require_once '../../frontend/Student/Student.php';
session_start();

header('Content-Type: application/json'); // Tell browser to expect JSON

$inputJSON = file_get_contents('php://input'); // Read the raw request body
$input = json_decode($inputJSON, true); // Decode JSON into a PHP associative array

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON received.']);
    exit();
}


$email = $input['email'] ?? null;
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

$role = $input['role'] ?? null;
if (!$role) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}


// generate temporary password
$newPassword = generateRandomPassword();

if (resetPassword($newPassword, $email, $role))
    echo json_encode(['success' => true,
                      'password' => $newPassword]);
else{

    echo json_encode(['success' => false]);
    exit();
}

