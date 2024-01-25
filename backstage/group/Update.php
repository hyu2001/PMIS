<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PMIS-GROUP</title>
    <!-- <link rel="stylesheet" type="text/css" href="..\..\lib\Farbtastic\farbtastic\farbtastic.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="farbtastic12/farbtastic/farbtastic.js"></script>
    <link rel="stylesheet" href="farbtastic12/farbtastic/farbtastic.css" type="text/css" />
</head>

<?php
include("../../connectsql.php");

if($_GET['edit'] != ''){
    $g_id = $_GET['edit'];
    $sql = "SELECT* FROM `user_group` WHERE `user_group`.`g_id` = $g_id";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);
}
else{
    // header("Location: page.php");
    $url = "page.php"; 
    echo "<script type='text/javascript'>";
    echo "window.location.href='$url'";
    echo "</script>";
}
?>


<body>

    <form method="POST" action="Update-2.php">
        Group代號:<?php echo $row['g_id']; ?>
        <br>
        Group名稱:<input name="g_name" value="<?php echo $row['g_name']; ?>" style="width:75px" />
        <br>
        <label for="color">Group顏色:</label>
        <input type="text" id="color" name="color" value="<?php echo $row['g_color'] ?>" />
        <div id="colorpicker"></div>
        <br>
        <input name="g_id" type="hidden" value="<?php echo $row['g_id']; ?>" />
        <input name="submit" type="submit" value="送出" />
    </form>
    </br>
    <a href="../index.php">回上一頁</a>

    <script type="text/javascript">
      $(document).ready(function() {
        $('#colorpicker').farbtastic('#color');
      });
    </script>
</body>
</html>

