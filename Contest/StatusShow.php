<?php
header('Content-Type:application/json; charset=gbk');

require_once("../Php/LoadData.php");


if (array_key_exists('RunID', $_GET) && array_key_exists('ConID', $_GET)) {
    $RunID = intval($_GET['RunID']);
    $ConID = intval($_GET['ConID']);

    $sql = "SELECT `Show` FROM `oj_constatus` WHERE `RunID`=$RunID AND `ConID`=$ConID LIMIT 1";
    $result = oj_mysql_query($sql);
    $row = oj_mysql_fetch_array($result);

    if (can_edit_contest($ConID)) {
        if ($row['Show'] == 1) {
            $sql = "UPDATE `oj_constatus` SET `Show`= 0 WHERE `RunID`=$RunID AND `ConID`=$ConID LIMIT 1";
        } else {
            $sql = "UPDATE `oj_constatus` SET `Show`= 1 WHERE `RunID`=$RunID AND `ConID`=$ConID LIMIT 1";
        }
        oj_mysql_query($sql);

        echo  json_encode("{status: 0}");
    } else {
        echo  json_encode("{status: 1}");
    }
} else {
    echo  json_encode("{status: 2}");
}
oj_mysql_close();
