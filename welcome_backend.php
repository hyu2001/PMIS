<?php
session_start();
?>
<!--主頁面，引用各功能程式碼-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> PMIS </title>
    
    <style>
	.hidden 
    {
		display: none;
	}
	</style>
</head>
<body>

<?php
if($_SESSION['isLogin']==1)  //有登入行為
{
    //引入連線資料庫的程式碼
    include_once("connectsql.php");

    //右上角顯示使用者基本資料
    $sql = "SELECT g_name FROM user_group WHERE g_id = '{$_SESSION['UGROUP']}'";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);
    echo "<div style='color:#fbfbfb;background-color:#2f3274;margin-bottom:10px;padding: 10px;width: 100%;display: flex;'>";
    echo "<b>&nbsp;&nbsp;".$_SESSION['UNAME']."</b>&nbsp;";
    echo ",<b>".$rst['g_name']."</b>&nbsp;Group";
    echo ",<b>".$_SESSION['UID']."</b>&nbsp;";
    echo "</div></div>";
    echo "<form method='post' action = '{$_SERVER['PHP_SELF']}'>";
    echo "<input type='submit' name='Logout' value='Log out'>";
    echo "</form>";

    //引入filter
    include("filter/button.php");

    //引入新增專案程式檔
    include("project/Create.php");

    include("show_board/show_view.php");
}
else
{
    //阻擋沒登入就直接訪問URL的使用者，將其導向至登入頁面
    echo "<script>window.location='Login.php'</script>"; 
}

if(isset($_POST['Logout']))  //登出->導至登入頁面
{
    $_SESSION['isLogin'] = 0;
    echo "<script>window.location='Login.php'</script>";
}
?>