<!DOCTYPE html>

<html lang="zh-cn">
<?php require_once('Php/HTML_Head.php') ?>

<?php
$RunID = 0;
if (array_key_exists('RunID', $_GET)) {
	$RunID = intval($_GET['RunID']);

	if ($RunID > $JudgeMacRunID - 1) {
		header('Location: /Message.php?Msg=信息获取出现错误');
		return;
	} else if ($RunID < 1) {
		header('Location: /Message.php?Msg=信息获取出现错误');
		return;
	}
} else {
	header('Location: /Status.php');
	//return;
}

if (is_admin()) {
	$sql = "SELECT * FROM `oj_status` WHERE `RunID`='" . $RunID . "' LIMIT 1";
} else {
	$sql = "SELECT * FROM `oj_status` WHERE `RunID`='" . $RunID . "' AND `Show`=1 LIMIT 1";
}
$rs = oj_mysql_query($sql);
$row = oj_mysql_fetch_array($rs);
if (!$row) {
	header('Location: /Message.php?Msg=未找到该状态信息');
	return;
}

$StatusData = $row;

$TF = get_user_tailsAndFight($StatusData['User']);
$Fight = $TF['fight'];
$Tails = $TF['tails'];

$AllStatus = explode("|", $StatusData['AllStatus']);
?>

