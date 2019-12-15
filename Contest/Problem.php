<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once("Html_Head.php"); ?>

<?php
$NowProblem = 0;
if (array_key_exists('Problem', $_GET)) {
    $NowProblemEng = addslashes(trim($_GET['Problem']));
    $NowProblem = (ord($NowProblemEng) - ord('A') + 0);
}

$AllProblem = explode('|', $ConData['Problem']);
$ProNum = count($AllProblem);
if ($ProNum == 0 || !isset($AllProblem[$NowProblem])) {
    header('Location: /Message.php?Msg=题目信息获取失败');
    return;
}

$sql = "SELECT * FROM `oj_problem` WHERE `proNum`=" . $AllProblem[$NowProblem] . " LIMIT 1";
$result = oj_mysql_query($sql);

if (!$result) {
    header('Location: /Message.php?Msg=题目信息获取失败');
    return;
}
$ProblemData = oj_mysql_fetch_array($result);

if (!isset($ProblemData)) {
    header('Location: /Message.php?Msg=题目信息获取失败');
    return;
}

$NowDate = date('Y-m-d H:i:s');

if ($NowDate < $ConData['StartTime']) {
    if (!can_edit_contest($ConID)) {
        header('Location: /Message.php?Msg=比赛未开始');
        return;
    }
}

$sql = "SELECT count(distinct(`User`)) AS value FROM `oj_constatus` WHERE `Status` = " . Accepted . " AND `Show`=1 AND `Problem` = " . $NowProblem . " AND `ConID`=" . $ConID . " AND `SubTime`<'" . $FreezeTime . "'";
if (can_edit_contest($ConID)) {
    $sql = "SELECT count(distinct(`User`)) AS value FROM `oj_constatus` WHERE `Status` = " . Accepted . " AND `Problem` = " . $NowProblem . " AND `ConID`=" . $ConID . " AND `SubTime`<'" . $FreezeTime . "'";
}
$rs = oj_mysql_query($sql);
$PassNum = oj_mysql_fetch_array($rs);

$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `Show`=1 AND `Problem` = " . $NowProblem . " AND `ConID`=" . $ConID;
if (can_edit_contest($ConID)) {
    $sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `Problem` = " . $NowProblem . " AND `ConID`=" . $ConID;
}
$rs = oj_mysql_query($sql);
$SubNum = oj_mysql_fetch_array($rs);
?>

