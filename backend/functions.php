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
        $query = "select password, id, role from aUsers where email = '$email'";
        $return = false;

        if ($result = $mysqli->query($query)){

            $row = $result->fetch_array(MYSQLI_NUM);
            if ($row !== null){
                if (password_verify($pass, $row[0]))
                    $return = $row;
            }
        }
        else{
            echo $mysqli->error;
        }
        /*
       $password = password_hash('password', PASSWORD_DEFAULT);
       $query = "
                insert into aUsers values(null, 'admin@nast.edu.np', '$password', '-1', '0');
       ";
       $mysqli->query($query);
        */
        $mysqli->close();
        return $return;
    }
    function deleteContentsOf($tableName, $identifier, $id){
        $mysqli = dbConnect();
        $query = "delete from $tableName where $identifier = '$id'";

        if ($mysqli->query($query) === TRUE)
            echo "successfully deleted";
        else
            echo "Error updating: " . $mysqli->error;
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
    $query = "
                update Classes set
                teacher = '51'
                where teacher = '$tID';
    ";

    $mysqli->query($query);

    $query = "delete from Users where id = '$tID'";
    $mysqli->query($query);
    $mysqli->close();
}

function recoverTeacher($tID)
{
    $mysqli = dbConnect();
    $query = "update Users set
                 deleted = '0',
                 created_date = current_date
                 where id = '$tID'";

    $mysqli->query($query);

    $query = "update Teacher set
                 deleted = '0'
                 where id = '$tID'";
    $mysqli->query($query);

    $query = "update Classes set
                 deleted = '0',
                 created_date = current_date
                 where teacher = '$tID' and deleted = '-1'";
    $mysqli->query($query);

    $mysqli->close();
}

function recoverStudent($sID)
{
    $mysqli = dbConnect();
    $query = "update Users set
                 deleted = '0',
                 created_date = current_date
                 where id = '$sID'";

    $mysqli->query($query);

    $query = "update Student set
                 deleted = '0'
                 where id = '$sID'";
    $mysqli->query($query);

    $mysqli->close();
}
function recoverClass($cID)
{
    $mysqli = dbConnect();

    $query = "update Classes set
                 deleted = '-1',
                 created_date = current_date
                 where id = '$cID'";

    $mysqli->query($query);
    $mysqli->close();
}

function deleteClass($cID) : bool
{
    $mysqli = dbConnect();
    $query = "update Classes set
                                deleted = 1,
                                created_date = current_date
                                where id = $cID 
    ";

    $result = $mysqli->query($query);
    $mysqli->close();
    return $result;
}

function deleteStudent($sID) : bool
{
    $mysqli = dbConnect();
    $query = "update Student set
                                deleted = '1'
                                where id = '$sID' 
    ";

    $result = $mysqli->query($query);

    $query = "update Users set
                                deleted = '1',
                                created_date = current_date
                                where id = '$sID' 
    ";

    $result = $mysqli->query($query);

    $mysqli->close();
    return $result;
}

    function getID($tableName){
        $mysqli = dbConnect();

        if ($tableName != "aStudent") $query = "select id from $tableName";
        else $query = "select roll_number from $tableName";
        $result = $mysqli->query($query);

        /*--------- numeric array ---------*/
        while ($row = $result->fetch_array(MYSQLI_NUM)){
           $data[] = $row[0];
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
       $query = "select $column from $tableName where $idName = '$id'";
       $result = $mysqli->query($query);
       $row = $result->fetch_array(MYSQLI_NUM);
       $mysqli->close();
       return $row[0];
    }

    /*
     * returns one row of data from table
     */
function getRow($tableName, $idName, $id){
    $mysqli = dbConnect();
    $query = "select * from $tableName where $idName = '$id'";
    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);
    $mysqli->close();
    return $row;
}

    /* getColumn($tableName, $column, $id)
       returns column $column , where table $tableName has multiple rows for id $id.
    */
