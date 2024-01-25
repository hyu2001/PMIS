<?php
include("../connectsql.php");

$gantt_id = $_POST['id'];
$lightboxType = $_POST['type'];

switch($lightboxType){
    case "task":
        $sql = "SELECT `t_id` AS `id` FROM `task` WHERE `gantt_id` = $gantt_id";
        $f = 't';
        break;
    case "milestone":
        $sql = "SELECT `m_id` AS `id` FROM `milestone` WHERE `gantt_id` = $gantt_id";
        $f = 'm';
        break;
}
$rs = mysqli_query($conn, $sql);
$rst = mysqli_fetch_array($rs);

echo $f.$rst['id'];
?>