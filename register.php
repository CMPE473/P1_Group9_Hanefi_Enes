<?php
    session_start();
    if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0)
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

        $username = $_POST['uname'];
        $password = $_POST['pword'];
        $cpassword = $_POST['cpword'];
         
        if($username == '')
        {
            $errmsg_arr[] = 'You must enter your Username';
            $errflag = true;
        }
        elseif(!preg_match("/^[a-zA-Z0-9.\S]+$/",$username))
        {
            $errmsg_arr[] = 'Username must contain only numbers and letters';
            $errflag = true;
        }
        if($password == '')
        {
            $errmsg_arr[] = 'You must enter your Password';
            $errflag = true;
        }
        elseif(!preg_match("/^[\S]+$/",$password))
        {
            $errmsg_arr[] = 'Password must not contain spaces';
            $errflag = true;
        }
        elseif($cpassword == '')
        {
            $errmsg_arr[] = 'You must enter Confirm Password';
            $errflag = true;
        }
        elseif($password != $cpassword)
        {
            $errmsg_arr[] = 'Your password must match';
            $errflag = true;
        }
        if($errflag)
        {
            $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
            session_write_close();
            header("location: register.php");
            exit();
        }

        $dbhost = "localhost";
        $dbname = "auction house";
        $dbuser = "root";
        $dbpass = "1234";
         
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
        $result = $conn->prepare("SELECT * FROM user WHERE username= :username");
        $result->bindParam(':username', $username);
        $result->execute();
        $rows = $result->fetch(PDO::FETCH_NUM);

        if($rows > 0)
        {
            $errmsg_arr[] = 'Username already exists';
            $errflag = true;;
        }
        if($errflag)
        {
            $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
            session_write_close();
            header("location: register.php");
            exit();
        }
        
        $sql = "INSERT INTO user (username, password) VALUES (:username,:password)";
        $q = $conn->prepare($sql);
        $q->execute(array(':username'=>$username,':password'=>$password));
        header("location: index.php");
    }
    include('register.html');
?>