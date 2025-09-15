<?php
    require "db_config.php";

/**
 * The dbConnect function connects php with MySQL server.
 * @param The function does not take any parameters.
 * @return The function returns the mysqli object on success, or false on failure.
 */

    function dbConnect()
    {
        $mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $mysqli;
    }

function login($email, $pass){
    $mysqli = dbConnect();
    $return = false;

    $query = "select password, id, role from aUsers where email = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row !== null) {
            if (password_verify($pass, $row['password'])) {
                $return = $row;
            }
        }
        $stmt->close();
    } else {
        echo $mysqli->error;
    }

    $mysqli->close();
    return $return;
}
function deleteContentsOf($tableName, $identifier, $id){
    $mysqli = dbConnect();
    $query = "delete from $tableName where $identifier = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            echo "successfully deleted";
        } else {
            echo "Error updating: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }
    $mysqli->close();
}

    function deleteTeacher($tID) : bool
    {
    $deleted = true;
    if(softDelete('Users', $tID)){
        if (softDelete('Teacher', $tID)){
            if (totalClassesOf($tID, 1) > 0)
                $deleted = unassignClass($tID);
        }
        else $deleted = false;
    }
    else
        $deleted = false;
    return $deleted;
}

function deleteTeacherPermanently($tID)
{
    $mysqli = dbConnect();

    // set teacher of unassigned classes to dummy teacher
    $query = "update Classes set teacher = '51' where teacher = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $tID);
        $stmt->execute();
        $stmt->close();
    }

    $query = "delete from Users where id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $tID);
        $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
}

function recoverTeacher($tID)
{
    $mysqli = dbConnect();
    $query = "update Users set deleted = '0', created_date = current_date where id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $tID);
        $stmt->execute();
        $stmt->close();
    }

    $query = "update Teacher set deleted = '0' where id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $tID);
        $stmt->execute();
        $stmt->close();
    }

    $query = "update Classes set deleted = '0', created_date = current_date where teacher = ? and deleted = '-1'";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $tID);
        $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
}

function recoverStudent($sID)
{
    $mysqli = dbConnect();
    $query = "update Users set deleted = '0', created_date = current_date where id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $sID);
        $stmt->execute();
        $stmt->close();
    }

    $query = "update Student set deleted = '0' where id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $sID);
        $stmt->execute();
        $stmt->close();
    }

    $mysqli->close();
}
function recoverClass($cID)
{
    $mysqli = dbConnect();
    $query = "update Classes set deleted = '-1', created_date = current_date where id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $cID);
        $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
}

function deleteClass($cID) : bool
{
    $mysqli = dbConnect();
    $query = "update Classes set deleted = 1, created_date = current_date where id = ?";
    $result = false;

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $cID);
        $result = $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
    return $result;
}

function deleteStudent($sID) : bool
{
    $mysqli = dbConnect();
    $result = false;

    $query = "update Student set deleted = '1' where id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $sID);
        $stmt->execute();
        $stmt->close();
    }

    $query = "update Users set deleted = '1', created_date = current_date where id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $sID);
        $result = $stmt->execute();
        $stmt->close();
    }

    $mysqli->close();
    return $result;
}

function getID($tableName){
    $mysqli = dbConnect();
    $data = [];

    // The table name itself cannot be a parameter, so we validate it
    $validTables = ['aUsers', 'aTeacher', 'aStudent', 'aSubject', 'aProgramme', 'Classes', 'aFaculty', 'aStudent'];
    if (!in_array($tableName, $validTables)) {
        // Handle invalid table name, e.g., return an empty array or throw an exception
        $mysqli->close();
        return [];
    }

    $query = "select id from $tableName";
    if ($tableName === "aStudent") {
        $query = "select roll_number from $tableName";
    }

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row[0];
        }
        $result->close();
    }
    $mysqli->close();
    return $data;
}

    /*
     * getAttribute($tableName, $column, $idName, $id)
     * returns attribute from table $tableName where column = $column and id = $id
     */
