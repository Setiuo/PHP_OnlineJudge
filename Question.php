<!DOCTYPE html>

<html lang="zh-cn">
<?php require_once('Php/HTML_Head.php') ?>

<?php
if (array_key_exists('Problem', $_GET)) {
    $ProblemID = intval($_GET['Problem']);
    $sql = "SELECT `Show` FROM `oj_problem` WHERE `proNum`=" . $ProblemID . " LIMIT 1";
    $result = oj_mysql_query($sql);
    $ShowRow = oj_mysql_fetch_array($result);

    if (!isset($ShowRow['Show'])) {
        header('Location: /Message.php?Msg=未找到题目');
        die();
    }
    if ($ShowRow['Show'] == 0 && !can_edit_problem()) {
        header('Location: /Message.php?Msg=题目已被隐藏');
        die();
    }
} else {
    header('Location: /Message.php?Msg=未知题号');
    die();
}

$sql = "SELECT * FROM `oj_problem` WHERE `proNum`='" . $ProblemID . "' LIMIT 1";
$result = oj_mysql_query($sql);
$ProblemData;

if ($result) {
    $ProblemData = oj_mysql_fetch_array($result);

    if (!$ProblemData) {
        header('Location: /Message.php?Msg=未知题号');
        die();
    }
} else {
    header('Location: /Message.php?Msg=查找失败');
    die();
}

$sql = "SELECT count(*) AS value FROM `oj_status` WHERE `Status` = " . Accepted . " AND `Show`=1 AND `Problem` = " . $ProblemID;
if (can_edit_problem()) {
    $sql = "SELECT count(*) AS value FROM `oj_status` WHERE `Status` = " . Accepted . " AND Problem = " . $ProblemID;
}
$rs = oj_mysql_query($sql);
$PassNum = oj_mysql_fetch_array($rs);
$PassNum = $PassNum['value'];

$sql = "SELECT count(*) AS value FROM `oj_status` WHERE `Show`=1 AND `Problem` = " . $ProblemID;
if (can_edit_problem()) {
    $sql = "SELECT count(*) AS value FROM `oj_status` WHERE `Problem` = " . $ProblemID;
}
$rs = oj_mysql_query($sql);
$SubNum = oj_mysql_fetch_array($rs);
$SubmitNum = $SubNum['value'];
?>

<body>
    <?php require_once('Php/Page_Header.php') ?>

    <div class="container">
        <script src="/CodeMirror/codemirror.js"></script>
        <link rel="stylesheet" href="/CodeMirror/codemirror.css">
        <script src="/CodeMirror/clike.js"></script>
        <script src="/CodeMirror/pascal.js"></script>
        <script src="/CodeMirror/python.js"></script>
        <script src="/CodeMirror/matchbrackets.js"></script>

        <link rel="stylesheet" href="/highlight/styles/default.css">
        <script src="/highlight/highlight.pack.js"></script>
        <script>
            hljs.initHighlightingOnLoad("C", "C++", "Java", "Python");
        </script>

        <?php
        if (can_edit_problem()) {
        ?>
            <script>
                function changeStatus() {
                    $.get(<?php echo '"/Php/ProblemStatus.php?Problem=' . $ProblemID . '"' ?>, function(msg) {
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

        <div class="animated fadeInDown">
            <h1 class="text-center"><?php echo $ProblemData['Name'] ?>
                <?php
                if (can_read_test()) {
                    echo '<a class="label label-success" href="/ViewData.php?Problem=' . $ProblemID . '">数据</a> ';
                }
                if (can_edit_problem()) {
                    echo '<a class="label label-warning" href="/NewProblem.php?Problem=' . $ProblemID . '">编辑</a> ';
                }
                if (can_edit_problem()) {
                    if ($ShowRow['Show'] == 1) {
                        echo '<a href="javascript:changeStatus()" class="label label-primary">隐藏</a>';
                    } else {
                        echo '<a href="javascript:changeStatus()" class="label label-info">显示</a>';
                    }
                }

                ?>
            </h1>

            <table class="autotable" align="center">
                <tr>
                    <td><b>时间限制：</b><?php echo $ProblemData['LimitTime'] ?>ms</td>
                    <td><b>内存限制：</b><?php echo $ProblemData['LimitMemory'] ?>kb</td>
                </tr>

                <tr>
                    <td><b>提交总数：</b><?php echo $SubmitNum ?></td>
                    <td><b>通过数量：</b><?php echo $PassNum ?></td>
                </tr>

            </table>
            <br>

            <center>
                <div class="btn-group">
                    <button type="button" class="btn btn-default" id="btnShowSubmit" data-backdrop="static" data-toggle="modal" data-target="#submitcode">提交代码</button>
                    <a type="button" class="btn btn-default" href=<?php echo '"/Status.php?Problem=' . $ProblemID . '"' ?>>查看记录</a>
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
                    <a type="button" class="btn btn-default" href=<?php echo '"/Status.php?Problem=' . $ProblemID . '"' ?>>查看记录</a>
                </div>
            </center>
        </div>

        <div class="modal fade" id="submitcode" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="codeform" onsubmit="return(pstsubmit());">
                        <div class="modal-body" id="codemodalbody">
                            <textarea hidden name="code" id="codeeditor"></textarea>
                            <textarea hidden name="pid" id="pid"><?php echo $ProblemID ?></textarea>
                        </div>

                        <div class="modal-footer">
                            <div class="float-left">
                                语言：
                                <select name="language" id="language" style="height:32px;width:120px;">
                                    <option value="C">C</option>
                                    <option value="C++">C++</option>
                                    <option value="Java">Java</option>
                                    <option value="Python3.7">Python3.7</option>
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
    $PageActive = '#problem';
    require_once('Php/Page_Footer.php');
    ?>
</body>

</html>