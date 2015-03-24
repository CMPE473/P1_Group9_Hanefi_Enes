<?php
    session_start();
    include('session.php');
    
    $errmsg_arr = array();
    $errflag = false;
    
    $bidPrice = $_POST['bidPrice'];
    $bidder = $_SESSION['username'];
    $itemID = $_POST['itemID'];

    $result = $conn->prepare("SELECT * FROM item WHERE itemID = :itemID AND isSold = :isSold");
    $result->execute(array(':itemID' => $itemID, ':isSold' => 'no'));
    $rows = $result->fetch(PDO::FETCH_ASSOC);
    
    if($rows > 0)
    {   
        if($bidPrice >= $rows['price'])
        {
            $errmsg_arr[] = 'You can not bid higher than price. You can buy instead.';
            $errflag = true;
        }
        elseif($bidPrice <= $rows['bidPrice'])
        {
            $errmsg_arr[] = 'You can not bid lower than the current bid.';
            $errflag = true;
        }
        elseif($bidder == $rows['owner'])
        {
            $errmsg_arr[] = 'You can not bid to your item.';
            $errflag = true;
        }
        else
        {
            $result = $conn->prepare("UPDATE item SET bidPrice = :bidPrice, bidder = :bidder WHERE itemID = :itemID");
            $result->execute(array(':bidPrice' => $bidPrice, ':bidder' => $bidder, ':itemID' => $itemID));
            header("location: bid-confirm.php");
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