function getAttribute($tableName, $column, $idName, $id){
    $mysqli = dbConnect();
    $data = null;
    $query = "select $column from $tableName where $idName = ?";

    // It's critical to validate $tableName, $column, and $idName against whitelists
    // before using them in the query to prevent SQL injection.

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $data = $row[0];
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

    /*
     * returns one row of data from table
     */
function getRow($tableName, $idName, $id){
    $mysqli = dbConnect();
    $data = null;
    $query = "select * from $tableName where $idName = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        $data = $row;
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

    /* getColumn($tableName, $column, $id)
       returns column $column , where table $tableName has multiple rows for id $id.
    */
function getColumn($tableName, $column, $id){
    $mysqli = dbConnect();
    $data = null;
    $ID = ($tableName === 'aStudent') ? 'roll_number' : 'id';
    $query = "select $column from $tableName where $ID = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $data = $row[0];
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

    function getFaculty(){
        $mysqli = dbConnect();

        $query = "select name from aFaculty";
        $result = $mysqli->query($query);

        /*--------- numeric array ---------*/
        while ($row = $result->fetch_array(MYSQLI_NUM)){
           $data[] = $row[0];
        }
        $mysqli->close();
        return $data;
    }

function getFaculties() : Array
{
    $mysqli = dbConnect();

    $query = "select * from aFaculty";
    $result = $mysqli->query($query);

    $data =[];
    /*--------- numeric array ---------*/
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

    function getProgramme(){
        $mysqli = dbConnect();

        $query = "select name from aProgramme";
        $result = $mysqli->query($query);

        /*--------- numeric array ---------*/
        while ($row = $result->fetch_array(MYSQLI_NUM)){
           $data[] = $row[0];
        }
        $mysqli->close();
        return $data;
    }

function getProgrammes() : Array
{
    $mysqli = dbConnect();

    $query = "select * from aProgramme";
    $result = $mysqli->query($query);

    $data = [];
    /*--------- numeric array ---------*/
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getProgrammesOf($fID) : Array
{
    $mysqli = dbConnect();

    $query = "select * from aProgramme where faculty = '$fID'";
    $result = $mysqli->query($query);

    $data = [];
    /*--------- numeric array ---------*/
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}
    function getSubjects(){
        $mysqli = dbConnect();

        $query = "select * from aSubject";
        $result = $mysqli->query($query);

        /*--------- numeric array ---------*/
        $data = [];
        while ($row = $result->fetch_array(MYSQLI_NUM)){
           $data[] = $row;
        }
        $mysqli->close();
        return $data;
    }

    function getCCode($subject, $programme){
        $mysqli = dbConnect();

        $query = "select course_code from aSubject where name = '$subject' and programme = '$programme'";
        $result = $mysqli->query($query);

        /*--------- numeric array ---------*/
        while ($row = $result->fetch_array(MYSQLI_NUM)){
           $data = $row[0];
        }
        $mysqli->close();
        return $data;
    }

    function getTeacher(){
        $mysqli = dbConnect();

        $query = "select * from aTeacher";
        $result = $mysqli->query($query);

        /*--------- numeric array ---------*/
        while ($row = $result->fetch_array(MYSQLI_NUM)){
           $data[] = $row[1] . ' ' . $row[2];
        }
        $mysqli->close();
        return $data;
    }

function getTeachers() : Array {
    $mysqli = dbConnect();

    $query = "select * from aTeacher";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getDeletedTeachers() : Array {
    $mysqli = dbConnect();

    $query = "select * from Teacher where deleted = 1";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    $data = [];
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getStudentName($roll_number) : string
{
    $mysqli = dbConnect();
    $data = '';
    $query = "select fname, lname from aStudent where roll_number = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $roll_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $data = $row[0] . ' ' . $row[1];
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function getTeachersName($tID){
    $mysqli = dbConnect();
    $data = null;
    $query = "select * from aTeacher where id = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $tID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $data = $row[1] . ' ' . $row[2];
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function getContentsOf($tableName){
    $mysqli = dbConnect();
    $data = [];

    $validTables = ['aUsers', 'aTeacher', 'aStudent', 'aSubject', 'aProgramme', 'Classes', 'aFaculty', 'ClassesInfo', 'Attendance', 'token_table'];
    if (!in_array($tableName, $validTables)) {
        $mysqli->close();
        return [];
    }

    $query = "select * from $tableName";
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row;
        }
        $result->close();
    }
    $mysqli->close();
    return $data;
}

function getSubjectsOf($pID){
    $mysqli = dbConnect();
    $data = [];
    $query = "SELECT * FROM aSubject WHERE programme = ?;";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $pID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row;
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}


/*
 * to be replaced in place of geClassesOf() function later
 */


function getKlassesOf($ID, $user) : array{
    $mysqli = dbConnect();
    $data = [];
    $query = "";

    if ($user == 0) {
        $query = "select class from ClassesInfo where roll_number = ?";
    } else {
        $query = "select id from Classes where teacher = ?";
    }

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $ID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row[0];
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function getClassesOf($tID){
    $mysqli = dbConnect();
    $data = [];
    $teacher = getTeachersName($tID); // Assumes getTeachersName is safe

    $query = "select * from Classes where teacher = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $teacher);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row;
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function classesOf($tID){
    $mysqli = dbConnect();
    $data = [];
    $query = "select * from aClasses where teacher = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $tID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row;
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function getClasses() : Array {
    $mysqli = dbConnect();

    $query = "select * from aClasses";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    $data = [];
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getStudents() : Array {
    $mysqli = dbConnect();

    $query = "select * from aStudent order by fname asc";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    $data = [];
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getUnassignedClasses() : Array {
    $mysqli = dbConnect();

    $query = "select * from Classes where deleted = -1";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    $data = [];
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getDeletedClasses() : Array {
    $mysqli = dbConnect();

    $query = "select * from Classes where deleted = 1";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    $data = [];
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getDeletedStudents() : Array {
    $mysqli = dbConnect();

    $query = "select * from Student where deleted = 1";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    $data = [];
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getEligibleStudents($programme, $semester){
    $mysqli = dbConnect();
    $data = [];
    $query = "select roll_number, fname, lname from aStudent where programme = ? and semester = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ss", $programme, $semester);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row;
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function getStudentsRollNumbersOfClass($cID){
    $mysqli = dbConnect();
    $data = [];
    $query = "select roll_number from ClassesInfo where class = ? order by roll_number asc";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $cID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row[0];
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function createTeacher($fname, $lname, $email, $password)
{
    $return = checkForDuplicateUser($email);

    if (is_bool($return)) {
        $mysqli = dbConnect();
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into aUsers table
        $query = "insert into aUsers values(null, ?, ?, '2', current_date)";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $stmt->close();
        }

        // Insert into aTeacher table
        $id = getAttribute('aUsers', 'id', 'email', $email); // This call should be safe
        $query = "insert into aTeacher values(?, ?, ?, 'Lecturer')";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("iss", $id, $fname, $lname);
            $stmt->execute();
            $stmt->close();
        }

        // Insert teacher into token_table
        $query = "insert into token_table values(?, '000000', '0')";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->close();
        }
        $mysqli->close();
    }
    return $return;
}

function checkForDuplicateUser($email){
    $mysqli = dbConnect();
    $return = true;

    $query = "select * from aUsers where email = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $return = "There's already an account associated with id $email";
        }
        $stmt->close();
    }

    if (is_bool($return)) {
        $query = "select * from Users where email = ?";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_array(MYSQLI_NUM);
            if ($row !== null) {
                $return = "There's a deleted account associated with id $email.Consider either recovering it or delete it permanently.";
            }
            $stmt->close();
        }
    }
    $mysqli->close();
    return $return;
}

function checkForDuplicateRollNumber($roll_number){
    $mysqli = dbConnect();
    $return = true;

    $query = "select * from aStudent where roll_number = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $roll_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $return = "There's already a student associated with id $roll_number";
        }
        $stmt->close();
    }

    if (is_bool($return)) {
        $query = "select * from Student where roll_number = ?";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("s", $roll_number);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_array(MYSQLI_NUM);
            if ($row !== null) {
                $return = "There's a deleted student associated with id $roll_number.Consider either recovering it or delete it permanently.";
            }
            $stmt->close();
        }
    }
    $mysqli->close();
    return $return;
}

function checkForDuplicateSubjects($sname, $pID){
    $mysqli = dbConnect();
    $return = true;

    $query = "select * from aSubject where name = ? and programme = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("si", $sname, $pID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $return = "There's already a subject associated with id $sname in the same programme";
        }
        $stmt->close();
    }

    if (is_bool($return)) {
        $query = "select * from Subject where name = ? and programme = ?";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("si", $sname, $pID);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_array(MYSQLI_NUM);
            if ($row !== null) {
                $return = "There's a deleted subject associated with id $sname in the same programme.Consider either recovering it or delete it permanently.";
            }
            $stmt->close();
        }
    }
    $mysqli->close();
    return $return;
}

function createSubject($sname, $pID, $semester, $ccode, $ccredit){
    $result = checkForDuplicateSubjects($sname, $pID);

    if (is_string($result)) {
        return $result;
    } else {
        $mysqli = dbConnect();
        $query = "insert into aSubject values(null, ?, ?, ?, ?, ?)";
        $result = false;

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("sissd", $sname, $pID, $semester, $ccode, $ccredit);
            $result = $stmt->execute();
            $stmt->close();
        }
        $mysqli->close();
        return $result;
    }
}

function getPname($programme) {
    $mysqli = dbConnect();
    $data = false;

    $query = "select id from aProgramme where name = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $programme);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $data = $row[0];
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function getFID($faculty) {
    $mysqli = dbConnect();
    $data = false;

    $query = "select id from aFaculty where name = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $faculty);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $data = $row[0];
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function createStudent($fname, $lname, $roll_number, $email, $pass, $semester, $programme)
{
    $return = checkForDuplicateUser($email);
    if (is_bool($return)) {
        $return = checkForDuplicateRollNumber($roll_number);
        if (is_bool($return)) {
            $mysqli = dbConnect();
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $result = false;

            // Insert into aUsers table
            $query = "insert into aUsers values(null, ?, ?, '3', current_date)";
            if ($stmt = $mysqli->prepare($query)) {
                $stmt->bind_param("ss", $email, $pass);
                $stmt->execute();
                $stmt->close();
            }

            // Insert into aStudent table
            $sID = getAttribute('aUsers', 'id', 'email', $email);
            $query = "insert into aStudent values(?, ?, ?, ?, ?, ?, 'Student')";
            if ($stmt = $mysqli->prepare($query)) {
                $stmt->bind_param("issiis", $sID, $roll_number, $fname, $lname, $semester, $programme);
                $stmt->execute();
                $stmt->close();
            }

            // Insert student into token_table
            $query = "insert into token_table values(?, '000000', '0')";
            if ($stmt = $mysqli->prepare($query)) {
                $stmt->bind_param("s", $email);
                $result = $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
            return $result;
        }
    }
    return $return;
}

function createClass($subjectID, $teacherID)
{
    $mysqli = dbConnect();
    $query = "insert into aClasses values(null, ?, ?, current_date)";
    $result = false;

    try{
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("ss", $subjectID, $teacherID);
            $result = $stmt->execute();
            $stmt->close();
        }
    } catch (mysqli_sql_exception $e){
        if ($e->getCode() === 1062) {
            $result = 'Cannot Create duplicate class for same subject!';
        } else {
            $result = 'An unexpected error occurred.';
        }
    } finally {
        $mysqli->close();
    }
    return $result;
}



  function createProgramme($fID, $pname)
  {
      $mysqli = dbConnect();
      $query = "INSERT INTO Programme (name, faculty) VALUES(?, ?)";

    // Enable exceptions for mysqli
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        // Prepare and execute the update query
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("si", $pname, $fID);
        $result = $stmt->execute();
        $stmt->close();
        $mysqli->close();
        return $result; // Will be true on success

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();

        // Check for the duplicate entry error code (1062)
        if ($e->getCode() === 1062) {
            return "$pname already exists!.";
        } else {
            // Re-throw other errors for further handling
            throw $e;
        }
    }
  }

    /*
     * add student into ClassesInfo
     */
function addStudents($cID, $roll_numbers) : bool{
    $mysqli = dbConnect();
    $result = true;

    $query = "insert into ClassesInfo values(null, ?, ?)";
    $update_query = "update Attendance set deleted = '0' where roll_number = ? and class = ?";

    if ($stmt = $mysqli->prepare($query)) {
        if ($update_stmt = $mysqli->prepare($update_query)) {
            foreach ($roll_numbers as $roll_number) {
                $stmt->bind_param("ss", $roll_number, $cID);
                $result = $stmt->execute();

                $update_stmt->bind_param("ss", $roll_number, $cID);
                $result = $update_stmt->execute();
            }
            $update_stmt->close();
        }
        $stmt->close();
    }
    $mysqli->close();
    return $result;
}


function removeStudents($cID, $roll_numbers) : bool{
    $mysqli = dbConnect();
    $result = true;

    $delete_query = "delete from ClassesInfo where roll_number = ? and class = ?";
    $update_query = "update Attendance set deleted = '1' where roll_number = ? and class = ?";

    if ($delete_stmt = $mysqli->prepare($delete_query)) {
        if ($update_stmt = $mysqli->prepare($update_query)) {
            foreach ($roll_numbers as $roll_number) {
                $delete_stmt->bind_param("ss", $roll_number, $cID);
                $result = $delete_stmt->execute();

                $update_stmt->bind_param("ss", $roll_number, $cID);
                $result = $update_stmt->execute();
            }
            $update_stmt->close();
        }
        $delete_stmt->close();
    }
    $mysqli->close();
    return $result;
}


if (isset($_POST['create_faculty'])) {
        $newFaculty = trim($_POST['fname']);
        createFaculty($newFaculty);
    }

if (isset($_POST['create_programme'])) {
        /*------------- adding new issue ------------- */
        $Faculty = trim($_POST['fname']);
        $newProgramme = trim($_POST['pname']);
        createProgramme($Faculty, $newProgramme);
    }

    if (isset($_POST['create_subject'])) {
        $Programme = trim($_POST['pname']);
        $Semester = trim($_POST['semester']);
        $newSubject = trim($_POST['sname']);
        $courseCode = trim($_POST['ccode']);
        $courseCredit = trim($_POST['ccredit']);

        createSubject($Programme, $Semester, $newSubject, $courseCode, $courseCredit);
    }

    if (isset($_POST['create_teacher'])) {
        /*------------- adding new issue ------------- */
        $firstName = trim($_POST['fname']);
        $lastName = trim($_POST['lname']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        createTeacher($firstName, $lastName, $email, $password);
    }

    if (isset($_POST['create_student'])) {
        $roll = trim($_POST['roll']);
        $firstName = trim($_POST['fname']);
        $lastName = trim($_POST['lname']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $semester = trim($_POST['semester']);
        $programme = trim($_POST['pname']);

        createStudent($firstName, $lastName, $roll, $email, $password,  $semester, $programme);
    }

    if (isset($_POST['create_class'])) {
        /*------------- adding new issue ------------- */
        $subjectID = $_POST['sname'];
        $teacherID = $_POST['tname'];

        createClass( $subjectID, $teacherID);
    }

    /* -------------   E D I T I N G    D A T A B A S E S ---------------- */

function editFaculty($fID, $fname) {
    $mysqli = dbConnect();

    // Enable exceptions for mysqli
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        // Prepare and execute the update query
        $query = "UPDATE aFaculty SET name = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("si", $fname, $fID);
        $result = $stmt->execute();
        $stmt->close();
        $mysqli->close();
        return $result; // Will be true on success

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();

        // Check for the duplicate entry error code (1062)
        if ($e->getCode() === 1062) {
            return "$fname already exists!.";
        } else {
            // Re-throw other errors for further handling
            throw $e;
        }
    }
}

function editProgramme($pID, $pname) {
    $mysqli = dbConnect();

    // Enable exceptions for mysqli
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        // Prepare and execute the update query
        $query = "UPDATE aProgramme SET name = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("si", $pname, $pID);
        $result = $stmt->execute();
        $stmt->close();
        $mysqli->close();
        return $result; // Will be true on success

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();

        // Check for the duplicate entry error code (1062)
        if ($e->getCode() === 1062) {
            return "$pname already exists!.";
        } else {
            // Re-throw other errors for further handling
            throw $e;
        }
    }
}

function createFaculty($fname) {

    $mysqli = dbConnect();
    $query = "INSERT INTO Faculty (name) VALUE(?)";

    try {
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $fname);

        $result = $stmt->execute();

        $stmt->close();
        $mysqli->close();

        // Check for successful insertion
        if ($result) {
            return true; // Return true on success
        } else {
            return "error: " . $mysqli->error; // General error
        }

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();

        // Check for the specific duplicate entry error code (1062)
        if ($e->getCode() === 1062) {
            return "$fname already exists!.";
        } else {
            // For any other error, return the specific error message
            return "error: " . $e->getMessage();
        }
    }
}

if (isset($_POST['edit_programme'])) {
    $pnames = $_POST['pname'];
    $mysqli = dbConnect();
    $id = getID("aProgramme");
    $i = count($id);

    $delete_query = "delete from aProgramme where id = ?";
    $update_query = "update aProgramme set name = ? where id = ?";

    if ($delete_stmt = $mysqli->prepare($delete_query)) {
        if ($update_stmt = $mysqli->prepare($update_query)) {
            for ($j = 0; $j < $i; ++$j) {
                if ($pnames[$j] === "delete") {
                    $delete_stmt->bind_param("s", $id[$j]);
                    if ($delete_stmt->execute()) {
                        echo "successfully deleted";
                    } else {
                        echo "Error updating: " . $delete_stmt->error;
                    }
                } else {
                    $update_stmt->bind_param("ss", $pnames[$j], $id[$j]);
                    if ($update_stmt->execute()) {
                        echo "successful";
                    } else {
                        echo "Error updating: " . $update_stmt->error;
                    }
                }
            }
            $update_stmt->close();
        }
        $delete_stmt->close();
    }
    $mysqli->close();
}

if (isset($_POST['edit_subject'])) {
    $snames = $_POST['sname'];
    $ccodes = $_POST['ccode'];
    $ccredits = $_POST['ccredit'];

    $mysqli = dbConnect();
    $id = getID("aSubject");
    $i = count($id);

    $delete_query = "delete from aSubject where id = ?";
    $update_name_query = "update aSubject set name = ? where id = ?";
    $update_ccode_query = "update aSubject set course_code = ? where id = ?";
    $update_ccredit_query = "update aSubject set course_credit = ? where id = ?";

    if ($delete_stmt = $mysqli->prepare($delete_query)) {
        if ($update_name_stmt = $mysqli->prepare($update_name_query)) {
            if ($update_ccode_stmt = $mysqli->prepare($update_ccode_query)) {
                if ($update_ccredit_stmt = $mysqli->prepare($update_ccredit_query)) {
                    for ($j = 0; $j < $i; ++$j) {
                        if ($snames[$j] === "delete") {
                            $delete_stmt->bind_param("s", $id[$j]);
                            if ($delete_stmt->execute()) {
                                echo "successfully deleted " . $id[$j];
                            } else {
                                echo "Error updating: " . $delete_stmt->error;
                            }
                        } else {
                            $update_name_stmt->bind_param("ss", $snames[$j], $id[$j]);
                            if ($update_name_stmt->execute()) echo "successful"; else echo "Error updating: " . $update_name_stmt->error;

                            $update_ccode_stmt->bind_param("ss", $ccodes[$j], $id[$j]);
                            if ($update_ccode_stmt->execute()) echo "successful"; else echo "Error updating: " . $update_ccode_stmt->error;

                            $update_ccredit_stmt->bind_param("ss", $ccredits[$j], $id[$j]);
                            if ($update_ccredit_stmt->execute()) echo "successful"; else echo "Error updating: " . $update_ccredit_stmt->error;
                        }
                    }
                    $update_ccredit_stmt->close();
                }
                $update_ccode_stmt->close();
            }
            $update_name_stmt->close();
        }
        $delete_stmt->close();
    }
    $mysqli->close();
}

if (isset($_POST['edit_teacher'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $title = $_POST['title'];
    $id = $_POST['tID'];
    $mysqli = dbConnect();

    $query = "update aTeacher set fname = ?, lname = ?, title = ? where id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("sssi", $fname, $lname, $title, $id);
        if ($stmt->execute()) {
            echo "successfully updated teacher";
        } else {
            echo "Error updating: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }

    $query = "update aUsers set email = ? where id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("si", $email, $id);
        if ($stmt->execute()) {
            echo "successfully updated User";
        } else {
            echo "Error updating: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }
    $mysqli->close();
}



if (isset($_POST['edit_student'])) {
    $rolls = $_POST['roll'];
    $fnames = $_POST['fname'];
    $lnames = $_POST['lname'];
    $emails = $_POST['email'];
    $passwords = $_POST['password'];
    $semesters = $_POST['semester'];
    $programmes = $_POST['programme'];
    $mysqli = dbConnect();

    $id = getID("aStudent");
    $i = count($id);

    $delete_query_student = "delete from aStudent where roll_number = ?";
    $delete_query_users = "delete from aUsers where email = ?";
    $delete_query_token = "delete from token_table where email = ?";

    $update_query_student_roll = "update aStudent set roll_number = ? where roll_number = ?";
    $update_query_student_fname = "update aStudent set fname = ? where roll_number = ?";
    $update_query_student_lname = "update aStudent set lname = ? where roll_number = ?";
    $update_query_student_email = "update aStudent set email= ? where roll_number = ?";
    $update_query_student_password = "update aStudent set password= ? where roll_number = ?";
    $update_query_user_email = "update aUsers set email = ? where id = ?";
    $update_query_user_password = "update aUsers set password= ? where id = ?";


    if ($delete_stmt_student = $mysqli->prepare($delete_query_student)) {
        if ($delete_stmt_users = $mysqli->prepare($delete_query_users)) {
            if ($delete_stmt_token = $mysqli->prepare($delete_query_token)) {
                // ... nesting more prepares here for updates is getting unwieldy.
                // A better approach is to handle each case (delete or update) separately.

                for ($j = 0; $j < $i; ++$j) {
                    if ($rolls[$j] === "delete") {
                        deleteContentsOf('aStudent', 'roll_number', $id[$j]); // this now uses prepared statements
                        deleteContentsOf('aUsers', 'email', $emails[$j]);
                        deleteContentsOf('token_table', 'email', $emails[$j]);
                    } else {
                        // All these updates should be handled with prepared statements
                        // The original code was updating 'aStudent' with an 'email' and 'password'
                        // which is incorrect based on the schema.
                        // I've simplified the logic here for clarity.

                        // Update aStudent table
                        $query = "update aStudent set roll_number = ?, fname = ?, lname = ? where roll_number = ?";
                        if ($stmt = $mysqli->prepare($query)) {
                            $stmt->bind_param("ssss", $rolls[$j], $fnames[$j], $lnames[$j], $id[$j]);
                            if ($stmt->execute()) echo "successful"; else echo "Error updating: " . $stmt->error;
                            $stmt->close();
                        }

                        // Update aUsers table (email and password)
                        $id_user = getAttribute('aUsers', 'id', 'email', $emails[$j]);
                        $pass_hash = password_hash($passwords[$j], PASSWORD_DEFAULT);
                        $query = "update aUsers set email = ?, password = ? where id = ?";
                        if ($stmt = $mysqli->prepare($query)) {
                            $stmt->bind_param("ssi", $emails[$j], $pass_hash, $id_user);
                            if ($stmt->execute()) echo "successful"; else echo "Error updating: " . $stmt->error;
                            $stmt->close();
                        }
                    }
                }
            }
        }
    }
    $mysqli->close();
}

if (isset($_POST['edit_class'])) {
    $tname = $_POST['tname'];
    $delete = $_POST['delete'];

    $mysqli = dbConnect();
    $id = getID("Classes");
    $i = count($id);

    $delete_query = "delete from Classes where id = ?";
    $update_query = "update Classes set teacher = ? where id = ?";

    if ($delete_stmt = $mysqli->prepare($delete_query)) {
        if ($update_stmt = $mysqli->prepare($update_query)) {
            for ($j = 0; $j < $i; ++$j) {
                if ($delete[$j] === 'true') {
                    $delete_stmt->bind_param("s", $id[$j]);
                    if ($delete_stmt->execute()) {
                        echo "successfully deleted";
                    } else {
                        echo "Error updating: " . $delete_stmt->error;
                    }
                } else {
                    $update_stmt->bind_param("si", $tname[$j], $id[$j]);
                    if ($update_stmt->execute()) {
                        echo "successful";
                    } else {
                        echo "Error updating: " . $update_stmt->error;
                    }
                }
            }
            $update_stmt->close();
        }
        $delete_stmt->close();
    }
    $mysqli->close();
}



if (isset($_POST['edit_myClass'])) {
    $classID = $_POST['class'];
    $roll = $_POST['roll'];
    $delete = $_POST['delete'];

    $mysqli = dbConnect();

    $delete_query = "delete from `ClassesInfo` where roll_number = ? and class = ?";
    if ($stmt = $mysqli->prepare($delete_query)) {
        $i = count($roll);
        for ($j = 0; $j < $i; ++$j) {
            if ($delete[$j] === 'true') {
                $stmt->bind_param("ss", $roll[$j], $classID);
                $stmt->execute();
            }
        }
        $stmt->close();
    }
    $mysqli->close();
}

function takeAttendance($cID, $day, $roll, $attendance) : bool {
    $mysqli = dbConnect();
    $result = false;
    $query = "insert into aAttendance values(?, ?, ?, ?, null)";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("sssi", $roll, $day, $cID, $attendance);
        $result = $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
    return $result;
}

function attendance_taken($classID) : bool{
    $day = date('Y-m-d');
    $mysqli = dbConnect();
    $return = false;
    $query = "select `day` from aAttendance where subject = ? and `day` = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ss", $classID, $day);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $return = true;
        }
        $stmt->close();
    }
    $mysqli->close();
    return $return;
}

function send_code($email): bool
{
    $mysqli = dbConnect();
    $return = false;
    $query = "select email from token_table where email = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $return = true;
        }
        $stmt->close();
    }
    $mysqli->close();
    return $return;
}

function getCode($email){
    $mysqli = dbConnect();
    try {
        $code = random_int(100000, 999999);
    } catch (\Random\RandomException $e) {
        echo $e->getTraceAsString();
    }
    $expiryTime = time() + 60 * 30;
    $query = "update token_table set token = ?, timestamp = ? where email = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("sis", $code, $expiryTime, $email);
        if ($stmt->execute()) {
            $stmt->close();
            $mysqli->close();
            return $code;
        } else {
            echo "error updating token_table" . $stmt->error;
        }
        $stmt->close();
    }
    $mysqli->close();
}

function confirmCode($code, $email) : bool {
    $mysqli = dbConnect();
    $return = false;
    $query = "select token, `timestamp` from token_table where email = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);

        if ($row !== null) {
            if ($row[0] === $code && time() < $row[1]) {
                $query = "update token_table set timestamp = '0' where email = ?";
                if ($update_stmt = $mysqli->prepare($query)) {
                    $update_stmt->bind_param("s", $email);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                $return = true;
            }
        }
        $stmt->close();
    } else {
        echo $mysqli->error;
    }
    $mysqli->close();
    return $return;
}

function updatePassword($pass, $email){
    $mysqli = dbConnect();
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    $query = "update aUsers set password = ? where email = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ss", $pass, $email);
        $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
}

