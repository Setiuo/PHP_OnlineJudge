<!DOCTYPE html>

<html lang="zh-cn">
<?php require_once('Php/HTML_Head.php') ?>

<?php

if (!isset($LandUser)) {
	header('Location: /Message.php?Msg=您没有登陆，无权访问测试点编辑页面');
	die();
}
if (!can_edit_problem()) {
	header('Location: /Message.php?Msg=您无权访问测试点编辑页面');
	die();
}

$problemID = intval($_GET['Problem']);
$dataID = intval($_GET['Data']);
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

		<h3>测试点 # <?php echo intval($_GET['Data']) ?></h3>
		<br>

		<form id="dataform" onsubmit="return save()">
			<div class="panel panel-default">
				<div class="panel-heading">基础信息</div>
				<div class="panel-body">

					<div class="col-xs-6">
						<div class="input-group">
							<span class="input-group-addon">题目ID</span>
							<input name="problemID" type="text" class="form-control" value="<?php echo $problemID ?>">
						</div>
						<br>
					</div>

					<div class="col-xs-6">
						<div class="input-group">
							<span class="input-group-addon">测试点ID</span>
							<input name="dataID" type="number" class="form-control" value="<?php echo $dataID ?>">
						</div>
					</div>

				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">输入数据</div>
				<div class="panel-body">
					<textarea style="height:200px" name="inputData" class="form-control"><?php echo $inputData ?></textarea>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">输出数据</div>
				<div class="panel-body">
					<textarea style="height:200px" name="outputData" class="form-control"><?php echo $outputData ?></textarea>
				</div>
			</div>

			<center>
				<a class="btn btn-default" href="javascript:history.go(-1);">返回</a>
				<a class="btn btn-default" href="javascript:save();">保存</a>
			</center>
		</form>

	</div>
	<?php
	$PageActive = '#problem';
	require_once('Php/Page_Footer.php');
	?>

	<script>
		function save() {
			$.post("/Php/SubmitData.php", $('#dataform').serialize(), function(msg) {
				var obj = eval('(' + msg + ')');

				if (obj.status === 0) {
					alert('测试点编辑成功！');
					history.go(-1);
				} else {
					alert('测试点编辑失败！');
					history.go(-1);
				}
			});

			return false;
		}
	</script>
</body>

</html>