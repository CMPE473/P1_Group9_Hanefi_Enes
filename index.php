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
    include('index.html');

    if($_POST)
    {
        $errmsg_arr = array();
        $errflag = false;

        $username = $_POST['uname'];
        $password = $_POST['pword'];

        if($username == '')
        {
            $errmsg_arr[] = 'You must enter your Username';
            $errflag = true;
        }
        if($password == '')
        {
            $errmsg_arr[] = 'You must enter your Password';
            $errflag = true;
        }
        if($errflag)
        {
            $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
            session_write_close();
            header("location: index.php");
            exit();
        }

        $dbhost = "localhost";
        $dbname = "auction house";
        $dbuser = "root";
        $dbpass = "1234";
     
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
        $result = $conn->prepare("SELECT * FROM user WHERE username= :username AND password= :password");
        $result->bindParam(':username', $username);
        $result->bindParam(':password', $password);
        $result->execute();
        $rows = $result->fetch(PDO::FETCH_NUM);

        if($rows > 0)
        {
            $_SESSION['username'] = $username;
            header("location: home.php");
        }
        else
        {
            $errmsg_arr[] = 'Username and Password are not correct';
            $errflag = true;
        }
        if($errflag)
        {
            $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
            session_write_close();
            header("location: index.php");
            exit();
        }
    }
?>