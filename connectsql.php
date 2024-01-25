<?php
    $host = "127.0.0.1:3000";
    $user = "root";
    $psw = "";
    $db_name = "pmis";

    $conn = mysqli_connect($host,$user,$psw,$db_name);
    if ($conn)
    {
        echo " ";
    }
    else
    {
        echo "Fail to connect database!<br>";
    }
?>