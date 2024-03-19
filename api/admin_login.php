<?php

include("dbConnect.php");

$input = file_get_contents('php://input');
$decode = json_decode($input, true);

$name = $decode['name'];
$password = $decode['password'];


    $sql =  "SELECT * from `adminlogin` WHERE `admin_name` = '$name' AND `admin_password` = $password";
    $result = mysqli_query($conn, $sql);


    if(mysqli_num_rows($result) >0){
        session_start();
        $_SESSION["admin"] = true;
         echo json_encode("success");
      
    }

    else{
        echo json_encode("unsuccess");
    }




?>