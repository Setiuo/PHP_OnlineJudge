<?php
require_once("../Php/LoadData.php");
header("Content-Type: text/html; charset=utf-8"); //防止界面乱码

global $ConData;
global $NowDate;
global $ConID;
global $ProEngNum;

$Skin = "";

if (isset($LandUser)) {
	$sql = "SELECT `skin` FROM `oj_user` WHERE `name`='" . $LandUser . "' LIMIT 1";
	$rs = oj_mysql_query($sql);
	if (!$rs) {
		unset($_SESSION['username']);
		header('Location: /Message.php?Msg=用户信息载入失败');
		die();
	}
	$row = oj_mysql_fetch_array($rs);
	$Skin = $row['skin'];
}

if (!array_key_exists('ConID', $_GET)) {
	header('Location: /Message.php?Msg=比赛信息获取失败');
	die();
}

$ConID = intval($_GET['ConID']);

$sql = "SELECT * FROM `oj_contest` WHERE `ConID`=" . $ConID . " LIMIT 1";
$result = oj_mysql_query($sql);
$ConData = oj_mysql_fetch_array($result);

if (!$ConData) {
	header('Location: /Message.php?Msg=比赛信息获取失败');
	die();
}

if ($ConData['Show'] == 0 && !can_edit_contest($ConID)) {
	header('Location: /Message.php?Msg=比赛已被隐藏');
	die();
}

//检查比赛是否需要密码进入
if ($ConData['Type'] == 1 && $_SERVER['PHP_SELF'] != "/Contest/PassWord.php" && (!can_edit_contest($ConID))) {
	if (isset($_SESSION['ConPassWord_' . $ConID])) {
		if ($_SESSION['ConPassWord_' . $ConID] != $ConData['PassWord']) {
			header('Location: /Contest/PassWord.php?ConID=' . $ConID);
			die();
		}
	} else {
		header('Location: /Contest/PassWord.php?ConID=' . $ConID);
		die();
	}
} else if ($_SERVER['PHP_SELF'] == "/Contest/PassWord.php") {
	if ((can_edit_contest($ConID)) || (isset($_SESSION['ConPassWord_' . $ConID]) && $_SESSION['ConPassWord_' . $ConID] == $ConData['PassWord'])) {
		header('Location: /Contest/Pandect.php?ConID=' . $ConID);
		die();
	}
}

$NowDate = date('Y-m-d H:i:s');

//封榜时间
if ($ConData['Practice'] == 0 && $ConData['Rule'] == 'ACM' && $NowDate <= $ConData['UnfreezeTime'] && !can_edit_contest($ConID)) {
	$FreezeTime = $ConData['FreezeTime'];
} else {
	$FreezeTime = $ConData['OverTime'];
}

$ProEngNum = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
?>


<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo $WebHtmlTitle ?></title>

	<link href="/css/custom.css?v=<?php echo $OJ_Version ?>" rel="stylesheet">
	<link href="/css/community.css" rel="stylesheet">
	<link href="/css/animate.min.css" rel="stylesheet">

	<script src="/js/jquery-1.11.1.min.js"></script>
	<script src="/js/jsencrypt.min.js"></script>

	<script>
		(function() {
			var addcss = function(file) {
				document.write('<link href="' + file + '" rel="stylesheet">');
			};

			<?php
			if (isset($LandUser)) {
				echo "addcss('/css/bootstrap." . $Skin . ".min.css');";
			} else {
				echo "addcss('/css/bootstrap.lumen.min.css');";
				//echo "addcss('/css/bootstrap.spacelab.min.css');";
			}

			?>

		})();
	</script>
</head>