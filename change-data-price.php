<?php
    session_start();
    include('session.php');
    
    $errmsg_arr = array();
    $errflag = false;
    
    $newPrice = $_POST['newPrice'];
    $itemID = $_POST['itemID'];

    $result = $conn->prepare("SELECT * FROM item WHERE itemID = :itemID AND owner = :owner AND isSold = :isSold");
    $result->execute(array(':itemID' => $itemID, ':owner' => $_SESSION['username'], 'isSold' => 'no'));
    $rows = $result->fetch(PDO::FETCH_ASSOC);
    
    if($rows > 0)
    {   
        if($newPrice <= $rows['bidPrice'])
        {
            $errmsg_arr[] = 'Price can not be equal or lower than bid price. You can accept its bid instead.';
            $errflag = true;
        }
        else
        {
            $result = $conn->prepare("UPDATE item SET price = :newPrice WHERE itemID = :itemID");
            $result->execute(array(':newPrice' => $newPrice, ':itemID' => $itemID));
            header("location: change-price-confirm.php");    
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