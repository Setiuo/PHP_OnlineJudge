<!DOCTYPE html>

<html lang="zh-cn">
<?php require_once('Php/HTML_Head.php') ?>

<body>
	<?php require_once('Php/Page_Header.php') ?>

	<div class="container">
		<div class="panel panel-default animated fadeInLeft">
			<div class="panel-body">
				<h1>出错啦:</h1>

				<?php
				if (array_key_exists('Msg', $_GET)) {
					echo '<h3>' . $_GET['Msg'] . '</h3>';
				}
				?>
				<a class="btn btn-default" href="javascript:history.go(-1);">返回</a>
			</div>
		</div>


	</div>

	<?php
	$PageActive = '';
	require_once('Php/Page_Footer.php');
	?>

</body>

</html>