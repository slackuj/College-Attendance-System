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
$classID = $input['cID'] ?? null;

if (!$tID || !$classID) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}
$row = getRow('Classes', 'id', $classID);
if ($row[4] == -1)
    updateAttribute('Classes', 'deleted', '0', 'id', $classID);
echo json_encode(['success' => updateAttribute('Classes', 'teacher', $tID, 'id', $classID)]);
exit();
