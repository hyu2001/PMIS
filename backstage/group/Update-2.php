<?php
include("../../connectsql.php");
$sql = "UPDATE user_group SET g_name='{$_POST['g_name']}'";
if(!empty($_POST['g_id']) && !empty($_POST['g_name']))
{
    $sql = "UPDATE user_group SET g_name='{$_POST['g_name']}', g_color='{$_POST['color']}' WHERE g_id='{$_POST['g_id']}'";

    if (mysqli_query($conn, $sql)) 
    {
        echo "成功編輯Group資料<br>";
    } 
    else
    {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} 
?>
<p><a href="../index.php">回到主頁</a></p>
