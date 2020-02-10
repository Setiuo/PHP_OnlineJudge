<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (is_admin_max()) {
    if (array_key_exists('RunID', $_GET) && array_key_exists('ConID', $_GET)) {
        $RunID = intval($_GET['RunID']);
        $ConID = intval($_GET['ConID']);

        $sql = "SELECT `prohibit` FROM `oj_judge_task` WHERE `runID`=$RunID AND `contestID`=$ConID LIMIT 1";
        $result = oj_mysql_query($sql);
        $row = oj_mysql_fetch_array($result);

        if ($row['prohibit'] == 1) {
            $sql = "UPDATE `oj_judge_task` SET `prohibit`= 0 WHERE `runID`=$RunID AND `contestID`=$ConID";
        } else {
            $sql = "UPDATE `oj_judge_task` SET `prohibit`= 1 WHERE `runID`=$RunID AND `contestID`=$ConID";
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
