<?php
header('Content-Type: text/html; charset=utf-8');
include("../../connectsql.php");

if($_POST['del'] != '')
{
    $dpt_id = $_POST['del'];
    $sql = "DELETE FROM `department`  WHERE `department`.`dpt_id` = '$dpt_id'";

    if (mysqli_query($conn, $sql)) 
    {
        $url = "../index.php";
        // header("Location:index.php");
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