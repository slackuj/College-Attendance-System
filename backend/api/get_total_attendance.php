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

$mysqli = dbConnect();
$query = "
                SELECT
                roll_number,
                (SELECT COUNT(DISTINCT day) FROM aAttendance WHERE class = '$subject') AS total_class_days,
                SUM(CASE WHEN attendance = 1 THEN 1 ELSE 0 END) AS present_days,
                SUM(CASE WHEN attendance = 0 THEN 1 ELSE 0 END) AS absent_days,
                SUM(CASE WHEN attendance = -1 THEN 1 ELSE 0 END) AS leave_days,
                ROUND((SUM(CASE WHEN attendance = 1 THEN 1 ELSE 0 END) / (SELECT COUNT(DISTINCT day) FROM aAttendance WHERE class = '$subject')) * 100, 2) AS attendance_percentage
                FROM
                aAttendance
                WHERE
                class = '$subject'
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
