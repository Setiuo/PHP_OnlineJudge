<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<?php

if (!isset($LandUser)) {
    header('Location: /Message.php?Msg=您没有登陆，无权访问');
    die();
}
if (!can_edit_problem()) {
    header('Location: /Message.php?Msg=您不是管理员，无权访问');
    die();
}

$status = 0;
if (array_key_exists('Problem', $_GET)) {
    $status = 1;
    $problemID = intval($_GET['Problem']);
    $sql = "SELECT * FROM `oj_problem` WHERE `proNum`='" . $problemID . "' LIMIT 1";
    $result = oj_mysql_query($sql);

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
}

$sql = "SELECT max(proNum) AS value FROM `oj_problem` LIMIT 1";
$result = oj_mysql_query($sql);
$NewID = oj_mysql_fetch_array($result);
?>

<body>
    <?php require_once('Php/Page_Header.php') ?>

    <script src="/ckeditor/ckeditor.js"></script>

    <script type="text/javascript">
        window.onload = function() {
            CKEDITOR.replace('Description');
            CKEDITOR.replace('InputFormat');
            CKEDITOR.replace('OutputFormat');
            CKEDITOR.replace('Hint');
            CKEDITOR.replace('Source');
        };
    </script>

    <script>
        function submit_problemdata() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            $.post("/Php/SubmitNewPro.php", $('#newproblemform').serialize(), function(msg) {
                var obj = eval('(' + msg + ')');

                if (obj.status === 0) {
                    if (obj.type === 0) {
                        alert('添加题目成功！');
                        history.go(-1);
                    } else if (obj.type === 1) {
                        alert('修改题目成功！');
                        history.go(-1);
                    }
                    //location.reload();
                }
            });

            return false;
        }
    </script>

    <div class="container">
        <div class="panel panel-default">

            <div class="panel-heading">新建题目</div>

            <div class="panel-body animated fadeInLeft">
                <form onsubmit="return submit_problemdata()" id="newproblemform">
                    <div>
                        <!--style="display:none"-->
                        <input name="NewType" style="display:none" value=<?php echo $status; ?>>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">题目名称</span>
                                    <input name="ProName" type="text" class="form-control" value=<?php echo ($status == 1) ? '"' . $ProblemData['Name'] . '"' : '""'; ?>>

                                </div>
                                <br>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">题目编号</span>
                                    <?php
                                    if ($status == 1) {
                                        echo '<input name="ProNum" type="number" class="form-control" value="' . $ProblemData['proNum'] . '" readonly="readonly">';
                                    } else {
                                        echo '<input name="ProNum" type="number" class="form-control" value="' . ($NewID['value'] + 1) . '">';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">限制时间(ms)</span>
                                    <input name="LimitTime" type="number" class="form-control" placeholder="1000" value=<?php echo ($status == 1) ? $ProblemData['LimitTime'] : '1000'; ?>>

                                </div>
                                <br>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">限制内存(kb)</span>
                                    <input name="LimitMemory" type="number" class="form-control" placeholder="65536" value=<?php echo ($status == 1) ? $ProblemData['LimitMemory'] : '65536'; ?>>


                                </div>
                            </div>
                        </div>

                        <div>
                            <span class="input-group-addon">题目描述：</span>
                            <textarea name="Description" class="form-control" style="margin-top:8px;height:80px"><?php echo ($status == 1) ? $ProblemData['Description'] : ''; ?></textarea>
                        </div>
                        <br />

                        <div>
                            <span class="input-group-addon">输入格式：</span>
                            <textarea name="InputFormat" class="form-control" style="margin-top:8px;height:80px"><?php echo ($status == 1) ? $ProblemData['InputFormat'] : ''; ?></textarea>
                        </div>
                        <br />

                        <div>
                            <span class="input-group-addon">输出格式：</span>
                            <textarea name="OutputFormat" class="form-control" style="margin-top:8px;height:80px"><?php echo ($status == 1) ? $ProblemData['OutputFormat'] : ''; ?></textarea>
                        </div>
                        <br />

                        <div>
                            <span class="input-group-addon">样例输入：</span>
                            <textarea name="ExpInput" class="form-control" style="margin-top:8px;height:80px"><?php echo ($status == 1) ? $ProblemData['EmpInput'] : ''; ?></textarea>
                        </div>
                        <br />

                        <div>
                            <span class="input-group-addon">样例输出：</span>
                            <textarea name="ExpOutput" class="form-control" style="margin-top:8px;height:80px"><?php echo ($status == 1) ? $ProblemData['EmpOutput'] : ''; ?></textarea>
                        </div>
                        <br />

                        <div>
                            <span class="input-group-addon">提示</span>
                            <textarea name="Hint" class="form-control" style="margin-top:8px;height:80px"><?php echo ($status == 1) ? $ProblemData['Hint'] : ''; ?></textarea>
                        </div>
                        <br />

                        <div>
                            <span class="input-group-addon">来源：</span>
                            <textarea name="Source" class="form-control" style="margin-top:8px;height:80px"><?php echo ($status == 1) ? $ProblemData['Source'] : ''; ?></textarea>
                        </div>
                        <br />

                        <div class="input-group">
                            <span class="input-group-addon">测试点文件编号</span>
                            <input name="Test" type="text" class="form-control" placeholder="1&2&3&4" value=<?php echo ($status == 1) ? '"' . $ProblemData['Test'] . '"' : '""'; ?>>
                        </div>

                        <!--
                        <div class="input-group">
                            <span class="input-group-addon">选择测试点文件</span>
                            <input class="btn btn-default" type="hidden" name="MAX_FILE_SIZE" value="30000" />
                            <input class="btn btn-default" type="file" name="userfile" />
                        </div>
                        -->

                        <center><button id="post_submit" style="margin-top:10px; width:500px;" class="btn btn-default">提交</button></center>
                        <br />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    $PageActive = '#admin';
    require_once('Php/Page_Footer.php');
    ?>
</body>

</html>