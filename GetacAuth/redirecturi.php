<?php
session_start();
$_SESSION['isLogin'] = 0; //判斷現在是否已經登入，初始化為0
//取得網頁回覆的token值
$token = $_GET['token'];

//設置sso的url
$url = 'https://sso.getac.com/Auth/UserInfo';

//client
$username = 'TBNIVgikHUCJLxPOiIu7MkyfDeafeYnE';
$password = '7yqnxfpGJKWFUOL82MJSdygsNsE0GCFtq7LWGlaCdnyBOsLzSbmROUlJS7ZFbyAG';

$json_data = array
(
    'Token' => $token
);

$data_string = json_encode($json_data);

$headers = array
(
    'Content-Type: application/json',
    'Authorization: Bearer '.base64_encode("$username:$password")
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE); 

$response = curl_exec($ch);
curl_close($ch);

$user_info = json_decode($response);

//從SSO取得登入user的資料
if($user_info===NULL)
{
    echo "回傳資料為空 <br>";
}
else
{
    $uid = $user_info->UserDetail->EmpNo; 
    $uemail = $user_info->UserDetail->Email;
    $uname = $user_info->UserDetail->EmpName;
    
    $_SESSION['UID'] = $uid;
    $_SESSION['UEMAIL'] = $uemail;
    $_SESSION['UNAME'] = $uname;
}

//載入所需php檔
include("../connectsql.php");

//獲取登入的使用者資料-from database
$sql = "SELECT * FROM `user_info`
        INNER JOIN user_group ON user_info.u_group = user_group.g_id
        WHERE `u_id`='$uid'";
$rst = mysqli_query($conn,$sql);
$row = mysqli_num_rows($rst);

//初始進入系統清空filter
$_SESSION['filtered_group'] = 0;
$_SESSION['filtered_member'] = array($_SESSION['UID']);
$_SESSION['filtered_dept'] = array(0);
$_SESSION['filtered_state'] = array(0);
$_SESSION['filtered_daterange'] = "null";

//初始進入系統所顯示的資料
$_SESSION['current_project'] = 0;
$_SESSION['current_group'] = 0;
$_SESSION['current_member'] = array($_SESSION['UID']);  //先設置current_member=0，這樣到一開始gantt_getdata的才有東西抓
$_SESSION['current_dept'] = array();
$_SESSION['current_state'] = array();
$_SESSION['current_daterange'] = "null";
$_SESSION['current_sort'] = "letter";

//進入系統首先顯示登入user自己的project
$uid = $_SESSION['UID'];
$sql = "SELECT DISTINCT p_id FROM project 
        INNER JOIN show_all ON project.p_id = show_all.project
        WHERE u_id IN ('$uid') ORDER BY SUBSTR(TRIM(p_name),1,1) ASC";    
$rs = mysqli_query($conn,$sql); 
$pid = array();
while($project = $rs->fetch_assoc())
{
    array_push($pid,$project['p_id']);
};
$_SESSION['current_project'] = $pid;  


if ($row>0)   //已經有使用者資料 = 已註冊過 -> 直接進入系統主頁
{
    //紀錄當前登入的使用者的Group到SESSION中
    $sql = "SELECT * FROM user_info
            WHERE u_id = '$uid'";
    $rs = mysqli_query($conn,$sql);
    $rst = mysqli_fetch_array($rs);
    $_SESSION['UGROUP'] = $rst['u_group']; 
    
    $_SESSION['isLogin'] = 1;  //已經登入成功，登入狀態設為1

    $url = "../welcome.php";
    echo "<script type='text/javascript'>";
    echo "window.location.href='$url'";
    echo "</script>";
} 
else          //資料庫中還沒有使用者資料->第一次登入->跳轉至選group畫面
{
    // header("Location:select_group.php");
    // exit();

    $url = "select_group.php";
    echo "<script type='text/javascript'>";
    echo "window.location.href='$url'";
    echo "</script>";
}

?>