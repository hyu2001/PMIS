<?php
include("../connectsql.php");
include("../lib/getsql.php");
//-----------------------------//
if (isset($_GET['pid'])) 
{
	$p_id = $_GET['pid'];
    $sql = "SELECT `user`, `text`, `time`, `reason`
            FROM (
            SELECT `mkp_user` AS `user`, `mkp_text` AS `text`, `mkp_time` AS `time`, `mkp_reason` AS `reason`
            FROM `mark_p`
            WHERE `mkp_project`=$p_id
            UNION 
            SELECT `mkt_user` AS `user`, `mkt_text` AS `text`, `mkt_time` AS `time`, `mkt_reason` AS `reason`
            FROM `mark_t`
            WHERE `mkt_project`=$p_id
            UNION 
            SELECT `mkm_user` AS `user`, `mkm_text` AS `text`, `mkm_time` AS `time`, `mkm_reason` AS `reason`
            FROM `mark_m`
            WHERE `mkm_project`=$p_id
            ) AS MARK
            ORDER BY time DESC";
    $rs = mysqli_query($conn,$sql);
    while($rst = mysqli_fetch_array($rs))
    {
        $user_name = get_user_name($rst['user']);
        if($rst['reason'] != null){
            echo $user_name.$rst['text']."(異動原因：".$rst['reason'].") ".$rst['time']."<br>";
        }else{
            echo $user_name.$rst['text']." (".$rst['time'].")<br>";
        }
    }
}
?>