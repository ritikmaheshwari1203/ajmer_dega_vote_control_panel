<?php
include("dbConnect.php");

session_start();

// if(isset($_SESSION["admin"]) && $_SESSION["admin"]==true){


$sqli = "SELECT * FROM `teachers`";
$res = mysqli_query($conn, $sqli);


while($row=mysqli_fetch_assoc($res)){
    $output[]=array($row['teacher_id'],$row['teacher_name']);
}


echo json_encode($output);


// }

// else{


//     // header("Location: login.html");
//     echo json_encode("NonvalidUser");

// }




?>