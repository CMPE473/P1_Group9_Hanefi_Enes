<?php
    session_start();
    include('session.php');
    
    $errmsg_arr = array();
    $errflag = false;
    
    $itemID = $_POST['itemID'];

    $result = $conn->prepare("SELECT * FROM item WHERE itemID = :itemID AND isSold = :isSold");
    $result->execute(array(':itemID' => $itemID, ':isSold' => 'no'));
    $rows = $result->fetch(PDO::FETCH_ASSOC);
    
    if($rows > 0)
    {   
        if($_SESSION['username'] == $rows['owner'])
        {
            $errmsg_arr[] = 'You can not buy your item';
            $errflag = true;
        }
        else
        {
            $result = $conn->prepare("UPDATE item SET isSold = :isSold, bidder = :bidder, bidPrice = :bidPrice WHERE itemID = :itemID");
            $result->execute(array(':itemID' => $itemID, ':isSold' => 'yes', ':bidder' => $_SESSION['username'], ':bidPrice' => $rows['price']));
            header("location: buy-confirm.php");
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
        header("location: buyout-bid.php");
        exit();
    }
?>