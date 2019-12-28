<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<?php

//获取用户数量
$sql = "SELECT count(*) as `value` FROM `oj_user`";
$rs = oj_mysql_query($sql);
$ConCount = oj_mysql_fetch_array($rs);
$clength = $ConCount['value'];

//定义常量，一页中最大显示数量
define("MaxRankNum", 20);
//定义常量，一页中最多显示按钮数量(奇数)
define("MaxButtonNum", 5);

//计算总页数
$AllPage = $clength / MaxRankNum;

//获取当前页数
$iPage = 1;
if (array_key_exists('Page', $_GET)) {
    $iPage = intval($_GET['Page']);
}
$iPage = floor($iPage);
//计算上一页的页数
$LastPage = ($iPage - 1 <= 0) ? 1 : ($iPage - 1);
//计算下一页的页数
$NextPage = $iPage * MaxRankNum < $clength ? $iPage + 1 : $iPage;
//最小页数
$MinPage = 1;
$iPage = $iPage >=  $MinPage ? $iPage : 1;
//最大页数
$MaxPage = ceil(($clength * 1.0) / MaxRankNum);
$iPage = $iPage <=  $MaxPage ? $iPage : $MaxPage;
//根据页数计算显示第一个的排名
$Rank = ($iPage - 1) * MaxRankNum;

//开始显示的按钮数字
$StaButNum;
//至结束显示的按钮数字
$EndButNum;

//如果最大页数小于等于一页中最多显示按钮数量
if ($MaxPage <= MaxButtonNum) {
    $StaButNum = 1;
    $EndButNum = $MaxPage;
} else {
    //将当前页的数字当作中间的按钮
    $iCenBuNum = $iPage;
    //开始显示的按钮数字为 最多显示按钮数量/2
    $StaButNum = $iCenBuNum - floor(MaxButtonNum / 2);
    //至结束显示的按钮的数字为 最多显示按钮数量/2
    $EndButNum = $iCenBuNum + floor(MaxButtonNum / 2);

    //如果开始显示的数字<=0，说明不能把当前页的数字当作中间的按钮
    if ($StaButNum <= 0) {
        //将至结束显示的按钮的数字调整
        $EndButNum -= $StaButNum - 1;
        //开始显示的数字显示为1
        $StaButNum -= $StaButNum - 1;
    }

    //如果结束显示的数字>最多显示按钮数量，说明不能把当前页的数字当作中间的按钮
    if ($EndButNum > $MaxPage) {
        //调整开始按钮的值
        $StaButNum -= ($EndButNum - $MaxPage);
        //结束显示的数字显示为最大值
        $EndButNum = $MaxPage;
    }
}

//读取数据库中的用户信息，保存在数组中
$sql = "SELECT `name`,`tails`,`signature`,`fight`,`regtime` FROM `oj_user` ORDER BY `fight` DESC, `regtime` LIMIT " . ($iPage - 1) * MaxRankNum . ", " . MaxRankNum;
$result = oj_mysql_query($sql);
$AllUserData = array();

if (!$result) {
    header('Location: /Message.php?Msg=用户信息获取失败');
}

while ($row = oj_mysql_fetch_array($result)) {
    $PassProNum = 0;
    $AllNum = 0;

    $sql = "SELECT count(*) AS value FROM `oj_status` WHERE User='" . $row['name'] . "' AND `Show`=1";
    if (is_admin()) {
        $sql = "SELECT count(*) AS value FROM `oj_status` WHERE User='" . $row['name'] . "'";
    }
    $rs = oj_mysql_query($sql);
    $AllNum = oj_mysql_fetch_array($rs);

    $sql = "SELECT `Problem` FROM `oj_status` WHERE `User`='" . $row['name'] . "' AND `Status` = " . Accepted . " AND `Show`=1";
    if (is_admin()) {
        $sql = "SELECT `Problem` FROM `oj_status` WHERE `User`='" . $row['name'] . "' AND `Status` = " . Accepted . "";
    }
    $rs = oj_mysql_query($sql);
    $PassProblem = array();
    while ($ProblemData = oj_mysql_fetch_array($rs)) {
        if (!in_array($ProblemData['Problem'], $PassProblem)) {
            array_push($PassProblem, $ProblemData['Problem']);
            $PassProNum++;
        }
    }

    $AllUserData[] = array(
        "Name" => $row['name'],
        "Tails" => $row['tails'],
        "Text" => $row['signature'],
        "q_PassNum" => $PassProNum,
        "q_AllNum" => $AllNum['value'],
        "Fight" => $row['fight'],
        "RegisterTime" => $row['regtime']
    );
}