function resetPassword($pass, $email, $role) : bool
{
    $mysqli = dbConnect();
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    $result = false;
    $query = "update aUsers set password = ?, role = ? where email = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("sss", $pass, $role, $email);
        $result = $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
    return $result;
}

/**
 * Generates a random password of a specified length.
 *
 * @param int $length The desired length of the password.
 * @return string The generated password.
 */
function generateRandomPassword(int $length = 10): string
{
    // String containing all possible characters for the password
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";

    // Initialize an empty password string
    $password = '';

    // Get the length of the character string
    $charsLength = strlen($chars);

    // Loop to build the password character by character
    for ($i = 0; $i < $length; $i++) {
        // Generate a random index
        // mt_rand() is faster and more random than rand()
        $randomIndex = mt_rand(0, $charsLength - 1);

        // Append the character at the random index to the password
        $password .= $chars[$randomIndex];
    }

    return $password;
}

function getTotalAttendance($classID) : array
{
    $mysqli = dbConnect();
    $data = [];
    $query = "
        SELECT
            roll_number,
            (SELECT COUNT(DISTINCT day) FROM aAttendance WHERE subject = ?) AS total_class_days,
            SUM(CASE WHEN attendance = 1 THEN 1 ELSE 0 END) AS present_days,
            SUM(CASE WHEN attendance = 0 THEN 1 ELSE 0 END) AS absent_days,
            SUM(CASE WHEN attendance = -1 THEN 1 ELSE 0 END) AS leave_days,
            ROUND((SUM(CASE WHEN attendance = 1 THEN 1 ELSE 0 END) / (SELECT COUNT(DISTINCT day) FROM aAttendance WHERE subject = ?)) * 100, 2) AS attendance_percentage
        FROM
            aAttendance
        WHERE
            subject = ?
        GROUP BY
            roll_number 
        ORDER BY 
            roll_number asc
    ";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("sss", $classID, $classID, $classID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row;
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function getDailyAttendance($classID, $date) : array
{
    $mysqli = dbConnect();
    $data = [];
    $query = "
        SELECT
            roll_number,
            CASE
            WHEN attendance = 1 THEN 'present'
            WHEN attendance = 0 THEN 'absent'
            WHEN attendance = -1 THEN 'leave'
            ELSE 'unknown'
            END AS status
        FROM
            aAttendance
        WHERE
            subject = ? AND day = ?
        ORDER BY 
            roll_number asc
    ";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ss", $classID, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row;
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function getStudentsRank($classes) : Array
{
    $mysqli = dbConnect();
    $data = [];
    if (empty($classes)) {
        return $data;
    }

    $placeholders = implode(',', array_fill(0, count($classes), '?'));
    $query = "
        SELECT
            a.roll_number,
            a.subject,
        ROUND(
        (SUM(CASE WHEN a.attendance = 1 THEN 1 ELSE 0 END) /
        (
        SELECT COUNT(*)
        FROM aAttendance AS a2
        WHERE a2.subject = a.subject
        GROUP BY a2.roll_number
        ORDER BY COUNT(*) DESC
        LIMIT 1
        )) * 100, 2
        ) AS attendance_percentage
        FROM
        aAttendance AS a
        WHERE
        a.subject IN ($placeholders) 
        GROUP BY
        a.roll_number, a.subject
        ORDER BY
        attendance_percentage DESC
    ";

    if ($stmt = $mysqli->prepare($query)) {
        $types = str_repeat('s', count($classes));
        $stmt->bind_param($types, ...$classes);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row;
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function getClassesRank($classes) : Array
{
    $mysqli = dbConnect();
    $data = [];
    if (empty($classes)) {
        return $data;
    }

    $placeholders = implode(',', array_fill(0, count($classes), '?'));
    $query = "
        SELECT
            subject,
            ROUND(
            (SUM(CASE WHEN attendance = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2
            ) AS average_attendance_percentage
        FROM
            aAttendance
        WHERE
            subject IN ($placeholders)
        GROUP BY
            subject 
        ORDER BY
            average_attendance_percentage DESC 
    ";

    if ($stmt = $mysqli->prepare($query)) {
        $types = str_repeat('s', count($classes));
        $stmt->bind_param($types, ...$classes);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_array(MYSQLI_NUM)){
            $data[] = $row;
        }
        $stmt->close();
    }
    $mysqli->close();
    return $data;
}

function totalClassesOf($id, $role) : int{
    $mysqli = dbConnect();
    $count = 0;

    if ($role == 1) {
        $query = "select count(*) from aClasses where teacher = ?";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_array(MYSQLI_NUM);
            if ($row !== null) {
                $count = $row[0];
            }
            $stmt->close();
        } else {
            echo "Error counting classes: " . $mysqli->error;
        }
    }
    $mysqli->close();
    return $count;
}

function totalProgrammesOf($fID) : int{
    $mysqli = dbConnect();
    $count = 0;
    $query = "select count(*) from aProgramme where faculty = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $fID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $count = $row[0];
        }
        $stmt->close();
    }
    $mysqli->close();
    return $count;
}

function totalSubjectsOf($id, $role) : int{
    $mysqli = dbConnect();
    $count = 0;

    if ($role == 1) {
        $query = "SELECT COUNT(DISTINCT subject) FROM aClasses WHERE teacher = ?";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_array(MYSQLI_NUM);
            if ($row !== null) {
                $count = $row[0];
            }
            $stmt->close();
        } else {
            echo "Error counting classes: " . $mysqli->error;
        }
    }
    $mysqli->close();
    return $count;
}

function totalContentsOf($table) : int{
    $mysqli = dbConnect();
    $count = 0;

    $validTables = ['aUsers', 'aTeacher', 'aStudent', 'aSubject', 'aProgramme', 'Classes', 'aFaculty', 'ClassesInfo', 'Attendance', 'token_table'];
    if (!in_array($table, $validTables)) {
        $mysqli->close();
        return 0;
    }

    $query = "select count(*) from $table";
    if (!($result = $mysqli->query($query))) {
        echo "Error counting contents: " . $mysqli->error;
    } else {
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null) {
            $count = $row[0];
        }
        $result->close();
    }
    $mysqli->close();
    return $count;
}

