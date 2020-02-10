<!DOCTYPE html>

<html lang="zh-cn">
<?php require_once("Html_Head.php"); ?>

<?php
$RunID = intval($_GET['RunID']);

if (!can_read_log()) {
	header('Location: /Message.php?Msg=您没有权限查看日志');
	die();
}
?>

<body>
	<?php require_once("Header.php"); ?>

	<div class="container">

		<h3>详细评测信息 ID: <?php echo $RunID ?></h3>
		<?php
		echo '<div class="panel panel-default animated fadeInLeft">';
		echo '<div class="panel-heading">编译日志</div>';
		echo '<div class="panel-body">';
		echo '<pre class="SlateFix">';

		echo '评测日志需要在评测机端查看';

		echo '</pre>';
		echo '</div>';
		echo '</div>';
		?>

	</div>
	<?php
	$PageActive = '#admin';
	require_once('Footer.php');
	?>
</body>

</html>