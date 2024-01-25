<?php
session_start();
include("../connectsql.php");
include("../lib/normallib.php");
$uid = $_SESSION['UID'];
$uemail = $_SESSION['UEMAIL'];
$uname = $_SESSION['UNAME'];

//將新使用者的資料存進資料庫
$sql = "INSERT INTO `user_info` (`u_id`, `u_name`, `u_email`, `u_group`) 
        VALUES ('$uid', '$uname', '$uemail', 1)";
$rst = mysqli_query($conn,$sql);
write_staff_json();  //更新staff名單Json檔
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Group</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <form method="POST" action='gotohome.php'>
    <div class="container">
        <!-- <a href="../welcome.php" class="back-button">&lt; 返回PMIS </a> -->
        <h1 class="title">Welcome to PMIS</h1>
        <p class="info"> 工號：<?php echo $uid ?> </p>
        <p class="info"> 姓名：<?php echo $uname ?> </p>
        </br>
        <label for="group">請先選擇您的組別:</label>
        <select name="u_group" style="width: 100%">
        <?php
            $sql = "SELECT * FROM user_group WHERE g_outside = 'n'";
            $result = mysqli_query($conn,$sql);
            $totolrow = mysqli_num_rows($result);
            for($i=0; $i < $totolrow; $i++){
                $rst = mysqli_fetch_array($result);
                echo '<option value="' ,$rst['g_id'],'">' , $rst['g_name'] , '</option>';
            }
        ?>
        </select>
        <input name="u_id" type="hidden" value="<?php echo $uid; ?>" />
        <button name="submit" type="submit" class="confirm-button">確認-進入PMIS</button>
    </div>
  </form>
</body>
</html>