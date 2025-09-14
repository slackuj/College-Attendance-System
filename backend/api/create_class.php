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


$tID = $input['tID'] ?? null;
$subjectID = $input['subjectID'] ?? null;

if (!$tID || !$subjectID) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

$result = createClass($subjectID, $tID);
if (is_bool($result)) {
    echo json_encode(['success' => true]);
    exit();
}
else{
    echo json_encode(['success' => false,
                      'error' => $result]);
}
