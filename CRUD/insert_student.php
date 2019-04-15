<?php
/*
    Author: Wembo Otepa Mulumba
    Last Working Date: April-11-2019
    Description: This file insert new student.
    File: insert_json.php
*/

// Access-Control
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


$postData = file_get_contents("php://input");
$dataObject = json_decode($postData);

$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

// Create connection
$conn = mysql_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}

mysql_select_db($dbname, $conn);

// Escape user inputs for security
$SID = mysql_real_escape_string($dataObject->SID);
$name = mysql_real_escape_string($dataObject->name);
$IID = mysql_real_escape_string($dataObject->IID);
$major = mysql_real_escape_string($dataObject->major);
$degreeHeld = mysql_real_escape_string($dataObject->degreeHeld);
$career = mysql_real_escape_string($dataObject->career);

// sql statemnet to insert data
$sql = "INSERT INTO `students` (`SID`, `name`, `IID`, `major`, `degreeHeld`, `career`) VALUES
        (" . $SID . ", '" .$name. "'," .$IID. ", '" .$major. "', '" . $degreeHeld . "', '" . $career. "')";

mysql_query($sql); // execute the query
mysql_close($conn); // close the connection
?>
