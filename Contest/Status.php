<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once("Html_Head.php"); ?>

<?php
$AddHref = "";
$AllCmd = "";
$result;


if (!can_edit_contest($ConID)) {
	$AllCmd = ' WHERE `Show`=1';
}

if (array_key_exists('ConID', $_GET)) {
	if ($_GET['ConID'] != "") {
		$iConID = intval($_GET['ConID']);
		$AddHref = $AddHref . '&ConID=' . $iConID;

		if ($AllCmd == "")
			$AllCmd = ' WHERE';
		else
			$AllCmd = $AllCmd . ' AND';

		$AllCmd = $AllCmd . ' `ConID`=' . $iConID;
	}
}

if (array_key_exists('Problem', $_GET)) {
	$iProblem = htmlspecialchars(addslashes(trim($_GET['Problem'])));

	if ($_GET['Problem'] != "") {
		$AddHref = $AddHref . '&Problem=' . $iProblem;

		if ($AllCmd == "")
			$AllCmd = ' WHERE';
		else
			$AllCmd = $AllCmd . ' AND';

		$AllCmd = $AllCmd . ' `Problem`=' . (ord($iProblem) - ord('A') + 0);
	}
}

if (array_key_exists('Status', $_GET)) {
	$iStatus = htmlspecialchars(addslashes(trim($_GET['Status'])));

	if ($_GET['Status'] != "" && ($ConData['Rule'] == 'ACM' || can_edit_contest($ConID) || $NowDate >= $ConData['OverTime'])) {
		$AddHref = $AddHref . '&Status=' . $iStatus;

		if ($AllCmd == "")
			$AllCmd = ' WHERE';
		else
			$AllCmd = $AllCmd . ' AND';

		$AllCmd = $AllCmd . ' `Status`="' . $iStatus . '"';
	}
}

if (array_key_exists('Language', $_GET)) {
	$iLanguage = htmlspecialchars(addslashes(trim($_GET['Language'])));

	if ($_GET['Language'] != "") {
		$Language = str_replace("+", "%2B", $iLanguage);
		$AddHref = $AddHref . '&Language=' . $Language;

		if ($AllCmd == "")
			$AllCmd = ' WHERE';
		else
			$AllCmd = $AllCmd . ' AND';

		$AllCmd = $AllCmd . ' `Language`="' . $iLanguage . '"';
	}
}

$OnlyMyself = false;
if ($NowDate <= $ConData['OverTime'] && !can_edit_contest($ConID)) {
	$OnlyMyself = true;

	if ($AllCmd == "")
		$AllCmd = ' WHERE';
	else
		$AllCmd = $AllCmd . ' AND';

	$AllCmd = $AllCmd . ' `User`="' . $LandUser . '"';
}

if (array_key_exists('User', $_GET) && !$OnlyMyself) {
	$iUser = htmlspecialchars(addslashes(trim($_GET['User'])));

	if ($_GET['User'] != "") {
		$AddHref = $AddHref . '&User=' . $iUser;

		if ($AllCmd == "")
			$AllCmd = ' WHERE';
		else
			$AllCmd = $AllCmd . ' AND';

		$AllCmd = $AllCmd . ' `User`="' . $iUser . '"';
	}
}

$AddHref = htmlspecialchars($AddHref);

//获取状态数量
$sql = "SELECT count(*) AS `value` FROM `oj_constatus` " . $AllCmd;
$rs = oj_mysql_query($sql);
$StaCount = oj_mysql_fetch_array($rs);
$clength = $StaCount['value'];
//获取当前页数
$iPage = 1;
if (array_key_exists('Page', $_GET)) {
	$iPage = intval($_GET['Page']);
}
$iPage = floor($iPage);

//定义常量，一页中最大显示数量
define("MaxRankNum", 20);
//定义常量，一页中最多显示按钮数量(奇数)
define("MaxButtonNum", 5);

//计算总页数
$AllPage = $clength / MaxRankNum;

//计算上一页的页数
$LastPage = ($iPage - 1 <= 0) ? 1 : ($iPage - 1);
//计算下一页的页数
$NextPage = $iPage * MaxRankNum < $clength ? $iPage + 1 : $iPage;
//最小页数
$MinPage = 1;
$iPage = $iPage >=  $MinPage ? $iPage : 1;
//最大页数
$MaxPage = ceil(($clength * 1.0) / MaxRankNum);
$MaxPage = $MaxPage > 0 ? $MaxPage : 1;
$iPage = $iPage <=  $MaxPage ? $iPage : $MaxPage;
//根据页数计算显示第一个的排名
$Rank = ($iPage - 1) * MaxRankNum;

