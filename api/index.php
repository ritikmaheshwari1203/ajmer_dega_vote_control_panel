<?php
include("dbConnect.php");

require "../component/vendor/autoload.php";
// require "./dbconnect.php";


$Competition = "votingFinger";
// $location = $decode['location'];
$range = 16;
// $Cname = $decode['Cname'];

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



$result=mysqli_query($conn,$sql);
$html='<h1  style="display:flex; justify-content:center">Result of '.$Competition.' Competition</h1> <table>';
$html.="<tr><td><b>Sr.No</b></td><td><b>Name</b></td><td><b>Father Name</b></td><td><b>Marks</b></td><td><b>Vidhansabha</b></td><td><b>File Link</b></td></tr>";

for($q=0;$q<count($new_output);$q++){
    $j=$q+1;
    $html.='<tr><td>'.$j.'</td><td>'.$new_output[$q][0].'</td><td>'.$new_output[$q][12].'</td><td>'.$new_output[$q][8].'</td><td>'.$new_output[$q][3].'</td><td><a target="_blank" href="https://ajmerdegavote.in/uploads/'.$new_output[$q][2].'/'.$new_output[$q][3].'/'.$new_output[$q][4].'">Click here</a></td></tr>';
    $j=0;
}
// while ($row=mysqli_fetch_assoc($result)) {
//     // $id=(string)$row['id'];
//     $html.='<tr><td>'.$row['id'].'</td><td>'.$row['name'].'</td><td>'.$row['UUID'].'</td><td>'.$row['city'].'</td><td>'.$row['phoneNo'].'</td></tr>';
// }
$html.='</table>';
// echo $html;
$mypdf=new \Mpdf\Mpdf();
$mypdf->WriteHTML($html);
$name=time();
$file='../junk folder/'.$name.'.pdf';
$mypdf->output($file,'F');
echo json_encode(array('id'=>$name));

// $sqli = "SELECT * FROM `teachers`";
// $res = mysqli_query($conn, $sqli);


// // $numrows=mysqli_num_rows($res);
// $w=1;

// while($row=mysqli_fetch_assoc($res)){

//         // echo "id=".$row['teacher_id']."teacher_name=".$row['teacher_name'].$row['phoneNo']."</br>";
//         $i=strval($row['phoneNo']);
//         $q= substr($row['teacher_name'],0,3).substr($i,0,4);
//         // echo $q;
//         $sqli_7 = "UPDATE  `teachers` SET `password` = '$q' where `teacher_id`=$w";
//         mysqli_query($conn, $sqli_7);
//         $w=$w+1;
//     // $output[]=array($row['teacher_id'],$row['teacher_name'],$row['work_assigned']);
// }










?>