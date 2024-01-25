<?php
//生成側邊Group按鈕
echo "<a href='filter/session_group.php?group=0' class='btn-switch'>All</a>";

$sql = "SELECT * FROM user_group WHERE g_outside = 'n'";
$result = mysqli_query($conn,$sql);
$totolrow = mysqli_num_rows($result);
for($i=0; $i < $totolrow; $i++)
{
    $rst = mysqli_fetch_array($result);
    echo "<a style='background-color:{$rst['g_color']};' href='filter/session_group.php?group={$rst['g_id']}' class='btn-switch'>{$rst['g_name']}</a>";  //預設勾選
}
?>
        