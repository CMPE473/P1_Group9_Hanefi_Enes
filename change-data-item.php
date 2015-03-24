<?php
    session_start();
    include('session.php');
    
    $errmsg_arr = array();
    $errflag = false;
    
    $name = $_POST['name'];
    $itemID = $_POST['itemID'];
    $description = $_POST['description'];


    $result = $conn->prepare("SELECT * FROM item WHERE itemID = :itemID AND isSold = :isSold AND owner = :owner");
    $result->execute(array(':itemID' => $itemID, ':isSold' => 'no', ':owner' => $_SESSION['username']));
    $rows = $result->fetch(PDO::FETCH_ASSOC);
    
    if($rows > 0)
    {   
        if($name == "")
        {
            $name = $rows['name'];
        }
        if($description == "")
        {
            $description == $rows['description'];
        }
        $result = $conn->prepare("UPDATE item SET name = :name, description = :description WHERE itemID = :itemID");
        $result->execute(array(':name' => $name, ':description' => $description, ':itemID' => $itemID));
        header("location: edit-confirm.php");
    }
    else
    {
        $errmsg_arr[] = 'Incorrect itemID or item is sold';
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