<body>
	<?php require_once('Php/Page_Header.php') ?>

	<?php
	if (is_admin()) {
		?>
		<script>
			function afreshEva(runID) {
				$.get("/Php/AfreshEva.php?ReEva=" + runID, function(msg) {
					var obj = eval('(' + msg + ')');
					if (obj.status === 0) {
						location.reload();
					}
				});
			}

			function changeStatusShow(runID) {
				$.get("/Php/StatusShow.php?RunID=" + runID, function(msg) {
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

		<link rel="stylesheet" href="/highlight/styles/default.css">
		<script src="/highlight/highlight.pack.js"></script>
		<script>
			hljs.initHighlightingOnLoad("gcc", "g++", "C++", "Java", "Python");
		</script>

		<h3 class="animated fadeInRight">详细评测信息 ID: <?php echo $StatusData['RunID'] ?> &nbsp;&nbsp;评测机:
			<?php echo $StatusData['Judger'] ?>&nbsp;&nbsp;
			<?php
			if (is_admin()) {
				echo '<a href="javascript:afreshEva(' . $RunID . ')" class="label label-warning" >重测</a> ';
			}
			if (can_read_log()) {
				echo ' <a class="label label-default" href="/ShowLog.php?RunID=' . $RunID . '">日志</a> ';
			}
			if (is_admin()) {
				if ($StatusData['Show'] == 1) {
					echo ' <a href="javascript:changeStatusShow(' . $RunID . ')" class="label label-primary">隐藏</a>';
				} else {
					echo ' <a href="javascript:changeStatusShow(' . $RunID . ')" class="label label-info">显示</a>';
				}
			}
			?>
		</h3>
		<div class="panel panel-default">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>运行ID</th>
						<th>用户</th>
						<th>题号</th>
						<th>评测结果</th>

						<th>用时(ms)</th>
						<th>内存(KB)</th>

						<th>语言</th>
						<th>代码长度(B)</th>
						<th>提交时间</th>
					</tr>
				</thead>
				<tbody>
					<tr>

						<td><?php echo $StatusData['RunID'] ?></td>
						<td><a href=<?php echo '"/OtherUser.php?User=' . $StatusData['User'] . '"' ?> class=<?php echo GetUserColor($Fight) ?>><?php echo $StatusData['User'] . ($Tails ? '(' . $Tails . ')' : '') ?></a></td>
						<td><a href=<?php echo '"/Question.php?Problem=' . $StatusData['Problem'] . '"' ?>>
								<?php echo $StatusData['Problem'] ?></a></td>

						<td>
							<?php
							$iStatic = $StatusData['Status'];
							if ($iStatic == Wating || $iStatic == Pending || $iStatic == Compiling || $iStatic == Running)
								echo '<a id="StatusTitle" data-content="点击刷新评测状态" class="label" href="javascript:location.reload();" data-status="' . $AllStatusName[$iStatic] . '">' . $AllStatusCName[$iStatic] . ' ' . $AllStatusName[$iStatic] . '</a>';
							else
								echo '<span class="label" data-status="' . $AllStatusName[$iStatic] . '">' . $AllStatusCName[$iStatic] . ' ' . $AllStatusName[$iStatic] . '</span>';
							?>
						</td>

						<td><?php echo $StatusData['UseTime'] ?></td>
						<td><?php echo $StatusData['UseMemory'] ?></td>

						<td><?php echo $StatusData['Language'] ?></td>
						<td><?php echo $StatusData['CodeLen'] ?></td>
						<td><?php echo $StatusData['SubTime'] ?></td>

					</tr>
				</tbody>
			</table>
		</div>
		<?php
		if ($StatusData['Status'] == CompileError) {
			echo '<div class="panel panel-default animated fadeInDown">';
			echo '<div class="panel-heading">编译错误信息</div>';
			echo '<div class="panel-body">';
			$File_Path = './Judge/Temporary_Error/' . $StatusData['RunID'] . '.log';
			if (file_exists($File_Path)) {
				$file_size = filesize($File_Path);
				if ($file_size >= 1 * 1024 * 1024) {
					echo "编译错误信息过长，无法显示.";
				} else {
					$file_arr = file($File_Path);

					for ($i = 0; $i < count($file_arr); $i++) {
						$str_encode = mb_convert_encoding($file_arr[$i], 'UTF-8', 'GBK');

						echo $str_encode . "<br/>";
					}
				}
			} else {
				echo "未找到编译错误日志.";
			}
			//$str = file_get_contents($File_Path);
			//$str = str_replace('\r', '<br/>', $str);
			echo '</div>';
			echo '</div>';
		} else if ($StatusData['Status'] != Wating && $StatusData['Status'] != Pending && $StatusData['Status'] != Compiling && $StatusData['Status'] != Running) {
			echo '<div class="panel panel-default animated fadeInDown">';
			echo '<div class="panel-heading">测试点详情</div>';
			echo '<table class="table table-striped table-hover">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>测试点</th>';
			echo '<th>评测结果</th>';
			echo '<th>用时(ms)</th>';
			echo '<th>内存(KB)</th>';
			echo '<th>返回值</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			foreach ($AllStatus as $val) {
				$iTest = explode("&", $val);

				if (count($iTest) == 5) {
					echo '<tr>';

					echo '<td>#' . $iTest[0];

					if (can_read_test()) {
						if ($iTest[0] < 10)
							echo '&nbsp;&nbsp;&nbsp;';
						else
							echo '&nbsp;';

						echo '<a class="label label-success" href="/ViewData_Def.php?Problem=' . $StatusData['Problem'] . '&Data=' . $iTest[0] . '">数据</a>';
					}
					echo '</td>';

					if ($iTest[1] >= 0)
						echo '<td><span class="label" data-status="' . $AllStatusName[$iTest[1]] . '">' . $AllStatusCName[$iTest[1]] . ' ' . $AllStatusName[$iTest[1]] . '</span></td>';
					else
						echo '<td><span class="label" data-status="Wating">NULL</span></td>';
					echo '<td>' . $iTest[2] . '</td>';
					echo '<td>' . $iTest[3] . '</td>';
					echo '<td>' . $iTest[4] . '</td>';
					echo '</tr>';
				}
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}
		?>

		<div class="panel panel-default animated fadeInDown">
			<div class="panel-heading">源代码</div>

			<?php
			if (isset($LandUser)) {
				if ($StatusData['User'] == $LandUser ||  can_read_code()) {

					echo '<div class="panel-body">';
					echo '<pre class="padding-0"><code class="C++">';

					/*
					$sql = "SELECT `code` FROM `oj_judge_task` WHERE `RunID`=" . $RunID . " LIMIT 1";
					$rs = oj_mysql_query($sql);
					$row = oj_mysql_fetch_array($rs);

					echo htmlspecialchars($row['code']);
					*/


					$File_Path = './Judge/Temporary_Code/' . $StatusData['RunID'];

					if (file_exists($File_Path)) {
						$file_arr = file($File_Path);

						for ($i = 0; $i < count($file_arr); $i++) {
							$str = $file_arr[$i];
							$str = htmlspecialchars($str);
							echo $str;
						}
					}


					echo '</code></pre></div>';
				} else {
					echo <<<NOTCODE
				<div class="panel-body">
				<p>您只能查看自己的代码哦~</p>
				</div>
NOTCODE;
				}
			} else {
				echo <<<NOTCODE
				<div class="panel-body">
				<p>您还没有登陆，不能查看代码哦~</p>
				</div>
NOTCODE;
			}
			?>
		</div>

	</div>
	<?php
	$PageActive = '#status';
	require_once('Php/Page_Footer.php');
	?>
</body>

</html>