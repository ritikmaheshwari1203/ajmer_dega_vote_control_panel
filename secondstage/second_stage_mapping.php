<?php
include("../api/dbConnect.php");

$input = file_get_contents('php://input');
$decode = json_decode($input, true);


$filecount=(int)$decode['filecount'];
$compname=$decode['compname'];
$FirstTeacherID=(int)$decode['FirstTeacherID'];
$SecondTeacherID=(int)$decode['SecondTeacherID'];
$ThirdTeacherID=(int)$decode['ThirdTeacherID'];
$assigned=(int)$decode['assigned'];

$sqli = "SELECT * FROM `teachers_marks` WHERE `Comp_name`='$compname' ORDER BY `average_mark` DESC LIMIT $filecount";
$res = mysqli_query($conn, $sqli);

while ($row=mysqli_fetch_assoc($res)) {
    $file_id[] = $row['Assign_file_id'];
    // $file_path_info=array$row['Comp_name']
}

$ids = join("','",$file_id);

$sqli = "SELECT * FROM `teachers_marks` where `Comp_name`='$compname' AND `assign_to_second_stage`=0 AND `Assign_file_id` IN ('$ids') LIMIT $assigned ";
$res = mysqli_query($conn, $sqli);

while ($row=mysqli_fetch_assoc($res)) {
    $update_file_id[] = $row['Assign_file_id'];
    // $file_path_info=array$row['Comp_name']
}


    for ($i=0; $i < count($update_file_id); $i++) { 
        # code...
        // $id=$list_Student_list[]
        $sqli_1 = "INSERT INTO `second_stage_mapping`(`student_id`, `teacher_id`) VALUES ($update_file_id[$i], $FirstTeacherID)";
        $res = mysqli_query($conn, $sqli_1);
        $sqli_2 = "INSERT INTO `second_stage_mapping`(`student_id`, `teacher_id`) VALUES ($update_file_id[$i], $SecondTeacherID)";
        $res = mysqli_query($conn, $sqli_2);
        $sqli_3 = "INSERT INTO `second_stage_mapping`(`student_id`, `teacher_id`) VALUES ($update_file_id[$i], $ThirdTeacherID)";
        $res = mysqli_query($conn, $sqli_3);
        
        $sqli_4 = "UPDATE `teachers_marks` SET assign_to_second_stage = 1 where `Assign_file_id`=$update_file_id[$i]";
        $res = mysqli_query($conn, $sqli_4);
    }

    $sql="SELECT * FROM `teachers` where  `teacher_id`=$FirstTeacherID";
    $res = mysqli_query($conn, $sql);
    $row=mysqli_fetch_assoc($res);
    $new_value = $row['work_assigned']+$assigned;
    $sqli_5 = "UPDATE  `teachers` SET work_assigned = $new_value where `teacher_id`=$FirstTeacherID";
    $res = mysqli_query($conn, $sqli_5);


    
    $sql="SELECT * FROM `teachers` where  `teacher_id`=$SecondTeacherID";
    $res = mysqli_query($conn, $sql);
    $row=mysqli_fetch_assoc($res);
    $new_value = $row['work_assigned']+$assigned;
    $sqli_6 = "UPDATE  `teachers` SET work_assigned = $new_value where `teacher_id`=$SecondTeacherID";
    $res = mysqli_query($conn, $sqli_6);

    
    $sql="SELECT * FROM `teachers` where  `teacher_id`=$ThirdTeacherID";
    $res = mysqli_query($conn, $sql);
    $row=mysqli_fetch_assoc($res);
    $new_value = $row['work_assigned']+$assigned;
    $sqli_7 = "UPDATE  `teachers` SET work_assigned = $new_value where `teacher_id`=$ThirdTeacherID";
    $res = mysqli_query($conn, $sqli_7);

    echo json_encode(array($row['work_assigned']));

       
// }

// else{
//     echo json_encode("Null");
// }








?>