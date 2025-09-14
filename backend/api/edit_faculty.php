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


$fID = $input['fID'] ?? null;
$fname = $input['fname'] ?? null;

if (!$fID || !$fname) {
    echo json_encode(['success' => false, 'error' => 'Missing data.']);
    exit();
}

$result = editFaculty($fID, $fname);
if (is_bool($result)){
    if($result)
        echo json_encode(['success' => true]);
    else
        echo json_encode(['success' => false, 'error' => 'failed']);
}

else if (is_string($result)){
    echo json_encode(['success' => false, 'error' => $result]);
}
exit();