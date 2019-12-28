<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (isset($LandUser)) {
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600);

    echo json_encode('{status:0}');
}
oj_mysql_close();
