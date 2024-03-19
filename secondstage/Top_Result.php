<?php

include("../api/dbConnect.php");

$input = file_get_contents('php://input');
$decode = json_decode($input, true);

$Competition = $decode['Competition'];
$location = $decode['location'];
$range = (int)$decode['range'];
// $Cname = $decode['Cname'];

if($Competition!="NULL" && $location!='NULL'){


    $sqli = "SELECT * FROM `second_stage_teachers_marks` WHERE `Comp_name`='$Competition' AND `vidhansabha`='$location' ORDER BY `average_mark` DESC LIMIT $range";
    

    
    $res = mysqli_query($conn, $sqli);
    
    $numrows=mysqli_num_rows($res);
    if($numrows>0){
    
        while($row=mysqli_fetch_assoc($res)){
            $marks[]=array($row['teachers_1_marks'],$row['teachers_2_marks'],$row['teachers_3_marks'],$row['average_mark']);
            $teacher_id[]=array($row['teacher_1_id'],$row['teacher_2_id'],$row['teacher_3_id']);
            $file_id[]=$row['Assign_file_id'];
        }

        $temp=["NULL","NULL","NULL"];
        

        for ($i=0; $i <count($teacher_id) ; $i++) { 
            # code...
            for ($h=0; $h < count($teacher_id[$i]) ; $h++) { 
                # code...
                $r=$teacher_id[$i][$h];
                $sqli = "SELECT * FROM `teachers` WHERE `teacher_id` = $r";
                $res3 = mysqli_query($conn, $sqli);

                if(mysqli_num_rows($res3)>0){
                    $row=mysqli_fetch_assoc($res3);
                    $temp[$h]=$row['teacher_name'];
                }

            }
            $teacher_name[]=$temp;
            $temp=["NULL","NULL","NULL"];

        }

        for ($i=0; $i <count($file_id) ; $i++) { 
            if($teacher_name[$i][1]=='NULL'){

                // $temp_id[]=$teacher_id[$i][1];
                $q=$teacher_id[$i][0];
                $sqli = "SELECT * FROM `second_stage_mapping` WHERE `student_id` = $file_id[$i] AND `teacher_id`!=$q";
                $res = mysqli_query($conn, $sqli);
                $count = mysqli_num_rows($res);
                // $j=0;
                $z=1;
                while($row=mysqli_fetch_assoc($res)){
    
                $temp_teacher_id=$row['teacher_id'];
                $sql = "SELECT * FROM `teachers` WHERE `teacher_id`=$temp_teacher_id";
                $res1 = mysqli_query($conn, $sql);
                $row=mysqli_fetch_assoc($res1);
                $teacher_name[$i][$z]=$row['teacher_name'];
                $z++;

                    
                }

            }
            if ($teacher_name[$i][2]=='NULL') {

                // $temp_id[]=$teacher_id[$i][1];
                $q=$teacher_id[$i][0];
                $w=$teacher_id[$i][1];
                $sqli = "SELECT * FROM `second_stage_mapping` WHERE `student_id` = $file_id[$i] AND `teacher_id`!=$q AND `teacher_id`!=$w ";
                $res = mysqli_query($conn, $sqli);
                $count = mysqli_num_rows($res);
                // $j=0;
                // $z=1;
                while($row=mysqli_fetch_assoc($res)){
    
                $temp_teacher_id=$row['teacher_id'];
                $sql = "SELECT * FROM `teachers` WHERE `teacher_id`=$temp_teacher_id";
                $res1 = mysqli_query($conn, $sql);
                $row=mysqli_fetch_assoc($res1);
                $teacher_name[$i][2]=$row['teacher_name'];
                // $z++;

                    
                }
            }
        }

        for ($i=0; $i <count($file_id) ; $i++) { 
            # code...
            $sqli = "SELECT * FROM `entries` WHERE `file_id` = $file_id[$i]";
            $res = mysqli_query($conn, $sqli);

            while($row=mysqli_fetch_assoc($res)){

                $file_id[$i]=array($row['Name'],$row['mobileNumber'],$row['compName'],$row['vidhansabha'],$row['filename']);
                $user_id_array[]=$row['userid'];
                }

        }

        for ($i=0; $i <count($user_id_array) ; $i++) { 
            # code...
            $sqli = "SELECT * FROM `participaters` WHERE `SrNo` = $user_id_array[$i]";
            $res = mysqli_query($conn, $sqli);

            while($row=mysqli_fetch_assoc($res)){

                $user_id_to_info[$i]=array($row['FatherName'],$row['Address']);
                }

        }

        $output = array($marks,$file_id,$teacher_name,$user_id_to_info);
        // $output = array($teacher_id,$teacher_name);

        for ($i=0; $i  < count($output[0]) ; $i++) { 
            $new_output[] = array_merge($output[1][$i],$output[0][$i],$output[2][$i],$output[3][$i]);
        }



        echo json_encode($new_output);




        // echo json_encode(array($marks,$file_id,$teacher_id));
    }
    
    else{
        echo json_encode("NULL");
    }
}

