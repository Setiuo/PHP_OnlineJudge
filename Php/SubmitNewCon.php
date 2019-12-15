<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (!isset($LandUser)) {
    echo json_encode('{status:1}');
    oj_mysql_close();
    return;
}
if (!can_edit_contest()) {
    echo json_encode('{status:1}');
    oj_mysql_close();
    return;
}

$AllProblem = explode('|', $_POST['Problem']);
$ProNum = count($AllProblem);
for ($i = 0; $i < $ProNum; $i++) {
    $sql = "SELECT `proNum` FROM `oj_problem` WHERE `proNum`=" . $AllProblem[$i] . " LIMIT 1";
    $result = oj_mysql_query($sql);

    if (!$result) {
        echo json_encode('{status:2}');
        oj_mysql_close();
        return;
    } else {
        $num = mysqli_num_rows($result);

        if (!$num) {
            echo json_encode('{status:2}');
            oj_mysql_close();
            return;
        }
    }
}

$Type = ($_POST['Type'] == 'Public') ? 0 : 1;

if ($_POST['NewType'] == 1) {
    $sql = "UPDATE `oj_contest` SET `Title` = '" . $_POST['Title'] . "', `ConID` = " . $_POST['ConID'] . ", `Synopsis` = '" . addslashes($_POST['Synopsis']) . "', `Organizer` = '" . $_POST['Organizer'] . "', `Rule` = '" . $_POST['Rule'] . "', `Type` = " . $Type . ", `PassWord` = '" . $_POST['PassWord'] . "', `StartTime` = '" . $_POST['StartTime'] . "', `OverTime` = '" . $_POST['OverTime'] . "', `FreezeTime` = '" . $_POST['FreezeTime'] . "', `UnfreezeTime` = '" . $_POST['UnfreezeTime'] . "', `EnrollStartTime` = '" . $_POST['EnrollStartTime'] . "', `EnrollOverTime` = '" . $_POST['EnrollOverTime'] . "' , `RiskRatio` = " . $_POST['RiskRatio'] . ", `RatingStatus` = 0, `Problem` = '" . $_POST['Problem'] . "', `Practice` = " . $_POST['Practice'] . " WHERE ConID = '" . $_POST['ConID'] . "'";
    $result = oj_mysql_query($sql);
    echo json_encode('{status:0, type:1}');
} else {
    $sql = "INSERT INTO `oj_contest` (`ConID`, `Title`, `Synopsis`, `Organizer`, `Rule`, `Type`, `PassWord`, `StartTime`, `OverTime`, `FreezeTime`, `UnfreezeTime`, `EnrollStartTime`, `EnrollOverTime`, `EnrollPeople`, `RiskRatio`, `RatingStatus`, `Problem`, `Show`, `Practice`) VALUES (" . $_POST['ConID'] . ", '" . $_POST['Title'] . "', '" . addslashes($_POST['Synopsis']) . "', '" . $_POST['Organizer'] . "','" . $_POST['Rule'] . "', '" . $Type . "', '" . $_POST['PassWord'] . "', '" . $_POST['StartTime'] . "', '" . $_POST['OverTime'] . "','" . $_POST['FreezeTime'] . "', '" . $_POST['UnfreezeTime'] . "', '" . $_POST['EnrollStartTime'] . "', '" . $_POST['EnrollOverTime'] . "', '', '" . $_POST['RiskRatio'] . "' ,'0' ,'" . $_POST['Problem'] . "', 0, " . $_POST['Practice'] . ");";
    $result = oj_mysql_query($sql);
    echo json_encode('{status:0, type:0}');
}

oj_mysql_close();
