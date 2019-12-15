<?php
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
		$iUseTime = "";

		//获取参赛者的AC提交信息
		$sql = "SELECT `SubTime` FROM `oj_constatus` WHERE (`SubTime`=(SELECT min(SubTime) FROM `oj_constatus` WHERE`Status`=" . Accepted . " AND `Show` = 1 AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "') AND `User`='" . $var . "')";
		if (can_edit_contest($ConID)) {
			$sql = "SELECT `SubTime` FROM `oj_constatus` WHERE (`SubTime`=(SELECT min(SubTime) FROM `oj_constatus` WHERE`Status`=" . Accepted . " AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "') AND `User`='" . $var . "')";
		}
		$result = oj_mysql_query($sql);
		$IsAC = oj_mysql_fetch_array($result);

		//获取参赛者错误提交信息
		$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `Status`!=" . Accepted . " AND `Status`!=" . CompileError . " AND `Show` = 1 AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "'";
		if (can_edit_contest($ConID)) {
			$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `Status`!=" . Accepted . " AND `Status`!=" . CompileError . " AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "'";
		}
		if ($IsAC) {
			$sql .=  " AND `SubTime`<='" . $IsAC['SubTime'] . "'";
		}
		$rs = oj_mysql_query($sql);
		$Num = oj_mysql_fetch_array($rs);
		$iAttemptNum = $Num['value'];
		//增加罚时

		//如果
		if ($IsAC) {
			//已经AC,计算罚时
			$iTimePenalty += 20 * $iAttemptNum;

			$iACStatus = 1;
			$iUserACNum++;

			$Startdate = strtotime($ConData['StartTime']);
			$Enddate   = strtotime($IsAC['SubTime']);

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
?>

<thead>
	<tr>
		<th>排名</th>
		<th>用户名</th>
		<th>AC题数</th>
		<th>罚时</th>
		<th>已通过题目</th>
		<th>未通过题目</th>
		<th>未尝试</th>
		<?php
		?>

	</tr>
</thead>
<tbody>
	<?php
	for ($i = 0; $i < $PeoNum; $i++) {
		if (!$PeopleRank[$i]['User']) {
			continue;
		}
		echo '<tr>';

		echo '<td>' . ($i + 1) . '</td>';
		$TF = get_user_tailsAndFight($PeopleRank[$i]['User']);
		echo '<td><a href="/OtherUser.php?User=' . $PeopleRank[$i]['User'] . '" class=' . GetUserColor($TF['fight']) . '>' . $PeopleRank[$i]['User'] . ($TF['tails'] ? '(' . $TF['tails'] . ')' : '') . '</a></td>';

		if ($PeopleRank[$i]['ACNum'] == $ProNum) {
			echo '<td class="rankyes" style="color:black">' . $PeopleRank[$i]['ACNum'] . '</td>';
		} else {
			echo '<td>' . $PeopleRank[$i]['ACNum'] . '</td>';
		}

		echo '<td>' . $PeopleRank[$i]['TimePenalty'] . '</td>';

		if (isset($PeopleRank[$i]['Pass'])) {
			$passCount = count($PeopleRank[$i]['Pass']);

			echo '<td class="SlateFixBlack rankyes">';
			for ($j = 0; $j < $passCount; $j++) {

				echo $ProEngNum[$PeopleRank[$i]['Pass'][$j]['problemID']];
				if ($PeopleRank[$i]['Pass'][$j]['try'] > 0)
					echo  '(<font color="red">+' . $PeopleRank[$i]['Pass'][$j]['try'] . '</font>)';
				echo ' ';
			}
			echo '</td>';
		} else {
			echo '<td></td>';
		}

		if (isset($PeopleRank[$i]['noPass'])) {
			$noPassCount = count($PeopleRank[$i]['noPass']);

			echo '<td class="SlateFixBlack rankno">';
			for ($j = 0; $j < $noPassCount; $j++) {

				echo $ProEngNum[$PeopleRank[$i]['noPass'][$j]['problemID']];
				if ($PeopleRank[$i]['noPass'][$j]['try'] > 0)
					echo  '(<font color="red">+' . $PeopleRank[$i]['noPass'][$j]['try'] . '</font>)';
				echo ' ';
			}
			echo '</td>';
		} else {
			echo '<td></td>';
		}


		if (isset($PeopleRank[$i]['noSubmit'])) {
			$noSubmitCount = count($PeopleRank[$i]['noSubmit']);

			echo '<td>';
			for ($j = 0; $j < $noSubmitCount; $j++) {

				echo $ProEngNum[$PeopleRank[$i]['noSubmit'][$j]] . ' ';
			}
			echo '</td>';
		} else {
			echo '<td></td>';
		}

		echo '</tr>';
	}
	?>
</tbody>