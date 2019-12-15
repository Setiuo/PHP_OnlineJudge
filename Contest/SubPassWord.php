<?php
header('Content-Type:application/json; charset=gbk');

require_once("../Php/LoadData.php");

if (isset($_POST["ConID"]) && isset($_POST["ConPassWord"])) {
    $sql = "SELECT `PassWord` FROM `oj_contest` WHERE `ConID`=" . $_POST["ConID"] . " LIMIT 1";
    $result = oj_mysql_query($sql);

    if (!isset($result)) {
        echo json_encode('{status:1}');
        return;
    }

    $ConData = oj_mysql_fetch_array($result);

    if ($ConData['PassWord'] == $_POST["ConPassWord"]) {

        $_SESSION['ConPassWord_' . $_POST["ConID"]] = $_POST["ConPassWord"];
        echo json_encode('{status:0, href:"/Contest/Pandect.php?ConID=' . $_POST["ConID"] . '"}');
    } else {
        echo json_encode('{status: 1}');
    }
} else {
    echo json_encode('{status: 2}');
}
oj_mysql_close();
