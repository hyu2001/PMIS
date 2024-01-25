<?php
include("connectsql.php");
include("getsql.php");
/*---------------------------------------*/ 

//--------------------------------------專案--------------------------------------//
//新增專案
function MarkCreateProject($who,$where)
{ 
    global $conn;
    //紀錄時間
    $theDate = new DateTime();
    $stringDate = $theDate->format('Y-m-d H:i:s');
    $when = $stringDate;

    $userName = get_user_name($who);
    $mk_text = "建立了專案";
    echo $userName.$mk_text." ($when) <br>";

    $sql = "INSERT INTO `mark_p` (`mkp_user`,`mkp_project`,`mkp_time`,`mkp_text`)
            VALUE ('$who','$where','$when','$mk_text')";
    mysqli_query($conn,$sql);
}

//刪除專案
function MarkDeleteProject($who,$where)
{ 
    global $conn;
    //紀錄時間
    $theDate = new DateTime();
    $stringDate = $theDate->format('Y-m-d H:i:s');
    $when = $stringDate;

    $userName = get_user_name($who);
    $projectname = get_project_name($where);
    $mk_text = "刪除了專案-".$projectname;
    echo $userName.$mk_text." ($when) <br>";

    $sql = "INSERT INTO `mark_p` (`mkp_user`,`mkp_project`,`mkp_time`,`mkp_text`)
            VALUE ('$who','$where','$when','$mk_text')";
    $rs = mysqli_query($conn,$sql);
}

//編輯專案
function MarkUpdateProject($who,$where,array $edit)
{ 
    global $conn;
    //紀錄時間
    $theDate = new DateTime();
    $stringDate = $theDate->format('Y-m-d H:i:s');
    $when = $stringDate;
    $reason = $edit['reason'];
    $userName = get_user_name($who);


    //修改專案名稱
    if($edit['name1'] != $edit['name2'])
    {
        $mk_text = "修改了專案名稱|{$edit['name1']} --> {$edit['name2']}";
        echo $userName.$mk_text." ($when) <br>";
    }

    //修改派發部門
    if($edit['dept1'] != $edit['dept2'])
    {
        $g_name1 = get_dept_name($edit['dept1']);
        $g_name2 = get_dept_name($edit['dept2']);

        $mk_text = "修改了派發部門| $g_name1 --> $g_name2";
        echo $userName.$mk_text." ($when) <br>";
    }

    //修改專案狀態
    $state = array('inprogress','maintain','pending','complete','urgent');
    $edit1 = $state[$edit['state1']-1];
    $edit2 = $state[$edit['state2']-1];
    if($edit['state1'] != $edit['state2'])
    {
        // $mk_text = "修改了專案狀態| {$edit['state1']} --> {$edit['state2']}";
        $mk_text = "修改了專案狀態| $edit1 --> $edit2";
        echo $userName.$mk_text." ($when) <br>";
    }
    $sql = "INSERT INTO `mark_p` (`mkp_user`,`mkp_project`,`mkp_time`,`mkp_text`,`mkp_reason`)
                VALUE ('$who','$where','$when','$mk_text','$reason')";
    $rs = mysqli_query($conn,$sql);
}

//編輯專案-狀態
function MarkUpdateProject_state($who,$where,array $edit)
{
    global $conn;
    //紀錄時間
    $theDate = new DateTime();
    $stringDate = $theDate->format('Y-m-d H:i:s');
    $when = $stringDate;

    $userName = get_user_name($who);

    //修改專案狀態
    $state = array('inprogress','maintain','pending','complete','urgent');
    $edit1 = $state[$edit['state1']-1];
    $edit2 = $state[$edit['state2']-1];
    if($edit['state1'] != $edit['state2'])
    {
        // $mk_text = "修改了專案狀態| {$edit['state1']} --> {$edit['state2']}";
        $mk_text = "修改了專案狀態| $edit1 --> $edit2";
        
        $sql = "INSERT INTO `mark_p` (`mkp_user`,`mkp_project`,`mkp_time`,`mkp_text`)
                VALUE ('$who','$where','$when','$mk_text')";
        $rs = mysqli_query($conn,$sql);
    }
}

