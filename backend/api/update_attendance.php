<?php
require_once '../functions.php';

header('Content-Type: application/json'); // Tell browser to expect JSON

$inputJSON = file_get_contents('php://input'); // Read the raw request body
$input = json_decode($inputJSON, true); // Decode JSON into a PHP associative array

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON received.']);
    exit();
}

$cID = $input['cID'] ?? null;
$date = $input['date'] ?? null;
$attendanceDatas = $input['attendanceData'] ?? null;


if (!$attendanceDatas || !$cID || !$date) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

foreach ($attendanceDatas as $attendanceData){

    $roll_number = $attendanceData['roll_number'];
    $attendance = $attendanceData['attendance'];

    $mysqli = dbConnect();
    $query = "
            update aAttendance
            set
                attendance = '$attendance'
            where
                roll_number = '$roll_number' and `day` = '$date'
                ";
    $result = $mysqli->query($query);
    if (!$result) {
        echo json_encode(["success" => false]); // Output JSON
        exit();
    }
}

echo json_encode(["success" => true]); // Output JSON
exit();