//对用户按照战斗力进行排名
function my_sort($a, $b)
{
    if ($a['Fight'] == $b['Fight']) {
        return $a['RegisterTime'] < $b['RegisterTime'] ? -1 : 1;
    }

    return $a['Fight'] > $b['Fight'] ? -1 : 1;
}

usort($AllUserData, "my_sort");

/*
$arr1 = array_map(create_function('$n', 'return $n["Fight"];'), $AllUserData);
array_multisort($arr1, SORT_DESC, $AllUserData);
*/
?>

<body>
    <?php require_once('Php/Page_Header.php') ?>

    <div class="container">

        <center>
            <h2>排行榜</h2>
        </center>

        <center>
            <ul class="pagination">
                <li><a href=<?php echo '"Ranklist.php?Page=' . ($MinPage) . '"' ?>>&laquo;</a></li>
                <li><a href=<?php echo '"Ranklist.php?Page=' . ($LastPage) . '"' ?>>&lt;</a></li>

                <?php
                for ($i = $StaButNum; $i <= $EndButNum; $i++) {
                    if ($i == $iPage)
                        echo '<li class="active"><a href="Ranklist.php?Page=' . $i . '">' . $i . '</a></li>';
                    else
                        echo '<li><a href="Ranklist.php?Page=' . $i . '">' . $i . '</a></li>';
                }
                ?>

                <li><a href=<?php echo '"Ranklist.php?Page=' . ($NextPage) . '"' ?>>&gt;</a></li>
                <li><a href=<?php echo '"Ranklist.php?Page=' . ($MaxPage) . '"' ?>>&raquo;</a></li>
            </ul>
        </center>

        <div class="panel panel-default animated fadeInDown">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>排名</th>
                        <th>用户名</th>
                        <th>个性签名</th>
                        <th>通过题数</th>
                        <th>提交总次数</th>
                        <th>战斗力</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    for ($i = 0; $i < MaxRankNum; $i++) {
                        if (!isset($AllUserData[$i]['Name'])) {
                            continue;
                        }
                        ?>
                        <tr>
                            <td> <?php echo ($iPage - 1) * MaxRankNum + $i + 1 ?> </td>
                            <td>
                                <a href=<?php echo '"/OtherUser.php?User=' . $AllUserData["$i"]['Name'] . '"' ?> class=<?php echo GetUserColor($AllUserData[$i]['Fight']) ?>>
                                    <?php echo $AllUserData[$i]['Name'] . ($AllUserData[$i]['Tails'] ? '(' . $AllUserData[$i]['Tails'] . ')' : '') ?> </a>
                            </td>
                            <td> <?php echo $AllUserData[$i]['Text'] ?> </td>
                            <td> <?php echo $AllUserData[$i]['q_PassNum'] ?> </td>
                            <td> <?php echo $AllUserData[$i]['q_AllNum'] ?> </td>
                            <td> <?php echo $AllUserData[$i]['Fight'] ?> </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>

        <center>
            <ul class="pagination">
                <li><a href=<?php echo '"Ranklist.php?Page=' . ($MinPage) . '"' ?>>&laquo;</a></li>
                <li><a href=<?php echo '"Ranklist.php?Page=' . ($LastPage) . '"' ?>>&lt;</a></li>

                <?php
                for ($i =  $StaButNum; $i <= $EndButNum; $i++) {
                    if ($i == $iPage)
                        echo '<li class="active"><a href="Ranklist.php?Page=' . $i . '">' . $i . '</a></li>';
                    else
                        echo '<li><a href="Ranklist.php?Page=' . $i . '">' . $i . '</a></li>';
                }
                ?>

                <li><a href=<?php echo '"Ranklist.php?Page=' . ($NextPage) . '"' ?>>&gt;</a></li>
                <li><a href=<?php echo '"Ranklist.php?Page=' . ($MaxPage) . '"' ?>>&raquo;</a></li>
            </ul>
        </center>

    </div>

    <?php
    $PageActive = '#ranklist';
    require_once('Php/Page_Footer.php');
    ?>

</body>

</html>