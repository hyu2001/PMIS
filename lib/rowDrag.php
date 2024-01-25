<?php
include("../connectsql.php");

//獲取新的order值
function getNewOrder($pid){
    global $conn;
    $sql = "SELECT MAX(row_order) AS new_order FROM show_all WHERE project = $pid";
    $rs = mysqli_query($conn, $sql);
    $rst = mysqli_fetch_array($rs);

    if($rst['new_order'] == null)
    {   
        return 0;
    }
    else
    {
        return $rst['new_order'] + 1;
    }
}

//刪除項目時，該項目order之後的order都-1
function orderDelete($pid, $order){
    global $conn;
    $sql = "SELECT * FROM show_all WHERE project = $pid AND row_order > $order";
    $rs = mysqli_query($conn,$sql);

    while($rst = mysqli_fetch_array($rs)){
        $id = $rst['id'];

        //判斷要對type還是milestone進行row_order欄位的更新
        if($rst['type'] == 2){
            $type = "task";
            $id_name = "t_id";
        }else{
            $type = "milestone";
            $id_name = "mst_id";
        }

        //執行更新
        $sql2 = "UPDATE $type SET `row_order` = `row_order` - 1 WHERE $id_name = $id";
        mysqli_query($conn, $sql2);
    }
}
?>