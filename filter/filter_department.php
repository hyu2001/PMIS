<?php
$sql = "SELECT * FROM department";
$result = mysqli_query($conn,$sql);
$totolrow = mysqli_num_rows($result);
echo "<select class='multiple-select' multiple='multiple' name='dept[]'>";

for($i=0; $i < $totolrow; $i++)
{
    $rst = mysqli_fetch_array($result);
    $selected = in_array($rst['dpt_id'], $dptid) ? 'selected' : '';
    echo "<option value='{$rst['dpt_id']}' $selected>{$rst['dpt_name']}</option>";
}
echo "</select>";
?>