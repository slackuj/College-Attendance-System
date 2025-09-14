<?php
require_once '../functions.php';
require_once '../../frontend/Student/Student.php';
session_start();

header('Content-Type: application/json'); // Tell browser to expect JSON


// It's a good practice to check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $mysqli = dbConnect();

    $query = "SELECT id, name, programme, semester
                FROM aSubject
                WHERE id NOT IN (SELECT subject FROM Classes)";

    if ($result = $mysqli->query($query)){
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
    } else
        echo $mysqli->error;

    $mysqli->close();
    echo json_encode(["success" => true,
                      "subjects" => $subjects
    ]); // Output JSON
    exit();
}