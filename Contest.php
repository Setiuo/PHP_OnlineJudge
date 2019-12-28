<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<?php
//比赛显示优先级
const ConDoing = 1;		//进行中
const ConEnrolling = 2;	//报名中
const ConnoStart = 3;		//未开始
const ConOver = 4;			//已结束
$ModeStr = array('', '正在进行中', '报名中', '未开始', '已结束');
$ModeCss = array('', 'label-success', 'label-success', 'label-primary', 'label-default');

$Addsql = '';
if (isset($_GET['Search'])) {
	$SearchText = htmlspecialchars(addslashes(trim($_GET['Search'])));

	if (can_edit_contest()) {
		$Addsql .= " WHERE `Title` like '%" . $SearchText . "%'";
	} else {
		$Addsql .= " WHERE `Show`=1 AND `Title` like '%" . $SearchText . "%'";
	}
}

//获取比赛数量
$sql = "SELECT count(*) AS `value` FROM `oj_contest`" . $Addsql;
if (can_edit_contest()) {
	$sql = "SELECT count(*) AS `value` FROM oj_contest" . $Addsql;
}
$rs = oj_mysql_query($sql);
$ConCount = oj_mysql_fetch_array($rs);
$clength = $ConCount['value'];

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

$LimitShowSql = " ORDER BY `OverTime` DESC,`ConID` DESC LIMIT " . ($iPage - 1) * MaxRankNum . ", " . MaxRankNum;

$sql = "SELECT * FROM `oj_contest` WHERE `Show`=1";

if (can_edit_contest()) {
	$sql = "SELECT * FROM `oj_contest`";
}

if (isset($SearchText)) {
	if (can_edit_contest()) {
		$sql .= " WHERE `Title` like '%" . $SearchText . "%'";
	} else {
		$sql .= " AND `Title` like '%" . $SearchText . "%'";
	}
}

$sql .= $LimitShowSql;

$result = oj_mysql_query($sql);

$NowDate = date('Y-m-d H:i:s');

$AllContest = array();
while ($result && $row = oj_mysql_fetch_array($result)) {
	$iMode = ConOver;
	if ($NowDate <= $row['StartTime']) {
		if ($NowDate >= $row['EnrollStartTime'] && $NowDate <= $row['EnrollOverTime'])
			$iMode = ConEnrolling;
		else
			$iMode = ConnoStart;
	} else if ($NowDate <= $row['OverTime']) {
		$iMode = ConDoing;
	} else {
		$iMode = ConOver;
	}

	$TF = get_user_tailsAndFight($row['Organizer']);
	$AllContest[] = array(
		"ConID" => $row['ConID'],
		"Title" => $row['Title'],
		"Organizer" => $row['Organizer'],
		"Tails" => $TF['tails'],
		"Fight" => $TF['fight'],
		"Rule" => $row['Rule'],
		"Type" => $row['Type'],
		"Show" => $row['Show'],
		"StartTime" => $row['StartTime'],
		"OverTime" => $row['OverTime'],
		"Mode" => $iMode
	);
}


//按运行ID排序
function my_sort($a, $b)
{
	if ($a['Mode'] == $b['Mode']) {
		return $a['ConID'] < $b['ConID'] ? 1 : -1;
	}

	return $a['Mode'] < $b['Mode'] ? -1 : 1;
}

usort($AllContest, "my_sort");

?>

