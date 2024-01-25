<?php
session_start();
include("../connectsql.php");
include("../lib/normallib.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <form method="POST" action='Update-2.php'>
    <div class="container">
        <a href="../welcome.php" class="back-button">&lt; 返回PMIS </a>
        <h1 class="title">My Profile</h1>
        <p class="info">工號：<?php echo $_SESSION['UID'] ?></p>
        <label for="name">姓名:</label>
        <input type="text" id="u_name" name="u_name" value="<?php echo $_SESSION['UNAME']; ?>" placeholder="<?php echo $_SESSION['UNAME']; ?>">
        <label for="group">組:</label>
        <select name="u_group" style="width: 100%">
        <?php
            $sql = "SELECT * FROM user_group WHERE g_outside = 'n'";
            $result = mysqli_query($conn,$sql);
            $totolrow = mysqli_num_rows($result);
            for($i=0; $i < $totolrow; $i++){
                $rst = mysqli_fetch_array($result);
                if($rst['g_id']==$_SESSION['UGROUP']){
                    echo '<option value="' ,$rst['g_id'],'" selected>' , $rst['g_name'] , '</option>';
                }else{
                    echo '<option value="' ,$rst['g_id'],'">' , $rst['g_name'] , '</option>';
                }
            }
        ?>
        </select>
        <input name="u_id" type="hidden" value="<?php echo $_SESSION['UID']; ?>" />
        <button name="submit" type="submit" onclick="showConfirmation()" class="confirm-button">確認修改</button>
    </div>
  </form>
</body>
</html>