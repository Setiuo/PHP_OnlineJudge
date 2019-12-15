<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (is_admin()) {
    if (array_key_exists('RunID', $_GET)) {
        $RunID = intval($_GET['RunID']);
        $sql = "SELECT `Show` FROM `oj_status` WHERE `RunID`=" . $RunID . " LIMIT 1";
        $result = oj_mysql_query($sql);
        $row = oj_mysql_fetch_array($result);

        if ($row['Show'] == 1) {
            $sql = "UPDATE `oj_status` SET `Show`= 0 WHERE `RunID`=" . $RunID;
        } else {
            $sql = "UPDATE `oj_status` SET `Show`= 1 WHERE `RunID`=" . $RunID;
        }
        oj_mysql_query($sql);

        echo json_encode("{status:0}");
    } else {
        echo json_encode("{status:1}");
    }
} else {
    echo json_encode("{status:2}");
}
oj_mysql_close();
