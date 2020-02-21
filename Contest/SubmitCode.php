<?php
header('Content-Type:application/json; charset=gbk');

require_once("../Php/LoadData.php");

if (isset($_POST["code"]) && isset($_POST["language"]) && isset($_POST["ConID"]) && isset($_POST["NowPro"])) {
	if (!isset($LandUser)) {
		unset($_POST['code']);
		unset($_POST['language']);
		unset($_POST['ConID']);
		unset($_POST['NowPro']);

		echo json_encode("{status: 2}");
		die();
	}

	$ConID = intval($_POST["ConID"]);
	//获取问题编号
	$sql = "SELECT * FROM `oj_contest` WHERE `ConID`=" .  $ConID . " LIMIT 1";
	$result = oj_mysql_query($sql);
	$ConData = oj_mysql_fetch_array($result);

	$NowDate = date('Y-m-d H:i:s');
	if ($NowDate > $ConData['OverTime']) {

		echo json_encode("{status: 3}");
		die();
	} else if ($NowDate < $ConData['StartTime']) {
		echo json_encode("{status: 4}");
		die();
	}

	$AllPeople = $ConData['EnrollPeople'];
	$Data = explode('|', $AllPeople);

	if (!in_array($LandUser, $Data)) {
		//如果处于报名时间内，则添加报名人员
		if ($NowDate >= $ConData['EnrollStartTime'] && $NowDate <= $ConData['EnrollOverTime']) {
			if ($AllPeople == "") {
				$AllPeople = $LandUser;
			} else {
				$AllPeople .= ('|' . $LandUser);
			}

			$sql = "UPDATE `oj_contest` SET `EnrollPeople` = '" . $AllPeople . "' WHERE `ConID`='" . $ConID . "'";
			oj_mysql_query($sql);
		} else {
			echo json_encode("{status: 5}");
			die();
		}
	}

	if ($_POST['code'] == null) {
		echo json_encode("{status: 6}");
		die();
	}

	if (!isset($_SESSION['code_submitTime'])) {
		$_SESSION['code_submitTime']  = $NowTime;
	} else {
		if ($NowTime - $_SESSION['code_submitTime'] <= 5000) {
			echo json_encode("{status: 7}");
			die();
		}

		$_SESSION['code_submitTime']  = $NowTime;
	}

	$AllProblem = explode('|', $ConData['Problem']);

	$ConProblemID = intval($_POST["NowPro"]);
	$sql = "SELECT `LimitTime`, `LimitMemory`, `Test`, `Show` FROM `oj_problem` WHERE proNum='" . $AllProblem[$ConProblemID] . "'";
	$result = oj_mysql_query($sql);
	$ProblemData = oj_mysql_fetch_array($result);

	//获取运行ID
	$sql = 'SELECT max(RunID) AS VALUE FROM `oj_constatus` WHERE `ConID`=' . $ConID;
	$res = oj_mysql_query($sql);
	$resArray = oj_mysql_fetch_array($res);
	$RunID = max(1, $resArray['VALUE'] + 1);

	//评测模式
	$JudgeType = 2;
	if ($ConData['Rule'] == 'ACM')
		$JudgeType = 1;
	else if ($ConData['Rule'] == 'OI')
		$JudgeType = 2;

	$Language = addslashes(trim($_POST["language"]));

	//向数据库中插入评测任务
	$sql = 'INSERT INTO oj_judge_task(`runID`, `contestID`, `user`, `problemID`, `language`, `judgeType`, `limitTime`, `limitMemory`, `test`, `code`, `isRead`) values(' . $RunID . ', ' . $ConID . ', "' . $LandUser . '", ' . $AllProblem[$ConProblemID] . ', "' . $Language . '", ' . $JudgeType . ', ' . $ProblemData['LimitTime'] . ', ' . $ProblemData['LimitMemory'] . ', "' . $ProblemData['Test'] . '", "' . addslashes($_POST["code"]) . '", 0)';
	$result1 = oj_mysql_query($sql);

	$CodeLen = mb_strlen($_POST["code"], "utf-8");
	$NowTime = date('Y-m-d H:i:s');
	//向数据库中插入状态
	$sql = 'INSERT INTO oj_constatus(`RunID`, `ConID`, `User`, `Problem`, `Status`, `UseTime`, `UseMemory`, `Language`, `CodeLen`, `SubTime`, `AllStatus`, `Show`) values(' . $RunID . ', ' . $ConID . ', "' . $LandUser . '", ' . $ConProblemID . ', ' . Wating . ', -1, -1, "' . $Language . '", ' . $CodeLen . ', "' . $NowTime . '", " ", ' . $ProblemData['Show'] . ')';
	$result2 = oj_mysql_query($sql);

	//清空post值
	unset($_POST['code']);
	unset($_POST['language']);

	if ($result1 && $result2) {
		echo json_encode("{status: 0, contestID:" . $ConID . "}");
	} else {
		echo json_encode("{status: 8, contestID:" . $ConID . "}");
	}
} else {
	echo json_encode("{status: 1}");
}

oj_mysql_close();
