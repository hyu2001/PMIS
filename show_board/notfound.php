<?php
session_start();
if($_SESSION['current_project'][0] == 0){ //代表目前是"No Result Found"
    echo 0;
}else{
    echo 1;
}
?>