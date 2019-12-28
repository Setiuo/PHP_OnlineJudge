<?php
header('Content-Type:application/json; charset=gbk');
require_once("LoadData.php");

$data = "{status: 1}";
if (can_edit_problem()) {
    if (array_key_exists('Problem', $_GET)) {
        $ProblemID = intval($_GET['Problem']);
        $sql = "SELECT `Show` FROM `oj_problem` WHERE `proNum`=" . $ProblemID . " LIMIT 1";
        $result = oj_mysql_query($sql);
        $row = oj_mysql_fetch_array($result);

        if ($row['Show'] == 1) {
            $sql = "UPDATE `oj_problem` SET `Show`= 0 WHERE `proNum`=" . $ProblemID;
        } else {
            $sql = "UPDATE `oj_problem` SET `Show`= 1 WHERE `proNum`=" . $ProblemID;
        }
        oj_mysql_query($sql);

        $data = "{status: 0}";
    } else {
        $data = "{status: 1}";
    }
} else {
    $data = "{status: 1}";
}

echo json_encode($data);
oj_mysql_close();
