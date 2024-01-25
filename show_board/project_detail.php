<?php
session_start();
include("../connectsql.php");

$pid = $_SESSION['current_project'];
$pid = implode(',', $pid);

//==================專案的SQL===========================//
$sql1 = "SELECT `p_id` AS `id`, `p_name` AS `text`, `p_date1` AS `start_date`, `p_state` AS `status`, `p_department` AS `dept_id` FROM project INNER JOIN department ON project.p_department = department.dpt_id 
          WHERE p_id IN ($pid) ORDER BY SUBSTR(TRIM(p_name),1,1) ASC";
$rs1 = mysqli_query($conn,$sql1); 
//=====================================================//

//==================任務SQL和milestone的SQL===========================//
$sql2 = "
  SELECT * FROM
  (
      SELECT `t_id` AS `id`, `t_type` AS `type`, `t_name` AS `text`, `t_date1` AS `start_date`, `t_duration` AS `duration`, `t_project` AS `parent`, `u_id` AS `owner_id`, `task_status`, `row_order` FROM show_task WHERE t_project IN ($pid)
      UNION 
      SELECT `mst_id` AS `id`, `mst_type` AS `type`, `mst_name` AS `text`, `date1` AS `start_date`, 0 AS `duration`, `mst_project` AS `parent`, NULL AS `owner_id`, `task_status`, `row_order` FROM show_mst WHERE mst_project IN ($pid)
  ) AS subquery
  ORDER BY `row_order` ASC";
$rs2 = mysqli_query($conn,$sql2);
//==================================================================//

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
