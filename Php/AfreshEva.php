<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (is_admin()) {
	$runID = intval($_GET['ReEva']);

	//for ($runID = 1; $runID <= 143; $runID++)
	{
		$sql = "SELECT `Problem`, `Language`, `User` FROM `oj_status` WHERE `RunID`='" . $runID . "' LIMIT 1";
		$result = oj_mysql_query($sql);

		if ($result) {
			$sql = "UPDATE `oj_status` SET `Status`=" . Wating . " , `AllStatus`='', `UseTime`=-1 , `UseMemory`=-1 WHERE `RunID`='" . $runID . "'";
			oj_mysql_query($sql);

			$StatusData = oj_mysql_fetch_array($result);

			$sql = "SELECT `LimitTime`,`LimitMemory`,`Test` FROM `oj_problem` WHERE `proNum`='" . $StatusData['Problem'] . "' LIMIT 1";
			$result = oj_mysql_query($sql);
			$ProblemData = oj_mysql_fetch_array($result);

			$myfile = fopen("../Judge/log/data_" . $runID, "w");
			fwrite($myfile, $StatusData['Language']);
			fwrite($myfile, '|' . $StatusData['User']);
			fwrite($myfile, '|' . $StatusData['Problem']);
			fwrite($myfile, '|2');
			fwrite($myfile, '|' . $ProblemData['LimitTime']);
			fwrite($myfile, '|' . $ProblemData['LimitMemory']);
			fwrite($myfile, '|');
			fwrite($myfile, $ProblemData['Test']);
			fclose($myfile);
			copy("../Judge/log/data_" . $runID, "../Judge/Temporary_Data/" . $runID);

			echo  json_encode("{status: 0}");
		} else {
			echo  json_encode("{status: 1}");
		}
	}
} else {
	echo  json_encode("{status: 2}");
}
oj_mysql_close();
