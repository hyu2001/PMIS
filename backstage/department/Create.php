<!--新增department的輸入框-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> PMIS-DEPARTMENT1 </title>
</head>
<body>
    <form method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
    新增部門名稱: <input name = dpt_name>
    <input name='submit' type='submit' value='新增'>
    </form>
</body>
</html>

<?php
//執行SQL INSERT語句新增資料
if(!empty($_POST['dpt_name'])){
    $dpt_name = $_POST['dpt_name'];
    $sql = "INSERT INTO `department` (`dpt_id`, `dpt_name`) 
    VALUES (' ','$dpt_name')";
    mysqli_query($conn,$sql);
}
?>
