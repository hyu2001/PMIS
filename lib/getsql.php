<?php
include_once("connectsql.php");
/*---------------------------------*/ 

function get_user_name($u_id)
{
    global $conn;
    $sql = "SELECT u_name FROM user_info WHERE u_id = '$u_id'";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);
    return $rst['u_name'];
}

function get_group_name($g_id)
{
    global $conn;
    $sql = "SELECT g_name FROM user_group WHERE g_id = '$g_id'";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);
    $totol = mysqli_num_rows($rs);
    if($totol==0)
    {
        return '全部';
    }
    else
    {
        return $rst['g_name'];
    }
}

function get_dept_name($dpt_id)
{
    global $conn;
    $sql = "SELECT dpt_name FROM department WHERE dpt_id = '$dpt_id'";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);

    return $rst['dpt_name'];
}

function get_project_name($p_id)
{
    global $conn;
    $sql = "SELECT p_name FROM project WHERE p_id = '$p_id'";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);

    return $rst['p_name'];
}

function get_task_name($t_id)
{
    global $conn;
    $sql = "SELECT t_name FROM task WHERE t_id = '$t_id'";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);

    return $rst['t_name'];
}

function get_milestone_name($mst_id)
{
    global $conn;
    $sql = "SELECT mst_name FROM milestone WHERE mst_id = '$mst_id'";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);

    return $rst['mst_name'];
}
?>
