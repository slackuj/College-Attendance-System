<?php
//require_once "../vendor/autoload.php";
require_once "/home/slackup/Documents/project/CollegeAttendanceSystem/vendor/autoload.php";
require_once "../functions.php"; // Assuming this file contains your database connection logic

use OpenSpout\Reader\XLSX\Reader;

// Set the content type to application/json
header('Content-Type: application/json');

// Define the expected file key from the front-end
$file_key = "fileToUpload";

// Check if a file was uploaded successfully
if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {

    $target_dir = "uploads/";
    $fileType = strtolower(pathinfo($_FILES[$file_key]["name"], PATHINFO_EXTENSION));

    // Basic file validation
    if ($fileType != "xlsx") {
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "Sorry, only XLSX files are allowed."]);
        exit;
    }

    // Move the uploaded file to a temporary location for processing
    // Note: It's often safer to use a unique filename to prevent conflicts
    $unique_filename = uniqid('upload_') . '.' . $fileType;
    $target_file = $target_dir . $unique_filename;

    // Create the uploads directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES[$file_key]["tmp_name"], $target_file)) {

        try {
            $reader = new Reader();
            $reader->open($target_file);

            $successCount = 0;
            $errorCount = 0;
            $errorRows = [];

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $index => $row) {
                    // Skip the header row (index 1)
                    if ($index === 1) {
                        continue;
                    }

                    $rowData = $row->toArray();


                        // FirstName, LastName, RollNumber, Email, Password, Faculty, programme, Semester
                        $firstName = $rowData[0] ?? null;
                        $lastName = $rowData[1] ?? null;
                        $rollNumber = $rowData[2] ?? null;
                        $email = $rowData[3] ?? null;
                        $password = $rowData[4] ?? null;
                        $programme = $rowData[5] ?? null;
                        $semester = $rowData[6] ?? null;

                        // Basic validation
                        if (empty($programme) || empty($semester)
                            || empty($firstName) || empty($lastName) || empty($rollNumber)
                            || empty($email) || empty($password)) {
                            $errorRows[] = "Row $index: Missing data.";
                            continue;
                    }


                    $pname = getPname($programme);

                    // Attempt to create the teacher
                    // Assuming createTeacher() returns true on success, false on failure
                    // and handles its own database sanitation/insertion.
                    if ($pname !== false){

                        $result = createStudent($firstName, $lastName, $rollNumber, $email, $password, $semester, $pname);
;
                    if (is_bool($result)) {
                        ++$successCount;
                    } else {
                        $errorCount++;
                        $errorRows[] = "Row $index: $result.";
                    }
                    } else {
                        $errorCount++;
                        $errorRows[] = "Row $index: $result.";
                    }
                }
            }

            $reader->close();
            unlink($target_file); // Delete the temporary file

            http_response_code(200); // OK
            echo json_encode([
                "success" => "true",
                "message" => [
                    "successCount" => $successCount,
                    "errorCount" => $errorCount,
                    "errors" => $errorRows
                ]
            ]);

        } catch (Exception $e) {
            // Error during file processing (e.g., corrupted file)
            http_response_code(500); // Internal Server Error
            echo json_encode([
                "success" => "false",
                "error" => "Error processing file: " . $e->getMessage()
            ]);
        }
    } else {
        // Error moving the uploaded file
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "success" => "false",
            "error" => "Sorry, there was an error uploading your file."
        ]);
    }
} else {
    // No file uploaded or upload error
    http_response_code(400); // Bad Request
    $errorMessage = "No file uploaded or an upload error occurred.";
    if (isset($_FILES[$file_key]["error"])) {
        // Provide more specific error messages from PHP's built-in codes
        switch ($_FILES[$file_key]["error"]) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errorMessage = "The uploaded file exceeds the maximum size allowed.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $errorMessage = "The file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $errorMessage = "No file was uploaded.";
                break;
            default:
                $errorMessage = "An unknown upload error occurred.";
        }
    }
    echo json_encode([
        "success" => "false",
        "error" => $errorMessage
    ]);
}