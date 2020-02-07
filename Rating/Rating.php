<?php
header('Content-Type:application/json; charset=gbk');
require_once("../Php/LoadData.php");

$ConID = intval($_GET["ConID"]);
if (!can_edit_contest($ConID)) {
    echo json_encode("{status:2}");
    die();
}

$sql = "SELECT `StartTime`,`EnrollPeople`,`Problem`,`RiskRatio`,`RatingStatus` FROM `oj_contest` WHERE `ConID`=" . $ConID . " LIMIT 1";
$result = oj_mysql_query($sql);
$ConData = oj_mysql_fetch_array($result);

if ($ConData['RatingStatus'] == 1) {
    echo json_encode("{status:1}");
    die();
}

//以用户名的形式储存所有提交信息
global $contestAllStatus_User;
$contestAllStatus_User = array();

$sql = 'SELECT `User`, `Problem`, `Status`, `SubTime` FROM `oj_constatus` WHERE `Show`=1 AND `ConID`=' . $ConID;
$result = oj_mysql_query($sql);
while ($iStatus = oj_mysql_fetch_array($result)) {
    $contestAllStatus_User[$iStatus['User']][] = $iStatus;
}

//查找参赛者AC的提交时间，没有则返回NULL
function find_user_accepted($user, $problem)
{
    $submitTime = null;
    $submitTime_Text = null;

    global $contestAllStatus_User;
    if (isset($contestAllStatus_User[$user])) {
        foreach ($contestAllStatus_User[$user] as $iStatus) {
            if ($iStatus['Status'] == Accepted && $iStatus['Problem'] == $problem) {
                $submitTime_new = strtotime($iStatus['SubTime']);

                if (!$submitTime || ($submitTime_new < $submitTime)) {
                    $submitTime = $submitTime_new;
                    $submitTime_Text = $iStatus['SubTime'];
                }
            }
        }
    }

    return $submitTime_Text;
}
//计算参赛者WA的次数
function count_user_wa($user, $problem, $time)
{
    $count = 0;
    global $contestAllStatus_User;

    if (isset($contestAllStatus_User[$user])) {
        foreach ($contestAllStatus_User[$user] as $iStatus) {
            if ($iStatus['Problem'] == $problem) {
                if (
                    $iStatus['Status'] != Accepted && $iStatus['Status'] != Wating && $iStatus['Status'] != Pending &&
                    $iStatus['Status'] != Running && $iStatus['Status'] != CompileError && $iStatus['Status'] != Accepted
                ) {
                    if ($time) {
                        $submitTime_new = strtotime($iStatus['SubTime']);
                        $compareTime = strtotime($time);

                        if ($submitTime_new < $compareTime) {
                            $count++;
                        }
                    } else {
                        $count++;
                    }
                }
            }
        }
    }

    return $count;
}

$PeopleRank = array();

$AllPeople = $ConData['EnrollPeople'];
$Data = explode('|', $AllPeople);
$PeoNum = count($Data);

$AllProblem = explode('|', $ConData['Problem']);
$ProNum = count($AllProblem);

foreach ($Data as $var) {
    $iUserACNum = 0;
    $iTimePenalty = 0;

    $iData = array('User' => $var, 'ACNum' => 0, 'TimePenalty' => 0);
    for ($i = 0; $i < $ProNum; $i++) {
        $iACStatus = 0;
        $iAttemptNum = 0;
        $iUseTime = "";

        $IsAC = find_user_accepted($var, $i);
        $iAttemptNum = count_user_wa($var, $i, $IsAC);

        //增加罚时
        //如果
        if ($IsAC) {
            //已经AC,计算罚时
            $iTimePenalty += 20 * $iAttemptNum;

            $iACStatus = 1;
            $iUserACNum++;

            $Startdate = strtotime($ConData['StartTime']);
            $Enddate   = strtotime($IsAC);

            $Timediff = $Enddate - $Startdate;
            $Days =     intval($Timediff / 86400);
            $Remain =   $Timediff % 86400;
            $Hours =    intval($Remain / 3600);
            $Remain =   $Remain % 3600;
            $Mins =     intval($Remain / 60);
            $Secs =     $Remain % 60;

            $iTimePenalty += ($Mins + $Hours * 60 + $Days * 24 * 60);

            if ($Days > 0) {
                $iUseTime = $Days . ' days ' . $Hours . ':' . $Mins . ':' . $Secs;
            } else {
                $iUseTime = $Hours . ':' . $Mins . ':' . $Secs;
            }
        } else if ($iAttemptNum != 0) {
            $iACStatus = 2;
        }

        if ($iACStatus == 1)
            $iData['Pass'][] = array('problemID' => $i, 'try' => $iAttemptNum);
        else if ($iACStatus == 2)
            $iData['noPass'][] = array('problemID' => $i, 'try' => $iAttemptNum);
        else
            $iData['noSubmit'][] = $i;
    }



    $iData['ACNum'] = $iUserACNum;
    $iData['TimePenalty'] = $iTimePenalty;
    array_push($PeopleRank, $iData);
}

function my_sort($a, $b)
{
    if ($a["ACNum"] == $b["ACNum"]) {
        if ($a["TimePenalty"] == $b["TimePenalty"]) {
            return 0;
        }

        return ($a["TimePenalty"] < $b["TimePenalty"]) ? -1 : 1;
    }

    return ($a["ACNum"] > $b["ACNum"]) ? -1 : 1;
}
usort($PeopleRank, "my_sort");

//每人奖励战斗力点数
$reward = 2;

$myfile = fopen("./data/cf_rating_start_" . $ConID . ".txt", "w");
fwrite($myfile, $ConData['RiskRatio'] . ' ' . $reward . "\n");
$RankNum = 0;
$LastACNum = -1;
$LastUsedTime = 0;
for ($i = 0; $i < $PeoNum; $i++) {
    if (!$PeopleRank[$i]['User']) {
        continue;
    }

    if ($LastACNum != $PeopleRank[$i]['ACNum'] ||  $LastUsedTime != $PeopleRank[$i]['TimePenalty']) {
        $LastACNum = $PeopleRank[$i]['ACNum'];
        $LastUsedTime = $PeopleRank[$i]['TimePenalty'];
        $RankNum++;
    }
    $TF = get_user_tailsAndFight($PeopleRank[$i]['User']);

    fwrite($myfile, $RankNum . ' ' . $PeopleRank[$i]['User'] . ' ' . $TF['fight'] . "\n");
}
fclose($myfile);

$RatingData = '';
exec("calculate.exe $ConID", $arr, $ret);
if ($ret == 0) {
    $filename = "./data/cf_rating_end_$ConID.txt";
    $handle  = fopen($filename, "r");
    while (!feof($handle)) {
        $buffer = fgets($handle);
        //$buffer = iconv("UTF-8", "gbk//TRANSLIT", $buffer);
        $Data = explode(' ', $buffer);

        if (count($Data) == 4) {
            $RatingData .= $Data[1] . '|' . $Data[0] . '|' . $Data[2] . '|' . $Data[3] . '&';

            $sql = "UPDATE `oj_user` SET `fight`= " . $Data[3] . " WHERE `name`='" . $Data[1] . "'";
            oj_mysql_query($sql);
        }
    }
    fclose($handle);

    $sql = "UPDATE `oj_contest` SET `RatingStatus`= 1, `RatingData`='$RatingData' WHERE `ConID`=$ConID";
    $result = oj_mysql_query($sql);

    if ($result) {
        echo  json_encode("{status: 0}");
    } else {
        echo json_encode("{status:2}");
    }
} else {
    echo json_encode("{status:2}");
    die();
}
