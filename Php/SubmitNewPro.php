<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (!isset($LandUser)) {
    echo json_encode('{status:1}');
    oj_mysql_close();
    return;
}
if (!can_edit_problem()) {
    echo json_encode('{status:1}');
    oj_mysql_close();
    return;
}

$iDescription = addslashes($_POST['Description']);
$iInputFormat = addslashes($_POST['InputFormat']);
$iOutputFormat = addslashes($_POST['OutputFormat']);
$iExpInput = addslashes($_POST['ExpInput']);
$iExpOutput = addslashes($_POST['ExpOutput']);
$iHint = addslashes($_POST['Hint']);
$iSource = addslashes($_POST['Source']);

if ($_POST['NewType'] == 1) {
    $sql = "UPDATE `oj_problem` SET `Name` = '" . $_POST['ProName'] . "', `LimitTime` = '" . $_POST['LimitTime'] . "', `LimitMemory` = '" . $_POST['LimitMemory'] . "', `Description` = '" . $iDescription . "', `InputFormat` = '" . $iInputFormat . "', `OutputFormat` = '" . $iOutputFormat . "', `EmpInput` = '" . $iExpInput . "', `EmpOutput` = '" . $iExpOutput . "', `Hint` = '" . $iHint . "', `Source` = '" . $iSource . "', `Test` = '" . $_POST['Test'] . "' WHERE proNum = '" . $_POST['ProNum'] . "'";
    $result = oj_mysql_query($sql);
    echo json_encode('{status:0, type:1}');
} else {
    $sql = "INSERT INTO `oj_problem` (`Name`, `proNum`, `LimitTime`, `LimitMemory`, `Description`, `InputFormat`, `OutputFormat`, `EmpInput`, `EmpOutput`, `Hint`, `Source`, `CreateTime`, `Test`) VALUES ('" . $_POST['ProName'] . "', '" . $_POST['ProNum'] . "', '" . $_POST['LimitTime'] . "', '" . $_POST['LimitMemory'] . "','" . $iDescription . "', '" . $iInputFormat . "', '" . $iOutputFormat . "', '" . $iExpInput . "', '" .  $iExpOutput . "', '" . $iHint . "', '" . $iSource . "', '" . date('Y-m-d') . "', '" . $_POST['Test'] . "');";
    $result = oj_mysql_query($sql);
    echo json_encode('{status:0, type:0}');
}

oj_mysql_close();
