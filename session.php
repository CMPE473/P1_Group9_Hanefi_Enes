<?php
    $dbhost     = "localhost";
    $dbname     = "auction house";
    $dbuser     = "root";
    $dbpass     = "1234";

    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

    $username=$_SESSION['username'];

    if(!isset($username))
    {
        $conn = null; 
        header('Location: index.php');
    }   
?>