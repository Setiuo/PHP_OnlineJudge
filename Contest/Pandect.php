<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once("Html_Head.php"); ?>

<body>
	<?php require_once("Header.php"); ?>

	<script>
		function joinContest(contestID) {
			$.get("/Contest/JoinContest.php?ConID=" + contestID, function(msg) {
				var obj = eval('(' + msg + ')');
				if (obj.status === 0) {
					location.reload();
				} else if (obj.status === 1) {
					alert('报名失败！');
				} else if (obj.status === 2) {
					alert('您未登陆，无法报名比赛！');
				}
			});
		}
	</script>

	<div class="container animated fadeInLeft">

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

				<table class="autotable" align="center">

					<tr>
						<td><b>竞赛规则:</b><?php echo $ConData['Rule'] ?></td>
						<?php
						if (can_edit_contest($ConID) && $ConData['Type'] == 1) {
							echo '<td><b>比赛密码:</b>' . $ConData['PassWord'];
							echo '</td>';
						}
						?>
					</tr>

					<tr>
						<td><b>开始时间:</b><?php echo $ConData['StartTime'] ?></td>
						<td><b>结束时间:</b><?php echo $ConData['OverTime'] ?></td>
					</tr>

					<tr>
						<td><b>报名时间:</b><?php echo $ConData['EnrollStartTime'] ?></td>
						<td><b>报名截止:</b><?php echo $ConData['EnrollOverTime'] ?></td>
					</tr>

					<?php if ($ConData['Practice'] == 0 && $ConData['Rule'] == 'ACM') { ?>
						<tr>
							<td><b>封榜时间:</b><?php echo $ConData['FreezeTime'] ?></td>
							<td><b>封榜状态:</b><?php
											if ($NowDate < $ConData['FreezeTime']) {
												echo '<font class="label label-primary">未封榜</font>';
											} else if ($NowDate > $ConData['UnfreezeTime']) {
												echo '<font class="label label-default">封榜已解除</font>';
											} else {
												echo '<font class="label label-success">封榜中</font>';
											}
											?>
							</td>
						</tr>
					<?php
					}
					?>
					<tr>
						<?php
						$ConStatus;
						$EnrollStatus;

						if ($NowDate <= $ConData['StartTime']) {
							$ConStatus = 0;
							echo '<td><b>比赛状态:</b><font class="label label-primary">未开始</font></td>';
						} else if ($NowDate <= $ConData['OverTime']) {
							$ConStatus = 1;
							echo '<td><b>比赛状态:</b><font class="label label-success">正在进行中</font></td>';
						} else {
							$ConStatus = 2;
							echo '<td><b>比赛状态:</b><font class="label label-default">已结束</font></td>';
						}

						if ($NowDate <= $ConData['EnrollStartTime']) {
							echo '<td><b>报名状态:</b><font class="label label-primary">未开始</font></td>';
						} else {
							$AllPeople = $ConData['EnrollPeople'];
							$Data = explode('|', $AllPeople);

							if ($NowDate <= $ConData['EnrollOverTime']) {
								if (!in_array($LandUser, $Data) || !isset($LandUser)) {
									echo '<td><b>报名状态:</b><a href="javascript:joinContest(' . $ConID . ')" class="label label-success">立即报名</a></td>';
								} else {
									echo '<td><b>报名状态:</b><font class="label label-success">已报名</font></td>';
								}
							} else {
								if (!in_array($LandUser, $Data)) {
									echo '<td><b>报名状态:</b><font class="label label-default">报名已截止</font></td>';
								} else {
									if (!in_array($LandUser, $Data) || !isset($LandUser)) {
										echo '<td><b>报名状态:</b><font class="label label-default">报名已截止</font></td>';
									} else {
										echo '<td><b>报名状态:</b><font class="label label-success">已报名</font></td>';
									}
								}
							}
						}
						?>
					</tr>

					<?php
					$TF = get_user_tailsAndFight($ConData['Organizer']);
					?>
					<tr>
						<td><b>参赛人数:</b><?php echo (isset($Data) && ($AllPeople != '')) ? count($Data) : 0 ?></td>
						<td><b>举办人:</b>
							<font class=<?php echo GetUserColor($TF['fight']) ?>><?php
																					echo $ConData['Organizer'] . ($TF['tails'] ? '(' . $TF['tails'] . ')' : '');
																					?></font>
						</td>
					</tr>
					<?php if ($ConData['RiskRatio'] != 0) { ?>
						<tr>
							<td><b>风险系数:</b><?php echo $ConData['RiskRatio'] ?></td>
							<?php
							if ($ConData['RatingStatus'] == 0) {
								if (can_edit_contest($ConID)) {
									echo '<td>';
									echo '<b>战斗力结算完毕:</b><a class="label label-warning" href="javascript:rating_settlement()">立即结算</a>';
									echo '</td>';
								} else {
									echo '<td><b>战斗力结算完毕:</b><font class="label label-danger">未结算</font></td>';
								}
							} else {
								echo '<td><b>战斗力结算完毕:</b><font class="label label-success">已结算</font></td>';
							}
							?>
						</tr>
					<?php } ?>
				</table>

				<?php if ($ConData['Synopsis'] != '') { ?>
					<h3>比赛简介</h3>
					<div class="panel panel-default">
						<div class="panel-body">
							<?php echo $ConData['Synopsis'] ?>
						</div>
					</div>
				<?php } ?>

				<?php if ($NowDate >= $ConData['StartTime'] || (can_edit_contest($ConID))) { ?>

					<h3>题目列表</h3>
					<div class="panel panel-default">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>题号</th>
									<th>题目名称</th>
									<?php
									if ($ConData['Rule'] == 'ACM' || can_edit_contest($ConID) || $NowDate >= $ConData['OverTime']) {
										echo '<th>通过人数</th>';
										echo '<th>总提交次数</th>';
									}
									?>
								</tr>
							</thead>
							<tbody>

								<?php
								$AllProblem = explode('|', $ConData['Problem']);
								$ProNum = count($AllProblem);

								for ($i = 0; $i < $ProNum; $i++) {
									$sql = "SELECT `Name` FROM `oj_problem` WHERE `proNum`=" . $AllProblem[$i] . " LIMIT 1";
									$result = oj_mysql_query($sql);
									if (!$result) {
										continue;
									}
									$ProblemData = oj_mysql_fetch_array($result);

									$sql = "SELECT count(distinct(`User`)) AS value FROM `oj_constatus` WHERE `Status` = " . Accepted . " AND `Show`=1 AND `Problem` = " . $i . " AND `ConID`=" . $ConID . " AND `SubTime`<'" . $FreezeTime . "'";
									if (can_edit_contest($ConID)) {
										$sql = "SELECT count(distinct(`User`)) AS value FROM `oj_constatus` WHERE `Status` = " . Accepted . " AND `Problem` = " . $i . " AND `ConID`=" . $ConID . " AND `SubTime`<'" . $FreezeTime . "'";
									}
									$rs = oj_mysql_query($sql);
									$PassNum = oj_mysql_fetch_array($rs);

									$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `Show`=1 AND `Problem` = " . $i . " AND `ConID`=" . $ConID;
									if (can_edit_contest($ConID)) {
										$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `Problem` = " . $i . " AND `ConID`=" . $ConID;
									}
									$rs = oj_mysql_query($sql);
									$SubNum = oj_mysql_fetch_array($rs);

									echo '<tr>';

									echo '<td>' . $ProEngNum[$i] . '</td>';
									echo '<td>';
									echo '<a href="/Contest/Problem.php?ConID=' . $ConID . '&Problem=' . $ProEngNum[$i] . '">' . $ProblemData['Name'] . '</a>';
									if ($ConStatus == 2 || can_edit_contest($ConID)) {
										echo ' [题库题号 <a href="/Question.php?Problem=' . $AllProblem[$i] . '">P' . $AllProblem[$i] . '</a>]';
									}
									echo '</td>';

									if ($ConData['Rule'] == 'ACM' || can_edit_contest($ConID) || $NowDate >= $ConData['OverTime']) {
										echo '<td>' . $PassNum['value'] . '</td>';
										echo '<td>' . $SubNum['value'] . '</td>';
									}

									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					</div>
				<?php } ?>
			</div>
		</div>

	</div>

	<script src="/MathJax-master/MathJax.js?config=TeX-AMS_HTML-full"></script>

	<script type="text/x-mathjax-config">
		MathJax.Hub.Config({
          tex2jax: {
            inlineMath: [ ['$','$'], ["\\(","\\)"] ],
            processEscapes: true  
          },
          TeX: {
            equationNumbers: { autoNumber: "AMS" },
            Macros: {
          du: '^\\circ',
              vv: '\\overrightarrow',
              bm: '\\boldsymbol',
            }
          },
          "HTML-CSS": {
			linebreaks: {automatic: true},
			showMathMenu: false
          },
            menuSettings: {
              zoom: "Double-Click"
          }
        });
	  </script>

	<?php
	$PageActive = "#c_overview";
	require_once('Footer.php');
	?>

</body>

</html>