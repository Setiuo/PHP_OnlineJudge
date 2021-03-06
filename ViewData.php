<!DOCTYPE html>

<html lang="zh-cn">
<?php require_once('Php/HTML_Head.php') ?>

<?php

if (!isset($LandUser)) {
	header('Location: /Message.php?Msg=您没有登陆，无权访问测试点页面');
	die();
}
if (!can_read_test()) {
	header('Location: /Message.php?Msg=您无权访问测试点页面');
	die();
}
$problemID = intval($_GET['Problem']);

$sql = "SELECT `Test`, `Show` FROM `oj_problem` WHERE `proNum`=" . $problemID . " LIMIT 1";
$result = oj_mysql_query($sql);
$ProblemData = oj_mysql_fetch_array($result);
$AllData = explode('&', $ProblemData['Test']);

if (!can_edit_problem() && ($ProblemData['Show'] == 0)) {
	header('Location: /Message.php?Msg=题目已被隐藏，您无权查看测试点');
	die();
}

?>

<body>
	<?php require_once('Php/Page_Header.php') ?>
	<div class="container">

		<div class="panel panel-default">
			<div class="panel-heading">题目数据</div>
			<div class="panel-body">
				<div class="panel panel-default animated fadeInLeft">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>测试点</th>
								<th>数据长度(B)</th>
								<th>查看</th>
								<?php if (can_edit_problem()) { ?>
									<th>操作</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>

							<?php
							foreach ($AllData as $Var) {
								$sql = "SELECT CHAR_LENGTH(`input`)+CHAR_LENGTH(`output`) AS VALUE FROM `oj_problem_data` WHERE `problemID`=$problemID AND `testID`=$Var";
								$res = oj_mysql_query($sql);
								$data = oj_mysql_fetch_array($res);

								$Size = $data['VALUE'] ? $data['VALUE'] : 0;

								echo '<tr>';
								echo '<td>' . $Var . '</td>';
								echo '<td>' . ($Size) . '</td>';
								echo '<td><a href="/ViewData_Def.php?Problem=' . $problemID . '&Data=' . $Var . '">点我~点我~就能看到数据了~</a></td>';
								if (can_edit_problem()) {
									echo '<td><a href="/ViewData_Edit.php?Problem=' . $problemID . '&Data=' . $Var . '">编辑</a></td>';
								}
								echo '</tr>';
							}
							?>

						</tbody>
					</table>
				</div>
				<br />
				<center> <a class="btn btn-default" href="javascript:history.go(-1);">返回</a> </center>
			</div>
		</div>
	</div>

	<?php
	$PageActive = '#problem';
	require_once('Php/Page_Footer.php');
	?>
</body>

</html>