<?php 
    session_start();
?>

<Doctype html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<title>Login</title>
</head>
<body>
    <?php
        $db;
        $userName = $_POST["userName"];
        $userType = $_POST["userType"];
        $pwd = $_POST["pwd"];

        if($userType === "Administrator"){
            if(connectDb($userName,$pwd)){
                //Administrator Login Error
                include('login_error.php');
            } else{
                echo "Administrator Login Success";
                $_SESSION['username'] = $userName;
                $_SESSION['type'] = $userType;
                $_SESSION['pwd'] = $pwd;

                header("location: queries.php"); //opening queries.php
            }
            
        } else if($userType === "Customer") {
            //echo "customer";
            verifyCustomerLogin();
            //move to queries.php
            //username and type will be saved in session $_SESSION["username"] $_SESSION["type"] 
        
        } else {// userType = employees like manager, hod, employee.
            //echo "other";
            verifyEmployeeLogin();
            //move to queries.php
            //username and type will be saved in session $_SESSION["username"] $_SESSION["type"] 
        }


        function connectDb($dbUser,$dbPassword){
            define('DB_SERVER', 'localhost:3306');
            define('DB_USERNAME', $dbUser);
            define('DB_PASSWORD', $dbPassword);
            define('DB_DATABASE', 'boa_db');
            global $db;
            $db = @mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            return mysqli_connect_errno(); // 0 - login success; other values- db login failed
        }

        function verifyCustomerLogin(){
            global $db;
            global $userName;
            global $pwd;
            global $userType;

            connectDb("root","");

            $sql = "SELECT customer_id FROM customer_login WHERE username = '$userName' and password = '$pwd'";
            $result = mysqli_query($db,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            //$active = $row['active'];
      
            $count = mysqli_num_rows($result);
      
            // If result matched username and password, table row must be 1 row
            if($count == 1) {
                $_SESSION['username'] = $userName;
                $_SESSION['type'] = $userType;
                header("location: queries.php");
            }else {
                //echo "Customer Login Error";
                include('login_error.php'); //run logout.php to destroy session and move to login page (Index page)
            }
            
        }

        function verifyEmployeeLogin(){
            global $db;
            global $userName;
            global $pwd;
            global $userType;

            $uType = ""; // convert user type to database format (Employee -> EMP)
            switch ($userType) {
                case "Employee":
                    $uType = "EMP";
                    break;
                
                case "Entry Operator":
                    $uType = "OPR";
                    break;
                
                case "Head of Department":
                    $uType = "HOD";
                    break;

                case "Manager":
                    $uType = "MGR";
                    break;
            }

            connectDb("root","");

            $sql = "SELECT employee_id FROM employee_login WHERE username = '$userName' and password = '$pwd' and type = '$uType'";
            $result = mysqli_query($db,$sql);
            //$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            //$active = $row['active'];
      
            $count = mysqli_num_rows($result);

            // If result matched username and password, table row must be 1 row
            if($count == 1) {
                $_SESSION['username'] = $userName;
                $_SESSION['type'] = $userType;
                header("location: queries.php");
            }else {
                //echo "Employee Login Error";
                include('login_error.php'); //run logout.php to destroy session and move to login page (Index page)
            }
        }
    ?>
</body>
</html>