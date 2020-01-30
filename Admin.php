<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<?php

if (!isset($LandUser)) {
    header('Location: /Message.php?Msg=您没有登陆，无权访问');
    die();
}
if (!is_admin()) {
    header('Location: /Message.php?Msg=您不是管理员，无权访问');
    die();
}
?>

<body>

    <?php require_once('Php/Page_Header.php') ?>

    <div class="container animated fadeInRight">
        <div class="panel panel-default">
            <div class="panel-body">
                <a style="margin-top:10px;" class="btn btn-warning" href="/admin_phpmyadmin/">进入Mysql数据库</a>
                <a style="margin-top:10px;" class="btn btn-danger" href="/admin_opcache/opcache.php">查看opcache缓存</a>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <a style="margin-top:10px;" class="btn btn-info" href="/NewProblem.php">新建题目</a>
                <a style="margin-top:10px;" class="btn btn-success" href="/NewContest.php">新建比赛</a>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <pre class="SlateFix">
管理员需知：
1.如需'修改'题目的测试点，请手动删除评测机中的测试点文件或新增一个测试点ID进行编辑并删除原错误的测试点ID。测试点最多支持50个。
2.只能通过更改数据库的方式赋予用户管理员权限
3.没有提供删除题目/比赛的功能，如果有不需要的题目/比赛请隐藏
4.只能通过更改数据库的方式删除题目/比赛
5.请勿尝试使用管理员权限对网站进行注入等攻击行为

如有其它操作（如修改测试点，删除题目/比赛，赋予管理员权限等），请联系：
Setiuo: QQ:751255159
                </pre>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <pre class="SlateFix">
星の力を秘めし鍵よ。

真の姿を我の前に示せ。

契約のもと <?php echo  $LandUser ?> が命じる。

封印解除（レリーズ）！
                </pre>
            </div>
        </div>
    </div>

    <?php
    $PageActive = '#admin';
    require_once('Php/Page_Footer.php');
    ?>
</body>

</html>