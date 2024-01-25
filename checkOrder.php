<?php
include("connectsql.php");

$sql = "SELECT p_id FROM project ORDER BY p_id ASC";
$rs = mysqli_query($conn,$sql);

//儲存所有project id
$pid = array();
while($row = mysqli_fetch_array($rs)){
    array_push($pid,$row["p_id"]);
}

//找到同樣專案底下的項目
for($i = 0; $i < sizeof($pid); $i++){
    $sql = "SELECT * FROM show_all WHERE project = $pid[$i] ORDER BY row_order ASC";
    $rs = mysqli_query($conn,$sql);
    
    echo "========".$i."[".$pid[$i]."]"."=========<br>";
    //逐一對任務或milestone設定其order值
    while($rst = mysqli_fetch_array($rs)){
        echo $rst['type']."_".$rst['id']." =>".$rst['row_order'];
        echo "<br>";
    }
}


?>