<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once("Html_Head.php");

$RatingData = explode('&', $ConData['RatingData']);
$DataNum = count($RatingData);
?>

<body>
	<?php
	require_once("Header.php");
	?>

	<div class="container">

		<div class="panel panel-default ">
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
					<table class="table table-striped table-hover text-center">
						<thead>
							<tr>
								<th>用户名</th>
								<th>比赛排名</th>
								<th>赛前战斗力</th>
								<th>战斗力增减</th>
								<th>赛后战斗力</th>
							</tr>
						</thead>
						<tbody>
							<?php
							for ($i = 0; $i < $DataNum; $i++) {
								if (!$RatingData[$i])
									continue;

								$iData = explode('|', $RatingData[$i]);
								$TF = get_user_tailsAndFight($iData[0]);

								$before = intval($iData[2]);
								$after =  intval($iData[3]);

							?>
								<tr data-rank="<?php echo $iData[1] ?>">
									<td><a href="/OtherUser.php?User=<?php echo $iData[0] ?>" class=<?php echo GetUserColor($TF['fight']) ?>><?php echo $iData[0] ?></a></td>
									<td><?php echo $iData[1] ?></td>
									<td><?php echo $iData[2] ?></td>
									<td class="SlateFixBlack <?php echo  $after >= $before ? 'rankyes' : 'rankno' ?>"><?php if ($after > $before) echo '+';
																														echo $after - $before ?></td>
									<td><?php echo $iData[3] ?></td>
								</tr>

							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php
	$PageActive = "#c_rating";
	require_once('Footer.php');
	?>


</body>

</html>