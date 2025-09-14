<?php
require_once '../functions.php';
session_start();

header('Content-Type: application/json'); // Tell browser to expect JSON

$inputJSON = file_get_contents('php://input'); // Read the raw request body
$input = json_decode($inputJSON, true); // Decode JSON into a PHP associative array

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON received.']);
    exit();
}


$sID = $input['sID'] ?? null;
if (!$sID) {
    echo json_encode(['success' => false, 'error' => 'Missing data.']);
    exit();
}

foreach ($sID as $id)
    recoverStudent($id);
echo json_encode(['success' => true]);
exit();
