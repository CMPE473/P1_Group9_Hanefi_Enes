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

	if($_POST)
	{
		$errmsg_arr = array();
		$errflag = false;

		$name = $_POST['name'];
		$price = $_POST['price'];
		$description = $_POST['description'];

	    if($name == "")
	    {
	        $errmsg_arr[] = "Name is required";
	        $errflag = true;
	    }
	    elseif(!preg_match("/^[a-zA-Z0-9]/",$name))
	    {
	        $errflag = true;
	        $errmsg_arr[] = "Name can contain characters and numbers";
	    }
	    if($price == "") 
	    {
	        $errmsg_arr[] = "Price is required";
	        $errflag = true;
	    }   
	    elseif(!is_numeric($price))
	    {
	        $errmsg_arr[] = "Price has to be number";
	        $errflag = true;
	    }
	    elseif($price < 0)
	    {
	        $errmsg_arr[] = "Price has to be positive number";
	        $errflag = true;
	    }
	    if($description == "")
	    {
	        $errmsg_arr[] = "Decription is required";
	        $errflag = true;
	    }
	    if($errflag)
	    {
	        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
	        session_write_close();
	        header("location: sell.php");
	        exit();
	    }
	
	    $sql = "INSERT INTO item (description, price, owner, name) VALUES (:description,:price,:own,:name)";
	    $q = $conn->prepare($sql);
	    $q->execute(array(':description'=>$description,':price'=>$price,':own'=>$_SESSION['username'], ':name' => $name));
	    header("location: sell-listed.php");
	}
	include('sell.html');
?>