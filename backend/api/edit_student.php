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
$fname = $input['fname'] ?? null;
$lname = $input['lname'] ?? null;
$email = $input['email'] ?? null;
$title = $input['title'] ?? null;


    if (!empty($email)){
        $return = checkForDuplicateUser($email);
        if (is_string($return)) {
            echo json_encode(['success' => false, 'error' => $return]);
            exit();
        }else
            updateAttribute('aUsers', 'email', $email, 'id', $sID);
    }
    if (!empty($fname))
        updateAttribute('aStudent', 'fname', $fname, 'id', $sID);
    if (!empty($lname))
        updateAttribute('aStudent', 'lname', $lname, 'id', $sID);
    if (!empty($title))
        updateAttribute('aStudent', 'title', $title, 'id', $sID);
    echo json_encode(['success' => true]);
exit();