function getColumn($tableName, $column, $id){
    $mysqli = dbConnect();
    $ID = $tableName === 'aStudent' ? 'roll_number' : 'id';// should be correct assignment
    $query = "select $column from $tableName where $ID = '$id'";
    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);
    $mysqli->close();
    return $row[0];
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

        $query = "select fname, lname from aStudent where roll_number = '$roll_number'";
        $result = $mysqli->query($query);

        /*--------- numeric array ---------*/
        while ($row = $result->fetch_array(MYSQLI_NUM)){
           $data = $row[0] . ' ' . $row[1];
        }
        $mysqli->close();
        return $data;
    }

    function getTeachersName($tID){
        $mysqli = dbConnect();

        $query = "select * from aTeacher where id = $tID";
        $result = $mysqli->query($query);

        /*--------- numeric array ---------*/
        while ($row = $result->fetch_array(MYSQLI_NUM)){
           $data = $row[1] . ' ' . $row[2];
        }
        $mysqli->close();
        return $data;
    }

    function getContentsOf($tableName){
        $mysqli = dbConnect();

        $query = "select * from $tableName";
        $result = $mysqli->query($query);

        /*--------- numeric array ---------*/
        while ($row = $result->fetch_array(MYSQLI_NUM)){
           $data[] = $row;
        }
        $mysqli->close();
        return $data;
    }

function getSubjectsOf($pID){
    $mysqli = dbConnect();

    $query = "SELECT * FROM aSubject
              WHERE programme = $pID;";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}


/*
 * to be replaced in place of geClassesOf() function later
 */


function getKlassesOf($ID, $user) : array{
    $mysqli = dbConnect();

    /*if ($user == 1)
        $query = "select * from Classes where teacher = '$teacher'";
    else*/ if ($user == 0)
        $query = "select class from ClassesInfo where roll_number = '$ID'";
    else
        $query = "select id from Classes where teacher = '$ID'";

    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    $data = [];
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row[0];
    }
    $mysqli->close();
    return $data;
}

    function getClassesOf($tID){
        $mysqli = dbConnect();

        $teacher = getTeachersName($tID);
        $query = "select * from Classes where teacher = '$teacher'";
        $result = $mysqli->query($query);

        /*--------- numeric array ---------*/
        while ($row = $result->fetch_array(MYSQLI_NUM)){
           $data[] = $row;
        }
        $mysqli->close();
        return $data;
    }

function classesOf($tID){
    $mysqli = dbConnect();

    $query = "select * from aClasses where teacher = '$tID'";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
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

    $query = "select roll_number, fname, lname from aStudent 
            where programme = '$programme' and semester = '$semester'";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getStudentsRollNumbersOfClass($cID){
    $mysqli = dbConnect();

    $query = "select roll_number from ClassesInfo 
            where class = '$cID' order by roll_number asc";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    $data = [];
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row[0];
    }
    $mysqli->close();
    return $data;
}

    function createTeacher($fname, $lname, $email, $password)
    {
        // S E N D    E M A I L   T O   T E A C H E R   O N   S U C C E S S F U L    C R E A T I O N

        $return = checkForDuplicateUser($email);

        if (is_bool($return)){

        $mysqli = dbConnect();
        $password = password_hash($password, PASSWORD_DEFAULT);

        // insert into aUsers table
        $query = "insert into aUsers 
        values(null, '$email', '$password', '2', current_date)";
        $mysqli->query($query);

        // insert into aTeacher table
        $id = getAttribute('aUsers', 'id','email', $email);
        $query = "insert into aTeacher values('$id', '$fname', '$lname', 'Lecturer')";
        $mysqli->query($query);

        // insert teacher into token_table
        $query = "insert into token_table values('$email', '000000', '0')";
        $mysqli->query($query);
        $mysqli->close();
        }

        return $return;
    }

function checkForDuplicateUser($email){

    $mysqli = dbConnect();
    $return = true;
    $query = "select * from aUsers where email = '$email'";

    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);
    if ($row == null){

        $query = "select * from Users where email = '$email'";

        $result = $mysqli->query($query);
        $row = $result->fetch_array(MYSQLI_NUM);

        if ($row !== null)
            $return = "There's a deleted account associated with id $email.Consider either recovering it or delete it permanently.";
    } else {
        $return = "There's already an account associated with id $email";
    }
    $mysqli->close();
    return $return;
}

