<?php
require_once '../functions.php';

header('Content-Type: application/json'); // Tell browser to expect JSON

$inputJSON = file_get_contents('php://input'); // Read the raw request body
$input = json_decode($inputJSON, true); // Decode JSON into a PHP associative array

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON received.']);
    exit();
}

$subject = $input['subject'] ?? null;

if (!$subject) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

$month = $input['month'] ?? null;

if (!$month) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}
$year = $input['year'] ?? null;

if (!$year) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

$mysqli = dbConnect();
$query = "
                SELECT
                roll_number AS `roll_number`,
                (SELECT COUNT(DISTINCT day) FROM aAttendance WHERE class = '$subject' AND MONTH(day) = '$month' AND YEAR(day) = '$year') AS total_classes,
                SUM(CASE WHEN attendance = 1 THEN 1 ELSE 0 END) AS total_present_days,
                SUM(CASE WHEN attendance = 0 THEN 1 ELSE 0 END) AS total_absent_days,
                SUM(CASE WHEN attendance = -1 THEN 1 ELSE 0 END) AS total_leave_days,
                ROUND(
                (SUM(CASE WHEN attendance = 1 THEN 1 ELSE 0 END) / (SELECT COUNT(DISTINCT day) FROM aAttendance WHERE class = '$subject' AND MONTH(day) = '$month' AND YEAR(day) = '$year')) * 100, 2
                ) AS attendance_percentage
                FROM
                aAttendance
                WHERE
                class = '$subject' AND MONTH(day) = '$month' AND YEAR(day) = '$year' 
                GROUP BY
                roll_number
                ORDER BY 
                roll_number asc
                ";
$monthlyAttendances = [];
if ($result = $mysqli->query($query))
/*->num_rows > 0*/ {
    while($row = $result->fetch_assoc()) {
        $monthlyAttendances[] = $row;
    }
}
else{
    echo $mysqli->error;
    exit();
}
$mysqli->close();

$names = [];
foreach ($monthlyAttendances as $monthlyAttendance){
   $names[] = getStudentName($monthlyAttendance['roll_number']);
}

echo json_encode(["success" => true,
                  "attendance" => $monthlyAttendances,
                  "names" => $names
                ]); // Output JSON
exit();
