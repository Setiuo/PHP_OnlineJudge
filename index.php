<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<?php
$NowDate = date('Y-m-d H:i:s');

//比赛显示优先级
const ConDoing = 1;        //进行中
const ConEnrolling = 2;    //报名中
const ConnoStart = 3;        //未开始
const ConOver = 4;            //已结束
$ModeStr = array('', '正在进行中', '报名中', '未开始', '已结束');
$ModeCss = array('', 'label-success', 'label-success', 'label-primary', 'label-default');

$sql = "SELECT * FROM `oj_contest` WHERE `Show`=1 ORDER BY `OverTime` DESC,`ConID` DESC LIMIT 0, 20";
if (can_edit_contest()) {
    $sql = "SELECT * FROM `oj_contest` ORDER BY `OverTime` DESC,`ConID` DESC LIMIT 0, 20";
}
$result = oj_mysql_query($sql);

$AllContest = array();
while ($row = oj_mysql_fetch_array($result)) {
    $iMode = ConOver;
    if ($NowDate <= $row['StartTime']) {
        if ($NowDate >= $row['EnrollStartTime'] && $NowDate <= $row['EnrollOverTime'])
            $iMode = ConEnrolling;
        else
            $iMode = ConnoStart;
    } else if ($NowDate <= $row['OverTime']) {
        $iMode = ConDoing;
    } else {
        $iMode = ConOver;
    }

    $AllContest[] = array(
        "ConID" => $row['ConID'],
        "Title" => $row['Title'],
        "Organizer" => $row['Organizer'],
        "Rule" => $row['Rule'],
        "Type" => $row['Type'],
        "Show" => $row['Show'],
        "StartTime" => $row['StartTime'],
        "OverTime" => $row['OverTime'],
        "Mode" => $iMode
    );
}


function my_sort($a, $b)
{
    if ($a['Mode'] == $b['Mode']) {
        return $a['ConID'] < $b['ConID'] ? 1 : -1;
    }

    return $a['Mode'] < $b['Mode'] ? -1 : 1;
}

usort($AllContest, "my_sort");
?>

