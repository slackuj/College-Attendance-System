<?php
require_once '../functions.php';
session_start();

header('Content-Type: application/json'); // Tell browser to expect JSON

$inputJSON = file_get_contents('php://input'); // Read the raw request body
$input = json_decode($inputJSON, true); // Decode JSON into a PHP associative array

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON received.']);
    exit();
}


$fname = $input['fname'] ?? null;
$lname = $input['lname'] ?? null;
$email = $input['email'] ?? null;
$pass = $input['pass'] ?? null;

if (!$fname || !$lname || !$email || !$pass) {
    echo json_encode(['success' => false, 'error' => 'Missing data.']);
    exit();
}

$result = createTeacher($fname, $lname, $email, $pass);
if (is_bool($result)) {
    echo json_encode(['success' => true]);
}
else{
    echo json_encode(['success' => false,
                      'error' => $result]);
}
exit();
