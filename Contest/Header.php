<?php
if (!isset($ConData))
	die();
?>

<script src="/js/click.js" type="text/javascript"></script>

<?php
if (can_edit_contest($ConID)) {
?>

	<script>
		function rating_settlement() {
			$.get(<?php echo '"/Rating/Rating.php?ConID=' . $ConID . '"' ?>, function(msg) {
				var obj = eval('(' + msg + ')');
				if (obj.status === 0) {
					location.reload();
				} else if (obj.status === 1) {
					alert('已经结算完毕。');
				} else {
					alert('结算时发生异常。');
				}
			});
		}

		function show_all_problem() {
			$.get(<?php echo '"/Contest/ShowAllProblem.php?ConID=' . $ConID . '"' ?>, function(msg) {
				var obj = eval('(' + msg + ')');
				if (obj.status === 0) {
					location.reload();
				}
			});
		}

		function hide_all_problem() {
			$.get(<?php echo '"/Contest/HideAllProblem.php?ConID=' . $ConID . '"' ?>, function(msg) {
				var obj = eval('(' + msg + ')');
				if (obj.status === 0) {
					location.reload();
				}
			});
		}

		function rejudge_all_status() {
			var problemWord = prompt("请输入比赛中的题号(A/B/C)，输入0则重测所有提交记录。", "");
			if (problemWord) {

				if (problemWord == 0) {
					if (confirm('确定要重测该比赛中的所有提交记录吗？这将消耗大量时间')) {
						$.get(<?php echo '"/Contest/AfreshEva_All.php?ConID=' . $ConID . '"' ?>, function(msg) {
							var obj = eval('(' + msg + ')');
							if (obj.status === 0) {
								location.reload();
							}
						});
					}
				} else if (confirm('确定要重测题号为' + problemWord + '的所有提交记录吗？')) {
					let problemID = problemWord.charCodeAt() - 65;
					if (problemID >= 0) {
						$.get(<?php echo '"/Contest/AfreshEva_All.php?ConID=' . $ConID . '"' ?> + '&ProblemID=' + problemID, function(msg) {
							var obj = eval('(' + msg + ')');
							if (obj.status === 0) {
								location.reload();
							}
						});
					} else {
						alert('题号输入错误');
					}
				}
			}
		}
	</script>
<?php
}
?>

<script>
	function logout() {
		$.get("/Php/Logout.php", function(msg) {
			var obj = eval('(' + msg + ')');
			if (obj.status === 0) {
				location.reload();
			}
		});
	}
</script>

<nav class="navbar navbar-default navbar-static-top" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#topNavbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/Contest.php">返回<?php echo $WebName ?></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="topNavbar">
			<ul class="nav navbar-nav">
				<li id="c_overview" role="presentation"><a href=<?php echo '"/Contest/Pandect.php?ConID=' . $ConID . '"'; ?>><span class="glyphicon glyphicon-home"><span class="font-size-1"> </span>比赛总览</span></a></li>

				<?php
				if ($NowDate >= $ConData['StartTime'] || can_edit_contest($ConID)) {
					echo '<li id="c_problem" role="presentation"><a href= "/Contest/Problem.php?ConID=' . $ConID . '"><span class="glyphicon glyphicon-file"><span class="font-size-1"> </span>题目列表</span></a></li>';
					echo '<li id="c_status" role="presentation"><a href= "/Contest/Status.php?ConID=' . $ConID . '"><span class="glyphicon glyphicon-list-alt"><span class="font-size-1"> </span>提交记录</span></a></li>';
					echo '<li id="c_rank" role="presentation"><a href= "/Contest/Rank.php?ConID=' . $ConID . '"><span class="glyphicon glyphicon-stats"><span class="font-size-1"> </span>排名</span></a></li>';
				}
				?>

				<?php
				if ($ConData['RatingStatus'] == 1) {
					echo '<li id="c_rating" role="presentation"><a href="/Contest/Rating.php?ConID=' . $ConID . '"><span class="	glyphicon glyphicon-flash"><span class="font-size-1"> </span>战斗力变化</span></a></li>';
				}
				?>

			</ul>
			<ul class="nav navbar-nav navbar-right">


				<?php
				if (!isset($LandUser)) {
					require_once("../html/Login.html");
				} else {
					if (is_admin()) {
						echo '<li id="admin"><a href="/Admin.php">管理员面板</a></li>';
					}
					echo '<li id="user"><a href="/User.php">' . $LandUser . '</a></li>';
					echo '<li><a href="javascript:logout()">退出账号</a></li>';
				}
				?>

			</ul>
		</div>
	</div>
</nav>

<?php
if (can_edit_contest($ConID)) {
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

<h1 class="text-center"><?php echo $ConData['Title'] ?>
	<?php
	if (can_edit_contest()) {
		echo '<a class="label label-warning" href="/NewContest.php?ConID=' . $ConData['ConID'] . '">编辑</a> ';
	}
	if (can_edit_contest($ConID)) {
		if ($ConData['Show'] == 1) {
			echo '<a href="javascript:changeContestStatus(' . $ConData['ConID'] . ')" class="label label-primary">隐藏</a>';
		} else {
			echo '<a href="javascript:changeContestStatus(' . $ConData['ConID'] . ')" class="label label-info">显示</a>';
		}

		echo ' <a class="label label-danger" href="/Contest/CodeSimilarity.php?ConID=' . $ConData['ConID'] . '">代码相似度检测</a> ';
	}
	?>
</h1>