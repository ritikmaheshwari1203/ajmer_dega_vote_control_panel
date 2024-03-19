<?php
include("dbConnect.php");




    $sqli = "SELECT * FROM `teachers`;";
    $res = mysqli_query($conn, $sqli);
    
    $numrows=mysqli_num_rows($res);
    if($numrows>0){
    
        while($row=mysqli_fetch_assoc($res)){
            $output[]=array($row['teacher_name'],$row['phoneNo'],$row['Address'],$row['expertise']);
        }
        echo json_encode($output);
    }
    
    else{
        echo json_encode("NULL");
    }

// echo $experience,$education,$post,$location;




?>