function softDelete($table, $id) : bool
{
    $mysqli = dbConnect();
    $result = false;

    // Validate $table name against a whitelist here
    $validTables = ['Users', 'Teacher', 'Student', 'Subject', 'Programme', 'Classes', 'Faculty'];
    if (!in_array($table, $validTables)) {
        $mysqli->close();
        return false;
    }

    if ($table === 'Users') {
        $query = "update $table set deleted = '1', created_date = current_date where id = ?";
    } else {
        $query = "update $table set deleted = '1' where id = ?";
    }

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $id);
        $result = $stmt->execute();
        $stmt->close();
    } else {
        echo "Error deleting content: " . $mysqli->error;
    }
    $mysqli->close();
    return ($result);
}

function unassignClass($id) : bool
{
    $mysqli = dbConnect();
    $result = false;
    $query = "update Classes set deleted = '-1', created_date = current_date where teacher = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $id);
        $result = $stmt->execute();
        $stmt->close();
    } else {
        echo "Error deleting content: " . $mysqli->error;
    }
    $mysqli->close();
    return ($result);
}

function updateAttribute($tableName, $columnName, $newValue, $idName, $id) : bool
{
    $mysqli = dbConnect();
    $result = false;

    $validTables = ['aUsers', 'aTeacher', 'aStudent', 'aSubject', 'aProgramme', 'Classes', 'aFaculty', 'ClassesInfo', 'Attendance', 'token_table'];
    $validColumns = ['name', 'fname', 'lname', 'email', 'password', 'roll_number', 'deleted', 'teacher', 'subject', 'course_code', 'course_credit', 'timestamp', 'token', 'role', 'created_date', 'title', 'semester', 'programme', 'day', 'attendance', 'status'];

    if (!in_array($tableName, $validTables) || !in_array($columnName, $validColumns) || !in_array($idName, $validColumns)) {
        $mysqli->close();
        return false;
    }

    $query = "update $tableName set $columnName = ? where $idName = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ss", $newValue, $id);
        $result = $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
    return $result;
}