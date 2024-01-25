<?php
session_start();
include("../connectsql.php");
if(isset($_POST['submit']))
{
    //將使用者選擇的group更新寫入至資料庫
    $g_id = $_POST['u_group'];
    $u_id = $_POST['u_id'];

    $sql = "UPDATE `user_info` SET `u_group` = {$g_id} WHERE `user_info`.`u_id` = '$u_id'";
    $rs = mysqli_query($conn,$sql);
    if($rs)
    {
        // echo "query成功";
        //紀錄當前登入的使用者的Group到SESSION中
        $_SESSION['UGROUP'] = $g_id;

        $_SESSION['isLogin'] = 1;  //已經登入成功，登入狀態設為1

        //轉至PMIS首頁
        echo "<script>window.location.href='../welcome.php'</script>";
    }
    else
    {
        echo "新增失敗".mysqli_error($conn);
    }
}
?>