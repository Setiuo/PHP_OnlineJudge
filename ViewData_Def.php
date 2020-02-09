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

$problemID = intval($_GET['Problem']);

if (!can_edit_problem()) {
	$sql = "SELECT `Show` FROM `oj_problem` WHERE `proNum`='" . $problemID . "' LIMIT 1";
	$result = oj_mysql_query($sql);
	$ProblemData = oj_mysql_fetch_array($result);

	if ($ProblemData['Show'] == 0) {
		header('Location: /Message.php?Msg=题目已被隐藏，您无权查看测试点');
		die();
	}
}

$dataID = $_GET['Data'];

$inputData = '';
$outputData = '';

$sql = "SELECT `input`,`output` FROM `oj_problem_data` WHERE `problemID`=$problemID AND `testID`=$dataID LIMIT 1";
$have = oj_mysql_query($sql);
$row = oj_mysql_fetch_array($have);
if ($row) {
	$testData = $row;
	$inputData = $testData["input"];
	$outputData = $testData["output"];
}
?>

<body>
	<?php require_once('Php/Page_Header.php') ?>
	<div class="container animated fadeInLeft">


		<h3>测试点 # <?php echo $dataID ?></h3>
		<br>
		<div class="panel panel-default">
			<div class="panel-heading">输入数据</div>
			<div class="panel-body">
				<pre class="SlateFix"><?php
										echo $inputData;
										?></pre>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">输出数据</div>
			<div class="panel-body">
				<pre class="SlateFix"><?php
										echo $outputData;
										?></pre>
			</div>
		</div>

	</div>
	<?php
	$PageActive = '#problem';
	require_once('Php/Page_Footer.php');
	?>
</body>

</html>