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

		<?php if (can_edit_problem()) { ?>
			<script>
				function delete_test(problem, test) {
					if (confirm('确定要删除测试点' + test + '吗？')) {

					}
				}
			</script>
		<?php } ?>

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
								$test_in_name = 'test' . $Var . '_in';
								$test_out_name = 'test' . $Var . '_out';

								$sql = 'SELECT `' . $test_in_name . '` FROM `oj_problem_test` WHERE `problemID` = ' . $problemID . ' LIMIT 1';
								$in_result = oj_mysql_query($sql);

								$sql = 'SELECT `' . $test_out_name . '` FROM `oj_problem_test` WHERE `problemID` = ' . $problemID . ' LIMIT 1';
								$out_result = oj_mysql_query($sql);

								if (!$in_result || !$out_result) {
									echo '<tr>';
									echo '<td>' . $Var . '</td>';
									echo '<td>-1</td>';
									echo '<td>测试点错误：文件缺失</td>';

									if (can_edit_problem()) {
										echo '<td><div class="input-group"><input type="file" name="userfile" />';
										echo '<a type="submit">上传</a></div></td>';
									}

									echo '</tr>';
									continue;
								}

								$testInData = oj_mysql_fetch_array($in_result);
								$testOutData = oj_mysql_fetch_array($out_result);

								$test_in = $testInData[$test_in_name];
								$test_out = $testOutData[$test_out_name];

								$Size_1 = mb_strlen($test_in);
								$Size_2 = mb_strlen($test_out);

								echo '<tr>';
								echo '<td>' . $Var . '</td>';
								echo '<td>' . ($Size_1 + $Size_2) . '</td>';
								echo '<td><a href="/ViewData_Def.php?Problem=' . $problemID . '&Data=' . $Var . '">点我~点我~就能看到数据了~</a></td>';
								if (can_edit_problem()) {
									echo '<td><a href="/ViewData_Edit.php?Problem=' . $problemID . '&Data=' . $Var . '">编辑</a></td>';
								}
								echo '</tr>';

								/*
								$InPath = "./Judge/data/" . intval($_GET['Problem']) . "/" . intval($_GET['Problem']) . "_" . $Var . ".in";
								$OutPath = "./Judge/data/" . intval($_GET['Problem']) . "/" . intval($_GET['Problem']) . "_" . $Var . ".out";

								if (!file_exists($InPath) || !file_exists($OutPath)) {
									echo '<tr>';
									echo '<td>' . $Var . '</td>';
									echo '<td>-1</td>';
									echo '<td>测试点错误：文件缺失</td>';

									if (can_edit_problem()) {
										echo '<td><div class="input-group"><input type="file" name="userfile" />';
										echo '<a type="submit">上传</a></div></td>';
									}

									echo '</tr>';
									continue;
								}

								$Size_1 = filesize($InPath);
								$Size_2 = filesize($OutPath);
								echo '<tr>';
								echo '<td>' . $Var . '</td>';
								echo '<td>' . ($Size_1 + $Size_2) . '</td>';
								echo '<td><a href="/ViewData_Def.php?Problem=' . intval($_GET['Problem']) . '&Data=' . $Var . '">点我~点我~就能看到数据了~</a></td>';
								if (can_edit_problem()) {
									echo '<td><a href="javascript:delete_test(' . intval($_GET["Problem"]) . ', ' . intval($Var) . ')">删除</a></td>';
								}
								echo '</tr>';
								*/
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