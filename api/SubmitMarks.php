<?php

include("dbConnect.php");

$input = file_get_contents('php://input');
$decode = json_decode($input, true);

$TotalMarks = (int)$decode['TotalMarks'];
$file_id = (int)$decode['file_id'];
$vidhanSbha = $decode['vidhanSbha'];
$Cname = $decode['Cname'];

session_start();
$teac_id = $_SESSION["teacher_id"] ;

    $sql_main =  "SELECT * from `teachers_marks` WHERE `Assign_file_id` = $file_id;";
    $result_main = mysqli_query($conn,$sql_main);

    if(mysqli_num_rows($result_main) == 0){
        $avgMarks = $TotalMarks/3;
      $sql = "INSERT INTO `teachers_marks`(`teachers_1_marks`, `Assign_file_id`, `teacher_1_id`,`Comp_name`,`vidhansabha`,`average_mark`) VALUES ($TotalMarks, $file_id, $teac_id,'$Cname','$vidhanSbha',$avgMarks);";
  
      $result = mysqli_query($conn, $sql);
      $sql = "UPDATE  `student_teacher_mapping` SET `work_completed` = 1 where `student_id`=$file_id AND `teacher_id`=$teac_id";
  
      $result = mysqli_query($conn, $sql);


  
      if($result){
            echo json_encode("success");
      }
    }

    else{

        $row=mysqli_fetch_assoc($result_main);

        if ($row['teachers_2_marks']==0) {
            $temp=$row['teachers_1_marks']+$TotalMarks;
            $avgMarks = $temp/3;
            $sql = "UPDATE  `teachers_marks` SET `teachers_2_marks` = $TotalMarks,`teacher_2_id`=$teac_id,`average_mark`=$avgMarks where `Assign_file_id`=$file_id";
            $res = mysqli_query($conn, $sql);

            $sql = "UPDATE  `student_teacher_mapping` SET `work_completed` = 1 where `student_id`=$file_id AND `teacher_id`=$teac_id";
  
            $result = mysqli_query($conn, $sql);
    
            echo json_encode("success");
        }

        else{
            $temp=$row['teachers_1_marks']+$TotalMarks+$row['teachers_2_marks'];
            $avgMarks = $temp/3;
            $sql = "UPDATE  `teachers_marks` SET `teachers_3_marks` = $TotalMarks,`teacher_3_id` = $teac_id,`average_mark`=$avgMarks where `Assign_file_id`=$file_id";
            $res = mysqli_query($conn, $sql);
    
            $sql = "UPDATE  `student_teacher_mapping` SET `work_completed` = 1 where `student_id`=$file_id AND `teacher_id`=$teac_id";
  
            $result = mysqli_query($conn, $sql);
            
            echo json_encode("success");

        }

    }




?>