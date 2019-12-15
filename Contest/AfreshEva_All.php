<?php
header('Content-Type:application/json; charset=gbk');

require_once("../Php/LoadData.php");

$ConID = intval($_GET['ConID']);

if (can_edit_contest($ConID)) {
	if (array_key_exists('ProblemID', $_GET))
		$ProblemID =  intval($_GET['ProblemID']);

	$sql = "SELECT `Problem`, `Language`, `User`, `RunID` FROM `oj_constatus` WHERE `ConID`= " . $ConID;
	if (isset($ProblemID)) {
		$sql .= ' AND `Problem`=' . $ProblemID;
	}
	$statusData_result = oj_mysql_query($sql);
	$error = false;

	$sql = "UPDATE `oj_constatus` SET `Status`=" . Wating . " , `AllStatus`='', `UseTime`=-1 , `UseMemory`=-1 WHERE `ConID`=" . $ConID;
	if (isset($ProblemID)) {
		$sql .= ' AND `Problem`=' . $ProblemID;
	}
	oj_mysql_query($sql);

	//获取比赛信息中所有题号对应的题库的题号
	$sql = "SELECT `Problem` FROM `oj_contest` WHERE `ConID`=" . $ConID . " LIMIT 1";
	$result = oj_mysql_query($sql);
	$row = oj_mysql_fetch_array($result);
	$AllProblem = explode('|', $row['Problem']);

	while ($StatusData = oj_mysql_fetch_array($statusData_result)) {
		$NowProblem = $StatusData['Problem'];

		//获取题目信息
		$sql = "SELECT `LimitTime`, `LimitMemory`, `Test` FROM `oj_problem` WHERE `proNum`='" . $AllProblem[$NowProblem] . "' LIMIT 1";
		$result = oj_mysql_query($sql);
		$ProblemData = oj_mysql_fetch_array($result);

		$myfile = fopen("../Judge/log/data_" . $StatusData['RunID'], "w");
		fwrite($myfile, $StatusData['Language']);
		fwrite($myfile, '|' . $StatusData['User']);
		fwrite($myfile, '|' . $AllProblem[$NowProblem]);
		fwrite($myfile, '|2');
		fwrite($myfile, '|' . $ProblemData['LimitTime']);
		fwrite($myfile, '|' . $ProblemData['LimitMemory']);
		fwrite($myfile, '|');
		fwrite($myfile, $ProblemData['Test']);
		fclose($myfile);
		copy("../Judge/log/data_" . $StatusData['RunID'], "../Judge/Temporary_ContestData/" . $StatusData['RunID']);
	}

	if ($error)
		echo  json_encode("{status: 1}");
	else
		echo  json_encode("{status: 0}");
} else {
	echo  json_encode("{status: 2}");
}

oj_mysql_close();;
