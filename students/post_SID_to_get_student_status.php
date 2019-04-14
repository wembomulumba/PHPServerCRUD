<?php
/**************************************************

Author: Wembo Otepa Mulumba
Last Working Date: April-14-2019
Description: PHP & MySQL backend to update and explain
             student passing status.
File: post_SID_to_get_student_status

**************************************************/
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

$SID = mysql_real_escape_string($dataObject->SID);

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

/* Calculating graduate GPA  */

$sql = "SELECT grade FROM enrollment WHERE SID = $SID and CID >= 915000";
$result = mysql_query($sql) or die(mysql_error());

$counter = 0;
$grade_point = 0;

while($row = mysql_fetch_array($result)){
	if($row['grade'] == 'A'){
	    $grade_point += 4;
        $counter++;
	}
	else if($row['grade'] == 'A-'){
	    $grade_point += 3.7;
        $counter++;
	}
	else if($row['grade'] == 'B+'){
	    $grade_point += 3.3;
        $counter++;
    }
    else if($row['grade'] == 'B'){
	    $grade_point += 3.0;
        $counter++;
    }
    else if($row['grade'] == 'B-'){
	    $grade_point += 2.7;
        $counter++;
    }
    else if($row['grade'] == 'C+'){
	    $grade_point += 2.3;
        $counter++;
    }
    else if($row['grade'] == 'C'){
	    $grade_point += 2.0;
        $counter++;
    }
    else if($row['grade'] == 'C-'){
	    $grade_point += 1.7;
        $counter++;
    }
    else if($row['grade'] == 'D+'){
	    $grade_point += 1.3;
        $counter++;
    }
    else if($row['grade'] == 'D'){
	    $grade_point += 1;
        $counter++;
    }
    else if($row['grade'] == 'F'){
	    $grade_point += 0;
        $counter++;
    }
}

/* End of calculating graduate GPA  */

/* Calculating cumulative credit taken */
$sql = "SELECT COUNT(grade) FROM enrollment WHERE SID = $SID and CID >= 915000";

$result = mysql_query($sql) or die(mysql_error());
$counting_classes_taken = mysql_fetch_row($result);

$cumulative_credit = (int)$counting_classes_taken[0] * 3;
$cumulative_credit = (string)$cumulative_credit;

$avg = $grade_point/$counter;
/* End of Calculating cumulative credit taken */



$sql = "UPDATE students
        SET GPA = ".$avg." , cumulative_credit = " . $cumulative_credit . "
        WHERE SID = $SID ";
mysql_query($sql);

$sql = "SELECT CID FROM enrollment
        WHERE  SID  = $SID AND CID = 915030";
$result = mysql_query($sql) or die(mysql_error());



/************Validation if Algorithm taken*************/

$check_if_taken_algorithm = mysql_fetch_row($result);
if(mysql_num_rows($result)== 0){
   echo "Haven't take Algorithm yet";
   $sql = "UPDATE students
           SET algorithm = 'Have not taken algorithm', cumulative_credit = " . $cumulative_credit . "
           WHERE SID = $SID ";
   mysql_query($sql);

   $sql = "Select c.CID, c.groupID
           from enrollment e, courses c
           where e.SID = $SID and c.CID = e.CID and e.CID>=915000";

   $result = mysql_query($sql) or die(mysql_error());

   /* Check if all core classes are taken */

   $group2 = false;
   $group3 = false;
   $group4 = false;

   while($row = mysql_fetch_array($result)){

       if($row['groupID'] == 2){
           $group2 = true;
       }
       else if($row['groupID'] == 3){
           $group3 = true;
       }
       else if($row['groupID'] == 4){
           $group4 = true;
       }
   }

   $arr = array($group2, $group3, $group4);

   $cc = 0;
   foreach ($arr as &$value) {
       if($value == true){
          $cc++;
       }
   }

   $cc = $cc * 3;

   $elective = (int)$cumulative_credit - $cc;
   $elective = (string)$elective;
   $cc = (string)$cc;

   $sql = "UPDATE students
           SET core_courses_credits = $cc
           WHERE SID = $SID ";
   mysql_query($sql);

   $sql = "UPDATE students
           SET elective_courses_credits = $elective
           WHERE SID = $SID ";
   mysql_query($sql);

}
else{
   echo "Algorithm taken";
        $sql = "UPDATE students
              SET algorithm = 'Algorithm taken', cumulative_credit = " . $cumulative_credit . "
              WHERE SID = $SID ";
        mysql_query($sql);

        $sql = "Select c.CID, c.groupID
                from enrollment e, courses c
                where e.SID = $SID and c.CID = e.CID and e.CID>=915000";

        $result = mysql_query($sql) or die(mysql_error());

        /* Check if all core classes are taken */

        $group2 = false;
        $group3 = false;
        $group4 = false;

        while($row = mysql_fetch_array($result)){

            if($row['groupID'] == 2){
                $group2 = true;
            }
            else if($row['groupID'] == 3){
                $group3 = true;
            }
            else if($row['groupID'] == 4){
                $group4 = true;
            }
        }

        /* Update if core courses are complete or incomplete and display elective credits*/
        if($group2 && $group3 && $group4){
            $sql = "
                    UPDATE students
                    SET core_courses_credits = 12
                    WHERE SID = $SID ";
            mysql_query($sql);


            $sql = "
                    SELECT COUNT(grade)
                    FROM enrollment
                    WHERE SID = $SID AND CID >= 915000";
            $result = mysql_query($sql) or die(mysql_error());
            $elective_courses = mysql_fetch_row($result);

            $elective_courses_credits = ((int)$elective_courses[0] - 4) * 3;
            $elective_courses_credits = (string)$elective_courses_credits;

            $sql = "UPDATE students
                    SET elective_courses_credits = $elective_courses_credits
                    WHERE SID = $SID";
            mysql_query($sql);
        }
        else{
            $arr = array($group2, $group3, $group4);

            $cc = 1;
            foreach ($arr as &$value) {
               if($value == true){
                  $cc++;
               }
            }

            $cc = $cc * 3;

            $elective = (int)$cumulative_credit - $cc;
            $elective = (string)$elective;
            $cc = (string)$cc;

            $sql = "UPDATE students
                   SET core_courses_credits = $cc
                   WHERE SID = $SID ";

            mysql_query($sql);


            $sql = "UPDATE students
                   SET elective_courses_credits = $elective
                   WHERE SID = $SID ";

            mysql_query($sql);
        }
}


