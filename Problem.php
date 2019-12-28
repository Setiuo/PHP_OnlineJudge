<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<?php
//定义常量，一页中最大显示数量
define("MaxRankNum", 20);
//定义常量，一页中最多显示按钮数量(奇数)
define("MaxButtonNum", 5);

//获取当前页数
$iPage = 1;
if (array_key_exists('Page', $_GET)) {
    $iPage = intval($_GET['Page']);
}
$iPage = floor($iPage);

$AddSql = '';
if (isset($_GET['Search'])) {
    $SearchText = htmlspecialchars(addslashes(trim($_GET['Search'])));

    if (is_numeric($SearchText)) {
        $iMaybeProblemID = intval($SearchText);
        $sql = "SELECT `proNum`  FROM `oj_problem` WHERE `proNum`=" . $iMaybeProblemID . " LIMIT 1";
        $result = oj_mysql_query($sql);
        $data = oj_mysql_fetch_array($result);
        if (isset($data['proNum'])) {
            header('Location: /Question.php?Problem=' . $iMaybeProblemID);
            die();
        }
    }

    if (can_edit_problem()) {
        $AddSql .= " WHERE `Name` like '%" . $SearchText . "%'";
    } else {
        $AddSql .= " AND `Name` like '%" . $SearchText . "%'";
    }
}

//获取题目数量
$sql = "SELECT count(*) as `value` FROM `oj_problem` WHERE `Show`=1" . $AddSql;
if (can_edit_problem()) {
    $sql = "SELECT count(*) as `value` FROM `oj_problem`" . $AddSql;
}
$rs = oj_mysql_query($sql);
$ProCount = oj_mysql_fetch_array($rs);
$clength = $ProCount['value'];

//计算总页数
$AllPage = $clength / MaxRankNum;

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
$LimitShowSql = " ORDER BY `proNum` DESC LIMIT " . ($iPage - 1) * MaxRankNum . ", " . MaxRankNum;

$sql = "SELECT `Name`,`proNum`,`CreateTime`,`Show` FROM `oj_problem` WHERE `Show`=1";
if (can_edit_problem()) {
    $sql = "SELECT `Name`,`proNum`,`CreateTime`,`Show` FROM `oj_problem`";
}

if (isset($SearchText)) {
    if (can_edit_problem()) {
        $sql .= " WHERE `Name` like '%" . $SearchText . "%'";
    } else {
        $sql .= " AND `Name` like '%" . $SearchText . "%'";
    }
}

$sql .= $LimitShowSql;

$result = oj_mysql_query($sql);

$AllProblem = array();
while ($result && $row = oj_mysql_fetch_array($result)) {
    $AllProblem[] = array(
        "Name" => $row['Name'],
        "proNum" => $row['proNum'],
        "CreateTime" => $row['CreateTime'],
        "Show" => $row['Show']
    );
}

//$arr1 = array_map(create_function('$n', 'return $n["proNum"];'), $AllProblem);
//array_multisort($arr1, SORT_DESC, $AllProblem);
?>

