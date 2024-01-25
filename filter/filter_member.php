
<?php
//迴圈1-外圈:Group
$sql1 = "SELECT * FROM user_group WHERE g_outside = 'n'";
$rs1 = mysqli_query($conn,$sql1);

echo "<select class='multiple-select' multiple='multiple' name='member[]'>";

//顯示全部的成員
$sql1 = "SELECT * FROM user_group WHERE g_outside = 'n'";
$rs1 = mysqli_query($conn,$sql1);
while($rst1 = mysqli_fetch_array($rs1))
{
    echo "<optgroup label='{$rst1['g_name']}'>";
    //迴圈2-內圈:Member
    $sql2 = "SELECT * FROM user_info WHERE u_group = {$rst1['g_id']}";
    $rs2 = mysqli_query($conn,$sql2);
    while($rst2 = mysqli_fetch_array($rs2))
    {
        $selected = in_array($rst2['u_id'], $uid) ? 'selected' : '';
        echo "<option value='{$rst2['u_id']}' $selected>{$rst2['u_name']}</option>";
    }
}
echo "</optgroup>";
echo "</select>";
?>