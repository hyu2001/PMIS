<!--以基本表格顯示從資料庫抓到的資料，並在每筆後方提供"刪除"與"編輯"按鈕-->

<?php
$sql = "SELECT* FROM user_group ORDER BY `g_id` ASC";
$result = mysqli_query($conn,$sql);

//以表格顯示資料
    echo "</br><table border='1'><tr><td>Group編號</td><td>Group名稱</td><td>外部Group</td><td>顏色</td>";

    while($row = mysqli_fetch_array($result)){
        echo "<tr><td>{$row['g_id']}</td>
              <td>{$row['g_name']}</td>
              <td>{$row['g_outside']}</td>
              <td bgcolor='{$row['g_color']}'></td>";
        if($row['g_id'] == 1){
            echo "<td></td>";
        }else{
            echo "<td><form method='post' action='group/Delete.php?'>
            <input type='hidden' name='del' value={$row['g_id']}>
            <button type='submit' onclick='return confirmDelete()'>刪除</button>
            </form></td>";
        }
        
        echo "<td><a href=group/Update.php?edit={$row['g_id']}>編輯</a></td></tr>";
    }
    echo "</table>";
?>

<script>
function confirmDelete() 
{
    if (confirm("確定要刪除該筆資料嗎？")) 
    {
        return true; // 確認後提交表單
    } 
    else 
    {
        return false; // 取消提交表單
    }
}
</script>