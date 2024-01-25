<?php
//把剛新增的project加入Session，並刷新頁面，這樣才能顯示在gantt上
session_start();
include("../connectsql.php");
$sql2 = "SELECT * FROM `project`  ORDER BY p_id DESC LIMIT 0 , 1";
$rs = mysqli_query($conn,$sql2);
$rst = mysqli_fetch_array($rs);

//判斷如果原本是no resualt found的話，那就將新增加的project取代no result found的位置
if($_SESSION['current_project'][0] == 0){
    $_SESSION['current_project'][0] = $rst['p_id'];
}else{
    array_push($_SESSION['current_project'],$rst['p_id']);
}
$url = "../welcome.php?";
echo "<script type='text/javascript'>";
echo "window.location.href='$url'";
echo "</script>";
exit;
?>