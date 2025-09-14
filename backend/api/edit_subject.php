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


$sID = $input['sID'] ?? null;
$sname = $input['sname'] ?? null;
$ccode = $input['ccode'] ?? null;
$ccredit = $input['ccredit'] ?? null;


$mysqli = dbConnect();
$query = "select programme from Subject where id = '$sID'";
$result = $mysqli->query($query);
$row = $result->fetch_array(MYSQLI_NUM);
$pID = $row[0];
$mysqli->close();

    if (!empty($sname)){
        $return = checkForDuplicateSubjects($sname, $pID);
        if (is_string($return)) {
            echo json_encode(['success' => false, 'error' => $return]);
            exit();
        }
        updateAttribute('aSubject', 'name', $sname, 'id', $sID);
    }
if (!empty($ccode))
    updateAttribute('aSubject', 'course_code', $ccode, 'id', $sID);
if (!empty($ccredit))
    updateAttribute('aSubject', 'course_credit', $ccredit, 'id', $sID);

    echo json_encode(['success' => true]);
exit();