//开始显示的按钮数字
$StaButNum;
//至结束显示的按钮数字
$EndButNum;

//如果最大页数小于等于一页中最多显示按钮数量
if ($MaxPage <= MaxButtonNum) {
	$StaButNum = 1;
	$EndButNum = $MaxPage;
} else {
	//将当前页的数字当作中间的按钮
	$iCenBuNum = $iPage;
	//开始显示的按钮数字为 最多显示按钮数量/2
	$StaButNum = $iCenBuNum - floor(MaxButtonNum / 2);
	//至结束显示的按钮的数字为 最多显示按钮数量/2
	$EndButNum = $iCenBuNum + floor(MaxButtonNum / 2);

	//如果开始显示的数字<=0，说明不能把当前页的数字当作中间的按钮
	if ($StaButNum <= 0) {
		//将至结束显示的按钮的数字调整
		$EndButNum -= $StaButNum - 1;
		//开始显示的数字显示为1
		$StaButNum -= $StaButNum - 1;
	}

	//如果结束显示的数字>最多显示按钮数量，说明不能把当前页的数字当作中间的按钮
	if ($EndButNum > $MaxPage) {
		//调整开始按钮的值
		$StaButNum -= ($EndButNum - $MaxPage);
		//结束显示的数字显示为最大值
		$EndButNum = $MaxPage;
	}
}

$LimitShowSql = " ORDER BY `RunID` DESC LIMIT " . ($iPage - 1) * MaxRankNum . ", " . MaxRankNum;
$sql = "SELECT * FROM `oj_constatus`" . $AllCmd . $LimitShowSql;
$result = oj_mysql_query($sql);

if (!$result) {
	header('Location: /Message.php?Msg=提交状态获取失败');
	die();
}

$AllStatus = array();

while ($row = oj_mysql_fetch_array($result)) {
	$AllStatus[] = array(
		"RunID" => $row['RunID'],
		"ConID" => $row['ConID'],
		"User" => $row['User'],
		"Problem" => $row['Problem'],
		"Status" => $row['Status'],
		"UseTime" => $row['UseTime'],
		"UseMemory" => $row['UseMemory'],
		"Language" => $row['Language'],
		"CodeLen" => $row['CodeLen'],
		"SubTime" => $row['SubTime'],
		"AllStatus" => $row['AllStatus'],
		"Show" => $row['Show']
	);
}

?>

