<?php
session_start();
include("../connectsql.php");

$pid = $_SESSION['current_project'];
$gid = $_SESSION['current_group'];
$u_id = $_SESSION['current_member'];
$pid = implode(',', $pid);
$uid = array_map(function($uid) { return "'$uid'"; }, $u_id);
$uid = implode(',', $uid);

//更改排序(sort)方式
if(isset($_POST['change_sort'])){
  $_SESSION['current_sort'] = $_POST['change_sort'];
}

//==================專案的SQL===========================//

if($_SESSION['current_sort'] == "member")  //排序方式:依成員
{
    $sql1 = "SELECT `p_id` AS `id`, `p_name` AS `text`, `p_date1` AS `start_date`, `p_state` AS `status`, `p_department` AS `dept_id` FROM project INNER JOIN department ON project.p_department = department.dpt_id 
              WHERE p_id IN ($pid) ORDER BY SUBSTR(TRIM(p_name),1,1) ASC";
}
else if($_SESSION["current_sort"] == "date")  //排序方式:依日期
{
    $sql1 = "SELECT `p_id` AS `id`, `p_name` AS `text`, `p_date1` AS `start_date`, `p_state` AS `status`, `p_department` AS `dept_id` FROM project INNER JOIN department ON project.p_department = department.dpt_id
              WHERE p_id IN ($pid) ORDER BY p_date1 ASC";    
}
else  //排序方式:默認方式-依字母
{
    $sql1 = "SELECT `p_id` AS `id`, `p_name` AS `text`, `p_date1` AS `start_date`, `p_state` AS `status`, `p_department` AS `dept_id` FROM project INNER JOIN department ON project.p_department = department.dpt_id 
              WHERE p_id IN ($pid) ORDER BY SUBSTR(TRIM(p_name),1,1) ASC";
}

$rs1 = mysqli_query($conn,$sql1); 

//=====================================================//

//==================任務和Milestone的SQL===========================//

if($u_id != null)   //有選擇使用者_ no ajax: $u_id[0] != '0'
{
  $sql2 = "
  SELECT * FROM
  (
      SELECT `t_id` AS `id`, `t_type` AS `type`, `t_name` AS `text`, `t_date1` AS `start_date`, `t_duration` AS `duration`, `t_project` AS `parent`, `u_id` AS `owner_id`, `task_status`, `row_order` FROM show_task WHERE t_project IN ($pid) AND u_id IN ($uid)
      UNION 
      SELECT `mst_id` AS `id`, `mst_type` AS `type`, `mst_name` AS `text`, `date1` AS `start_date`, 0 AS `duration`, `mst_project` AS `parent`, NULL AS `owner_id`, `task_status`, `row_order` FROM show_mst WHERE mst_project IN ($pid) AND u_id IN ($uid)
  ) AS subquery
  ORDER BY `row_order` ASC";
}
else if($gid!=0)     //沒有選擇使用者，但有選擇group
{
  $sql2 = "
  SELECT * FROM
  (
      SELECT `t_id` AS `id`, `t_type` AS `type`, `t_name` AS `text`, `t_date1` AS `start_date`, `t_duration` AS `duration`, `t_project` AS `parent`, `u_id` AS `owner_id`, `task_status`, `row_order` FROM show_task WHERE t_project IN ($pid) AND g_id = $gid
      UNION 
      SELECT `mst_id` AS `id`, `mst_type` AS `type`, `mst_name` AS `text`, `date1` AS `start_date`, 0 AS `duration`, `mst_project` AS `parent`, 'NULL' AS `owner_id`, `task_status`, `row_order` FROM show_mst WHERE mst_project IN ($pid) AND g_id = $gid
  ) AS subquery
  ORDER BY `row_order` ASC";
}
else
{                //使用者和group都沒選
  $sql2 = "
  SELECT * FROM
  (
      SELECT `t_id` AS `id`, `t_type` AS `type`, `t_name` AS `text`, `t_date1` AS `start_date`, `t_duration` AS `duration`, `t_project` AS `parent`, `u_id` AS `owner_id`, `task_status`, `row_order` FROM show_task WHERE t_project IN ($pid)
      UNION 
      SELECT `mst_id` AS `id`, `mst_type` AS `type`, `mst_name` AS `text`, `date1` AS `start_date`, 0 AS `duration`, `mst_project` AS `parent`, 'NULL' AS `owner_id`, `task_status`, `row_order` FROM show_mst WHERE mst_project IN ($pid)
  ) AS subquery
  ORDER BY `row_order` ASC";
}
$rs2 = mysqli_query($conn,$sql2);
//====================================================//


//1.專案資料
if ($rs1->num_rows > 0) {
    while($row1 = $rs1->fetch_assoc()) {
      $row1['start_date'] = date("d-m-Y", strtotime($row1['start_date']));
      $row1["duration"] = "10";  //附加type
      $row1["type"] = "project";  //附加type
      $project["data"][] = $row1;
    }
}

//2.任務和milestone資料
if($rs2){
  if ($rs2->num_rows > 0) {
      while($row2 = $rs2->fetch_assoc()) {
        if($row2['type']=='2')  //task
        {
          $row2['id'] = "t".$row2['id'];
          $row2['start_date'] = date("d-m-Y", strtotime($row2['start_date']));
          $row2["type"] = "task";  //附加type
          $project["data"][] = $row2;
        }
        else                  //milestone
        {
          $row2['id'] = "m".$row2['id'];
          $row2['start_date'] = date("d-m-Y", strtotime($row2['start_date']));
          $row2["type"] = "milestone";  //附加type
          $row2['rollup'] = true;  //milestone會在合併時間軸中被標示
          $project["data"][] = $row2;
          
        }
      }
    }
}

  
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
