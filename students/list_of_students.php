<?php

/*
    Author: Wembo Otepa Mulumba
    Last Working Date: April-14-2019
    Description: This file echo out a jSON object student graduation status.
    File: test_json.php
*/

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

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "Thomas", "123456", "DB_II");
$result = $conn->query("SELECT * from students");

$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"SID":"'                       . $rs["SID"]                        . '",';
    $outp .= '"name":"'                       . $rs["name"]                       . '",';
    $outp .= '"IID":"'                        . $rs["IID"]                        . '",';
    $outp .= '"major":"'                      . $rs["major"]                      . '",';
    $outp .= '"degreeHeld":"'                 . $rs["degreeHeld"]                 . '",';
    $outp .= '"career":"'                     . $rs["career"]                     . '",';
    $outp .= '"GPA":"'                        . $rs["GPA"]                        . '",';
    $outp .= '"cumulative_credit":"'          . $rs["cumulative_credit"]          . '",';
    $outp .= '"algorithm":"'                  . $rs["algorithm"]                  . '",';
    $outp .= '"core_courses_credits":"'       . $rs["core_courses_credits"]       . '",';
    $outp .= '"elective_courses_credits":"'   . $rs["elective_courses_credits"]   . '",';
    $outp .= '"grade_below_B":"'              . $rs["grade_below_B"]              . '",';
    $outp .= '"con_class":"'                  . $rs["con_class"]                  . '",';
    $outp .= '"passing_status":"'             . $rs["passing_status"]             . '",';
    $outp .= '"reason":"'                     . $rs["reason"]                     . '"}';
}
$outp ='{"records":['.$outp.']}';
$conn->close();

echo($outp);
?>
