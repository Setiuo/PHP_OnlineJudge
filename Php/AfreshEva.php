<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (is_admin()) {
	$runID = intval($_GET['RunID']);

	//for ($runID = 1; $runID <= 143; $runID++)
	{
		$sql = "SELECT `Problem`, `Language`, `User` FROM `oj_status` WHERE `RunID`='" . $runID . "' LIMIT 1";
		$result = oj_mysql_query($sql);

		if ($result) {
			$sql = "UPDATE `oj_status` SET `Status`=" . Wating . " , `AllStatus`='', `UseTime`=-1 , `UseMemory`=-1 WHERE `RunID`=" . $runID;
			oj_mysql_query($sql);

			$StatusData = oj_mysql_fetch_array($result);

			$sql = "SELECT `LimitTime`,`LimitMemory`,`Test` FROM `oj_problem` WHERE `proNum`='" . $StatusData['Problem'] . "' LIMIT 1";
			$result = oj_mysql_query($sql);
			$ProblemData = oj_mysql_fetch_array($result);

			$sql = "UPDATE `oj_judge_task` SET `judgeType`=2, `limitTime`=" . $ProblemData['LimitTime'] . ", `limitMemory`=" . $ProblemData['LimitMemory'] . ", `test`='" . $ProblemData['Test'] . "', `isRead`=0 WHERE `runID`=$runID AND `contestID`=0";
			$result = oj_mysql_query($sql);

			echo  json_encode("{status: 0}");
		} else {
			echo  json_encode("{status: 1}");
		}
	}
} else {
	echo  json_encode("{status: 2}");
}
oj_mysql_close();
