<?php
include("dbConnect.php");

session_start();

if(isset($_SESSION["admin"]) && $_SESSION["admin"]==true){

    $input = file_get_contents('php://input');
$decode = json_decode($input, true);

$vidhansabha=$decode['vidhansabha'];

$sqli = "SELECT * FROM `teachers` where `expert`=0 ORDER BY `teacher_name`";
$res = mysqli_query($conn, $sqli);

$sqli2 = "SELECT * FROM `entries` where  `Assigned`=0 AND `compName`='$vidhansabha'";
$res2=mysqli_query($conn,$sqli2);

$numrows=mysqli_num_rows($res2);

while($row=mysqli_fetch_assoc($res)){
    $output[]=array($row['teacher_id'],$row['teacher_name'],$row['work_assigned']);
}

$finalresult=array($output,$numrows);

echo json_encode($finalresult);


}

else{


    // header("Location: login.html");
    echo json_encode("NonvalidUser");

}




?>