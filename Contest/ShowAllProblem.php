<?php
header('Content-Type:application/json; charset=gbk');

require_once("../Php/LoadData.php");

if (array_key_exists('ConID', $_GET)) {
    $ConID = intval($_GET['ConID']);

    if (can_edit_contest($ConID)) {
        $sql = "SELECT `Problem` FROM `oj_contest` WHERE `ConID`=" . $ConID . " LIMIT 1";
        $result = oj_mysql_query($sql);
        $ProblemArray = oj_mysql_fetch_array($result);

        $AllProblem = explode('|', $ProblemArray['Problem']);
        $ProNum = count($AllProblem);

        $sql = "UPDATE `oj_problem` SET `Show`= 1 WHERE `proNum`=" . $AllProblem[0];
        for ($i = 1; $i < $ProNum; $i++) {

            $sql .= ' OR `proNum`=' . $AllProblem[$i];
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
