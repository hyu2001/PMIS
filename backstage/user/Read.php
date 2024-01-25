<!--以基本表格顯示從資料庫抓到的資料，並在每筆後方提供"刪除"與"編輯"按鈕-->

<?php
$sql = "SELECT* FROM user_info WHERE u_email IS NOT NULL ORDER BY `u_id` ASC";
$result = mysqli_query($conn,$sql);


//以表格顯示資料
    echo "<table border='1'><tr><td>UID</td><td>姓名</td><td>組</td>";

    while($row = mysqli_fetch_array($result)){
        $sql2 = "SELECT `g_name` FROM user_group WHERE `g_id` = $row[3] ";  //透過外鍵Ugroup的編號抓取group名稱
        $result2 = mysqli_query($conn,$sql2);
        $row2 = mysqli_fetch_array($result2);
        echo "<tr><td>{$row['u_id']}</td><td>{$row['u_name']}</td><td>{$row2['g_name']}</td>";
        echo "<td><a href=user/Update.php?edit={$row['u_id']}>編輯</a></td></tr>";
    }
    echo "</table>";
?>