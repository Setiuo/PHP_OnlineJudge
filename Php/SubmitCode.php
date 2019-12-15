<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (isset($_POST["code"]) && $_POST["language"]) {
	$data = "{status: 2}";

	if (!isset($LandUser)) {
		unset($_POST['code']);
		unset($_POST['language']);

		$data = "{status: 3}";
	} else {
		if ($_POST['code'] == null) {
			$data = "{status: 4}";
		} else {
			if (!isset($_SESSION['code_submitTime'])) {
				$_SESSION['code_submitTime']  = $NowTime;
			} else {
				if ($NowTime - $_SESSION['code_submitTime'] <= 5000) {
					echo json_encode("{status: 5}");
					oj_mysql_close();
					return;
				}

				$_SESSION['code_submitTime']  = $NowTime;
			}

			$ProblemID = intval($_POST["pid"]);
			$Language = addslashes(trim($_POST["language"]));

			$sql = "SELECT `LimitTime`, `LimitMemory`, `Test`, `Show` FROM `oj_problem` WHERE `proNum`='" . $ProblemID . "' LIMIT 1";
			$result = oj_mysql_query($sql);
			$ProblemData = oj_mysql_fetch_array($result);

			//获取运行ID
			$RunID = $JudgeMacRunID;

			//输出代码文件
			$myfile = fopen("../Judge/Temporary_Code/" . $RunID, "w");
			fwrite($myfile, $_POST["code"]);
			fclose($myfile);

			//向数据库中插入评测任务
			$sql = 'INSERT INTO oj_judge_task(`runID`, `contestID`, `user`, `problemID`, `language`, `judgeType`, `limitTime`, `limitMemory`, `test`, `code`, `isRead`) values(' . $RunID . ', 0, "' . $LandUser . '", ' . $ProblemID . ', "' . $Language . '", 2, ' . $ProblemData['LimitTime'] . ', ' . $ProblemData['LimitMemory'] . ', "' . $ProblemData['Test'] . '", "' . addslashes($_POST["code"]) . '", 0)';
			$result = oj_mysql_query($sql);

			//输出评测信息
			$myfile = fopen("../Judge/log/data_" . $RunID, "w");
			fwrite($myfile, $Language);
			fwrite($myfile, '|' . $LandUser);
			fwrite($myfile, '|' . $ProblemID);
			fwrite($myfile, '|2');
			fwrite($myfile, '|' . $ProblemData['LimitTime']);
			fwrite($myfile, '|' . $ProblemData['LimitMemory']);
			fwrite($myfile, '|');
			fwrite($myfile, $ProblemData['Test']);
			fclose($myfile);
			copy("../Judge/log/data_" . $RunID, "../Judge/Temporary_Data/" . $RunID);

			$CodeLen = mb_strlen($_POST["code"], "utf-8");
			$NowTime = date('Y-m-d H:i:s');
			//向数据库中插入状态
			$sql = 'INSERT INTO oj_status(`RunID`, `User`, `Problem`, `Status`, `UseTime`, `UseMemory`, `Language`, `CodeLen`, `SubTime`, `AllStatus`, `Show`) values(' . $RunID . ', "' . $LandUser . '", ' . $_POST["pid"] . ', ' . Wating . ', -1, -1, "' . $_POST["language"] . '", ' . $CodeLen . ', "' . $NowTime . '", " ", ' . $ProblemData['Show'] . ')';
			$result = oj_mysql_query($sql);

			//更新运行ID
			$RunID = $RunID + 1;
			$sql = "UPDATE `oj_data` SET `oj_runid`='$RunID' WHERE `oj_name`='$WebName'";
			oj_mysql_query($sql);

			//清空post值
			unset($_POST['code']);
			unset($_POST['language']);
			$data = "{status: 0}";
		}
	}
	echo json_encode($data);
	oj_mysql_close();
}
