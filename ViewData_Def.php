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
if (!array_key_exists('Problem', $_GET)) {
	header('Location: /Message.php?Msg=未知题号');
	die();
}
if (!array_key_exists('Data', $_GET)) {
	header('Location: /Message.php?Msg=未知测试点');
	die();
}

if (!can_edit_problem()) {
	$sql = "SELECT `Show` FROM `oj_problem` WHERE `proNum`='" . intval($_GET['Problem']) . "' LIMIT 1";
	$result = oj_mysql_query($sql);
	$ProblemData = oj_mysql_fetch_array($result);

	if ($ProblemData['Show'] == 0) {
		header('Location: /Message.php?Msg=题目已被隐藏，您无权查看测试点');
		die();
	}
}
$FileIn_Path = "./Judge/data/" . intval($_GET['Problem']) . "/" . intval($_GET['Problem']) . "_" . intval($_GET['Data']) . ".in";
$FileOut_Path = "./Judge/data/" . intval($_GET['Problem']) . "/" . intval($_GET['Problem']) . "_" . intval($_GET['Data']) . ".out";
?>

<body>
	<?php require_once('Php/Page_Header.php') ?>
	<div class="container animated fadeInLeft">


		<h3>测试点 # <?php echo intval($_GET['Data']) ?></h3>
		<br>
		<div class="panel panel-default">
			<div class="panel-heading">输入数据</div>
			<div class="panel-body">
				<pre class="SlateFix"><?php
										if (file_exists($FileIn_Path)) {
											$file_arr = file($FileIn_Path);

											for ($i = 0; $i < count($file_arr); $i++) {
												$str = $file_arr[$i];
												$str = str_replace("<", "&lt;", $str);
												$str = str_replace(">", "&gt;", $str);
												echo $str;
											}
										}
										?>
				</pre>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">输出数据</div>
			<div class="panel-body">
				<pre class="SlateFix"><?php
										if (file_exists($FileOut_Path)) {
											$file_arr = file($FileOut_Path);

											for ($i = 0; $i < count($file_arr); $i++) {
												$str = $file_arr[$i];
												$str = str_replace("<", "&lt;", $str);
												$str = str_replace(">", "&gt;", $str);
												echo $str;
											}
										}
										?>
				</pre>
			</div>
		</div>

	</div>
	<?php
	$PageActive = '#problem';
	require_once('Php/Page_Footer.php');
	?>
</body>

</html>