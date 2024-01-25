<?php
if($_GET['edit'] != '')
{
    $type = $_GET['type'];

    if ($type==2) //更新的類型是"任務"
    {
        include("../task/Update.php");
    }
    else if($type==3)//更新的類型是"任務"
    {
        include("../milestone/Update.php");
    }
    else
    {   
        echo "錯誤!!";   
    }
}
?>