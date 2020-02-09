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
$inData = addslashes($_POST['inputData']);
$outData = addslashes($_POST['outputData']);

$sql = "INSERT INTO oj_problem_data(problemID, testID, input, output) VALUE($problemID, $dataID, '$inData', '$outData') ON DUPLICATE KEY UPDATE `input`= '$inData', `output`='$outData'";
$result = oj_mysql_query($sql);

if ($result) {
    echo json_encode('{status:0}');
} else {
    echo json_encode('{status:1}');
}

oj_mysql_close();
