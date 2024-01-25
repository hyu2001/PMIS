<?php
$state_id = array(1,2,3,4,5);
$state_name = array('in progress','maintain','pending','complete','urgent');
echo "<select class='multiple-select' multiple='multiple' name='state[]'>";

for($i=0; $i < 5; $i++)
{
    $value = $i+1;
    $selected = in_array($state_id[$i], $state) ? 'selected' : '';
    echo "<option value={$value} $selected>{$state_name[$i]}</option>";
}
echo "</select>";
?>