else{
    if($Competition=="NULL"){
        $sqli = "SELECT * FROM `second_stage_teachers_marks` WHERE `vidhansabha`='$location' ORDER BY `average_mark` DESC LIMIT $range";
    }

    else{
        $sqli = "SELECT * FROM `second_stage_teachers_marks` WHERE `Comp_name`='$Competition' ORDER BY `average_mark` DESC LIMIT $range";
    }

    $res = mysqli_query($conn, $sqli);
    
    $numrows=mysqli_num_rows($res);
    if($numrows>0){
    
        while($row=mysqli_fetch_assoc($res)){
            $marks[]=array($row['teachers_1_marks'],$row['teachers_2_marks'],$row['teachers_3_marks'],$row['average_mark']);
            $teacher_id[]=array($row['teacher_1_id'],$row['teacher_2_id'],$row['teacher_3_id']);
            $file_id[]=$row['Assign_file_id'];
        }

        $temp=["NULL","NULL","NULL"];
        

        // for ($i=0; $i <count($teacher_id) ; $i++) { 
        //     # code...
        //     $ids = join("','",$teacher_id[$i]);   
            
            
        //     $sqli = "SELECT * FROM `teachers` WHERE `teacher_id` in ('$ids')";
        //     $res = mysqli_query($conn, $sqli);
        //     $j=0;
        //     while($row=mysqli_fetch_assoc($res)){
                
        //         $temp[$j]=$row['teacher_name'];
                
        //         $j++;
        //     }
        //     $teacher_name[]=$temp;
        //     $temp=["NULL","NULL","NULL"];
        // }


        for ($i=0; $i <count($teacher_id) ; $i++) { 
            # code...
            for ($h=0; $h < count($teacher_id[$i]) ; $h++) { 
                # code...
                $r=$teacher_id[$i][$h];
                $sql = "SELECT * FROM `teachers` WHERE `teacher_id` = $r";
                $res3 = mysqli_query($conn, $sql);

                if(mysqli_num_rows($res3)>0){
                    $row=mysqli_fetch_assoc($res3);
                    $temp[$h]=$row['teacher_name'];
                }

            }
            $teacher_name[]=$temp;
            $temp=["NULL","NULL","NULL"];

        }

        for ($i=0; $i <count($file_id) ; $i++) { 
            if($teacher_name[$i][1]=='NULL'){

                // $temp_id[]=$teacher_id[$i][1];
                $q=$teacher_id[$i][0];
                $sqli = "SELECT * FROM `second_stage_mapping` WHERE `student_id` = $file_id[$i] AND `teacher_id`!=$q";
                $res = mysqli_query($conn, $sqli);
                $count = mysqli_num_rows($res);
                // $j=0;
                $z=1;
                while($row=mysqli_fetch_assoc($res)){
    
                $temp_teacher_id=$row['teacher_id'];
                $sql = "SELECT * FROM `teachers` WHERE `teacher_id`=$temp_teacher_id";
                $res1 = mysqli_query($conn, $sql);
                $row=mysqli_fetch_assoc($res1);
                $teacher_name[$i][$z]=$row['teacher_name'];
                $z++;

                    
                }

            }
            if ($teacher_name[$i][2]=='NULL') {

                // $temp_id[]=$teacher_id[$i][1];
                $q=$teacher_id[$i][0];
                $w=$teacher_id[$i][1];
                $sqli = "SELECT * FROM `second_stage_mapping` WHERE `student_id` = $file_id[$i] AND `teacher_id`!=$q AND `teacher_id`!=$w ";
                $res = mysqli_query($conn, $sqli);
                $count = mysqli_num_rows($res);
                // $j=0;
                // $z=1;
                while($row=mysqli_fetch_assoc($res)){
    
                $temp_teacher_id=$row['teacher_id'];
                $sql = "SELECT * FROM `teachers` WHERE `teacher_id`=$temp_teacher_id";
                $res1 = mysqli_query($conn, $sql);
                $row=mysqli_fetch_assoc($res1);
                $teacher_name[$i][2]=$row['teacher_name'];
                // $z++;

                    
                }
            }
        }

        for ($i=0; $i <count($file_id) ; $i++) { 
            # code...
            $sqli = "SELECT * FROM `entries` WHERE `file_id` = $file_id[$i]";
            $res = mysqli_query($conn, $sqli);

            while($row=mysqli_fetch_assoc($res)){

                $file_id[$i]=array($row['Name'],$row['mobileNumber'],$row['compName'],$row['vidhansabha'],$row['filename']);
                $user_id_array[]=$row['userid'];
                }

        }

        for ($i=0; $i <count($user_id_array) ; $i++) { 
            # code...
            $sqli = "SELECT * FROM `participaters` WHERE `SrNo` = $user_id_array[$i]";
            $res = mysqli_query($conn, $sqli);

            while($row=mysqli_fetch_assoc($res)){

                $user_id_to_info[$i]=array($row['FatherName'],$row['Address']);
                }

        }

        $output = array($marks,$file_id,$teacher_name,$user_id_to_info);
        // $output = array($teacher_id,$teacher_name);

        for ($i=0; $i  < count($output[0]) ; $i++) { 
            $new_output[] = array_merge($output[1][$i],$output[0][$i],$output[2][$i],$output[3][$i]);
        }

        echo json_encode($new_output);
    }
    
    else{
        echo json_encode("NULL");
    }
}


?>