<!--新增group的輸入框-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> PMIS-GROUP </title>
</head>
<body>
    <form method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
    新增Group名稱: <input name = groupname>
    &nbsp;&nbsp;
    <input type = "checkbox" name = outside> 不是team group
    <input name='submit' type='submit' value='新增'>
    </form>
</body>
</html>

<?php
//執行SQL INSERT語句新增資料
if(!empty($_POST['groupname'])){
    $name = $_POST['groupname'];
    $outside = isset($_POST['outside'])? 'y':'n';
    $sql = "INSERT INTO `user_group` (`g_id`, `g_name`,`g_outside`) 
            VALUES (' ','$name','$outside')";
    mysqli_query($conn,$sql);

    if($outside=='y')
    {
        $sql = "SELECT * FROM `user_group`  ORDER BY g_id DESC LIMIT 0 , 1";
        $rs = mysqli_query($conn,$sql);
        $rst = mysqli_fetch_array($rs);

        $uid = 'OT'.$rst['g_id'];
        $sql2 = "INSERT INTO user_info (`u_id`, `u_name`,`u_group`)
                    VALUES('$uid', '{$rst['g_name']}', '{$rst['g_id']}')";
        mysqli_query($conn,$sql2);
    }
} 
?>
