<?php
include("dbConnect.php");

$input = file_get_contents('php://input');
$decode = json_decode($input, true);
$teacherID = (int)$decode['teacherID'];

$sqli = "SELECT * FROM `teachers` where `teacher_id`=$teacherID";
$res = mysqli_query($conn, $sqli);
$row=mysqli_fetch_assoc($res);
$teacher_assign_work=(int)$row['work_assigned'];

$sqli = "SELECT * FROM `student_teacher_mapping` where `teacher_id`=$teacherID AND `work_completed`= 1";
$res = mysqli_query($conn, $sqli);
// $row=mysqli_fetch_assoc($res);
$complete_assign_work= mysqli_num_rows($res);

$sqli = "SELECT * FROM `student_teacher_mapping` where `teacher_id`=$teacherID AND `work_completed`= 0";
$res = mysqli_query($conn, $sqli);


if(mysqli_num_rows($res)>0){

    while ($row = mysqli_fetch_assoc($res)) {
        $file_id[] = $row['student_id'];
    }
    
    $ids = join("','",$file_id); 
    
    $sqli = "SELECT * FROM `entries` where `file_id` in ('$ids')";
    $res = mysqli_query($conn, $sqli);
    
    
    
    while ($row = mysqli_fetch_assoc($res)) {
        $user_info[] = array($row['Name'],$row['mobileNumber'],$row['compName'],$row['vidhansabha'],$row['filename']);
    }
    
    
    echo json_encode(array($teacher_assign_work,$complete_assign_work,$user_info));
    
}

else{
    echo json_encode(array($teacher_assign_work,$complete_assign_work,"NULL"));
}



?>