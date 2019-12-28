<?php
header('Content-Type:application/json; charset=gbk');

require_once("../Php/LoadData.php");


//获取比赛提交状态的题号
$sql = "SELECT `ConID`, `Problem`, `Language`, `User` FROM `oj_constatus` WHERE `RunID`='" . intval($_GET['ReEva']) . "' LIMIT 1";
$result = oj_mysql_query($sql);

if ($result) {
	$StatusData = oj_mysql_fetch_array($result);
	$NowProblem = $StatusData['Problem'];

	if (can_edit_contest($StatusData['ConID'])) {

		$sql = "UPDATE `oj_constatus` SET `Status`=" . Wating . " , `AllStatus`='', `UseTime`=-1 , `UseMemory`=-1 WHERE `RunID`='" . intval($_GET['ReEva']) . "'";
		oj_mysql_query($sql);

		//获取比赛信息中所有题号对应的题库的题号
		$sql = "SELECT `Problem` FROM `oj_contest` WHERE `ConID`=" . intval($_GET['ConID']) . " LIMIT 1";
		$result = oj_mysql_query($sql);
		$row = oj_mysql_fetch_array($result);
		$AllProblem = explode('|', $row['Problem']);

		//获取题目信息
		$sql = "SELECT `LimitTime`,`LimitMemory`,`Test` FROM `oj_problem` WHERE `proNum`='" . $AllProblem[$NowProblem] . "' LIMIT 1";
		$result = oj_mysql_query($sql);
		$ProblemData = oj_mysql_fetch_array($result);


		$sql = "UPDATE `oj_judge_task` SET `problemID`=" . $AllProblem[$NowProblem] . ", `judgeType`=2, `limitTime`=" . $ProblemData['LimitTime'] . ", `limitMemory`=" . $ProblemData['LimitMemory'] . ", `test`='" . $ProblemData['Test'] . "', `isRead`=0 WHERE `RunID`=" . intval($_GET['ReEva']);
		$result = oj_mysql_query($sql);

		/*
		$myfile = fopen("../Judge/log/data_" . intval($_GET['ReEva']), "w");
		fwrite($myfile, $StatusData['Language']);
		fwrite($myfile, '|' . $StatusData['User']);
		fwrite($myfile, '|' . $AllProblem[$NowProblem]);
		fwrite($myfile, '|2');
		fwrite($myfile, '|' . $ProblemData['LimitTime']);
		fwrite($myfile, '|' . $ProblemData['LimitMemory']);
		fwrite($myfile, '|');
		fwrite($myfile, $ProblemData['Test']);
		fclose($myfile);
		copy("../Judge/log/data_" . intval($_GET['ReEva']), "../Judge/Temporary_ContestData/" . intval($_GET['ReEva']));
		*/

		echo  json_encode("{status: 0}");
	} else {
		echo  json_encode("{status: 1}");
	}
} else {
	echo  json_encode("{status: 2}");
}

oj_mysql_close();;
