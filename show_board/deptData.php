<?php
session_start();
include("../connectsql.php");

$today = date("d-m-Y");
$row = array(
    "id" => "1",    //id不可為0
    "text" => "查無資料",
    "start_date" => "$today",
    "duration" => "1",
    "status" => "1",
    "type" => "project"
);
$project["data"][] = $row;
  
//從資料庫抓所有Staff資料
$sql = "SELECT `u_id` AS `key`, `u_name` AS `label` FROM user_info";
$rs = mysqli_query($conn, $sql);

if ($rs) {
  if ($rs->num_rows > 0) {
    while($row = $rs->fetch_assoc()) {
      $project["staff"][] = $row;
    }
  }
}

//從資料庫抓Department資料
$sql = "SELECT `dpt_id` AS `key`, `dpt_name` AS `label` FROM department";
$rs = mysqli_query($conn, $sql);

if ($rs) {
  if ($rs->num_rows > 0) {
    while($row = $rs->fetch_assoc()) {
      $project["dept"][] = $row;
    }
  }
}

$options = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT; 
/*
設定json參數，
JSON_UNESCAPED_UNICODE: 取消unicode編碼，才能顯示中文
JSON_PRETTY_PRINT: 格式化JSON，以便於閱讀
*/

header('Content-Type: application/json');
$json_data = json_encode($project,$options);
echo $json_data;

?>