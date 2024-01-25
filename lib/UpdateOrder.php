<?php
include("connectsql.php");

//取得Drag資訊
$id = $_POST['id'];
$parent = $_POST['parent'];
$new_pos = $_POST['order'];

$UpdateType = substr($id,0,1);
$id = substr($id,1);

//正要更新order的是task還是milestone
if($UpdateType == 't')
{
    $updateType = "task";
    $updateIdName = "t_id";
}
else if($UpdateType == 'm')
{
    $updateType = "milestone";
    $updateIdName = "mst_id";
}

//取得舊位子
$sql = "SELECT * FROM $updateType WHERE $updateIdName = $id";
$rs = mysqli_query($conn, $sql);
$rst = mysqli_fetch_array($rs);
$old_pos = $rst['row_order'];


//往下移
if($new_pos > $old_pos)
{
    $sql2 = "SELECT * FROM show_all WHERE project = $parent AND row_order <= $new_pos AND row_order > $old_pos";
    $rs2 = mysqli_query($conn,$sql2);

    while($rst2 = mysqli_fetch_array($rs2)){
        $update_row = $rst2['id'];

        //判斷要對type還是milestone進行row_order欄位的更新
        if($rst2['type'] == 2){
            $type = "task";
            $id_name = "t_id";
        }else{
            $type = "milestone";
            $id_name = "mst_id";
        }

        //執行更新
        $sql3 = "UPDATE $type SET `row_order` = `row_order` - 1 WHERE $id_name = $update_row";
        mysqli_query($conn, $sql3);
    }
}
//往上移
else if($new_pos < $old_pos)
{
    $sql2 = "SELECT * FROM show_all WHERE project = $parent AND row_order >= $new_pos AND row_order < $old_pos";
    $rs2 = mysqli_query($conn,$sql2);

    while($rst2 = mysqli_fetch_array($rs2)){
        $update_row = $rst2['id'];

        //判斷要對type還是milestone進行row_order欄位的更新
        if($rst2['type'] == 2){
            $type = "task";
            $id_name = "t_id";
        }else{
            $type = "milestone";
            $id_name = "mst_id";
        }

        //執行更新
        $sql3 = "UPDATE $type SET `row_order` = `row_order` + 1 WHERE $id_name = $update_row";
        mysqli_query($conn, $sql3);
    }
}
//位子不變
else if($new_pos == $old_pos)
{
    return;
}

//更新成新位子
$sql = "UPDATE $updateType SET row_order = $new_pos WHERE $updateIdName = $id";
$rs = mysqli_query($conn, $sql);
?>