//--------------------------------------任務--------------------------------------//
//新增任務
function MarkCreateTask($who,$in,$where,$t_name)
{ 
    global $conn;
    //紀錄時間
    $theDate = new DateTime();
    $stringDate = $theDate->format('Y-m-d H:i:s');
    $when = $stringDate;

    $userName = get_user_name($who);
    $mk_text = "新增一項任務";
    echo $userName.$mk_text."-".$t_name." ($when) <br>";

    $sql = "INSERT INTO `mark_t` (`mkt_user`,`mkt_project`,`mkt_task`,`mkt_time`,`mkt_text`)
            VALUE ('$who','$in','$where','$when','$mk_text')";
    $rs = mysqli_query($conn,$sql);
}

//刪除任務
function MarkDeleteTask($who,$where)
{ 
    global $conn;
    //紀錄時間
    $theDate = new DateTime();
    $stringDate = $theDate->format('Y-m-d H:i:s');
    $when = $stringDate;

    $userName = get_user_name($who);
    $taskname = get_task_name($where);
    $mk_text = "刪除了任務-".$taskname;
    echo $userName.$mk_text." ($when) <br>";

    $sql2 = "SELECT t_project FROM task WHERE t_id = $where";
    $rs2 = mysqli_query($conn,$sql2);
    $rst2 = mysqli_fetch_array($rs2);
    $inproject = $rst2['t_project'];

    $sql = "INSERT INTO `mark_t` (`mkt_user`,`mkt_project`,`mkt_time`,`mkt_text`)
            VALUE ('$who','$inproject','$when','$mk_text')";
    $rs = mysqli_query($conn,$sql);
}

//編輯任務
function MarkUpdateTask($who,$in,$where,array $edit)
{ 
    global $conn;

    //紀錄時間
    $theDate = new DateTime();
    $stringDate = $theDate->format('Y-m-d H:i:s');
    $when = $stringDate;
    $reason = $edit['reason'];

    $userName = get_user_name($who);

    //修改任務名稱
    if($edit['name1'] != $edit['name2'])
    {
        $mk_text = "修改了任務名稱|{$edit['name1']} --> {$edit['name2']}";
        echo $userName.$mk_text." ($when) <br>";

        $sql = "INSERT INTO `mark_t` (`mkt_user`,`mkt_project`,`mkt_task`,`mkt_time`,`mkt_text`,`mkt_reason`)
                VALUE ('$who','$in','$where','$when','$mk_text','$reason')";
        $rs = mysqli_query($conn,$sql);
    }

    //修改時間
    if($edit['date11'] != $edit['date12'] || $edit['date21'] != $edit['date22'])
    {
        $date1 = $edit['date11']."~".$edit['date21'];
        $date2 = $edit['date12']."~".$edit['date22'];
        $mk_text = "修改了任務時間| $date1 --> $date2";
        echo $userName.$mk_text." ($when) <br>";

        $sql = "INSERT INTO `mark_t` (`mkt_user`,`mkt_project`,`mkt_task`,`mkt_time`,`mkt_text`,`mkt_reason`)
                VALUE ('$who','$in','$where','$when','$mk_text','$reason')";
        $rs = mysqli_query($conn,$sql);
    }

    //修改指派人員
    if($edit['user1'] != $edit['user2'])
    {
        $user1 = get_user_name($edit['user1']);
        $user2 = get_user_name($edit['user2']);
        $mk_text = "修改指派人員| $user1 --> $user2";
        echo $userName.$mk_text." ($when) <br>";

        $sql = "INSERT INTO `mark_t` (`mkt_user`,`mkt_project`,`mkt_task`,`mkt_time`,`mkt_text`,`mkt_reason`)
                VALUE ('$who','$in','$where','$when','$mk_text','$reason')";
        $rs = mysqli_query($conn,$sql);
    }

    
}

