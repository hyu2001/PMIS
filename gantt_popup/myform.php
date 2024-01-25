<?php
  include("../connectsql.php");
  $pid = $_POST['pid'];
  $sql1 = "SELECT * FROM project WHERE p_id = $pid";
  $rs1 = mysqli_query($conn,$sql1);
  $rst1 = mysqli_fetch_array($rs1);
?>
<p><b>
  <p>------------</p><span class="task-title"> </span> 
  <!-- 分隔線只是調整左邊空白距離 -->
  </b></p>
<div></br></div>
  <div class="custom-selectbox sz-full">
    <span class="the-title">Project Status: </span>
    <div class="custom-select" style="width:80%">
      <form method="POST" action="gantt_popup/Dbclick_Update.php">
        <select name="p_state" id="update_status" class="task-status">
          <?php
            $state_name = array('in progress','maintain','pending','complete','urgent'); 
            for($i=0; $i < 5; $i++)
            {
                $value = $i+1;
                $selected = $value == $rst1['p_state'] ? 'selected' : '';
                echo "<option value={$value} $selected>{$state_name[$i]}</option>";
            }
          ?>
        </select>
    </div>
  </div>
  <br />
  <div class="btns">
    <input type="hidden" name="p_id" value="">
    <input type="submit" name="submit" class="button btn-change-save" value="Save">
    </form>
    <div class="button btn-change-close">Cancel</div>
  </div>