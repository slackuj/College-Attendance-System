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


$pID = $input['pID'] ?? null;
$sname = trim($input['sname']) ?? null;
$semester = trim($input['semester']) ?? null;
$ccode = trim($input['ccode']) ?? null;
$ccredit = trim($input['ccredit']) ?? null;

if (!$pID || !$sname || !$semester || !$ccode || !$ccredit) {
    echo json_encode(['success' => false, 'error' => 'Missing data.']);
    exit();
}

$result = createSubject($sname, $pID, $semester, $ccode, $ccredit);
if (is_string($result)) {
    echo json_encode(['success' => false,
                      'error' => $result]);
}
else if (is_bool($result)){
    if ($result)
        echo json_encode(['success' => true]);
}
exit();
