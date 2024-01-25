<?php
header('Content-Type: text/html; charset=utf-8');
include("../../connectsql.php");

if($_POST['del'] != '')
{
    $id = $_POST['del'];
    $sql = "DELETE FROM `user_group`  WHERE `user_group`.`g_id` = '$id'";
    if (mysqli_query($conn, $sql)) 
    {
        // header("Location:index.php");
        $url = "../index.php"; 
        echo "<script type='text/javascript'>";
        echo "window.location.href='$url'";
        echo "</script>";
    } 
    else
    {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>