function checkForDuplicateRollNumber($roll_number){

    $mysqli = dbConnect();
    $return = true;
    $query = "select * from aStudent where roll_number = '$roll_number'";

    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);
    if ($row == null){

        $query = "select * from Student where roll_number = '$roll_number'";

        $result = $mysqli->query($query);
        $row = $result->fetch_array(MYSQLI_NUM);

        if ($row !== null)
            $return = "There's a deleted student associated with id $roll_number.Consider either recovering it or delete it permanently.";
    } else {
        $return = "There's already a student associated with id $roll_number";
    }
    $mysqli->close();
    return $return;
}

function checkForDuplicateSubjects($sname, $pID){

    $mysqli = dbConnect();

    $return = true;
    $query = "select * from aSubject where name = '$sname' and programme = '$pID'";

    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);
    if ($row == null){

        $query = "select * from Subject where name = '$sname' and programme = '$pID'";

        $result = $mysqli->query($query);
        $row = $result->fetch_array(MYSQLI_NUM);

        if ($row !== null)
            $return = "There's a deleted subject associated with id $sname in the same programme.Consider either recovering it or delete it permanently.";
    } else {
        $return = "There's already a subject associated with id $sname in the same programme";
    }
    $mysqli->close();
    return $return;
}

   function createSubject($sname, $pID, $semester, $ccode, $ccredit){

       $result = checkForDuplicateSubjects($sname, $pID);

       if (is_string($result))
           return $result;

       else {

       $mysqli = dbConnect();
       $query = "insert into aSubject values(null, '$sname', '$pID', '$semester', '$ccode', '$ccredit')";

       $result = $mysqli->query($query);
       $mysqli->close();
       return $result;
       }
   }

   function getPname($programme) {

    $mysqli = dbConnect();

    $query = "
                select id from aProgramme where name = '$programme'
    ";

    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);

    if ($row !== null)
        return $row[0];
    else
        return false;
   }

function getFID($faculty) {

    $mysqli = dbConnect();

    $query = "
                select id from aFaculty where name = '$faculty'
    ";

    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);

    if ($row !== null)
        return $row[0];
    else
        return false;
}

  function createStudent($fname, $lname, $roll_number, $email, $pass, $semester, $programme)
  {
      $return = checkForDuplicateUser($email);

      if (is_bool($return)) {
          $return = checkForDuplicateRollNumber($roll_number);
          if (is_bool($return)) {

              $mysqli = dbConnect();
              // insert into aUsers table
              $pass = password_hash($pass, PASSWORD_DEFAULT);
              $query = "insert into aUsers values(null, '$email', '$pass', '3', current_date)";

              $mysqli->query($query);

              // insert into aStudent table
              $sID = getAttribute('aUsers', 'id', 'email', $email);
              $query = "insert into aStudent values('$sID', '$roll_number', '$fname', '$lname', '$semester', '$programme', 'Student')";
              $mysqli->query($query);

              // insert student into token_table
              $query = "insert into token_table values('$email', '000000', '0')";
              $return = $mysqli->query($query);
              $mysqli->close();
          }
      }

      return $return;

  }

  function createClass($subjectID, $teacherID)
  {
      $mysqli = dbConnect();

      $query = "insert into aClasses values(null, '$subjectID', '$teacherID', current_date)";

      try{

          $result = $mysqli->query($query);
      } catch (mysqli_sql_exception $e){

          if ($e->getCode() === 1062) {
              $result = 'Cannot Create duplicate class for same subject!';
          } else {
              $result = 'An unexpected error occurred.';
          }
      } finally {

          $mysqli->close();
      }
      //if (!$result) echo "Error creating new class: " . $mysqli->error;

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
    foreach ($roll_numbers as $roll_number) {
        $query = "insert into ClassesInfo values(null, '$roll_number', '$cID')";

        $result = $mysqli->query($query);

        $query = "update Attendance set
                    deleted = '0' 
                    where roll_number = '$roll_number' and class = '$cID'";

        $result = $mysqli->query($query);
    }
    $mysqli->close();
    return $result;
}


