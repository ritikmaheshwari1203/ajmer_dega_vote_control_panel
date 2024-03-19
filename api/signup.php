<?php

include("dbConnect.php");

$input = file_get_contents('php://input');
$decode = json_decode($input, true);

$name = $decode['name'];
$Expertise = $decode['Expertise'];
$password = $decode['password'];
$mobileNo = $decode['mobileNo'];


    $sql =  "SELECT * from `teachers` WHERE `teacher_name` = '$name' AND `phoneNo` = '$mobileNo';";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 0){
      $sql = "INSERT INTO `teachers`(`teacher_name`, `expertise`, `password`, `phoneNo`,`expert`) VALUES ('$name', '$Expertise', '$password', '$mobileNo',1);";
  
      $result = mysqli_query($conn, $sql);
  
      if($result){
            echo json_encode("success");
      }
    }

    else{
        echo json_encode("unsuccess");
    }




?>