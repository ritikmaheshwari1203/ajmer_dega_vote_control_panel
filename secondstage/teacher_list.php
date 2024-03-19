<?php
include("../api/dbConnect.php");

// session_start();

// if(isset($_SESSION["admin"]) && $_SESSION["admin"]==true){


$sqli = "SELECT * FROM `teachers` where `expert`=1";
$res = mysqli_query($conn, $sqli);

// $num = mysqli_num_rows($res);
while($row=mysqli_fetch_assoc($res)){
    $output[]=array($row['teacher_id'],$row['teacher_name'],$row['work_assigned']);
}


echo json_encode($output);

?>