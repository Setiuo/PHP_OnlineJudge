<?php
//header('Content-Type:application/json; charset=gbk');
header("Content-Type: text/html; charset=utf-8"); //防止界面乱码
require_once("LoadData.php");

if (is_admin()) {
	for ($runID = 21; $runID <= 659; $runID++) {
		$sql = "SELECT `ConID`, `Problem`, `Language`, `User` FROM `oj_constatus` WHERE `RunID`='" . $runID . "' LIMIT 1";
		$result = oj_mysql_query($sql);

		if ($result) {
			//$sql = "UPDATE `oj_status` SET `Status`=" . Wating . " , `AllStatus`='', `UseTime`=-1 , `UseMemory`=-1 WHERE `RunID`=" . $runID;
			//oj_mysql_query($sql);

			$StatusData = oj_mysql_fetch_array($result);

			//获取比赛信息中所有题号对应的题库的题号
			$sql = "SELECT `Problem` FROM `oj_contest` WHERE `ConID`=" . $StatusData['ConID'] . " LIMIT 1";
			$result = oj_mysql_query($sql);
			$row = oj_mysql_fetch_array($result);
			$AllProblem = explode('|', $row['Problem']);

			$sql = "SELECT `LimitTime`,`LimitMemory`,`Test` FROM `oj_problem` WHERE `proNum`=" . $AllProblem[$StatusData['Problem']] . " LIMIT 1";
			$result = oj_mysql_query($sql);
			$ProblemData = oj_mysql_fetch_array($result);

			$Code = addslashes(file_get_contents("../Judge/Temporary_Code/" . $runID));
			$sql = 'INSERT INTO oj_judge_task(`runID`, `contestID`, `user`, `problemID`, `language`, `judgeType`, `limitTime`, `limitMemory`, `test`, `code`, `isRead`) values(' . $runID . ', ' . $StatusData['ConID'] . ', "' . $StatusData['User'] . '", ' . $AllProblem[$StatusData['Problem']] . ', "' . $StatusData['Language'] . '", 2, ' . $ProblemData['LimitTime'] . ', ' . $ProblemData['LimitMemory'] . ', "' . $ProblemData['Test'] . '", "' . $Code . '", 1)';
			$result = oj_mysql_query($sql);

			if ($result)
				echo  $runID . " success <br />";
			else
				echo  $runID . " fail <br />";
			//echo $sql . " fail <br />";
		} else {
			echo  json_encode("{status: 1}");
		}
	}
} else {
	echo  json_encode("{status: 2}");
}
oj_mysql_close();
