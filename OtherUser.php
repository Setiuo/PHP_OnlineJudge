<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<?php
$User;

if (array_key_exists('User', $_GET)) {
    $User = $_GET['User'];
}

if (isset($User)) {
    $sql = "SELECT * FROM `oj_user` WHERE `name`='" . $User . "' LIMIT 1";
    $rs = oj_mysql_query($sql);
    $UserData = oj_mysql_fetch_array($rs);

    if (!isset($UserData['name'])) {
        header('Location: /Message.php?Msg=未找到该用户');
        die();
    }

    //获取战斗力
    $Fight = $UserData['fight'];
    //获取小尾巴
    $Tails = $UserData['tails'];
    //获取E-Mail
    $E_Mail = $UserData['email'];
    //获取用户签名
    $Signature = $UserData['signature'];
    //获取注册时间
    $Regtime = $UserData['regtime'];
    //获取登陆时间
    $Logtime = $UserData['logtime'];

    $Allsubnum = 0;

    $PassProblem = array();
    $PassProNum = 0;
    $sql = "SELECT `Problem` FROM `oj_status` WHERE `User`='" . $User . "' AND `Status` = " . Accepted . " AND `Show`=1";

    if (is_admin()) {
        $sql = "SELECT `Problem` FROM `oj_status` WHERE `User`='" . $User . "' AND `Status` = " . Accepted;
    }

    $rs = oj_mysql_query($sql);

    while ($ProblemData = oj_mysql_fetch_array($rs)) {
        $Allsubnum++;

        if (!in_array($ProblemData['Problem'], $PassProblem)) {
            array_push($PassProblem, $ProblemData['Problem']);
            $PassProNum++;
        }
    }

    sort($PassProblem);

    $nPassProblem = array();
    $nPassProNum = 0;
    $sql = "SELECT `Problem` FROM `oj_status` WHERE `User`='" . $User . "' AND `Status` != " . Accepted . " AND `Show`=1";

    if (is_admin()) {
        $sql = "SELECT `Problem` FROM `oj_status` WHERE `User`='" . $User . "' AND `Status` != " . Accepted;
    }

    $rs = oj_mysql_query($sql);

    while ($ProblemData = oj_mysql_fetch_array($rs)) {
        $Allsubnum++;

        if (!in_array($ProblemData['Problem'], $PassProblem) && !in_array($ProblemData['Problem'], $PassProblem)) {
            array_push($nPassProblem, $ProblemData['Problem']);
            $nPassProNum++;
        }
    }

    sort($nPassProblem);
} else {
    header('Location: /Message.php?Msg=用户信息载入失败');
    die();
}
?>

<body>

    <?php require_once('Php/Page_Header.php') ?>

    <div class="container">

        <div class="panel panel-default">
            <div class="panel-heading">个人信息</div>
            <div class="panel-body">
                <form method="post">
                    <div class="panel panel-default float-center animated fadeInLeft" style="width:450px;">
                        <table class="table">
                            <tr>
                                <td>用户名</td>
                                <td>
                                    <font class=<?php echo GetUserColor($Fight) ?>> <?php echo $User . ($Tails ? '(' . $Tails . ')' : '') ?> </font>
                                </td>
                            </tr>

                            <tr>
                                <td>E-mail</td>
                                <td><?php echo $E_Mail ?>
                                </td>
                            </tr>

                            <tr>
                                <td>注册日期</td>
                                <td><?php echo is_admin() ? $Regtime : date("Y-m-d", strtotime($Regtime)) ?></td>
                            </tr>
                            <tr>
                                <td>最后登录日期</td>
                                <td><?php echo is_admin() ? $Logtime : date("Y-m-d", strtotime($Logtime)) ?></td>
                            </tr>
                            <tr>
                                <td>战斗力</td>
                                <td><?php echo $Fight ?></td>
                            </tr>
                            <tr>
                                <td>提交总次数</td>
                                <td><?php echo $Allsubnum ?></td>
                            </tr>

                            <tr>
                                <td>通过题数</td>
                                <td><?php echo $PassProNum ?></td>
                            </tr>
                            <tr>
                                <td>个性签名</td>
                                <td><?php echo $Signature ?></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-default animated fadeInDown">
            <div class="panel-heading">已解决的问题编号
                <a href=<?php echo '"/Status.php?User=' . $User . '&Status=' . Accepted . '"' ?> class="label label-primary">查看通过记录</a>
            </div>
            <div class="panel-body">

                <?php
                for ($i = 1; $i <= $PassProNum; $i++) {
                    echo '<a href="/Question.php?Problem=' . $PassProblem[$i - 1] . '" class="label label-primary">' . $PassProblem[$i - 1] . '</a> ';
                }
                ?>

            </div>
        </div>

        <div class="panel panel-default animated fadeInDown">
            <div class="panel-heading">尝试过但尚未解决的问题编号
                <a href=<?php echo '"/Status.php?User=' . $User . '"' ?> class="label label-primary">查看全部记录</a>
            </div>
            <div class="panel-body">

                <?php
                for ($i = 1; $i <= $nPassProNum; $i++) {
                    echo '<a href="/Question.php?Problem=' . $nPassProblem[$i - 1] . '" class="label label-default">' . $nPassProblem[$i - 1] . '</a> ';
                }
                ?>

            </div>
        </div>

    </div>
    <?php
    $PageActive = '';
    require_once('Php/Page_Footer.php')
    ?>
</body>

</html>