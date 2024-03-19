<?php
include("../api/dbConnect.php");


session_start();

if(!isset($_SESSION["teacher_id"]) && !isset($_SESSION["teacher_name"])){
    // header("Location: login.html");
    echo json_encode("NonvalidUser");
}

else{
    // session_start();
    $teac_id = $_SESSION["teacher_id"] ;
    $sqli = "SELECT * FROM `second_stage_mapping` where `teacher_id`=$teac_id AND `work_completed`=0";
    $res = mysqli_query($conn, $sqli);

    if(mysqli_num_rows($res)==0){

        echo json_encode("Null");

    }

    else{

        
        while($row=mysqli_fetch_assoc($res)){
            $output_file_id[]=$row['student_id'];
        }
        
        for ($i=0; $i < count($output_file_id) ; $i++) { 
            
            $sqli2 = "SELECT * FROM `entries` where  `file_id`=$output_file_id[$i]";
            $res2=mysqli_query($conn,$sqli2);
            $row=mysqli_fetch_assoc($res2);
        $file_path[]=array($row['compName'],$row['vidhansabha'],$row['filename']);
    }
    
    $complete_output= array($output_file_id,$file_path);
    
    echo json_encode($complete_output);
}
    
}





?>