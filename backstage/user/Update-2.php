<?php
session_start();
include("../../connectsql.php");
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

function write_staff_json()
{
    global $conn;
    $sql = "SELECT `u_id` AS `key`, `u_name` AS `label` FROM user_info";
    $rs = mysqli_query($conn, $sql);

    if ($rs) {
        $staffData = array();

        // 將查詢結果轉換為關聯陣列
        while ($row = mysqli_fetch_assoc($rs)) {
            $staffData[] = $row;
        }

        // 將資料轉換為 JSON 格式
        $jsonData = json_encode($staffData, JSON_PRETTY_PRINT);

        // 輸出 JSON 檔案
        $filename = 'C:\xampp\htdocs\mytest\azureGit\2023-IDC-PMIS\lib\staff_data.js';
        file_put_contents($filename, $jsonData);
    } else {
        echo "查詢資料時發生錯誤：" . mysqli_error($conn);
    }
}
?>
<p><a href="../index.php">回個人資訊</a></p>