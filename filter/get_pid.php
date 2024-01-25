<?php
include("connectsql.php");

function get_pid($gid, array $dpt_id, array $u_id, array $state, $daterange)
{
    
    global $conn;
    $dptid = implode(',', $dpt_id); 
    $state = implode(',',$state);
    $uid = array_map(function($u) { return "'$u'"; }, $u_id);
    $uid = implode(',', $uid);
    if($gid==0 && $dpt_id==null && $u_id==null)       //組合000
    {
        $sql = "SELECT p_id FROM project WHERE 1=1";
    }
    else if($gid != 0 && $dpt_id==null && $u_id==null) //組合001
    {
        $sql = "SELECT DISTINCT p_id FROM project
                INNER JOIN show_all ON project.p_id = show_all.project
                WHERE g_id = $gid";
    }
    else if($gid==0 && $dpt_id!=null && $u_id==null)  //組合010
    {
        $sql = "SELECT DISTINCT p_id FROM project WHERE p_department IN ($dptid)";
    }
    else if($gid!=0 && $dpt_id!=null && $u_id==null)  //組合011
    {
        $sql = "SELECT DISTINCT p_id FROM project 
                INNER JOIN show_all ON project.p_id = show_all.project
                WHERE project.p_department IN ($dptid) AND g_id = $gid
        ";
    }
    else if($gid==0 && $dpt_id==null && $u_id!=null)  //組合100
    {
        $sql = "SELECT DISTINCT p_id FROM project 
                INNER JOIN show_all ON project.p_id = show_all.project
                WHERE u_id IN ($uid)";
    }
    else if($gid!=0 && $dpt_id==null && $u_id!=null)  //組合101
    {
        $sql = "SELECT DISTINCT p_id FROM project 
                INNER JOIN show_all 
                ON show_all.project = project.p_id
                WHERE show_all.u_id IN ($uid)";
    }
    else if($gid==0 && $dpt_id!=null && $u_id!=null)  //組合110
    {
        $sql = "SELECT DISTINCT p_id FROM project
                INNER JOIN show_all ON project.p_id = show_all.project
                WHERE u_id IN ($uid) AND p_department IN ($dptid)";
    }
    else                                                //組合111
    {
        $sql = "SELECT DISTINCT p_id FROM project 
                INNER JOIN show_all 
                ON show_all.project = project.p_id
                WHERE p_department IN ($dptid) AND show_all.u_id IN ($uid)";
    }
    if($daterange != "null")  //有篩選時間區間
    {
        //處理時間格式
        $date1 = substr($daterange,0,10);  //擷取字串-開始時間
        $date2 = substr($daterange,13);   //擷取字串-結束時間
        //添加日期區間篩選SELECT
        $sql .= " AND p_date1<='$date2' AND p_date2>='$date1'";
    }

    //添加狀態篩選SELECT
    if($state != null)
    {
        $sql .= " AND p_state IN ($state)";
    }
    

    //執行SQL查詢
    $rs = mysqli_query($conn,$sql);
    
    if(mysqli_num_rows($rs) == 0)  //如果沒有條件相符的pid
    {     
        $pid = (0);               //pid設為0(至少要有東西給SQL跑，才不會錯誤)
    }
    else                          //有條件相符的pid，存進陣列裡
    {
        while($rst = mysqli_fetch_assoc($rs))
        {
            $pid[] = ($rst['p_id']);
        }
    }
    // echo $sql;
    return $pid;
}
?>