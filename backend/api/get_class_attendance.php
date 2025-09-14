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

if (!$cID) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

$mysqli = dbConnect();
$query = "SELECT `day` FROM aAttendance where class = '$cID'";
$classes = [];
if ($result = $mysqli->query($query))
/*->num_rows > 0*/ {
    while($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}
else
    echo $mysqli->error;

$mysqli->close();
echo json_encode(["success" => true, "data" => $classes]); // Output JSON
exit();