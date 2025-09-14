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


$cID = $input['cID'] ?? null;
$selectedStudents = $input['selectedStudents'] ?? null;

if (!$cID || !$selectedStudents) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

    echo json_encode(['success' => removeStudents($cID, $selectedStudents)]);
    exit();
