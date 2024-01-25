<?php
include("../connectsql.php");
//---------------------------------------------//

//從資料庫的date資料型態轉成date-picker格式
//傳入兩個日期格式: 開始日: YYYY-MM-DD 和 結束日:YYYY-MM-DD
//轉乘date-picker(時間選擇插件)所需的格式: MM/DD/YYYY - MM/DD/YYYY
function db_to_datepicker($DBdate1,$DBdate2){
    
    $year1 = substr($DBdate1,0,4);
    $month1 = substr($DBdate1,5,2);
    $day1 = substr($DBdate1,8,2);
    $pickerFormat1 = $month1."/".$day1."/".$year1;
    
    $year2 = substr($DBdate2,0,4);
    $month2 = substr($DBdate2,5,2);
    $day2 = substr($DBdate2,8,2);
    $pickerFormat2 = $month2."/".$day2."/".$year2;

    $pickerFormat = $pickerFormat1." - ".$pickerFormat2;
    return $pickerFormat;
}

//---------------------更新Project的開始時間和結束時間---------------------//
function proj_date_range($p_id)
{
    global $conn;
    $sql = "SELECT `date1`
                FROM (
                SELECT `t_date1` AS `date1`
                FROM `task`
                WHERE `t_project` = $p_id
                UNION 
                SELECT `mst_date` AS `date1`
                FROM `milestone`
                WHERE `mst_project` = $p_id
                ) AS DATE1
            ORDER BY date1 ASC LIMIT 0 , 1";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);
    if($rst == null)    //如果刪除的是最後一個子項目，就更新成當天時間
    {
        $today = date("Y-m-d"); 
        $date1 = $today;
    }
    else
    {
        $date1 = $rst['date1'];
    }
    
    $sql = "SELECT `date2`
                FROM (
                SELECT `t_date2` AS `date2`
                FROM `task`
                WHERE `t_project` = $p_id
                UNION 
                SELECT `mst_date` AS `date2`
                FROM `milestone`
                WHERE `mst_project` = $p_id
                ) AS DATE2
            ORDER BY date2 DESC LIMIT 0 , 1";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);
    if($rst == null)    //如果刪除的是最後一個子項目，就更新成當天時間
    {
        $today = date("Y-m-d"); 
        $date2 = $today;
    }
    else
    {
        $date2 = $rst['date2'];
    }

    $sql = "UPDATE `project` SET `p_date1`='$date1', `p_date2`='$date2' WHERE  `p_id`='$p_id'";
    mysqli_query($conn,$sql); 
}


//---------------------更新member名單並寫入json檔---------------------//
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

//---------------------將js送過來的日期字串轉為資料庫需要的格式---------------------//
function jsdate_to_dbdate($sdate){
    $month_short = array(
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    );
    
    $year = substr($sdate,11,4);
    $month = substr($sdate,4,3);
    for($i=0;$i<sizeof($month_short);$i++){
        if($month == $month_short[$i]){
            $month = $i+1;
            break;
        }
    }
    $day = substr($sdate,8,2);
    
    $start_day = $year."-".$month."-".$day;
    echo $start_day;
    return $start_day;
}
?>