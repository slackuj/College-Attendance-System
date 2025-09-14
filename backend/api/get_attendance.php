<?php
require_once '../functions.php';
require_once '../../frontend/Student/Student.php';
session_start();

header('Content-Type: application/json'); // Tell browser to expect JSON

$inputJSON = file_get_contents('php://input'); // Read the raw request body
$input = json_decode($inputJSON, true); // Decode JSON into a PHP associative array

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON received.']);
    exit();
}

$student = $_SESSION['student'];
$roll_number = $student->roll_number;

$subject = $input['subject'] ?? null;

if (!$roll_number || !$subject) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

$mysqli = dbConnect();
$query = "SELECT `day`, attendance FROM aAttendance where roll_number = '$roll_number' and class = '$subject'"; // Example query

if ($result = $mysqli->query($query))
/*->num_rows > 0*/ {
    while($row = $result->fetch_assoc()) {
        $attendance[] = $row;
    }
}
else
    echo $mysqli->error;

echo json_encode(["success" => true, "data" => $attendance]); // Output JSON
$mysqli->close();