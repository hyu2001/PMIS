<!--以基本表格顯示從資料庫抓到的資料，並在每筆後方提供"刪除"與"編輯"按鈕-->

<?php
$sql = "SELECT* FROM department ORDER BY `dpt_id` ASC";
$result = mysqli_query($conn,$sql);

//以表格顯示資料
    echo "</br><table border='1'><tr><td>部門編號</td><td>部門名稱</td>";

    while($row = mysqli_fetch_array($result)){
        echo "<tr><td>{$row['dpt_id']}</td><td>{$row['dpt_name']}</td>";
        if($row['dpt_id'] == 1){
            echo "<td></td>";
        }else{
            echo "<td><form method='post' action='department/Delete.php?'>
                  <input type='hidden' name='del' value={$row['dpt_id']}>
                  <button type='submit' onclick='return confirmDelete()'>刪除</button>
                  </form></td>";
        }
        
        echo "<td><a href=department/Update.php?edit={$row['dpt_id']}>編輯</a></td></tr>";
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