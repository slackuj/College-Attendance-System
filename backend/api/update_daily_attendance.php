<?php
require_once '../functions.php';

header('Content-Type: application/json'); // Tell browser to expect JSON

$inputJSON = file_get_contents('php://input'); // Read the raw request body
$input = json_decode($inputJSON, true); // Decode JSON into a PHP associative array

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON received.']);
    exit();
}

$attendancesToUpdate = $input['attendancesToUpdate'] ?? null;

if (!$attendancesToUpdate) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

$date = $input['date'] ?? null;

if (!$date) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

$mysqli = dbConnect();
foreach ($attendancesToUpdate as $attendanceToUpdate){

    $roll_number = $attendanceToUpdate['roll_number'];
    $attendance = $attendanceToUpdate['attendance'];

    $query = "
            update aAttendance
            set
                attendance = '$attendance'
            where
                roll_number = '$roll_number' and `day` = '$date'
                ";
    if (!$mysqli->query($query))
        /*->num_rows > 0*/ {
        echo $mysqli->error;
        exit();
    }
}

$mysqli->close();

echo json_encode(["success" => true,]); // Output JSON
exit();
