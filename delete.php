<?php
    session_start();
    include('session.php');
    
    $errmsg_arr = array();
    $errflag = false;
    
    $itemID = $_POST['itemID'];

    $result = $conn->prepare("SELECT * FROM item WHERE itemID = :itemID AND owner = :owner");
    $result->execute(array(':itemID' => $itemID, ':owner' => $_SESSION['username']));
    $rows = $result->fetch(PDO::FETCH_ASSOC);
    
    if($rows > 0)
    {   
        $result = $conn->prepare("DELETE FROM item WHERE itemID = :itemID");
        $result->execute(array(':itemID' => $itemID));
        header("location: delete-confirm.php");
    }
    else
    {
        $errmsg_arr[] = 'Incorrect itemID';
        $errflag = true;
    }
    if($errflag)
    {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
        session_write_close();
        header("location: change-price.php");
        exit();
    }
?>