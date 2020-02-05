<?php
require_once('Php/LoadData.php');
header("Content-Type: text/html; charset=utf-8"); //防止界面乱码

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
            //$AllSkin = array('Cerulean', 'cosmo', 'custom', 'cyborg', 'darkly', 'flatly', 'journal', 'lumen', 'paper', 'readable', 'sandstone', 'simplex', 'Slate', 'spacelab', 'superhero', 'united', 'yeti');
            if (isset($LandUser)) {
                echo "addcss('/css/bootstrap." . $Skin . ".min.css');";
            } else {
                //echo "addcss('/css/bootstrap.spacelab.min.css');";
                echo "addcss('/css/bootstrap.Cerulean.min.css');";
            }

            ?>

        })();
    </script>

    <!--
    <script language="javascript" type="text/javascript">
        function RefreshPage() {
            location.reload();
        }

        function GoHistoryPage() {
            history.go(-1);
        }


        function SkipPage(href) {
            location.href = href;
        }
    </script>
    -->

</head>