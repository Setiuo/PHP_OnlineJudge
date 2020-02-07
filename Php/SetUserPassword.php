<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (is_admin_max()) {
    $user = $_GET['user'];

    $sql = "UPDATE `oj_user` SET `password`= 'd8599493ae274d2416d2e50dd9397305' WHERE `name`='" . $user . "'";
    $result = oj_mysql_query($sql);
    if ($result) {
        echo json_encode("{status:0}");
    } else {
        echo json_encode("{status:1}");
    }
} else {
    echo json_encode("{status:1}");
}
oj_mysql_close();
