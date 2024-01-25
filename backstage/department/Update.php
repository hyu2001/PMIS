<?php
include("../../connectsql.php");

if($_GET['edit'] != ''){
    $dpt_id = $_GET['edit'];
    $sql = "SELECT* FROM `department`  WHERE `department`.`dpt_id` = '$dpt_id'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);
}
else{
    // header("Location: index.php");
    $url = "index.php";
    echo "<script type='text/javascript'>";
    echo "window.location.href='$url'";
    echo "</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset=""utf-8>
    <title>PMIS-DEPARTMENT</title>
</head>
<bode>
    <form method="POST" action="Update-2.php">
        部門代號:<?php echo $row['dpt_id']; ?>
        <br>
        部門名稱:<input name="dpt_name" value="<?php echo $row['dpt_name']; ?>" style="width:75px" />
        <input name="dpt_id" type="hidden" value="<?php echo $row['dpt_id']; ?>" />
        <input name="submit" type="submit" value="送出" />
    </form>
    <p><a href="../index.php">回上一頁</a></p>
</body>