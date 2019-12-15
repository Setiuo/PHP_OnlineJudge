<?php
header('Content-Type:application/json; charset=gbk');

require_once("../Php/LoadData.php");

if (!isset($LandUser)) {
    echo json_encode('{status: 2}');
    return;
}

if (isset($_GET["ConID"])) {
    $sql = "SELECT `EnrollPeople` FROM `oj_contest` WHERE `ConID`=" . intval($_GET["ConID"]) . " LIMIT 1";
    $result = oj_mysql_query($sql);

    if (!$result) {
        echo json_encode('{status: 1}');
        oj_mysql_close();
        return;
    }

    $ConData = oj_mysql_fetch_array($result);

    $AllPeople = $ConData['EnrollPeople'];
    $Data = explode('|', $AllPeople);

    if (!in_array($LandUser, $Data)) {
        if ($AllPeople == "") {
            $AllPeople = $LandUser;
        } else {
            $AllPeople .= ('|' . $LandUser);
        }

        $sql = "UPDATE `oj_contest` SET `EnrollPeople` = '" . $AllPeople . "' WHERE `ConID`='" . intval($_GET["ConID"]) . "'";
        oj_mysql_query($sql);

        echo json_encode('{status: 0}');
    }
}

oj_mysql_close();
