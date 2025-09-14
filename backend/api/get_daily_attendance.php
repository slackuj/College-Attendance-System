<?php
require_once '../functions.php';

header('Content-Type: application/json'); // Tell browser to expect JSON

$inputJSON = file_get_contents('php://input'); // Read the raw request body
$input = json_decode($inputJSON, true); // Decode JSON into a PHP associative array

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON received.']);
    exit();
}

$date = $input['date'] ?? null;

if (!$date) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}
$subject = $input['subject'] ?? null;

if (!$subject) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

$mysqli = dbConnect();
$query = "
                SELECT
                roll_number,
                CASE
                WHEN attendance = 1 THEN 'present'
                WHEN attendance = 0 THEN 'absent'
                WHEN attendance = -1 THEN 'leave'
                ELSE 'unknown'
                END AS attendance 
                FROM
                aAttendance
                WHERE
                class = '$subject' AND day = '$date'
                ORDER BY 
                roll_number asc
                ";
$dailyAttendances = [];
if ($result = $mysqli->query($query))
/*->num_rows > 0*/ {
    while($row = $result->fetch_assoc()) {
        $dailyAttendances[] = $row;
    }
}
else{
    echo $mysqli->error;
    exit();
}
$mysqli->close();

$names = [];
foreach ($dailyAttendances as $dailyAttendance){
   $names[] = getStudentName($dailyAttendance['roll_number']);
}

echo json_encode(["success" => true,
                  "attendance" => $dailyAttendances,
                  "names" => $names
                ]); // Output JSON
