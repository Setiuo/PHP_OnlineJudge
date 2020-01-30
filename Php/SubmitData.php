<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (!isset($LandUser)) {
    echo json_encode('{status:1}');
    oj_mysql_close();
    die();
}
if (!can_edit_problem()) {
    echo json_encode('{status:1}');
    oj_mysql_close();
    die();
}

$problemID = intval($_POST['problemID']);
$dataID = intval($_POST['dataID']);
$dataNum = intval($_POST['dataNum']);
$inData = addslashes($_POST['inputData']);
$outData = addslashes($_POST['outputData']);

$sql = "SELECT `problemID` FROM `oj_problem_test` WHERE `problemID`=$problemID LIMIT 1";
$have = oj_mysql_query($sql);
$row = oj_mysql_fetch_array($have);
if (!$row) {
    $sql = "INSERT INTO `oj_problem_test` (`problemID`) VALUES ($problemID);";
    oj_mysql_query($sql);
}

$sql = "UPDATE `oj_problem_test` SET `testNum`=$dataNum,  `test" . $dataID . "_in`='$inData', `test" . $dataID . "_out`='$outData' WHERE `problemID` = $problemID LIMIT 1";
$result = oj_mysql_query($sql);

if ($result) {
    echo json_encode('{status:0}');
} else {
    echo json_encode('{status:1}');
}

oj_mysql_close();
