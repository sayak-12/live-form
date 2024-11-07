<?php 
    $db = "grse";
    $server = "localhost";
    $user = "root";
    $pass = "";
    $conn = mysqli_connect($server, $user, $pass, $db);
    if(!$conn){
        die("Could not connect to localhost");
    }
?>