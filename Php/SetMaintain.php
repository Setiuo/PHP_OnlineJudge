<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (!is_admin_max()) {
    echo json_encode("{status: 1}");
}

if ($Maintain == 1) {
    $sql = "UPDATE `oj_data` SET `maintain`= 0 LIMIT 1";
} else {
    $sql = "UPDATE `oj_data` SET `maintain`= 1 LIMIT 1";
}

$result = oj_mysql_query($sql);

if ($result)
    echo json_encode("{status: 0}");
else
    echo json_encode("{status: 1}");

oj_mysql_close();
