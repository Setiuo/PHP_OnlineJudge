<!DOCTYPE html>

<html lang="zh-cn">
<?php require_once('Php/HTML_Head.php') ?>

<?php
$RunID = intval($_GET['RunID']);

if (!can_read_log()) {
	header('Location: /Message.php?Msg=您没有权限查看日志');
	return;
}
?>

<body>
	<?php require_once('Php/Page_Header.php') ?>

	<div class="container">

		<h3>详细评测信息 ID: <?php echo $RunID ?></h3>
		<?php
		echo '<div class="panel panel-default animated fadeInLeft">';
		echo '<div class="panel-heading">编译日志</div>';
		echo '<div class="panel-body">';
		echo '<pre class="SlateFix">';

		$File_Path = './Judge/log/Judge_' . $RunID . '.log';

		if (file_exists($File_Path)) {
			$file_arr = file($File_Path);

			for ($i = 0; $i < count($file_arr); $i++) {
				$str_encode = mb_convert_encoding($file_arr[$i], 'UTF-8', 'GBK');
				echo $str_encode;
			}
		}
		echo '</pre>';
		echo '</div>';
		echo '</div>';
		?>

	</div>
	<?php
	$PageActive = '#admin';
	require_once('Php/Page_Footer.php');
	?>
</body>

</html>