<?php
    session_start();
    include('session.php');
    
    if(isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0)
    {
        echo '<ul style="padding:0; color:red;">';
        foreach($_SESSION['ERRMSG_ARR'] as $msg)
        {
            echo '<li>',$msg,'</li>';
        }
        echo '</ul>';
        unset($_SESSION['ERRMSG_ARR']);
    }

    $search = "";
    if($_POST)
    {
    	$search = $_POST['search'];
    }
    $result = $conn->prepare("SELECT description, price, bidPrice, owner, itemID, name, bidder, isSold FROM item WHERE owner = :owner AND (name LIKE :search OR description LIKE :search) ORDER BY name");
    $result->execute(array(':search' => '%' . $search . '%', ':owner' => $_SESSION['username']));
    $result->setFetchMode(PDO::FETCH_ASSOC);
	include('change-price.html');
?>