//--------------------------------------Miletone--------------------------------------//
//新增Milestone
function MarkCreateMilestone($who,$in,$where,$m_name)
{ 
    global $conn;
    //紀錄時間
    $theDate = new DateTime();
    $stringDate = $theDate->format('Y-m-d H:i:s');
    $when = $stringDate;

    $userName = get_user_name($who);
    $mk_text = "新增一項Milestone";
    echo $userName.$mk_text."-".$m_name." ($when) <br>";

    $sql = "INSERT INTO `mark_m` (`mkm_user`,`mkm_project`,`mkm_milestone`,`mkm_time`,`mkm_text`)
            VALUE ('$who','$in','$where','$when','$mk_text')";
    $rs = mysqli_query($conn,$sql);
}

//刪除Milestone
function MarkDeleteMilestone($who,$where)
{ 
    global $conn;
    //紀錄時間
    $theDate = new DateTime();
    $stringDate = $theDate->format('Y-m-d H:i:s');
    $when = $stringDate;

    $userName = get_user_name($who);
    $mstname = get_milestone_name($where);

    $mk_text = "刪除了Milestone-".$mstname;
    echo $userName.$mk_text." ($when) <br>";

    $sql2 = "SELECT mst_project FROM milestone WHERE mst_id = $where";
    $rs2 = mysqli_query($conn,$sql2);
    $rst2 = mysqli_fetch_array($rs2);
    $inproject = $rst2['mst_project'];

    $sql = "INSERT INTO `mark_m` (`mkm_user`,`mkm_project`,`mkm_time`,`mkm_text`)
            VALUE ('$who','$inproject','$when','$mk_text')";
    $rs = mysqli_query($conn,$sql);
}

//編輯Milestone
function MarkUpdateMilestone($who,$in,$where,array $edit)
{ 
    global $conn;

    //紀錄時間
    $theDate = new DateTime();
    $stringDate = $theDate->format('Y-m-d H:i:s');
    $when = $stringDate;
    $reason = $edit['reason'];

    $userName = get_user_name($who);

    //修改Milestone名稱
    if($edit['name1'] != $edit['name2'])
    {
        $mk_text = "修改了Milestone名稱|{$edit['name1']} --> {$edit['name2']}";
        echo $userName.$mk_text." ($when) <br>";

        $sql = "INSERT INTO `mark_m` (`mkm_user`,`mkm_project`,`mkm_milestone`,`mkm_time`,`mkm_text`,`mkm_reason`)
                VALUE ('$who','$in','$where','$when','$mk_text','$reason')";
        $rs = mysqli_query($conn,$sql);  
    }

    //修改時間
    if($edit['date1'] != $edit['date2'])
    {
        $mk_text = "修改了Milestone時間| {$edit['date1']} --> {$edit['date2']}";
        echo $userName.$mk_text." ($when) <br>";

        $sql = "INSERT INTO `mark_m` (`mkm_user`,`mkm_project`,`mkm_milestone`,`mkm_time`,`mkm_text`,`mkm_reason`)
                VALUE ('$who','$in','$where','$when','$mk_text','$reason')";
        $rs = mysqli_query($conn,$sql);  
    }

    //修改指派人員
    if($edit['user1'] != $edit['user2'])
    {
        $user1 = get_user_name($edit['user1']);
        $user2 = get_user_name($edit['user2']);
        $mk_text = "修改指派人員| $user1 --> $user2";
        echo $userName.$mk_text." ($when) <br>";

        $sql = "INSERT INTO `mark_m` (`mkm_user`,`mkm_project`,`mkm_milestone`,`mkm_time`,`mkm_text`,`mkm_reason`)
                VALUE ('$who','$in','$where','$when','$mk_text','$reason')";
        $rs = mysqli_query($conn,$sql);  
    }  
}

?>