<?php
    session_start();
    include('session.php');
    
    $errmsg_arr = array();
    $errflag = false;
    
    $itemID = $_POST['itemID'];

    $result = $conn->prepare("SELECT * FROM item WHERE itemID = :itemID AND owner = :owner AND isSold = :isSold");
    $result->execute(array(':itemID' => $itemID, ':owner' => $_SESSION['username'], ':isSold' => 'no'));
    $rows = $result->fetch(PDO::FETCH_ASSOC);
    
    if($rows > 0)
    {   
        if($rows['bidder'] == "" && $rows['bidPrice'] == 0)
        {
            $errmsg_arr[] = 'There is no bid to your item';
            $errflag = true;
        }
        else
        {
            $result = $conn->prepare("UPDATE item SET price = :price, isSold = :isSold WHERE itemID = :itemID");
            $result->execute(array(':itemID' => $itemID, ':price' => $rows['bidPrice'], ':isSold' => 'yes'));
            header("location: sell-confirm.php");
        } 
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