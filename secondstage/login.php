<?php

include("../api/dbConnect.php");

$input = file_get_contents('php://input');
$decode = json_decode($input, true);

$name = $decode['name'];
$mobileNo = $decode['mobileNo'];
$password = $decode['password'];


    $sql =  "SELECT * from `teachers` WHERE `teacher_name` = '$name' AND `phoneNo` = '$mobileNo' AND `password`='$password' AND `expert`=1;";
    $result = mysqli_query($conn, $sql);
    // $row = mysqli_fetch_assoc($result);
    $row=mysqli_fetch_assoc($result);

    if(mysqli_num_rows($result) >0){
        session_start();
        $_SESSION["teacher_id"] = $row['teacher_id'];
        $_SESSION["teacher_name"] = $row['teacher_name'];
        // header("Location: ../pages/assignwork.html");
         echo json_encode("success");
      
    }

    else{
        echo json_encode("unsuccess");
    }




?>