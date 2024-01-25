<!--主頁面，引用各功能程式碼-->

<?php
//引入連線資料庫的程式碼
include_once("../connectsql.php");

//引入新增資料程式檔
include('Create.php');

//引入讀取資料程式檔，並把資料顯示在頁面
include('Read.php');
?>