<body>

	<?php require_once('Php/Page_Header.php') ?>

	<?php
	if (can_edit_contest()) {
		?>
		<script>
			function changeContestStatus(contestID) {
				$.get("/Contest/ContestStatus.php?ConID=" + contestID, function(msg) {
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
		<ul class="pagination">
			<li><a href=<?php echo '"/Contest.php?Page=' . ($MinPage) . '"' ?>>&laquo;</a></li>
			<li><a href=<?php echo '"/Contest.php?Page=' . ($LastPage) . '"' ?>>&lt;</a></li>

			<?php
			for ($i =  $StaButNum; $i <= $EndButNum; $i++) {
				if ($i == $iPage)
					echo '<li class="active"><a href="/Contest.php?Page=' . $i . '">' . $i . '</a></li>';
				else
					echo '<li><a href="/Contest.php?Page=' . $i . '">' . $i . '</a></li>';
			}
			?>

			<li><a href=<?php echo '"/Contest.php?Page=' . ($NextPage) . '"' ?>>&gt;</a></li>
			<li><a href=<?php echo '"/Contest.php?Page=' . ($MaxPage) . '"' ?>>&raquo;</a></li>
		</ul>

		<form action="/Contest.php" id="problem-search">
			<div class="input-group">

				<div class="input-group-btn">
					<?php
					if (can_edit_contest()) {
						echo '<a href="/NewContest.php" class="btn btn-default">新建比赛</a>';
					}
					?>
				</div>

				<input type="text" name="Search" class="form-control" placeholder="请输入标题" value=<?php echo (isset($SearchText) ? '"' . $SearchText . '"' : '""') ?>>

				<div class="input-group-btn">
					<button type="submit" class="btn btn-default" tabindex="-1">查找</button>
				</div>
			</div>
		</form>

		<div class="panel panel-default animated fadeInLeft">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>比赛ID</th>
						<?php
						if (can_edit_contest() || can_edit_contest()) {
							echo '<th>功能</th>';
						}
						?>
						<th>标题</th>
						<th>比赛状态</th>
						<th>开始时间</th>
						<th>竞赛时长</th>
						<th>比赛规则</th>
						<th>类型</th>
						<th>举办人</th>
					</tr>
				</thead>

				<tbody>
					<?php
					for ($i = 0; $i < MaxRankNum; $i++) {
						if (!isset($AllContest[$i]['ConID'])) {
							continue;
						}

						echo '<tr>';
						echo '<td>';
						echo '<a href="Contest/Pandect.php?ConID=' . $AllContest[$i]['ConID'] . '">' . $AllContest[$i]['ConID'] . '</a> ';
						echo '</td>';
						if (can_edit_contest()) {
							echo '<td>';
							echo ' <a class="label label-warning" href="/NewContest.php?ConID=' . $AllContest[$i]['ConID'] . '">编辑</a>';

							if ($AllContest[$i]['Show'] == 1) {
								echo ' <a href="javascript:changeContestStatus(' . $AllContest[$i]['ConID'] . ')" class="label label-primary">隐藏</a>';
							} else {
								echo ' <a href="javascript:changeContestStatus(' . $AllContest[$i]['ConID'] . ')" class="label label-info">显示</a>';
							}

							echo '</td>';
						}

						echo '<td>';
						echo '<a href="Contest/Pandect.php?ConID=' . $AllContest[$i]['ConID'] . '">' . $AllContest[$i]['Title'] . '</a>';
						echo '</td>';

						echo '<td>';
						echo '<span class="label ' . $ModeCss[$AllContest[$i]['Mode']] . '">' . $ModeStr[$AllContest[$i]['Mode']] . '</span>';
						echo '</td>';

						echo '<td>' . $AllContest[$i]['StartTime'] . '</td>';

						$Startdate = strtotime($AllContest[$i]['StartTime']);
						$Enddate   = strtotime($AllContest[$i]['OverTime']);

						$Timediff = $Enddate - $Startdate;
						$Days =     intval($Timediff / 86400);
						$Remain =   $Timediff % 86400;
						$Hours =    intval($Remain / 3600);
						$Remain =   $Remain % 3600;
						$Mins =     intval($Remain / 60);
						$Secs =     $Remain % 60;

						echo '<td>';
						if ($Days > 0) {
							echo $Days . ($Days == 1 ? ' day ' : ' days ');
						}
						echo $Hours . ':' . $Mins . ':' . $Secs;

						echo '<td>' . $AllContest[$i]['Rule'] . '</td>';

						if ($AllContest[$i]['Type'] == 1) {
							echo '<td><font color="red">Private</font></td>';
						} else {
							echo '<td><font color="green">Public</font></td>';
						}

						echo '<td><a href="/OtherUser.php?User=' . $AllContest[$i]['Organizer'] . '" class=' . GetUserColor($AllContest[$i]['Fight']) . '>' . $AllContest[$i]['Organizer'] . ($AllContest[$i]['Tails'] ? '(' . $AllContest[$i]['Tails'] . ')' : '') . '</a></td>';

						echo '</tr>';
					}

					?>
				</tbody>
			</table>
		</div>
		<center>
			<ul class="pagination">
				<li><a href=<?php echo '"/Contest.php?Page=' . ($MinPage) . '"' ?>>&laquo;</a></li>
				<li><a href=<?php echo '"/Contest.php?Page=' . ($LastPage) . '"' ?>>&lt;</a></li>

				<?php
				for ($i =  $StaButNum; $i <= $EndButNum; $i++) {
					if ($i == $iPage)
						echo '<li class="active"><a href="/Contest.php?Page=' . $i . '">' . $i . '</a></li>';
					else
						echo '<li><a href="/Contest.php?Page=' . $i . '">' . $i . '</a></li>';
				}
				?>

				<li><a href=<?php echo '"/Contest.php?Page=' . ($NextPage) . '"' ?>>&gt;</a></li>
				<li><a href=<?php echo '"/Contest.php?Page=' . ($MaxPage) . '"' ?>>&raquo;</a></li>
			</ul>
		</center>
	</div>

	<?php
	$PageActive = '#contest';
	require_once('Php/Page_Footer.php');
	?>

</body>

</html>