$sql = "SELECT COUNT(grade) FROM enrollment WHERE SID = $SID AND grade > 'B+' AND CID >= 915000";
$result = mysql_query($sql) or die(mysql_error());
$grade_below_b = mysql_fetch_row($result);

if( $grade_below_b[0] > 2){
    $sql = "UPDATE students
           SET grade_below_B = 0
           WHERE SID = $SID ";
    mysql_query($sql);
}else{
    $sql = "UPDATE students
           SET grade_below_B = 1
           WHERE SID = $SID ";
    mysql_query($sql);
}


/* Check Enrollment and Condition Table */
$sql1 = "SELECT CID
        FROM enrollment
        WHERE SID = $SID";

$result1 = mysql_query($sql1) or die(mysql_error());
$sql2 = " SELECT CID FROM conditions WHERE SID = $SID";

$result2 = mysql_query($sql2) or die(mysql_error());
$sql3 = " select count(CID) from conditions where SID = $SID";

$result3 = mysql_query($sql3) or die(mysql_error());
$temp = mysql_fetch_row($result3);

$undergrad_class_flag = $temp[0];

$array4 = array();
$array5 = array();
while ($row = mysql_fetch_array($result1)){
    array_push($array4, $row["CID"]);
}
while ($row = mysql_fetch_array($result2)){
    array_push($array5, $row["CID"]);
}

$array2 = mysql_fetch_array($result2);
$array1length = count($array4);
$array2length = count($array5);

$array3em = $array4[0];
$array4em = $array4[11];


$ddd = count(array_intersect($array4, $array5));


if($undergrad_class_flag == count(array_intersect($array4, $array5))){
        $sql = "UPDATE students
               SET con_class = 'taken'
               WHERE SID = $SID ";
        mysql_query($sql);
}
else{
    $sql = "UPDATE students
           SET con_class = 'not taken'
           WHERE SID = $SID ";
    mysql_query($sql);
}


$sql = "UPDATE students
       SET passing_status = 'Passing'
       WHERE SID = $SID";
mysql_query($sql);


$sql = "SELECT *
        FROM students
        WHERE SID = $SID";

$result = mysql_query($sql) or die(mysql_error());
$reason = " ";
while ($row = mysql_fetch_array($result)){

    if($row["GPA"] < 3.0){
            $passing_status = 0;
            $sql = "UPDATE students
                   SET passing_status = 'Not Passing'
                   WHERE SID = $SID";
            mysql_query($sql);

            $reason =  $reason . "GPA Less than 3.0";
        }


    if($row["cumulative_credit"] < 30){
        $passing_status = 0;
        $sql = "UPDATE students
               SET passing_status = 'Not Passing'
               WHERE SID = $SID";
        mysql_query($sql);

        $reason =  $reason . "cumulative credit less than 30, ";
    }
    if($row["algorithm"] == "Have not taken"){
        $passing_status = 0;
        $sql = "UPDATE students
               SET passing_status = 'Not Passing'
               WHERE SID = $SID";
        mysql_query($sql);
        $reason = $reason . "haven't taken algorithm, ";
    }

    if($row["core_courses_credits"] < 12){
        $passing_status = 0;
        $sql = "UPDATE students
               SET passing_status = 'Not Passing'
               WHERE SID = $SID";
        mysql_query($sql);
        $reason = $reason . "core courses less than 12 credits, ";
    }

    if($row["elective_courses_credits"] < 18){
         $passing_status = 0;
         $sql = "UPDATE students
                SET passing_status = 'Not Passing'
                WHERE SID = $SID";
         mysql_query($sql);
         $reason = $reason . "elective courses less than 18 credits, ";
    }

    if($row["grade_below_B"] == 0){
         $passing_status = 0;
         $sql = "UPDATE students
                SET passing_status = 'Not Passing'
                WHERE SID = $SID";
         mysql_query($sql);
         $reason = $reason . "More than 2 grades below a B, ";
    }

    if($row["con_class"] == "not taken"){
          $passing_status = 0;
          $sql = "UPDATE students
                 SET passing_status = 'Not Passing'
                 WHERE SID = $SID";
          mysql_query($sql);
          $reason = $reason . "Condition class have not been taken";
    }
}

if($reason == " "){
    $sql = "UPDATE students
            SET reason = 'Passing Status'
            WHERE SID = $SID";
    mysql_query($sql);
}else{
    $sql = "UPDATE students
            SET reason = '$reason'
            WHERE SID = $SID";
    mysql_query($sql);
}

/***************** Checking student status on passing and tell the reason why *****************/

mysql_close($conn);
?>
