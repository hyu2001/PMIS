<?php
session_start();
//從SESSION取出現在的filtered條件
$dptid = $_SESSION['filtered_dept'];
$uid = $_SESSION['filtered_member'];
$state = $_SESSION['filtered_state'];
$daterange = $_SESSION['filtered_daterange'];
$gid = $_SESSION['filtered_group'];
include("connectsql.php");
include("lib/getsql.php");
?>

<!DOCTYPE html>
<html>

<head>
  <title>PMIS</title>
  <meta charset="utf8">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Modaal/0.4.4/css/modaal.min.css"/><!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
  <!-- timeline -->
  <link rel="stylesheet" href="Frontend/public/js/dhtmlxgantt_material.css" type="text/css">
  <link  rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel='stylesheet' href='Frontend/public/css/style.css' />

</head>

<body class="close-bottom">
  <div id="header">
    <div class="header-inner">
      <h1><a href = "filter/filter_self.php" style="color:#ffffff; text-decoration:none;">PMIS</a></h1>
      <p>Project Manager System</p>

      <div class="user-info">
        <div class="content">
          <!-- <a href="#">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M12 1.99622C16.0499 1.99622 19.3567 5.19096 19.4958 9.24527L19.5 9.49622V13.5932L20.88 16.7492C20.949 16.9071 20.9847 17.0776 20.9847 17.25C20.9847 17.9403 20.425 18.5 19.7347 18.5L15 18.5015C15 20.1583 13.6568 21.5015 12 21.5015C10.4023 21.5015 9.09633 20.2526 9.00508 18.6778L8.99954 18.4992L4.27485 18.5C4.10351 18.5 3.93401 18.4648 3.77685 18.3965C3.14365 18.1215 2.8533 17.3852 3.12834 16.752L4.49999 13.5941V9.49611C4.50059 5.34132 7.85208 1.99622 12 1.99622ZM13.4995 18.4992L10.5 18.5015C10.5 19.3299 11.1716 20.0015 12 20.0015C12.7797 20.0015 13.4204 19.4066 13.4931 18.6459L13.4995 18.4992ZM12 3.49622C8.67983 3.49622 6.00047 6.17047 5.99999 9.49622V13.9058L4.65601 17H19.3525L18 13.9068L18.0001 9.50907L17.9964 9.28387C17.8853 6.0504 15.2416 3.49622 12 3.49622Z"
                fill="white" />
            </svg>
          </a>
          <a href="#">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M12 1.99902C17.5237 1.99902 22.0015 6.47687 22.0015 12.0006C22.0015 17.5243 17.5237 22.0021 12 22.0021C6.47626 22.0021 1.99841 17.5243 1.99841 12.0006C1.99841 6.47687 6.47626 1.99902 12 1.99902ZM12 3.49902C7.30469 3.49902 3.49841 7.3053 3.49841 12.0006C3.49841 16.6959 7.30469 20.5021 12 20.5021C16.6952 20.5021 20.5015 16.6959 20.5015 12.0006C20.5015 7.3053 16.6952 3.49902 12 3.49902ZM11.9963 10.4996C12.376 10.4994 12.69 10.7813 12.7399 11.1473L12.7468 11.2491L12.7504 16.7507C12.7507 17.1649 12.4151 17.5009 12.0009 17.5012C11.6212 17.5014 11.3072 17.2195 11.2573 16.8535L11.2504 16.7517L11.2468 11.2501C11.2465 10.8359 11.5821 10.4999 11.9963 10.4996ZM12.0004 7.00184C12.552 7.00184 12.9991 7.44896 12.9991 8.0005C12.9991 8.55205 12.552 8.99917 12.0004 8.99917C11.4489 8.99917 11.0017 8.55205 11.0017 8.0005C11.0017 7.44896 11.4489 7.00184 12.0004 7.00184Z"
                fill="white" />
            </svg>
          </a> -->
          <?php
            $groupname = get_group_name($_SESSION['UGROUP']);
          ?>
          <p><a href="user/edit_profile.php"><?php echo $groupname ?> Group, <?php echo $_SESSION['UNAME'] ?></a></p>
        </div>
    </div>
  </div>
  <div id="main">
    <div class="home-columns">
      <div class="home-sidebar">
        <?php include("filter/button-group.php"); ?>
      </div>
      <div class="home-content">
        <div class="filter-bar">
        <!-- filter 由此開始 -->
          <!-- <form method='POST' action='filter/session_filter.php'> -->
            <div class="custom-selectbox"><span class="the-title">成員</span>
              <div class="custom-select">
                  <?php include("filter/filter_member.php") ?>
              </div>
            </div>
            <div class="custom-selectbox"><span class="the-title">開案部門</span>
              <div class="custom-select">
                  <?php include("filter/filter_department.php") ?>
              </div>
            </div>
            <div class="custom-selectbox sz-md"><span class="the-title">專案狀態</span>
              <div class="custom-select">
                  <?php include("filter/filter_state.php") ?>
              </div>
            </div>
            <div class="custom-datetimes">
              <label for="">日期區間</label>
              <div class="datetimesbox"><i class="fa fa-calendar" aria-hidden="true"></i>
              <?php
                if($_SESSION['filtered_daterange'] == "null"){
                  echo "<input type='text' name='datefilter' value='' />";
                }else{
                  echo "<input type='text' name='datefilter' value='{$_SESSION['filtered_daterange']}' />";
                }
              ?>
              </div>
            </div>
            <a href="filter/clear.php" class="button">Clear </a>
            <button id="goToTodayButton" class="button" style="background-color: #1a73e8;">Today</button>
            <a href="#modal-editMsg-group" data-modaal-type="inline" data-modaal-animation="fade" class="modaal"></a>

            </div>
              <div class="main">
                <!-- 使用者工具列 -->
                <div class="user-tools">
                  <div class="switch-filter"><span class="dropdown">
                    <button class="dropdown-toggle"><i class="fas fa-arrow-down-wide-short"></i></button>
                    <label>
                      <input type="checkbox">
                      <ul>
                        <li><a class="dropdown-item" href="#">依專案字母排序</a></li>
                        <!-- <li><a class="dropdown-item" href="#">依成員排序</a></li> -->
                        <li><a class="dropdown-item" href="#">依日期排序</a></li>
                      </ul>
                    </label>
                  </span></div>
                <div class="switch-viewport"><div class="navigation js-btn-slide-container">
                  <span class="navigation__active-state js-btn-slide-active" role="presentation"></span>
                  <a class="navigation__btn navigation__btn--active js-btn-slide" role="button">日</a>
                  <a class="navigation__btn js-btn-slide" role="button">週</a>
                  <a class="navigation__btn js-btn-slide" role="button">月</a>
                </div>
              </div>

            <div class="switch-btn-1">
              <input class="switch-input" id="exampleSwitch" type="checkbox" name="exampleSwitch" />
              <label id="showData" class="switch-paddle" for="exampleSwitch"></label><span class="switch-text">Project Detail</span>
            </div>
          </form>
        <!--filter bar到此結束-->
        </div>
        
        <!-- <div class="main"> -->
            <!-- Timeline -->
            <div id="gantt_here" style="width: 100%; height: 100%"></div>
            <div id="no-result"></div>
            <div id="my-form"></div>
        <!-- </div> -->
      </div>
    </div>
    <div class="overlay"></div>
    <!-- Lightbox -->
    <div id="modal-choose-group" style="display:none">
      <div class="modal-content">
        <p>Verification successfully !</p>
        <p><b>Welcome to Progect Manager information system </b></p>
        <div class="custom-select sz-xl mb-20">
          <select name="" id="">
            <option value="0">選擇組別</option>
            <option value="0">ID</option>
            <option value="0">UI</option>
            <option value="0">UX</option>
          </select>
        </div>
        <a href="index.html" class="button-default">Submit</a>
      </div>
    </div>
                

    <!-- 異動紀錄Lightbox -->
    <div class="overlay"></div>
    <!-- Lightbox -->
    <div id="modal-editMsg-group" style="display:none;">
      <div class="modal-style2">
        <div class="modal-title"> 
          <h3>您確定要異動原排程嗎?</h3>
        </div>
        <div class="modal-content">
          <h4>請填選異動原因 *</h4>

          <!-- <form id='reason-form' method='post' action='#'> -->
          <div class="formGroup"> 
            <div class="col-6">
              <div class="form-check radio">
                <input name="test" value="123">
                <input type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="1" checked>
                  <label class="indicator" for="flexRadioDefault1">PM部分排程變更</label>
              </div>
            </div>
            <div class="col-6">
              <div class="form-check radio">
                <input type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="2">
                  <label class="indicator" for="flexRadioDefault2">開案時間延期</label>
              </div>
            </div>
            <div class="col-6">
              <div class="form-check radio">
                <input type="radio" name="flexRadioDefault" id="flexRadioDefault3" value="3">
                  <label class="indicator" for="flexRadioDefault3">因有急件須優先處理</label>
              </div>
            </div>
            <div class="col-6">
              <div class="form-check radio">
                <input type="radio" name="flexRadioDefault" id="flexRadioDefault4" value="4">
                  <label class="indicator" for="flexRadioDefault4">未收到執行所需資料</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-check radio">
                <input type="radio" name="flexRadioDefault" id="flexRadioDefault5" value="5">
                  <label class="indicator" for="flexRadioDefault5">其他</label>
                <input type="text" name="otherReason" class="input-text" value="">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="update_id" id="update_this" value="">
          <input type="submit" class="btn" id="cancel-reason" name="cancel_update"  value='取消'>
          <input type="submit" class="btn primary" id="confirm-reason">
        <!-- </form> -->
        </div>
      </div>
    </div>

    <div id="bottom-mark" class="close">
      <div class="item">
        <div class="accordion">
          <h3><i class="fa-solid fa-caret-down"></i> 異動紀錄</h3>
          <div class="mark-inner">
            <div class="scroll">
              <div class="table">
                
                <div class="tr">
                  <div class="td">
                    <div id="mark-here">
                      <p></p>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Javascript -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Modaal/0.4.4/js/modaal.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://cdn.ravenjs.com/3.10.0/raven.min.js"> </script><script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
  <!-- Timline -->
  <script src="Frontend/public/js/dhtmlxgantt.js"></script>
  <!-- <script src="Frontend/public/js/testdata.js"></script> -->
  <!-- <script src="staffData.js"></script> -->
  <script src="Frontend/public/js/timeline.js"></script>
  <script src="Frontend/public/js/web.js"></script>

  <script>
    const checkbox = document.querySelector('input[name=exampleSwitch]');
    const hidedata = document.querySelector('#hide-data');
    const viewdata = document.querySelector('#view-data');
    
    checkbox.addEventListener('change', function() {
        if (this.checked) {
          $(function(){
            $.ajax({
              url: 'show_board/notfound.php',
              dataType: 'json', 
              success: res =>{
                  console.log(res)
                  data = res;
              },
              error: err =>{
                  console.log(err)
              },
          }).then(function(){
              if (data == 0) {
                  document.getElementById('no-result').innerText = 'No result found';
              }else{
                gantt.clearAll();
                gantt.load("show_board/project_detail.php");
                console.log('project detail');
              }
          });
        });
      } else {
        $(function(){
          $.ajax({
              url: 'show_board/notfound.php',
              dataType: 'json',
              success: res =>{
                  console.log(res)
                  data = res;
              },
              error: err =>{
                  console.log(err)
              },
          }).then(function(){
              if (data == 0) {
                  document.getElementById('no-result').innerText = 'No result found';
              }else{
                gantt.clearAll();
                gantt.load("show_board/gantt_getdata.php");
                console.log('project filtered');
              }
          });
            
        });
        }
    });
  </script>
  <script>
    // 獲取按鈕元素
    var goToToday = document.getElementById("goToTodayButton");
    // 設定按鈕點擊事件
    goToToday.addEventListener("click", function() {
      // 獲取今天的日期
      var today = new Date();
      // 滾動到今天的日期
      gantt.showDate(today);
      console.log("scroll to Today");
    });
  </script>
</body>
</html>