<body>
    <?php require_once('Php/Page_Header.php') ?>

    <?php
    if (can_edit_contest()) {
    ?>
        <script>
            function changeContestStatus(contestID) {
                $.get(<?php echo '"/Contest/ContestStatus.php?ConID="' ?> + contestID, function(msg) {
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

    <div class="container animated fadeInLeft">
        <div class="jumbotron">
            <h1><?php echo $WebName ?></h1>
            <p><?php echo $WebTitle ?></p>
            <p><a href="/problem.php" class="btn btn-primary btn-lg" role="button">我要刷题！</a></p>
        </div>

        <h2>近期比赛</h2>

        <div class="panel panel-default">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>比赛ID</th>
                        <?php
                        if (can_edit_contest()) {
                            echo '<th>功能</th>';
                        }
                        ?>
                        <th>标题</th>
                        <th>比赛状态</th>
                        <th>开始时间</th>
                        <th>竞赛时长</th>
                        <th>比赛规则</th>
                        <th>类型</th>
                        <th>举办人</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i <=  4; $i++) {
                        if (!isset($AllContest[$i]['ConID'])) {
                            continue;
                        }

                        echo '<tr>';

                        echo '<td>';
                        echo '<a href="Contest/Pandect.php?ConID=' . $AllContest[$i]['ConID'] . '">' . $AllContest[$i]['ConID'] . '</a>';
                        echo '</td>';

                        if (can_edit_contest()) {
                            echo '<td>';
                            echo ' <a class="label label-warning" href="/NewContest.php?ConID=' . $AllContest[$i]['ConID'] . '">编辑</a>';

                            if ($AllContest[$i]['Show'] == 1) {
                                echo ' <a href="javascript:changeContestStatus(' . $AllContest[$i]['ConID'] . ')" class="label label-primary">隐藏</a>';
                            } else {
                                echo ' <a href="javascript:changeContestStatus(' . $AllContest[$i]['ConID'] . ')" class="label label-info">显示</a>';
                            }

                            echo '</td>';
                        }

                        echo '<td>';
                        echo '<a href="Contest/Pandect.php?ConID=' . $AllContest[$i]['ConID'] . '">' . $AllContest[$i]['Title'] . '</a>';
                        echo '</td>';

                        echo '<td>';
                        echo '<span class="label ' . $ModeCss[$AllContest[$i]['Mode']] . '">' . $ModeStr[$AllContest[$i]['Mode']] . '</span>';
                        echo '</td>';

                        echo '<td>' . $AllContest[$i]['StartTime'] . '</td>';

                        $Startdate = strtotime($AllContest[$i]['StartTime']);
                        $Enddate   = strtotime($AllContest[$i]['OverTime']);

                        $Timediff = $Enddate - $Startdate;
                        $Days =     intval($Timediff / 86400);
                        $Remain =   $Timediff % 86400;
                        $Hours =    intval($Remain / 3600);
                        $Remain =   $Remain % 3600;
                        $Mins =     intval($Remain / 60);
                        $Secs =     $Remain % 60;

                        echo '<td>';
                        if ($Days > 0) {
                            echo $Days . ($Days == 1 ? ' day ' : ' days ');
                        }
                        echo $Hours . ':' . $Mins . ':' . $Secs;

                        echo '<td>' . $AllContest[$i]['Rule'] . '</td>';

                        if ($AllContest[$i]['Type'] == 1) {
                            echo '<td><font color="red">Private</font></td>';
                        } else {
                            echo '<td><font color="green">Public</font></td>';
                        }

                        $TF = get_user_tailsAndFight($AllContest[$i]['Organizer']);

                        echo '<td><a href="/OtherUser.php?User=' . $AllContest[$i]['Organizer'] . '" class=' . GetUserColor($TF['fight']) . '>' . $AllContest[$i]['Organizer'] . ($TF['tails'] ? '(' . $TF['tails'] . ')' : '') . '</a></td>';

                        echo '</tr>';
                    }

                    ?>
                </tbody>
            </table>
        </div>

        <h2>注意事项</h2>

        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <span class="label label-danger">置顶</span>
                        <a style="color:red" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">欢迎在OJ中寻找BUG</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        发现BUG请反馈给我<br>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <span class="label label-warning">注</span>
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                            Java代码注意事项
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="panel-body">
                        Java代码的类名必须为Main<br>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                            欢迎来到OnlineJudge评测平台
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse">
                    <div class="panel-body">
                        这是一个运行在Windows平台上的代码评测系统<br>
                        作者耗费1个月时间编写整个系统框架，欢迎反馈BUG<br>
                        这是一个练手的项目，也是作者开发的第一个OJ项目。网站整体前端界面都是照搬qdacm的，网站的各种功能和数据处理、评测机是自己实现的<br>
                        在功能实现上没有考虑很多，很多功能执行效率都不高，实现方法也很临时工，安全性也很低。<br>
                        如果可能的话，作者会重写一个新的OJ系统，在安全性、兼容性和效率上会着重思考。
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                            更新日志
                        </a>
                    </h4>
                </div>
                <div id="collapseFour" class="panel-collapse collapse">
                    <div class="panel-body">
                        2020/2/9 增加了高级管理员隐藏代码功能，完善了管理员面板<br>
                        2020/2/5 增加了比赛中的战斗力结算功能<br>
                        2019/12/19 重写了评测机，支持远程Judger评测，可部署多个评测机共同评测<br>
                        2019/12/19 增加编译警告显示功能<br>
                        2019/12/07 增加了ACM赛制的封榜功能<br>
                        2019/12/06 增加了账号注册和登陆的验证码验证功能<br>
                        2019/12/04 增加了用户小尾巴显示<br>
                        2019/12/03 增加了对PHP7.2.10的支持<br>
                        2019/12/03 完善了用户权限分配，比赛举办者拥有对该比赛的操作权限<br>
                        2019/11/30 增加了Moss检测模式的代码相似度检测功能<br>
                        2019/11/28 增加了字符串匹配模式的代码相似度检测功能<br>
                        2019/11/23 增加了ACM赛制的练习模式，排行榜显示更友好<br>
                        2019/11/22 完善了题库搜索功能，输入题号直接跳转<br>
                        2019/11/22 增加了比赛代码快速重测功能<br>
                        2019/11/22 修复了一个用户登陆BUG<br>
                        2019/11/12 增加了比赛快速隐藏/显示题库题目功能<br>
                        2019/8/16 评测机增加了对一些函数的屏蔽，增加了评测机的安全性<br>
                        2019/8/15 重写了评测机[单线程评测]，增加了Special Judge功能<br>
                        2019/7/15 完善了评测状态自动刷新功能<br>
                        2019/7/15 更新了评测机评测算法，支持ACM赛制遇错停止评测的功能<br>
                        2019/7/14 增加了对Python语言的语法检查功能<br>
                        2019/7/14 完善了评测机的安全性，增加敏感字检测功能<br>
                        2019/7/14 更新了评测机评测算法，支持多线程评测，提高效率<br>
                        2019/4/29 修复了比赛界面点击姓名链接无法查看信息的BUG<br>
                        2019/4/21 更新题目和比赛页面，如果没有来源、提示或比赛描述，则不显示空内容<br>
                        2019/4/21 修改编辑题目和比赛页面的普通编辑框为富文本编辑框<br>
                        2019/4/20 修复了选择框默认内容显示错误的BUG<br>
                        2019/4/19 更新了分页算法，提高了数据库的读取效率，减轻服务器负担<br>
                        2019/4/17 修复了评测机监控内存大小异常的BUG<br>
                        2019/4/16 增加了页面的动态显示效果<br>
                        2019/4/14 增加了管理员评测日志查看功能<br>
                        2019/4/18 修复了评测记录中评测机名称显示错误的BUG<br>
                        2019/3/30 更改了连接数据库的方式，提高页面显示效率<br>
                        2019/3/27 修复了评测机在TLE时判WrongAnswer的BUG<br>
                        2019/3/25 增加了题库和比赛的关键字搜索功能<br>
                        2019/3/20 增加了OJ对OI赛制的支持<br>
                        2019/3/17 增加了评测机对Java和Python3.6语言的评测支持<br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    $PageActive = '';
    require_once('Php/Page_Footer.php');
    ?>

</body>

</html>