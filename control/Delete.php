<?php
if($_POST['del'] != '')
{
    $type = $_POST['type'];

    if ($type==1) //刪除的類型是"任務"
    {
        include("../task/Delete.php");
    }
    else if($type==2)//刪除的類型是"任務"
    {
        include("../milestone/Delete.php");
    }
    else
    {   
        echo "錯誤!!";   
    }
}
?>