<body>
    <?php require_once('Php/Page_Header.php') ?>

    <?php
    if (can_edit_problem()) {
        ?>
        <script>
            function changeStatus(problemID) {
                $.get("/Php/ProblemStatus.php?Problem=" + problemID, function(msg) {
                    var obj = eval('(' + msg + ')');
                    if (obj.status === 0) {
                        location.reload();
                    }
                });
            }
        </script>
    <?php
    }
    ?>

    <div class="container">

        <ul class="pagination">
            <li><a href=<?php echo '"Problem.php?Page=' . ($MinPage) . '"' ?>>&laquo;</a></li>
            <li><a href=<?php echo '"Problem.php?Page=' . ($LastPage) . '"' ?>>&lt;</a></li>

            <?php
            for ($i =  $StaButNum; $i <= $EndButNum; $i++) {
                if ($i == $iPage)
                    echo '<li class="active"><a href="Problem.php?Page=' . $i . '">' . $i . '</a></li>';
                else
                    echo '<li><a href="Problem.php?Page=' . $i . '">' . $i . '</a></li>';
            }
            ?>

            <li><a href=<?php echo '"Problem.php?Page=' . ($NextPage) . '"' ?>>&gt;</a></li>
            <li><a href=<?php echo '"Problem.php?Page=' . ($MaxPage) . '"' ?>>&raquo;</a></li>
        </ul>

        <table class="float-right" style="margin:20px 0 20px 0;width:300px">
            <tr>
                <?php
                if (can_edit_problem()) {
                    echo '<td><a class="btn btn-default" href="/NewProblem.php">新建</a><td>';
                }
                ?>
                <td>
                    <div class="input-group">
                        <input id="discuzproblem" data-enter="#goSeachProblem" type="text" class="form-control" placeholder="请输入题号或关键字" value=<?php echo (isset($SearchText) ? '"' .  $SearchText . '"' : '""') ?>>
                        <span class="input-group-btn">
                            <button id="goSeachProblem" class="btn btn-default" type="button">查找</button>
                        </span>
                    </div>
                </td>
            </tr>
        </table>
        <script>
            $("#goSeachProblem").click(function() {
                location.href = "/Problem.php?Search=" + $("#discuzproblem").val();
            });
        </script>
        <div class="panel panel-default animated fadeInLeft">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>状态</th>
                        <th>题号</th>
                        <?php
                        if (can_read_test() || can_edit_problem()) {
                            echo '<th>功能</th>';
                        }
                        ?>
                        <th>题目名称</th>
                        <th>通过率(通过/提交)</th>
                        <th>创建日期</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    for ($i = 0; $i < MaxRankNum; $i++) {
                        if (!isset($AllProblem[$i]['proNum'])) {
                            continue;
                        }

                        $HadPass = 0;

                        if (isset($LandUser)) {
                            $sql = "SELECT `RunID` FROM `oj_status` WHERE `User`='" . $LandUser . "' AND `Status` = " . Accepted . " AND `Show`=1 AND `Problem` = '" . $AllProblem[$i]['proNum'] . "' LIMIT 1";
                            if (can_edit_problem()) {
                                $sql = "SELECT `RunID` FROM `oj_status` WHERE `User`='" . $LandUser . "' AND `Status` = " . Accepted . " AND `Problem` = '" . $AllProblem[$i]['proNum'] . "' LIMIT 1";
                            }
                            $rs = oj_mysql_query($sql);
                            $ans = mysqli_num_rows($rs);

                            if ($ans) {
                                $HadPass = 1;
                            } else {
                                $sql = "SELECT `RunID` FROM `oj_status` WHERE `User`='" . $LandUser . "' AND `Status` != " . Accepted . " AND `Show`=1 AND `Problem` =" . $AllProblem[$i]['proNum'] . " LIMIT 1";
                                if (can_edit_problem()) {
                                    $sql = "SELECT `RunID` FROM `oj_status` WHERE `User`='" . $LandUser . "' AND `Status` != " . Accepted . " AND `Problem` =" . $AllProblem[$i]['proNum'] . " LIMIT 1";
                                }
                                $rs = oj_mysql_query($sql);
                                $ans2 = mysqli_num_rows($rs);

                                if ($ans2) {
                                    $HadPass = 2;
                                }
                            }
                        }

                        echo '<tr><td>';
                        if ($HadPass == 1) {
                            echo '<span class="glyphicon glyphicon-ok"></span> 已通过';
                        } else if ($HadPass == 0) {
                            echo '<span class="glyphicon glyphicon-minus"></span> 未尝试';
                        } else {
                            echo '<span class="glyphicon glyphicon-remove"></span> 未通过';
                        }

                        echo '</td>';

                        echo '<td><a href="/Question.php?Problem=' . $AllProblem[$i]['proNum'] . '">' . $AllProblem[$i]['proNum'] . '</a></td>';

                        if (can_edit_problem() || can_read_test()) {
                            echo '<td>';
                        }

                        if (can_edit_problem()) {
                            echo ' <a class="label label-warning" href="/NewProblem.php?Problem=' . $AllProblem[$i]['proNum'] . '">编辑</a>';
                        }
                        if (can_read_test()) {
                            echo ' <a class="label label-success" href="/ViewData.php?Problem=' . $AllProblem[$i]['proNum'] . '">数据</a>';
                        }
                        if (can_edit_problem()) {
                            if ($AllProblem[$i]['Show'] == 1) {
                                echo ' <a href="javascript:changeStatus(' . $AllProblem[$i]['proNum'] . ')" class="label label-primary">隐藏</a>';
                            } else {
                                echo ' <a href="javascript:changeStatus(' . $AllProblem[$i]['proNum'] . ')" class="label label-info">显示</a>';
                            }
                        }
                        if (can_edit_problem() || can_read_test()) {
                            echo '</td>';
                        }

                        echo '<td><a href="/Question.php?Problem=' . $AllProblem[$i]['proNum'] . '">' . $AllProblem[$i]['Name'] . '</a><span class="float-right"></span></td>';

                        $PassRate = 0;

                        $sql = "SELECT count(*) AS value FROM `oj_status` WHERE `Status` = " . Accepted . " AND `Show`=1 AND `Problem` = " . $AllProblem[$i]['proNum'];
                        if (can_edit_problem()) {
                            $sql = "SELECT count(*) AS value FROM `oj_status` WHERE `Status` = " . Accepted . " AND `Problem` = " . $AllProblem[$i]['proNum'];
                        }
                        $rs = oj_mysql_query($sql);
                        $PassNum = oj_mysql_fetch_array($rs);

                        $sql = "SELECT count(*) AS `value` FROM `oj_status` WHERE `Show`=1 AND `Problem` = " . $AllProblem[$i]['proNum'];
                        if (can_edit_problem()) {
                            $sql = "SELECT count(*) AS `value` FROM `oj_status` WHERE `Problem` = " . $AllProblem[$i]['proNum'];
                        }
                        $rs = oj_mysql_query($sql);
                        $AllSubNum = oj_mysql_fetch_array($rs);

                        if ($AllSubNum['value'] == 0) {
                            echo '<td>0.00%</td>';
                        } else {
                            $PassRate = ($PassNum['value'] / ($AllSubNum['value'])) * 100;
                            $PassRate = number_format($PassRate, 2);
                            echo '<td>' . $PassRate . '%</td>';
                        }


                        echo '<td>' . $AllProblem[$i]['CreateTime'] . '</td>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <center>
            <ul class="pagination">
                <li><a href=<?php echo '"Problem.php?Page=' . ($MinPage) . '"' ?>>&laquo;</a></li>
                <li><a href=<?php echo '"Problem.php?Page=' . ($LastPage) . '"' ?>>&lt;</a></li>

                <?php
                for ($i =  $StaButNum; $i <= $EndButNum; $i++) {
                    if ($i == $iPage)
                        echo '<li class="active"><a href="Problem.php?Page=' . $i . '">' . $i . '</a></li>';
                    else
                        echo '<li><a href="Problem.php?Page=' . $i . '">' . $i . '</a></li>';
                }
                ?>

                <li><a href=<?php echo '"Problem.php?Page=' . ($NextPage) . '"' ?>>&gt;</a></li>
                <li><a href=<?php echo '"Problem.php?Page=' . ($MaxPage) . '"' ?>>&raquo;</a></li>
            </ul>
        </center>
    </div>

    <?php
    $PageActive = '#problem';
    require_once('Php/Page_Footer.php');
    ?>

</body>

</html>