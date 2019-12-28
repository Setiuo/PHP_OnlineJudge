<?php
//以用户名的形式储存所有提交信息
global $contestAllStatus_User;
$contestAllStatus_User = array();
//以题号的形式储存所有提交信息
global $contestAllStatus_Problem;
$contestAllStatus_Problem = array();

$sql = 'SELECT `User`, `Problem`, `Status`, `SubTime` FROM `oj_constatus` WHERE `Show`=1 AND `ConID`=' . $ConID;
$result = oj_mysql_query($sql);
while ($iStatus = oj_mysql_fetch_array($result)) {
	$contestAllStatus_User[$iStatus['User']][] = $iStatus;
	$contestAllStatus_Problem[$iStatus['Problem']][] = $iStatus;
}

//查找参赛者AC的提交时间，没有则返回NULL
function find_user_accepted($user, $problem, $time)
{
	$submitTime = null;
	$submitTime_Text = null;

	global $contestAllStatus_User;
	if (isset($contestAllStatus_User[$user])) {
		foreach ($contestAllStatus_User[$user] as $iStatus) {
			if ($iStatus['Status'] == Accepted && $iStatus['Problem'] == $problem) {
				$can = true;

				if ($time) {
					$can = false;
					$submitTime_new = strtotime($iStatus['SubTime']);
					$compareTime = strtotime($time);

					if ($submitTime_new < $compareTime) {
						$can = true;
					}
				}

				if ($can) {
					//转化为时间戳
					$submitTime_new = strtotime($iStatus['SubTime']);

					if (!$submitTime || ($submitTime_new < $submitTime)) {
						$submitTime = $submitTime_new;
						$submitTime_Text = $iStatus['SubTime'];
					}
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
//获取Pending状态数目
function count_user_pend($user, $problem, $time)
{
	$count = 0;
	global $contestAllStatus_User;

	if (isset($contestAllStatus_User[$user])) {
		foreach ($contestAllStatus_User[$user] as $iStatus) {
			if ($iStatus['Problem'] == $problem) {
				if (
					$iStatus['Status'] == Wating || $iStatus['Status'] == Pending ||
					$iStatus['Status'] == Running
				) {
					$count++;
				}

				if ($time) {
					$submitTime_new = strtotime($iStatus['SubTime']);
					$compareTime = strtotime($time);

					if ($submitTime_new >= $compareTime) {
						$count++;
					}
				}
			}
		}
	}

	return $count;
}
//查找第一个AC的提交时间
function find_first_ac($problem)
{
	$submitTime_Text = null;
	$submitTime = null;

	global $contestAllStatus_Problem;
	if (isset($contestAllStatus_Problem[$problem])) {
		foreach ($contestAllStatus_Problem[$problem] as $iStatus) {
			$submitTime_new = strtotime($iStatus['SubTime']);
			if ((!$submitTime || $submitTime_new < $submitTime) && $iStatus['Status'] == Accepted) {
				$submitTime = $submitTime_new;
				$submitTime_Text = $iStatus['SubTime'];
			}
		}
	}

	return $submitTime_Text;
}
//计算问题AC数量
function count_problem_ac($problem, $time)
{
	$timestamp = strtotime($time);
	$count = 0;

	global $contestAllStatus_Problem;
	if (isset($contestAllStatus_Problem[$problem])) {
		foreach ($contestAllStatus_Problem[$problem] as $iStatus) {
			$submitTime_new = strtotime($iStatus['SubTime']);

			if ($submitTime_new < $timestamp && $iStatus['Status'] == Accepted) {
				$count++;
			}
		}
	}

	return $count;
}
//计算问题提交数量
function count_problem_submit($problem)
{
	$count = 0;
	global $contestAllStatus_Problem;

	if (isset($contestAllStatus_Problem[$problem])) {
		foreach ($contestAllStatus_Problem[$problem] as $iStatus) {
			$count++;
		}
	}

	return $count;
}

$PeopleRank = array();

$AllPeople = $ConData['EnrollPeople'];
$Data = explode('|', $AllPeople);
$PeoNum = count($Data);

//遍历每个参赛者
foreach ($Data as $var) {
	$iUserACNum = 0;
	$iTimePenalty = 0;

	$iData = array('User' => $var, 'ACNum' => 0, 'TimePenalty' => 0);

	for ($i = 0; $i < $ProNum; $i++) {
		$iACStatus = 0;
		$iAttemptNum = 0;
		$iPendNum = 0;
		$iUseTime = "";
		$iFirstAC = 0;

		/*
		//获取参赛者封榜前的AC提交信息
		$sql = "SELECT `SubTime` FROM `oj_constatus` WHERE (`SubTime`=(select min(SubTime) FROM `oj_constatus` WHERE`Status`=" . Accepted . " AND `Show` = 1 AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "' AND `SubTime` < '" . $FreezeTime . "') AND `User`='" . $var . "')";
		if (can_edit_contest($ConID)) {
			$sql = "SELECT `SubTime` FROM `oj_constatus` WHERE (`SubTime`=(select min(SubTime) FROM `oj_constatus` WHERE`Status`=" . Accepted . " AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "' AND `SubTime` < '" . $FreezeTime . "') AND `User`='" . $var . "')";
		}
		$result = oj_mysql_query($sql);
		$IsAC = oj_mysql_fetch_array($result);
		*/

		/*
		//错误过滤条件
		$filtrate = "`Status`!=" . Accepted . " AND `Status`!=" . CompileError . " AND `Status`!=" . Running . " AND `Status`!=" . Pending . " AND `Status`!=" . Wating . " AND `Status`!=" . Compiling;
		//获取参赛者封榜前的错误提交信息
		$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE " . $filtrate . " AND `Show` = 1 AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "' AND `SubTime` < '" . $FreezeTime . "'";
		if (can_edit_contest($ConID)) {
			$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE " . $filtrate . " AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "' AND `SubTime` < '" . $FreezeTime . "'";
		}
		if ($IsAC) {
			$sql .=  " AND `SubTime`<='" . $IsAC . "'";
		}
		$rs = oj_mysql_query($sql);
		$Num = oj_mysql_fetch_array($rs);
		$iAttemptNum = $Num['value'];
		*/

		$IsAC = find_user_accepted($var, $i, $FreezeTime);
		if ($IsAC)
			$iAttemptNum = count_user_wa($var, $i, $IsAC) + 1;
		else
			$iAttemptNum = count_user_wa($var, $i, $FreezeTime);

		if ($IsAC) {
			/*
			//获取第一个提交信息
			$sql = "SELECT min(SubTime) AS SubTime FROM `oj_constatus` WHERE`Status`=" . Accepted . " AND `Show` = 1 AND `ConID`=" . $ConID . " AND `Problem`=" . $i;
			if (can_edit_contest($ConID)) {
				$sql = "SELECT min(SubTime) AS SubTime FROM `oj_constatus` WHERE`Status`=" . Accepted . " AND `ConID`=" . $ConID . " AND `Problem`=" . $i;
			}
			$result = oj_mysql_query($sql);
			$FirstAC = oj_mysql_fetch_array($result);
			*/
			$FirstAC = find_first_ac($i);
			if ($IsAC == $FirstAC) {
				$iFirstAC = 1;
			}

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
				$iUseTime = $Days . ($Days == 1 ? ' day ' : ' days ') . $Hours . ':' . $Mins . ':' . $Secs;
			} else {
				$iUseTime = $Hours . ':' . $Mins . ':' . $Secs;
			}
		} else {
			/*
			//Pending条件：封榜时间内未出评判结果或封榜后提交了记录
			$pend_filtrate = "(((`Status`=" . Running . " OR `Status`=" . Pending . " OR `Status`=" . Wating . " OR `Status`=" . Compiling . ") AND `SubTime`<'" . $FreezeTime . "') OR (`SubTime`>='" . $FreezeTime . "' AND `Status`!=" . CompileError . "))";

			//获取封榜内错误次数
			$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE " . $pend_filtrate . " AND `Show` = 1 AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "'";
			if (can_edit_contest($ConID)) {
				$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE " . $pend_filtrate . " AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "'";
			}
			$rs = oj_mysql_query($sql);
			$Pend_Num_Array = oj_mysql_fetch_array($rs);
			$Pend_Num = $Pend_Num_Array['value'];
			*/
			$Pend_Num = count_user_pend($var, $i, $FreezeTime);

			if ($Pend_Num > 0) {
				$iACStatus = 3;
				$iPendNum = $Pend_Num;
			} else if ($iAttemptNum != 0) {
				$iACStatus = 2;
			}
		}

		$iData[$i] = array('AttemptNum' => $iAttemptNum, 'PendNum' => $iPendNum, 'UseTime' => $iUseTime, 'ACStatus' => $iACStatus, 'FirstPass' => $iFirstAC);
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
?>

<thead>
	<tr>
		<th>排名</th>
		<th>用户名</th>
		<th>AC题数</th>
		<th>罚时</th>

		<?php
		for ($i = 0; $i < $ProNum; $i++) {
			/*
			$sql = "SELECT count(*) as value FROM `oj_constatus` WHERE `ConID` = " . $ConID . " AND `Status` = " . Accepted . " AND `Show` = 1 AND `Problem` = " . $i . " AND `SubTime`<'" . $FreezeTime . "'";
			if (can_edit_contest($ConID)) {
				$sql = "SELECT count(*) as value FROM `oj_constatus` WHERE `ConID` = " . $ConID . " AND `Status` = " . Accepted . " AND `Problem` = " . $i . " AND `SubTime`<'" . $FreezeTime . "'";
			}
			$rs = oj_mysql_query($sql);
			$PassProNum = oj_mysql_fetch_array($rs);
			*/
			$PassProNum = count_problem_ac($i, $FreezeTime);

			/*
			$sql = "SELECT count(*) as value FROM `oj_constatus` WHERE `ConID` = " . $ConID . " AND `Show` = 1 AND `Problem` = " . $i;
			if (can_edit_contest($ConID)) {
				$sql = "SELECT count(*) as value FROM `oj_constatus` WHERE `ConID` = " . $ConID . " AND `Problem` = " . $i;
			}
			$rs = oj_mysql_query($sql);
			$AllProNum = oj_mysql_fetch_array($rs);
			*/
			$AllProNum = count_problem_submit($i);

			echo '<th><a href="/Contest/Problem.php?ConID=' . $ConID . '&Problem=' . $ProEngNum[$i] . '">' . $ProEngNum[$i] . '(' . $PassProNum . '/' . $AllProNum . ')</a></th>';
		}
		?>

	</tr>
</thead>
<tbody>
	<?php
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

		echo '<tr>';

		echo '<td>' . ($RankNum) . '</td>';
		$TF = get_user_tailsAndFight($PeopleRank[$i]['User']);

		echo '<td><a href="/OtherUser.php?User=' . $PeopleRank[$i]['User'] . '" class=' . GetUserColor($TF['fight']) . '>' . $PeopleRank[$i]['User'] . ($TF['tails'] ? '(' . $TF['tails'] . ')' : '') . '</a></td>';

		if ($PeopleRank[$i]['ACNum'] == $ProNum) {
			echo '<td class="rankyes" style="color:black">' . $PeopleRank[$i]['ACNum'] . '<br> <span style="color:red">All Killed</span> </td>';
		} else {
			echo '<td>' . $PeopleRank[$i]['ACNum'] . '</td>';
		}

		echo '<td>' . $PeopleRank[$i]['TimePenalty'] . '</td>';

		for ($j = 0; $j < $ProNum; $j++) {
			if (($PeopleRank[$i][$j]['AttemptNum'] + $PeopleRank[$i][$j]['PendNum']) > 1) {
				$try_text = ' tries ';
			} else {
				$try_text = ' try ';
			}

			if ($PeopleRank[$i][$j]['ACStatus'] == 1) {
				if ($PeopleRank[$i][$j]['FirstPass'] == 1) {
					echo '<td class="SlateFixBlack rankfirst">' . $PeopleRank[$i][$j]['AttemptNum'] . $try_text . '<br><span class="tenaltyText">' . $PeopleRank[$i][$j]['UseTime'] . '</span></td>';
				} else {
					echo '<td class="SlateFixBlack rankyes">' . $PeopleRank[$i][$j]['AttemptNum'] . $try_text . '<br><span class="tenaltyText">' . $PeopleRank[$i][$j]['UseTime'] . '</span></td>';
				}
			} else if ($PeopleRank[$i][$j]['ACStatus'] == 2) {
				echo '<td class="SlateFixBlack rankno">' . $PeopleRank[$i][$j]['AttemptNum'] . $try_text . '<br>' . $PeopleRank[$i][$j]['UseTime'] . '</td>';
			} else if ($PeopleRank[$i][$j]['ACStatus'] == 3) {
				echo '<td class="SlateFixBlack rankpending">' . $PeopleRank[$i][$j]['AttemptNum'] . ' + ' . $PeopleRank[$i][$j]['PendNum'] . $try_text . '<br>' . $PeopleRank[$i][$j]['UseTime'] . '</td>';
			} else {
				echo '<td></td>';
			}
		}

		echo '</tr>';
	}
	?>
</tbody>