<?php
include("../../connectsql.php");

if(!empty($_POST['dpt_id']) && !empty($_POST['dpt_name'])){
    $sql = "UPDATE department SET dpt_name='{$_POST['dpt_name']}' WHERE dpt_id='{$_POST['dpt_id']}'";
}
if (mysqli_query($conn, $sql)) {
    echo "成功編輯部門資料<br>";
} 
else{
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
?>
<p><a href="../index.php">回到主頁</a></p>