<?php 
    session_start();
?>

<Doctype html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<title>Bank of Asia - Select 1</title>
</head>
<body>

    <h3>SELECT Query 1</h3>
    <?php 
        $db;
        include("../db_connect.php"); //connects to the Database according to the user level. variable $db will be used.

        $sql = "SELECT username, type FROM employee_login WHERE employee_id = '00005';";
        //Only an  Admin can execute this command

        $result = mysqli_query($db,$sql);
        
        if ($result) { // query successful
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            echo "<p>Returned row count: " . mysqli_num_rows($result) . "</p>";

            echo "<p>Result: </p>";
             
            echo "<table border='1'><tr><td>username</td><td>type</td></tr>";
            
            echo "<tr><td>" . $row['username'] . "</td><td>" . $row['type'] . "</td></tr>";

            echo "</table>";
            // Free result set
            mysqli_free_result($result);

        } else {
            echo "<p>Error: Invalid Data or you have NO PERMISSION to execute this query</p>";
            echo "<p>Error description: " . mysqli_error($db) . "</p>";
        }
          
        mysqli_close($db);

        echo "<h4>You will be redirected to Query Page in <em>8 seconds</em></h4>";

        header( "refresh:8; url=../queries.php" );

    ?>

</body>
</html>