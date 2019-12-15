<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<body>

    <?php require_once('Php/Page_Header.php') ?>

    <div class="animated fadeInDown">
        <center>

            <?php
            echo '<h1>' . $WebName . '</h1>';
            ?>
            <h2>一个运行在Windows平台的源程序在线判题系统</h2>
            <br>
            <a>前端界面(HTML, CSS)参考 qdacm.com</a><br />
            <a>评测机(C++)由 Setiuo 编写</a><br>
            <a>服务端(PHP)由 Setiuo 编写</a><br>
            <a>Web服务器使用 Nginx</a><br>
            <a>前端框架使用 Bootstrap</a><br>
            <a>数学公式使用 MathJax</a><br>
            <a>代码编辑框使用 CodeMirror</a><br>
            <a>富文本编辑框使用 CKEditor 4</a><br><br>
            此评测平台仅供学习<br /><br />
            如果您有任何问题，可以联系我：<br />
            QQ：751255159<br />
            E-mail：751255159@qq.com
        </center>
        <div>

            <div class="container">
                <h2>当前状态</h2>
                <div class="panel panel-default">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>评测机</th>
                                <th>运行状态</th>
                                <th>累计评测</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="maxtext">
                                    <a>Judger</a>
                                </td>
                                <td class="maxtext">
                                    <?php
                                    if ($JudgeMac_1 == 1) {
                                        echo '<span class="label label-success">正常运行中</span>';
                                    } else {
                                        echo '<span class="label label-danger">已关闭</span>';
                                    }
                                    ?>
                                </td>
                                <td class="maxtext">
                                    <a><?php echo $JudgeAllRun_1 ?> 次</a>
                                </td>
                            </tr>

                            <tr>
                                <td class="maxtext">
                                    <a>Judger_Contest</a>
                                </td>
                                <td class="maxtext">
                                    <?php
                                    if ($JudgeMac_2 == 1) {
                                        echo '<span class="label label-success">正常运行中</span>';
                                    } else {
                                        echo '<span class="label label-danger">已关闭</span>';
                                    }
                                    ?>
                                </td>
                                <td class="maxtext">
                                    <a><?php echo $JudgeAllRun_2 ?> 次</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                如果您有什么意见或可反馈的问题，可以在这里提交
                <textarea data-ctrlenter="#post_submit" name="content" placeholder="这里填具体描述" style="margin-top:8px;height:80px" class="form-control"></textarea>
                <button id="post_submit" style="margin-top:10px;" class="btn btn-default">提交</button>
            </div>
        </div>
    </div>

    <?php
    $PageActive = '';
    require_once('Php/Page_Footer.php');
    ?>
</body>

</html>