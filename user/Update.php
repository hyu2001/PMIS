<?php
include("../connectsql.php");

if($_GET['edit'] != ''){
    $id = $_GET['edit'];
    $sql = "SELECT* FROM `user_info`  WHERE `user_info`.`u_id` = '$id'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);
}
else{
    // header("Location: index.php");
    $url = "../index.php"; 
    echo "<script type='text/javascript'>";
    echo "window.location.href='$url'";
    echo "</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset=""utf-8>
    <title>PMIS-USER</title>
</head>
<bode>
    <form method="POST" action="Update-2.php">
        UID:<?php echo $row['u_id']; ?>
        <br>
        姓名:<input name="u_name" value="<?php echo $row['u_name']; ?>" style="width:75px" />
        組: 
        <select name="u_group"> <!--下拉選單選擇user屬於的gorup-->
            <?php
            $sql = "SELECT * FROM user_group";
            $result = mysqli_query($conn,$sql);
            $totolrow = mysqli_num_rows($result);
            for($i=0; $i < $totolrow; $i++){
                $rst = mysqli_fetch_array($result);
                echo '<option value="' ,$rst['g_id'],'">' , $rst['g_name'] , '</option>';
            }
            ?>
        </select>
        <input name="u_id" type="hidden" value="<?php echo $row['u_id']; ?>" />
        <input name="submit" type="submit" value="送出" />
    </form>
    <p><a href="index.php">回上一頁</a></p>
</body>