<body>
	<?php
	require_once("Header.php");
	?>

	<?php
	if (can_edit_contest($ConID)) {
	?>
		<script>
			function afreshEva(runID, conID) {
				$.get("/Contest/AfreshEva.php?RunID=" + runID + '&ConID=' + conID, function(msg) {
					var obj = eval('(' + msg + ')');
					if (obj.status === 0) {
						location.reload();
					}
				});
			}

			function changeStatusShow(runID, conID) {
				$.get("/Contest/StatusShow.php?RunID=" + runID + "&ConID=" + conID, function(msg) {
					var obj = eval('(' + msg + ')');
					if (obj.status === 0) {
						location.reload();
					}
				});
			}
		</script>
	<?php
	}
	?>

	<div class="container">
		<div class="panel panel-default">
			<div id="contesthead" class="panel-heading" style="padding:0 0 0 0;">
				<ul class="nav nav-tabs" role="tablist">
					<?php
					if (can_edit_contest($ConID)) {
					?>
						<li role="presentation"><a class="label label-warning" href="javascript:show_all_problem()">显示题目</a></li>
						<li role="presentation"><a class="label label-default" href="javascript:hide_all_problem()">隐藏题目</a></li>
						<li role="presentation"><a class="label label-danger" href="javascript:rejudge_all_status()">重测代码</a></li>
					<?php
					}
					?>
					<li>
						<h4>&nbsp;</h4>
					</li>
				</ul>
			</div>
			<div class="panel-body">

				<!--<meta http-equiv="refresh" content="5">-->
				<center>
					<ul class="pagination">
						<li><a href=<?php echo '"Status.php?Page=' . ($MinPage) . $AddHref . '"' ?>>&laquo;</a></li>
						<li><a href=<?php echo '"Status.php?Page=' . ($LastPage) . $AddHref . '"' ?>>&lt;</a></li>

						<?php
						for ($i =  $StaButNum; $i <= $EndButNum; $i++) {
							if ($i == $iPage)
								echo '<li class="active"><a href="Status.php?Page=' . $i . $AddHref . '">' . $i . '</a></li>';
							else
								echo '<li><a href="Status.php?Page=' . $i . $AddHref . '">' . $i . '</a></li>';
						}
						?>

						<li><a href=<?php echo '"Status.php?Page=' . ($NextPage) . $AddHref . '"' ?>>&gt;</a></li>
						<li><a href=<?php echo '"Status.php?Page=' . ($MaxPage) . $AddHref . '"' ?>>&raquo;</a></li>
					</ul>
				</center>

				<form>
					<div class="input-group" style="padding-bottom:15px;">
						<input type="hidden" name="ConID" value=<?php if (isset($iConID) && $iConID) echo '"' . $iConID . '"';
																else echo '""' ?> class="form-control" readonly="readonly">
						<span class="input-group-addon">用户名</span>
						<input name="User" type="text" value=<?php if (isset($iUser) && $iUser) echo '"' . $iUser . '"';
																else echo '""' ?> class="form-control">
						<span class="input-group-addon">题号</span>
						<input name="Problem" type="text" value=<?php if (isset($iProblem) && $iProblem) echo '"' . $iProblem . '"';
																else echo '""' ?> class="form-control">

						<?php
						if ($ConData['Rule'] == 'ACM' || can_edit_contest($ConID) || $NowDate >= $ConData['OverTime']) {
						?>
							<span class="input-group-addon">评测结果</span>

							<select name="Status" class="form-control">
								<option value="">All</option>
								<option value=<?php echo Accepted; ?>>correct</option>
								<option value=<?php echo PresentationError; ?>>presentation-error</option>
								<option value=<?php echo TimeLimitExceeded; ?>>timelimit</option>
								<option value=<?php echo MemoryLimitExceeded; ?>>memorylimit</option>
								<option value=<?php echo WrongAnswer; ?>>wrong-answer</option>
								<option value=<?php echo RuntimeError; ?>>run-error</option>
								<option value=<?php echo OutputLimitExceeded; ?>>outputlimit</option>
								<option value=<?php echo CompileError; ?>>compile-error</option>
								<option value=<?php echo SystemError; ?>>system-error</option>
							</select>

							<script language=JavaScript>
								document.getElementsByName("Status")[0].value = <?php echo '"' . (isset($_GET['Status']) ? $iStatus : '') . '"' ?>;
							</script>
						<?php
						}
						?>

						<span class="input-group-addon">语言</span>

						<select name="Language" class="form-control">
							<option value="">All</option>
							<option value="C">C</option>
							<option value="C++">C++</option>
							<option value="Java">Java</option>
							<option value="Python3.7">Python3.7</option>
						</select>

						<script language=JavaScript>
							document.getElementsByName("Language")[0].value = <?php echo '"' . (isset($_GET['Language']) ? $iLanguage : '') . '"' ?>;
						</script>

						<span class="input-group-btn">
							<button class="btn btn-default">查询</button>
						</span>
					</div>
				</form>

				<div class="panel panel-default animated fadeInDown">
					<table class="table table-striped table-hover" id="StatusTable">
						<thead>
							<tr>
								<th hidden>比赛ID</th>
								<th>运行ID</th>
								<th>用户</th>
								<th>题号</th>
								<th>评测结果</th>

								<?php
								if ($ConData['Rule'] == 'ACM' || can_edit_contest($ConID) || $NowDate >= $ConData['OverTime']) {
									echo '<th>用时(ms)</th>';
									echo '<th>内存(KB)</th>';
								}
								?>

								<th>语言</th>
								<th>代码长度(B)</th>
								<th>提交时间</th>
							</tr>
						</thead>
						<tbody>
							<?php
							for ($i = 0; $i < MaxRankNum; $i++) {
								if (!isset($AllStatus[$i]['RunID']) || !isset($AllStatus[$i]['ConID'])) {
									continue;
								}

								$TF = get_user_tailsAndFight($AllStatus[$i]['User']);
								if ($ConData['Rule'] == 'OI' && !can_edit_contest($ConID) && $NowDate <= $ConData['OverTime']) {
									echo '<tr>';
									echo '<td hidden>' . $AllStatus[$i]['ConID'] . '</td>';

									echo '<td>' . $AllStatus[$i]['RunID'] . '</td>';

									echo '<td>';
									echo '<a href="/OtherUser.php?User=' . $AllStatus[$i]['User'] . '" class=' . GetUserColor($TF['fight']) . '>' . $AllStatus[$i]['User'] . ($TF['tails'] ? '(' . $TF['tails'] . ')' : '') . '</a>';
									echo '</td>';

									echo '<td>';
									echo '<a href="/Contest/Problem.php?ConID=' . $AllStatus[$i]['ConID'] . '&Problem=' . $ProEngNum[$AllStatus[$i]['Problem']] . '">' . $ProEngNum[$AllStatus[$i]['Problem']] . '</a>';
									echo '</td>';

									echo '<td>';
									echo '<a class="label" href="/Contest/Detail.php?ConID=' . $AllStatus[$i]['ConID'] . '&RunID=' . $AllStatus[$i]['RunID'] . '" data-status="Accepted">Submit Success</a>';
									echo '</td>';

									echo '<td>';
									echo $AllStatus[$i]['Language'];
									echo '</td>';

									echo '<td>';
									echo $AllStatus[$i]['CodeLen'];
									echo '</td>';

									echo '<td>';
									echo $AllStatus[$i]['SubTime'];
									echo '</td>';

									echo '</tr>';
								} else {
									echo '<tr>';

									echo '<td hidden>' . $AllStatus[$i]['ConID'] . '</td>';
									if (can_edit_contest($ConID)) {
										unset($_GET['RunID']);
										echo '<td>';
										echo $AllStatus[$i]['RunID'];
										echo ' &nbsp;';
										echo '<a href="javascript:afreshEva(' . $AllStatus[$i]['RunID'] . ',' . $AllStatus[$i]['ConID'] . ')" class="label label-warning">重测</a>';

										if ($AllStatus[$i]['Show'] == 1) {
											echo ' <a href="javascript:changeStatusShow(' . $AllStatus[$i]['RunID'] . ',' . $AllStatus[$i]['ConID'] . ')" class="label label-primary">隐藏</a>';
										} else {
											echo ' <a href="javascript:changeStatusShow(' . $AllStatus[$i]['RunID'] . ',' . $AllStatus[$i]['ConID'] . ')" class="label label-info">显示</a>';
										}
										echo '</td>';
									} else {
										echo '<td>' . $AllStatus[$i]['RunID'] . '</td>';
									}

									echo '<td>';
									echo '<a href="/OtherUser.php?User=' . $AllStatus[$i]['User'] . '" class=' . GetUserColor($TF['fight']) . '>' . $AllStatus[$i]['User'] . ($TF['tails'] ? '(' . $TF['tails'] . ')' : '') . '</a>';
									echo '</td>';

									echo '<td>';
									echo '<a href="/Contest/Problem.php?ConID=' . $AllStatus[$i]['ConID'] . '&Problem=' . $ProEngNum[$AllStatus[$i]['Problem']] . '">' . $ProEngNum[$AllStatus[$i]['Problem']] . '</a>';
									echo '</td>';

									echo '<td>';
									echo '<a class="label" href="/Contest/Detail.php?ConID=' . $AllStatus[$i]['ConID'] . '&RunID=' . $AllStatus[$i]['RunID'] . '" data-status="' . $AllStatusName[$AllStatus[$i]['Status']] . '">' . $AllStatusCName[$AllStatus[$i]['Status']] . ' ' . $AllStatusName[$AllStatus[$i]['Status']] . '</a>';
									echo '</td>';

									echo '<td>';
									echo $AllStatus[$i]['UseTime'];
									echo '</td>';

									echo '<td>';
									echo $AllStatus[$i]['UseMemory'];
									echo '</td>';

									echo '<td>';
									echo $AllStatus[$i]['Language'];
									echo '</td>';

									echo '<td>';
									echo $AllStatus[$i]['CodeLen'];
									echo '</td>';

									echo '<td>';
									echo $AllStatus[$i]['SubTime'];
									echo '</td>';

									echo '</tr>';
								}
							}
							?>
						</tbody>
					</table>
				</div>
				<center>
					<ul class="pagination">
						<li><a href=<?php echo '"Status.php?Page=' . ($MinPage) . $AddHref . '"' ?>>&laquo;</a></li>
						<li><a href=<?php echo '"Status.php?Page=' . ($LastPage) . $AddHref . '"' ?>>&lt;</a></li>

						<?php
						for ($i =  $StaButNum; $i <= $EndButNum; $i++) {
							if ($i == $iPage)
								echo '<li class="active"><a href="Status.php?Page=' . $i . $AddHref . '">' . $i . '</a></li>';
							else
								echo '<li><a href="Status.php?Page=' . $i . $AddHref . '">' . $i . '</a></li>';
						}
						?>

						<li><a href=<?php echo '"Status.php?Page=' . ($NextPage) . $AddHref . '"' ?>>&gt;</a></li>
						<li><a href=<?php echo '"Status.php?Page=' . ($MaxPage) . $AddHref . '"' ?>>&raquo;</a></li>
					</ul>
				</center>
			</div>
		</div>
	</div>
	<?php
	$PageActive = "#c_status";
	require_once('Footer.php');
	?>
	<script src="/js/refreshStatus_contest.js?v=<?php echo $OJ_Version ?>"></script>
</body>

</html>