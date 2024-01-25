<?php
session_start();
include("../connectsql.php");
include("../lib/normallib.php");
if(isset($_POST['submit'])){
    if($_POST['u_name']==null){
        echo "<font color='red' size=5>姓名不可為空!</font><br>";
    }else{
        $sql = "UPDATE user_info SET `u_name` ='{$_POST['u_name']}' , `u_group` ='{$_POST['u_group']}' WHERE `u_id`='{$_POST['u_id']}'";
        if(!empty($_POST['u_id']) && !empty($_POST['u_name']) && !empty($_POST['u_group'])){
            $sql = "UPDATE user_info SET `u_name` ='{$_POST['u_name']}' , `u_group` ='{$_POST['u_group']}' WHERE `u_id`='{$_POST['u_id']}'";
            if (mysqli_query($conn, $sql)) {
                echo "<font color='red' size=5>成功編輯User資料</font><br>";
                write_staff_json();  //更新staff名單Json檔
                $_SESSION['UNAME'] = $_POST['u_name'];
                $_SESSION['UGROUP'] = $_POST['u_group'];
            } 
            else{
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }
    
}
?>
<p><a href="edit_profile.php">回個人資訊</a></p>