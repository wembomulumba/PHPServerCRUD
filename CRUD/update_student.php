<?php
/*
   Author: Wembo Otepa Mulumba
    Last Working Date: April-14-2019
    Description: This file update student.
    File: update_student.php
*/

 
// Access-Control Settings to allow clients to read/write from this server
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

// mysql database security settings
$servername = "localhost";
$username = "Thomas";
$password = "123456";
$dbname = "DB_II";

// Create connection
$conn = mysql_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}

mysql_select_db($dbname, $conn);

// Escape user inputs for security
$SID = mysql_real_escape_string($dataObject->SID);
$IID = mysql_real_escape_string($dataObject->IID);
$major = mysql_real_escape_string($dataObject->major);

$sql = "UPDATE students
        SET IID = $IID, major = '$major'
        WHERE SID = $SID ";

mysql_query($sql);
// close the connection
mysql_close($conn);
?>
