<?php
header('Content-Type:application/json; charset=gbk');

require_once("../Php/LoadData.php");

if (array_key_exists('ConID', $_GET)) {
    $ConID = intval($_GET['ConID']);

    if (can_edit_contest($ConID)) {
        $sql = "SELECT `Show` FROM `oj_contest` WHERE `ConID`=" . $ConID . " LIMIT 1";
        $result = oj_mysql_query($sql);
        $row = oj_mysql_fetch_array($result);

        if ($row['Show'] == 1) {
            $sql = "UPDATE `oj_contest` SET `Show`= 0 WHERE `ConID`=" . $ConID;
        } else {
            $sql = "UPDATE `oj_contest` SET `Show`= 1 WHERE `ConID`=" . $ConID;
        }
        oj_mysql_query($sql);

        echo json_encode("{status:0}");
    } else {
        echo json_encode("{status:1}");
    }
} else {
    echo json_encode("{status:2}");
}

oj_mysql_close();;
