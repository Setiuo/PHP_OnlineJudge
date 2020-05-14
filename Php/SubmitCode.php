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
					die();
				}

				$_SESSION['code_submitTime']  = $NowTime;
			}

			$ProblemID = intval($_POST["pid"]);
			$Language = addslashes(trim($_POST["language"]));

			$sql = "SELECT `LimitTime`, `LimitMemory`, `Test`, `Show` FROM `oj_problem` WHERE `proNum`='" . $ProblemID . "' LIMIT 1";
			$result = oj_mysql_query($sql);
			$ProblemData = oj_mysql_fetch_array($result);

			$CodeLen = mb_strlen($_POST["code"], "utf-8");
			$NowTime = date('Y-m-d H:i:s');
			//向数据库中插入状态
			$sql = 'INSERT INTO oj_status(`User`, `Problem`, `Status`, `UseTime`, `UseMemory`, `Language`, `CodeLen`, `SubTime`, `AllStatus`, `Show`) values("' . $LandUser . '", ' . $ProblemID . ', ' . Wating . ', -1, -1, "' . $Language . '", ' . $CodeLen . ', "' . $NowTime . '", " ", ' . $ProblemData['Show'] . ')';
			$result2 = oj_mysql_query($sql);

			//获取运行ID
			$sql = 'SELECT @@IDENTITY AS VALUE';
			$res = oj_mysql_query($sql);
			$resArray = oj_mysql_fetch_array($res);
			$RunID = $resArray['VALUE'];

			//向数据库中插入评测任务
			$sql = 'INSERT INTO oj_judge_task(`runID`, `contestID`, `user`, `problemID`, `language`, `judgeType`, `limitTime`, `limitMemory`, `test`, `code`, `isRead`) values(' . $RunID . ', 0, "' . $LandUser . '", ' . $ProblemID . ', "' . $Language . '", 2, ' . $ProblemData['LimitTime'] . ', ' . $ProblemData['LimitMemory'] . ', "' . $ProblemData['Test'] . '", "' . addslashes($_POST["code"]) . '", 0)';
			$result1 = oj_mysql_query($sql);

			//清空post值
			unset($_POST['code']);
			unset($_POST['language']);

			if ($result1 && $result2) {
				$data = "{status: 0}";
			} else {
				$data = "{status: 2}";
			}
		}
	}

	echo json_encode($data);
	oj_mysql_close();
}
