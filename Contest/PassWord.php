<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once("Html_Head.php"); ?>

<body>
    <?php
    require_once("Header.php");

    if ($ConData['Type'] == 0) {
        echo "<script>location.href='/Contest/Pandect.php?ConID=" . $ConID . "';</script>";
        die();
    }
    ?>

    <script>
        function joinContest(contestID) {
            $.post("/Contest/SubPassWord.php", $('#passwordform').serialize(), function(msg) {
                var obj = eval('(' + msg + ')');

                if (obj.status === 0) {
                    location.href = obj.href;
                } else if (obj.status === 1) {
                    alert('密码输入错误！');
                } else {
                    alert('比赛信息获取出错！');
                }
            });

            return false;
        }
    </script>

    <div class="container animated fadeInLeft">
        <div class="panel panel-default">
            <div id="contesthead" class="panel-heading" style="padding:0 0 0 0;">
                <ul class="nav nav-tabs" role="tablist">

                    <li>
                        <h4>&nbsp;</h4>
                    </li>
                </ul>
            </div>
            <div class="panel-body">

                <h3>请输入比赛密码： </h3>
                <form class="input-group" autocomplete="off" id="passwordform" onsubmit="return joinContest()">
                    <span class="input-group-addon">密码：</span>
                    <input type="hidden" name="ConID" value=<?php echo '"' . $ConID . '"'; ?>>
                    <input type="password" name="ConPassWord" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Go!</button>
                    </span>
                </form>
            </div>
        </div>

    </div>

    <?php
    $PageActive = "";
    require_once('Footer.php');
    ?>

</body>

</html>