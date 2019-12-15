<?php
if (!can_edit_contest($ConID) && ($NowDate <= $ConData['OverTime'])) {
	echo '<h3 class="text-center">注意：OI赛制不实时显示排名</h3>';
	return;
}

$PeopleRank = array();

$AllPeople = $ConData['EnrollPeople'];
$Data = explode('|', $AllPeople);
$PeoNum = count($Data);

//遍历每个参赛者
foreach ($Data as $var) {
	$Score = 0;

	$iData = array('User' => $var, 'Score' => 0);

	for ($i = 0; $i < $ProNum; $i++) {
		$iScore = 0;
		$iSubmit = 0;

		//获取参赛者最后一次提交的信息
		$sql = "SELECT `AllStatus` FROM `oj_constatus` WHERE (SubTime=(SELECT max(SubTime) FROM `oj_constatus` WHERE `Show` = 1 AND `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "') AND `User`='" . $var . "')";
		if (can_edit_contest($ConID)) {
			$sql = "SELECT `AllStatus` FROM `oj_constatus` WHERE (SubTime=(SELECT max(SubTime) FROM `oj_constatus` WHERE `ConID`=" . $ConID . " AND `Problem`=" . $i . " AND `User`='" . $var . "') AND `User`='" . $var . "')";
		}
		$result = oj_mysql_query($sql);
		$LastSubmit = oj_mysql_fetch_array($result);

		if ($LastSubmit) {
			$iSubmit = 1;
			$AllStatus = explode("|", $LastSubmit['AllStatus']);

			$iTestNum = count($AllStatus) - 1;
			$EveTesScore = $iTestNum == 0 ? 0 : 100 / $iTestNum;

			foreach ($AllStatus as $val) {
				$iTest = explode("&", $val);

				if (isset($iTest[1]) && $iTest[1] == 4) {
					$iScore += $EveTesScore;
				}
			}
		}

		$Score += $iScore;
		$iData[$i] = array('Score' => $iScore, 'Submit' => $iSubmit);
	}


	$iData['Score'] = $Score;
	array_push($PeopleRank, $iData);
}

function my_sort($a, $b)
{
	return ($a["Score"] > $b["Score"]) ? -1 : 1;
}

usort($PeopleRank, "my_sort");
?>

<thead>
	<tr>
		<th>排名</th>
		<th>用户名</th>
		<th>得分</th>

		<?php
		for ($i = 0; $i < $ProNum; $i++) {
			$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `ConID` = " . $ConID . " AND `Status` = " . Accepted . " AND `Show` = 1 AND `Problem` = " . $i;
			if (can_edit_contest($ConID)) {
				$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `ConID` = " . $ConID . " AND `Status` = " . Accepted . " AND `Problem` = " . $i;
			}
			$rs = oj_mysql_query($sql);
			$PassProNum = oj_mysql_fetch_array($rs);

			$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `ConID` = " . $ConID . " AND `Show` = 1 AND `Problem` = " . $i;
			if (can_edit_contest($ConID)) {
				$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `ConID` = " . $ConID . " AND `Problem` = " . $i;
			}
			$rs = oj_mysql_query($sql);
			$AllProNum = oj_mysql_fetch_array($rs);

			echo '<th><a href="/Contest/Problem.php?ConID=' . $ConID . '&Problem=' . $ProEngNum[$i] . '">' . $ProEngNum[$i] . '(' . $PassProNum['value'] . '/' . $AllProNum['value'] . ')</a></th>';
		}
		?>

	</tr>
</thead>
<tbody>
	<?php
	//排名计算
	$LastPeoScore = 0;
	$PeoRank = 0;
	$EqualRank = 1;

	for ($i = 0; $i < $PeoNum; $i++) {
		if (!$PeopleRank[$i]['User']) {
			continue;
		}

		if ($LastPeoScore != $PeopleRank[$i]['Score']) {
			$LastPeoScore = $PeopleRank[$i]['Score'];
			$PeoRank += $EqualRank;
			$EqualRank = 1;
		} else {
			$EqualRank++;
		}
		echo '<tr>';

		echo '<td>' . ($PeoRank) . '</td>';
		$TF = get_user_tailsAndFight($PeopleRank[$i]['User']);
		echo '<td><a href="/OtherUser.php?User=' . $PeopleRank[$i]['User'] . '" class=' . GetUserColor($TF['fight']) . '>' . $PeopleRank[$i]['User'] . ($TF['tails'] ? '(' . $TF['tails'] . ')' : '') . '</a></td>';
		echo '<td>' . $PeopleRank[$i]['Score'] . '</td>';

		for ($j = 0; $j < $ProNum; $j++) {
			if (ceil($PeopleRank[$i][$j]['Score']) == 100 || floor($PeopleRank[$i][$j]['Score']) == 100) {
				echo '<td class="SlateFixBlack rankyes">' . $PeopleRank[$i][$j]['Score'] . '</td>';
			} else if ($PeopleRank[$i][$j]['Submit'] == 1) {
				echo '<td class="SlateFixBlack rankno">' . $PeopleRank[$i][$j]['Score'] . '</td>';
			} else {
				echo '<td></td>';
			}
		}

		echo '</tr>';
	}
	?>
</tbody>