function removeStudents($cID, $roll_numbers) : bool{

    $mysqli = dbConnect();
    $result = true;
    foreach ($roll_numbers as $roll_number) {
        $query = "delete from ClassesInfo where roll_number = '$roll_number' and class = '$cID'";

        $result = $mysqli->query($query);

        $query = "update Attendance set
                    deleted = '1' 
                    where roll_number = '$roll_number' and class = '$cID'";

        $result = $mysqli->query($query);
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

        for ($j = 0; $j < $i; ++$j){
        if ($pnames[$j] === "delete") {
          $query = "delete from aProgramme where id = '$id[$j]'";

            if ($mysqli->query($query) === TRUE)
            echo "successfully deleted";
            else
                echo "Error updating: " . $mysqli->error;
            }
            else  {
            $query = "update aProgramme set name = '$pnames[$j]' where id = '$id[$j]'";
        if ($mysqli->query($query) === TRUE)
            echo "successful";
            else
                echo "Error updating: " . $mysqli->error;
            }
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

        for ($j = 0; $j < $i; ++$j){
        if ($snames[$j] === "delete") {
          $query = "delete from aSubject where id = '$id[$j]'";

            if ($mysqli->query($query) === TRUE)
            echo "successfully deleted $id[$j]";
            else
                echo "Error updating: " . $mysqli->error;
            }
            else  {
                $query = [];
                $query[] = "update aSubject set name = '$snames[$j]' where id = '$id[$j]'";
                $query[] = "update aSubject set course_code = '$ccodes[$j]' where id = '$id[$j]'";
                $query[] = "update aSubject set course_credit = '$ccredits[$j]' where id = '$id[$j]'";

            foreach ($query as $q){
                if ($mysqli->query($q[0]) === TRUE)
                    echo "successful";
                else
                    echo "Error updating: " . $mysqli->error;
                }
            }
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

        $query = "update aTeacher set 
                    fname = '$fname',
                    lname = '$lname',
                    title = '$title'
                    where id = $id
                    ";
        if ($mysqli->query($query) === TRUE)
                    echo "successfully updated teacher";
       else
                    echo "Error updating: " . $mysqli->error;

       $query = "update aUsers set 
                    email = '$email'
                    where id = $id
                    ";
       if ($mysqli->query($query) === TRUE)
           echo "successfully updated User";
       else
           echo "Error updating: " . $mysqli->error;
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
	   $faculties = $_POST['programme'];

        $mysqli = dbConnect();
        $id = getID("aStudent");
        $i = count($id);

        for ($j = 0; $j < $i; ++$j){
        if ($rolls[$j] === "delete") {
            deleteContentsOf('aStudent', 'roll_number', $id[$j]);
            deleteContentsOf('aUsers', 'email', $emails[$j]);
            deleteContentsOf('token_table', 'email', $emails[$j]);// because id is not same for aTeacher table & aUsers table!
            }
            else  {
                $query = [];
            $query[] = "update aStudent set roll_number = '$rolls[$j]' where roll_number = '$id[$j]'";
            $query[] = "update aStudent set fname = '$fnames[$j]' where roll_number = '$id[$j]'";
            $query[] = "update aStudent set lname = '$lnames[$j]' where roll_number = '$id[$j]'";
            $query[] = "update aStudent set email= '$emails[$j]' where roll_number = '$id[$j]'";
            $query[] = "update aStudent set password= '$passwords[$j]' where roll_number = '$id[$j]'";
            foreach ($query as $q){
                if ($mysqli->query($q) === TRUE)
                    echo "successful";
                else
                    echo "Error updating: " . $mysqli->error;
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

        for ($j = 0; $j < $i; ++$j){
        if ($delete[$j] === 'true') { // because tname == '' for <select> instead of 'delete'
          $query = "delete from Classes where id = '$id[$j]'";

            if ($mysqli->query($query) === TRUE){
                echo "successfully deleted";
                // drop table class from database !!!
                //$id = '`' . $id . '`';
                //$query = "drop table $id";
                //if ($mysqli->query($query) === TRUE)
                //    echo "table $id successfully deleted";
                //else
                //    echo "Error deleting table: " . $mysqli->error;
            }
            else
                echo "Error updating: " . $mysqli->error;
            }
            else  {
            $query = "update Classes set teacher = '$tname[$j]' where id = '$id[$j]'";
                if ($mysqli->query($query) === TRUE)
                    echo "successful";
                else
                    echo "Error updating: " . $mysqli->error;
                }
            }
	$mysqli->close();
   }



if (isset($_POST['edit_myClass'])) {
    $classID = $_POST['class'];
    $roll = $_POST['roll'];
    $delete = $_POST['delete'];

    $i = count($roll);
    if (is_int($classID))
        $classID = '`' . $classID . '`';

    for ($j = 0; $j < $i; ++$j){
        if ($delete[$j] === 'true')
            deleteContentsOf($classID, "roll_number", $roll[$j]);
    }
}

function takeAttendance($cID, $day, $roll, $attendance) : bool {
    $mysqli = dbConnect();

    $query = "insert into aAttendance values('$roll', '$day', '$cID', '$attendance', null)";
    $result = $mysqli->query($query);
    $mysqli->close();
    return $result;
}

function attendance_taken($classID) : bool{

    $day = date('Y-m-d');//$_POST['day'];
    $mysqli = dbConnect();
    $query = "select `day` from aAttendance where subject = '$classID' and `day` = '$day'";

    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);
    if ($row !== null)
        $return = true;
    else
        $return = false;
    $mysqli->close();
    return $return;
}

function send_code($email): bool
{
    $mysqli = dbConnect();
   $query = "select email from token_table where email = '$email'";

   $result = $mysqli->query($query);
   $row = $result->fetch_array(MYSQLI_NUM);
   if ($row !== null)
       $return = true;
   else
       $return = false;
   $mysqli->close();
   return $return;
}

function getCode($email){
    $mysqli = dbConnect();
    try {
        $code = random_int(100000, 999999);
    }
    catch (\Random\RandomException $e) {
        echo $e->getTraceAsString();
    }
   $expiryTime = time() + 60 * 30;// 30 minutes
   $query = "update token_table
             set
                token = '$code',
                timestamp = '$expiryTime'
             where
                   email = '$email'
             ";
   if ($mysqli->query($query)){
       $mysqli->close();
       return $code;
   }
   else
       echo "error updating token_table" . $mysqli->error;
   $mysqli->close();
}

function confirmCode($code, $email) : bool {

    $mysqli = dbConnect();
    $query = "select token, `timestamp` from token_table where email = '$email'";

    if ($result = $mysqli->query($query)){

        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row !== null){
            if ($row[0] === $code && time() < $row[1]){

                // remove timestamp to prevent multiple use of the code
                $query = "update token_table
                                        set
                                            timestamp = '0'
                                        where
                                            email = '$email'
                         ";
                $mysqli->query($query);
                $mysqli->close();
                return true;
            }
            else{
                $mysqli->close();
                return false;
            }
        }
        else
            return false;
    }
    else
        echo $mysqli->error;
    $mysqli->close();
    return false;
}

function updatePassword($pass, $email){
   $mysqli = dbConnect();
   $pass = password_hash($pass, PASSWORD_DEFAULT);
   $query = "update aUsers
                      set
                         password = '$pass'
                      where email = '$email'
        ";
   $mysqli->query($query);

   $mysqli->close();
}

function resetPassword($pass, $email, $role) : bool
{
    $mysqli = dbConnect();
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    $query = "update aUsers
                      set
                         password = '$pass',
                         role = '$role'
                      where email = '$email'
        ";
    $result = $mysqli->query($query);

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

    $query = "
                SELECT
                roll_number,
                (SELECT COUNT(DISTINCT day) FROM aAttendance WHERE subject = '$classID') AS total_class_days,
                SUM(CASE WHEN attendance = 1 THEN 1 ELSE 0 END) AS present_days,
                SUM(CASE WHEN attendance = 0 THEN 1 ELSE 0 END) AS absent_days,
                SUM(CASE WHEN attendance = -1 THEN 1 ELSE 0 END) AS leave_days,
                ROUND((SUM(CASE WHEN attendance = 1 THEN 1 ELSE 0 END) / (SELECT COUNT(DISTINCT day) FROM aAttendance WHERE subject = '$classID')) * 100, 2) AS attendance_percentage
                FROM
                aAttendance
                WHERE
                subject = '$classID'
                GROUP BY
                roll_number 
                ORDER BY 
                roll_number asc
    ";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getDailyAttendance($classID, $date) : array
{
    $mysqli = dbConnect();

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
                subject = '$classID' AND day = '$date'
                ORDER BY 
                roll_number asc
    ";
    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getStudentsRank($classes) : Array
{
   $mysqli = dbConnect();
   $classIds = implode(',', $classes);

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
                a.subject IN ($classIds) 
                GROUP BY
                a.roll_number, a.subject
                ORDER BY
                attendance_percentage DESC 
       ";

    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    $data = [];
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function getClassesRank($classes) : Array
{
    $mysqli = dbConnect();
    $classIds = implode(',', $classes);

    $query = "
                SELECT
                subject,
                ROUND(
                (SUM(CASE WHEN attendance = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2
                ) AS average_attendance_percentage
                FROM
                aAttendance
                WHERE
                subject IN ($classIds)
                GROUP BY
                subject 
                ORDER BY
                average_attendance_percentage DESC 
       ";

    $result = $mysqli->query($query);

    /*--------- numeric array ---------*/
    $data = [];
    while ($row = $result->fetch_array(MYSQLI_NUM)){
        $data[] = $row;
    }
    $mysqli->close();
    return $data;
}

function totalClassesOf($id, $role) : int{
   if ($role == 1)
       $query = "select count(*) from aClasses where teacher = '$id'";
   $mysqli = dbConnect();

    if (!($result = $mysqli->query($query)))
        echo "Error counting classes: " . $mysqli->error;
    $row = $result->fetch_array(MYSQLI_NUM);
    $mysqli->close();
    return $row[0];
}

function totalProgrammesOf($fID) : int{
    $mysqli = dbConnect();
    $query = "select count(*) from aProgramme where faculty = '$fID'";
    $result = $mysqli->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);
    $mysqli->close();
    return $row[0];
}

function totalSubjectsOf($id, $role) : int{
    if ($role == 1)
        $query = "SELECT COUNT(DISTINCT subject)
                    FROM aClasses
                    WHERE teacher = '$id';";
    $mysqli = dbConnect();

    if (!($result = $mysqli->query($query)))
        echo "Error counting classes: " . $mysqli->error;
    $row = $result->fetch_array(MYSQLI_NUM);
    $mysqli->close();
    return $row[0];
}

function totalContentsOf($table) : int{
    $mysqli = dbConnect();
    $query = "select count(*) from $table";

    if (!($result = $mysqli->query($query)))
        echo "Error counting contents: " . $mysqli->error;
    $row = $result->fetch_array(MYSQLI_NUM);
    $mysqli->close();
    return $row[0];
}

function softDelete($table, $id) : bool
{
   $mysqli = dbConnect();
   if ($table === 'Users')
       $query = "update $table set
                 deleted = '1',
                 created_date = current_date
                 where id = '$id'";
   else
       $query = "update $table set
                 deleted = '1'
                 where id = '$id'";


   if (!($result = $mysqli->query($query)))
        echo "Error deleting content: " . $mysqli->error;
    $mysqli->close();
   return ($result);
}

function unassignClass($id) : bool
{
    $mysqli = dbConnect();
    $query = "update Classes set
                 deleted = '-1',
                 created_date = current_date
                 where teacher = '$id'
   ";

    if (!($result = $mysqli->query($query)))
        echo "Error deleting content: " . $mysqli->error;
    $mysqli->close();
   return ($result);
}

function updateAttribute($tableName, $columnName, $newValue, $idName, $id) : bool
{
   $mysqli = dbConnect();
   $query = "update $tableName set
                                $columnName = '$newValue'
                                where $idName = '$id' 
   ";

   $result = $mysqli->query($query);
   $mysqli->close();
   return $result;
}
