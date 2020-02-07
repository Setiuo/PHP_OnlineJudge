<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (is_admin_max()) {
    $jur = $_GET['jur'];
    $user = $_GET['user'];

    $sql = "UPDATE `oj_user` SET `jurisdiction`= $jur WHERE `name`='" . $user . "'";
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
