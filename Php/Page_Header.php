<script src="/js/click.js" type="text/javascript"></script>

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
	<div class="container ">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#topNavbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/"><?php echo $WebName ?></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="topNavbar">
			<ul class="nav navbar-nav">
				<li id="problem">
					<a href="Problem.php">
						<span class="glyphicon glyphicon-file">
							<span class="font-size-1"></span>题库</span>
					</a>
				</li>
				<li id="status">
					<a href="Status.php">
						<span class="glyphicon glyphicon-list-alt">
							<span class="font-size-1"></span>提交记录</span>
					</a>
				</li>
				<li id="contest">
					<a href="Contest.php">
						<span class="glyphicon glyphicon-flag">
							<span class="font-size-1"></span>比赛</span>
					</a>
				</li>
				<!--
				<li id="discuss">
					<a href="Discuss.php">
						<span class="glyphicon glyphicon-comment">
							<span class="font-size-1"></span>论坛</span>
					</a>
				</li>
-->
				<li id="ranklist">
					<a href="Ranklist.php">
						<span class="glyphicon glyphicon-stats">
							<span class="font-size-1">
							</span>排行榜</span>
					</a>
				</li>
			</ul>

			<ul class="nav navbar-nav navbar-right">

				<?php
				if (!isset($LandUser)) {
					require_once("html/Login.html");
				} else {
					echo '<li> <a style="color: rgb(0, 153, 255);font-weight: bold;" href="https://www.setiuo.top">首页 </a> </li>';
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