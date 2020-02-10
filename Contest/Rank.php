<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once("Html_Head.php"); ?>

<body>
	<?php
	require_once("Header.php");

	if (!isset($ConData)) {
		header('Location: /Message.php?Msg=比赛数据获取异常');
		die();
	}

	$AllProblem = explode('|', $ConData['Problem']);
	$ProNum = count($AllProblem);
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


				<div class="panel panel-default animated fadeInDown">

					<?php
					if ($ConData['Rule'] == 'ACM') {
						if ($ConData['Practice'])
							include_once('Rank_ACM_Practice.php');
						else
							include_once('Rank_ACM.php');
					} else
						include_once('Rank_OI.php');
					?>

				</div>
			</div>
		</div>
	</div>
	<?php
	$PageActive = "#c_rank";
	require_once('Footer.php');
	?>
</body>

</html>