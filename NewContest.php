<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<?php

if (!isset($LandUser)) {
    header('Location: /Message.php?Msg=您没有登陆，无权访问');
    die();
}

$status = 0;
if (array_key_exists('ConID', $_GET)) {
    $status = 1;
    $conID = intval($_GET['ConID']);
}

if (!can_edit_contest()) {
    header('Location: /Message.php?Msg=您不是管理员，无权访问');
    die();
}

if ($status == 1) {
    $sql = "SELECT * FROM `oj_contest` WHERE `ConID`='" . $conID . "' LIMIT 1";
    $result = oj_mysql_query($sql);

    if ($result) {
        $ContestData = oj_mysql_fetch_array($result);

        if (!$ContestData) {
            header('Location: /Message.php?Msg=未知比赛ID');
            die();
        }
    } else {
        header('Location: /Message.php?Msg=比赛查找失败');
        die();
    }
}

$sql = "SELECT max(ConID) AS value FROM `oj_contest` LIMIT 1";
$result = oj_mysql_query($sql);
$NewID = oj_mysql_fetch_array($result);
?>

<body>
    <?php require_once('Php/Page_Header.php') ?>

    <script src="/ckeditor/ckeditor.js"></script>

    <script type="text/javascript">
        window.onload = function() {
            CKEDITOR.replace('Synopsis');
        };
    </script>

    <script>
        function submit_contestdata() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            $.post("/Php/SubmitNewCon.php", $('#newproblemform').serialize(), function(msg) {
                var obj = eval('(' + msg + ')');

                if (obj.status === 0) {
                    if (obj.type == 0) {
                        alert('添加比赛成功！');
                        history.go(-1);
                    } else if (obj.type == 1) {
                        alert('修改比赛成功！');
                        history.go(-1);
                    }
                    //location.reload();
                } else if (obj.status === 2) {
                    alert('题号输入错误，请检查题库中是否存在所对应的题目！');
                } else {
                    alert('提交时发生错误');
                }
            });

            return false;
        }
    </script>

    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">新建比赛</div>
            <div class="panel-body animated fadeInLeft">
                <form id="newproblemform" onsubmit="return submit_contestdata()">
                    <div>

                        <input name="NewType" style="display:none" value=<?php echo $status; ?>>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">比赛标题</span>
                                    <input name="Title" type="text" class="form-control" value=<?php echo ($status == 1) ? '"' . $ContestData['Title'] . '"' : '""'; ?>>
                                </div>
                                <br>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">比赛ID</span>
                                    <?php
                                    if ($status == 1) {
                                        echo '<input name="ConID" type="number" class="form-control" value="' . $ContestData['ConID'] . '" readonly="readonly">';
                                    } else {
                                        echo '<input name="ConID" type="number" class="form-control" value="' . ($NewID['value'] + 1) . '">';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">比赛开始时间</span>
                                    <?php
                                    if ($status == 1) {
                                        $StartTime = $ContestData['StartTime'];
                                        $Time_1 = substr($StartTime, 0, 10);
                                        $Time_2 = substr($StartTime, 11, 5);
                                        $StartTime = $Time_1 . 'T' . $Time_2;
                                        echo '<input name="StartTime" type="datetime-local" class="form-control" value="' . $StartTime . '">';
                                    } else {
                                        echo '<input name="StartTime" type="datetime-local" class="form-control">';
                                    }
                                    ?>
                                </div>
                                <br>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">比赛结束时间</span>
                                    <?php
                                    if ($status == 1) {
                                        $OverTime = $ContestData['OverTime'];
                                        $Time_1 = substr($OverTime, 0, 10);
                                        $Time_2 = substr($OverTime, 11, 5);
                                        $OverTime = $Time_1 . 'T' . $Time_2;
                                        echo '<input name="OverTime" type="datetime-local" class="form-control" value="' . $OverTime . '">';
                                    } else {
                                        echo '<input name="OverTime" type="datetime-local" class="form-control" value="">';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">封榜开始时间</span>
                                    <?php
                                    if ($status == 1) {
                                        $StartTime = $ContestData['FreezeTime'];
                                        $Time_1 = substr($StartTime, 0, 10);
                                        $Time_2 = substr($StartTime, 11, 5);
                                        $StartTime = $Time_1 . 'T' . $Time_2;
                                        echo '<input name="FreezeTime" type="datetime-local" class="form-control" value="' . $StartTime . '">';
                                    } else {
                                        echo '<input name="FreezeTime" type="datetime-local" class="form-control">';
                                    }
                                    ?>
                                </div>
                                <br>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">封榜结束时间</span>
                                    <?php
                                    if ($status == 1) {
                                        $OverTime = $ContestData['UnfreezeTime'];
                                        $Time_1 = substr($OverTime, 0, 10);
                                        $Time_2 = substr($OverTime, 11, 5);
                                        $OverTime = $Time_1 . 'T' . $Time_2;
                                        echo '<input name="UnfreezeTime" type="datetime-local" class="form-control" value="' . $OverTime . '">';
                                    } else {
                                        echo '<input name="UnfreezeTime" type="datetime-local" class="form-control" value="">';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">报名开始时间</span>
                                    <?php
                                    if ($status == 1) {
                                        $EnrollStartTime = $ContestData['EnrollStartTime'];
                                        $Time_1 = substr($EnrollStartTime, 0, 10);
                                        $Time_2 = substr($EnrollStartTime, 11, 5);
                                        $EnrollStartTime = $Time_1 . 'T' . $Time_2;
                                        echo '<input name="EnrollStartTime" type="datetime-local" class="form-control" value="' . $EnrollStartTime . '">';
                                    } else {
                                        echo '<input name="EnrollStartTime" type="datetime-local" class="form-control">';
                                    }
                                    ?>
                                </div>
                                <br>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">报名结束时间</span>
                                    <?php
                                    if ($status == 1) {
                                        $EnrollOverTime = $ContestData['EnrollOverTime'];
                                        $Time_1 = substr($EnrollOverTime, 0, 10);
                                        $Time_2 = substr($EnrollOverTime, 11, 5);
                                        $EnrollOverTime = $Time_1 . 'T' . $Time_2;
                                        echo '<input name="EnrollOverTime" type="datetime-local" class="form-control" value="' . $EnrollOverTime . '">';
                                    } else {
                                        echo '<input name="EnrollOverTime" type="datetime-local" class="form-control">';
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">比赛规则</span>
                                    <select name="Rule" class="form-control">
                                        <?php
                                        if ($status == 1 && $ContestData['Rule'] == 'OI') {
                                            echo '<option value="ACM">ACM</option>';
                                            echo '<option value="OI" selected = "selected">OI</option>';
                                        } else {
                                            echo '<option value="ACM" selected = "selected">ACM</option>';
                                            echo '<option value="OI">OI</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <br>
                            </div>


                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">作业/比赛</span>
                                    <select name="Practice" class="form-control">
                                        <?php
                                        if ($status == 1 && $ContestData['Practice'] == 1) {
                                            echo '<option value=1 selected="selected">Practice</option>';
                                            echo '<option value=0>Contest</option>';
                                        } else {
                                            echo '<option value="1">Practice</option>';
                                            echo '<option value="0" selected="selected">Contest</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">比赛类型</span>
                                    <select name="Type" class="form-control">
                                        <?php
                                        if ($status == 1 && $ContestData['Type'] == 1) {
                                            echo '<option value="Public">Public</option>';
                                            echo '<option value="Private" selected = "selected">Private</option>';
                                        } else {
                                            echo '<option value="Public" selected = "selected">Public</option>';
                                            echo '<option value="Private">Private</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">密码</span>
                                    <input name="PassWord" type="text" class="form-control" value=<?php echo ($status == 1) ? '"' . $ContestData['PassWord'] . '"' : '""'; ?>>

                                </div>
                                <br>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">举办人</span>
                                    <input name="Organizer" type="text" class="form-control" value=<?php echo ($status == 1) ? '"' . $ContestData['Organizer'] . '"' : $LandUser; ?>>
                                </div>
                                <br>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon">风险系数</span>
                                    <input name="RiskRatio" type="number" step="0.1" class="form-control" value=<?php echo ($status == 1) ? '"' . $ContestData['RiskRatio'] . '"' : '""'; ?>>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">题号 (比赛开始后请不要更改题号顺序)</span>
                                    <input name="Problem" type="text" class="form-control" placeholder="1000|1001|1002" value=<?php echo ($status == 1) ? '"' . $ContestData['Problem'] . '"' : '""'; ?>>
                                </div>
                                <br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <span class="input-group-addon">比赛简介</span>
                                <textarea name="Synopsis" class="form-control" style="margin-top:8px;height:80px"><?php echo ($status == 1) ? $ContestData['Synopsis'] : ''; ?></textarea>

                            </div>
                        </div>

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