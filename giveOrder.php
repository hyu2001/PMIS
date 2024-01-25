<?php
include("connectsql.php");

$sql = "SELECT p_id FROM project";
$rs = mysqli_query($conn,$sql);

//儲存所有project id
$pid = array();
while($row = mysqli_fetch_array($rs)){
    array_push($pid,$row["p_id"]);
}

//找到同樣專案底下的項目
for($i = 0; $i < sizeof($pid); $i++){
    $sql = "SELECT * FROM show_all WHERE project = $pid[$i] ORDER BY date1 ASC";
    $rs = mysqli_query($conn,$sql);


    $order = 0;
    echo "========".$i."[".$pid[$i]."]"."=========<br>";
    //逐一對任務或milestone設定其order值
    while($rst = mysqli_fetch_array($rs)){

        //先判斷項目是task還是milestone
        if($rst['type'] == 2){  
            $type = "task";
            $id_name = "t_id";
        }else{  
            $type = "milestone";
            $id_name = "mst_id";
        }

        //執行更新
        $sql = "UPDATE $type SET row_order = $order WHERE $id_name = {$rst['id']}";
        mysqli_query($conn,$sql);
        
        echo $rst['id']." =>".$order;
        echo "<br>";
        $order++;
    }
}


?>