<?php
include("dbConnect.php");

$input = file_get_contents('php://input');
$decode = json_decode($input, true);

$Competition=$decode['Competition'];
$location=$decode['location'];

if($Competition!="NULL" && $location!='NULL'){


    $sqli = "SELECT * FROM `entries` WHERE `compName`='$Competition' AND `vidhansabha`='$location'";
    

    
    $res = mysqli_query($conn, $sqli);
    
    $numrows=mysqli_num_rows($res);
    if($numrows>0){
    
        while($row=mysqli_fetch_assoc($res)){
            $output[]=array($row['Name'],$row['mobileNumber'],$row['compName'],$row['vidhansabha'],$row['filename']);
        }
        echo json_encode($output);
    }
    
    else{
        echo json_encode("NULL");
    }
}

else{
    if($Competition=="NULL"){
        $sqli = "SELECT * FROM `entries` WHERE `vidhansabha`='$location'";
    }

    else{
        $sqli = "SELECT * FROM `entries` WHERE `compName`='$Competition'";
    }

    $res = mysqli_query($conn, $sqli);
    
    $numrows=mysqli_num_rows($res);
    if($numrows>0){
    
        while($row=mysqli_fetch_assoc($res)){
            $output[]=array($row['Name'],$row['mobileNumber'],$row['compName'],$row['vidhansabha'],$row['filename']);
        }
        echo json_encode($output);
    }
    
    else{
        echo json_encode("NULL");
    }
}
// echo $experience,$education,$post,$location;




?>