<body>
    <?php require_once("Header.php"); ?>

    <?php
    if (can_edit_problem()) {
        ?>
        <script>
            function changeStatus() {
                $.get(<?php echo '"/Php/ProblemStatus.php?Problem=' . $ProblemData['proNum'] . '"' ?>, function(msg) {
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
        <div class="panel panel-default">
            <div id="contesthead" class="panel-heading" style="padding:0 0 0 0;">
                <ul class="nav nav-tabs" role="tablist">
                    <?php
                    if (can_edit_contest($ConID)) {
                        ?>
                        <li role="presentation"><a class="label label-warning" href="javascript:show_all_problem()">显示题目</a></li>
                        <li role="presentation"><a class="label label-default" href="javascript:hide_all_problem()">隐藏题目</a></li>
                        <li role="presentation"><a class="label label-danger" href="javascript:rejudge_all_status()">重测代码</a></li>
                    <?php
                    }
                    ?>
                    <li>
                        <h4>&nbsp;</h4>
                    </li>
                </ul>
            </div>
            <div class="panel-body">

                <center>
                    <ul class="pagination">
                        <?php
                        for ($i = 0; $i < $ProNum; $i++) {
                            echo '<li id="p_' . $ProEngNum[$i] . '"><a href="/Contest/Problem.php?ConID=' . $ConID . '&Problem=' . $ProEngNum[$i] . '">' . $ProEngNum[$i] . '</a></li>';
                        }
                        ?>

                    </ul>
                </center>
                <div class="animated fadeInDown">
                    <link rel="stylesheet" href="/highlight/styles/default.css">
                    <script src="/highlight/highlight.pack.js"></script>
                    <script>
                        hljs.initHighlightingOnLoad("gcc", "g++", "C++", "Java", "Python");
                    </script>

                    <script src="/CodeMirror/codemirror.js"></script>
                    <link rel="stylesheet" href="/CodeMirror/codemirror.css">
                    <script src="/CodeMirror/clike.js"></script>
                    <script src="/CodeMirror/pascal.js"></script>
                    <script src="/CodeMirror/python.js"></script>
                    <script src="/CodeMirror/matchbrackets.js"></script>

                    <h1 class="text-center"><?php echo $ProblemData['Name'] ?>
                        <?php
                        if (can_edit_problem()) {
                            echo '<a class="label label-success" href="/ViewData.php?Problem=' . $ProblemData['proNum'] . '">数据</a> ';
                            echo '<a class="label label-warning" href="/NewProblem.php?Problem=' . $ProblemData['proNum'] . '">编辑</a> ';

                            if ($ProblemData['Show'] == 1) {
                                echo '<a href="javascript:changeStatus()" class="label label-primary">隐藏</a>';
                            } else {
                                echo '<a href="javascript:changeStatus()" class="label label-info">显示</a>';
                            }
                        } else if (can_read_test() && $ProblemData['Show'] == 1) {
                            echo '<a class="label label-success" href="/ViewData.php?Problem=' . $ProblemData['proNum'] . '">查看数据</a> ';
                        }
                        ?>
                    </h1>
                    <table class="autotable" align="center">
                        <tr>
                            <td><b>时间限制：</b><?php echo $ProblemData['LimitTime'] ?>ms</td>
                            <td><b>内存限制：</b><?php echo $ProblemData['LimitMemory'] ?>KB</td>
                        </tr>

                        <?php
                        if ($ConData['Rule'] == 'ACM' || can_edit_contest($ConID) || $NowDate >= $ConData['OverTime']) {
                            echo '<tr>';
                            echo '<td><b>提交总数：</b>' . $SubNum['value'] . '</td>';
                            echo '<td><b>通过人数：</b>' . $PassNum['value'] . '</td>';
                            echo '</tr>';
                        }
                        ?>

                    </table>
                    <br>

                    <center>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" id="btnShowSubmit" data-backdrop="static" data-toggle="modal" data-target="#submitcode">提交代码</button>
                            <a type="button" class="btn btn-default" href=<?php echo '"/Contest/Status.php?ConID=' . $ConID . '&Problem=' . $ProEngNum[$NowProblem] . '"'; ?>>查看记录</a>
                        </div>
                    </center>
                    <h3>题目描述</h3>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php echo $ProblemData['Description'] ?>
                        </div>
                    </div>
                    <h3>输入格式</h3>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php echo $ProblemData['InputFormat'] ?>
                        </div>
                    </div>
                    <h3>输出格式</h3>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php echo $ProblemData['OutputFormat'] ?>
                        </div>
                    </div>
                    <h3>样例输入</h3>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <pre class="SlateFix"><?php echo $ProblemData['EmpInput'] ?></pre>
                        </div>
                    </div>
                    <h3>样例输出</h3>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <pre class="SlateFix"><?php echo $ProblemData['EmpOutput'] ?></pre>
                        </div>
                    </div>

                    <?php if ($ProblemData['Hint'] != '') { ?>
                        <h3>提示</h3>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php echo $ProblemData['Hint'] ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($ProblemData['Source'] != '') { ?>
                        <h3>来源</h3>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php echo $ProblemData['Source'] ?>
                            </div>
                        </div>
                    <?php } ?>


                    <center>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" id="btnShowSubmit" data-backdrop="static" data-toggle="modal" data-target="#submitcode">提交代码</button>
                            <a type="button" class="btn btn-default" href=<?php echo '"/Contest/Status.php?ConID=' . $ConID . '&Problem=' . $ProEngNum[$NowProblem] . '"'; ?>>查看记录</a>
                        </div>
                    </center>
                </div>

                <div class="modal fade" id="submitcode" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="codeform" onsubmit="return(pstsubmit_contest());">
                                <div class="modal-body" id="codemodalbody">
                                    <textarea hidden name="code" id="codeeditor"></textarea>
                                    <textarea hidden name="ConID" id="ConID"><?php echo $ConID ?></textarea>
                                    <textarea hidden name="NowPro" id="NowPro"><?php echo $NowProblem ?></textarea>
                                </div>

                                <div class="modal-footer">
                                    <div class="float-left">
                                        语言：
                                        <select name="language" id="language" style="height:32px;width:120px;">

                                            <option value="Gcc">C</option>
                                            <option value="C++">C++</option>
                                            <option value="Java">Java</option>
                                            <option value="Python">Python3.6</option>
                                        </select>
                                        <button id="SubmitCodeButton" type="submit" class="btn btn-primary">提交代码</button>
                                    </div>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/MathJax-master/MathJax.js?config=TeX-AMS_HTML-full"></script>

    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({
          tex2jax: {
            inlineMath: [ ['$','$'], ["\\(","\\)"] ],
            processEscapes: true  
          },
          TeX: {
            equationNumbers: { autoNumber: "AMS" },
            Macros: {
          du: '^\\circ',
              vv: '\\overrightarrow',
              bm: '\\boldsymbol',
            }
          },
          "HTML-CSS": {
            linebreaks: {automatic: true},
            showMathMenu: false
          },
            menuSettings: {
              zoom: "Double-Click"
          }
        });
      </script>

    <?php
    $PageActive = "#c_problem,#p_" . $ProEngNum[$NowProblem] . "";
    require_once('Footer.php');
    ?>
</body>

</html>