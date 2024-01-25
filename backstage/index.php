<!--主頁面，引用各功能程式碼-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> PMIS Backstage Management </title>
</head>
<body>
    <h1 style='color:#383947'>PMIS Backstage Management</h1>
    <?php

    //連線資料庫的程式碼
    include_once("../connectsql.php");

    echo "<hr style='border-color:blue; border-width:5px;'>";
    //=====Group====//
    echo "<h2><span style='background-color: #D3D3D3;'>Group</span></h2>";
    //新增資料
    include('group/Create.php');
    //讀取資料，並把資料顯示在頁面
    include('group/Read.php');


    //=====Department=====//
    echo "<h2><span style='background-color: #D3D3D3;'>Department</span></h2>";
    //新增資料
    include('department/Create.php');
    //讀取資料，並把資料顯示在頁面
    include('department/Read.php');


    //=====user=====//
    echo "<h2><span style='background-color: #D3D3D3;'>User list</span></h2>";
    //讀取資料，並把資料顯示在頁面
    include('user/Read.php');
    ?>
</body>
</html>

