<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "file";

    $conn = mysqli_connect($servername, $username, $password, $database);
    
    // if(!$conn){
    //     die(mysqli_connect_error());
    // }
    // else{
    //     echo "Successfully connected"; 
    // }
?>