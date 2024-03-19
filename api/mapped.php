<?php
include("dbConnect.php");

$input = file_get_contents('php://input');
$decode = json_decode($input, true);

$FirstTeacherID=(int)$decode['FirstTeacherID'];
$SecondTeacherID=(int)$decode['SecondTeacherID'];
$ThirdTeacherID=(int)$decode['ThirdTeacherID'];
$assigned=(int)$decode['assigned'];
$vidhansabha=$decode['vidhansabha'];

    $sql_Student = "SELECT * FROM `entries` where  `Assigned`= 0 AND `compName`= '$vidhansabha' LIMIT $assigned";
    $res = mysqli_query($conn, $sql_Student);

    $number_rows_files = mysqli_num_rows($res);

    if($number_rows_files>0){
     

    while($row=mysqli_fetch_assoc($res)){
        $id_Student_list[]=$row['file_id'];
    }

    for ($i=0; $i < count($id_Student_list); $i++) { 
        # code...
        // $id=$list_Student_list[]
        $sqli_1 = "INSERT INTO `student_teacher_mapping`(`student_id`, `teacher_id`,`Comp_name`) VALUES ($id_Student_list[$i], $FirstTeacherID, '$vidhansabha')";
        $res = mysqli_query($conn, $sqli_1);
        $sqli_2 = "INSERT INTO `student_teacher_mapping`(`student_id`, `teacher_id`,`Comp_name`) VALUES ($id_Student_list[$i], $SecondTeacherID, '$vidhansabha')";
        $res = mysqli_query($conn, $sqli_2);
        $sqli_3 = "INSERT INTO `student_teacher_mapping`(`student_id`, `teacher_id`,`Comp_name`) VALUES ($id_Student_list[$i], $ThirdTeacherID, '$vidhansabha')";
        $res = mysqli_query($conn, $sqli_3);
        
        $sqli_4 = "UPDATE `entries` SET Assigned = 1 where `file_id`=$id_Student_list[$i]";
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

       
}

else{
    echo json_encode("Null");
}

    





?>