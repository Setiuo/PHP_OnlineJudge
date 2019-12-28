<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php')?>

<body>
    <?php require_once ('Php/Page_Header.php') ?>

	<div class="container">

        <ul class="pagination">
            <li><a href="/discuss/">&laquo;</a></li>
            <li><a href="/discuss/1/">&lt;</a></li>

            <li class="active"><a href="/discuss/1/">1</a></li>

            <li><a href="/discuss/2/">2</a></li>

            <li><a href="/discuss/3/">3</a></li>

            <li><a href="/discuss/4/">4</a></li>

            <li><a href="/discuss/5/">5</a></li>

            <li><a href="/discuss/2/">&gt;</a></li>
            <li><a href="/discuss/5/">&raquo;</a></li>
        </ul>
        <table class="float-right" style="margin:20px 0 20px 0;width:250px">
            <tr>
                <td>
                    <a href="#newpost" class="btn btn-default">新建</a>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon">题号</span>
                        <input id="discuzproblem" data-enter="#godiscuzproblem" type="text" class="form-control">
                        <span class="input-group-btn">
                            <button id="godiscuzproblem" class="btn btn-default" type="button">Go!</button>
                        </span>
                    </div>
                </td>
            </tr>
        </table>
        <script>
            $("#godiscuzproblem").click(function () {
                location.href = "/Discuss.php?Num=" + $("#discuzproblem").val();
            });
        </script>

        <div class="panel panel-default">
            <table class="table table-striped table-hover vertical-center">
                <thead>
                    <tr>
                        <th>标题</th>
                        <th>楼主</th>
                        <th>回复/查看</th>
                    </tr>
                </thead>
                <tbody>


                    <tr>
                        <td class="maxtext">
                            <span class="label label-danger">置顶</span>

                            <a href="/Discuss/59896037789f28f80a303a0e" style="color:red">测试Openjudge</a>

                        </td>
                        <td class="mintext">
                            <a href="/User/XiaoJiang" class="myuser-base myuser-red">XiaoJiang</a><br>
                            2017-8-8 14:54:47
                        </td>
                        <td class="mintext">
                            8<br>
                            1209
                        </td>
                    </tr>

                    <tr>
                        <td class="maxtext">

                            <a class="label label-primary" href="/Discuss/P/2739">P2739</a>
                            <a href="/Discuss/5c4858696f1a51240f2c5653">嘤</a>

                        </td>
                        <td class="mintext">
                            <a href="/User/达拉崩吧" class="myuser-base myuser-cyan">达拉崩吧</a><br>
                            2019-1-23 20:4:57
                        </td>
                        <td class="mintext">
                            1<br>
                            18
                        </td>
                    </tr>

                    <tr>
                        <td class="maxtext">


                            <a href="/Discuss/5c48523c6f1a51240f2c564c">冲鸭</a>

                        </td>
                        <td class="mintext">
                            <a href="/User/昆图库塔" class="myuser-base myuser-cyan">昆图库塔</a><br>
                            2019-1-23 19:38:36
                        </td>
                        <td class="mintext">
                            1<br>
                            12
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <center>
            <ul class="pagination">
                <li><a href="/discuss/">&laquo;</a></li>
                <li><a href="/discuss/1/">&lt;</a></li>

                <li class="active"><a href="/discuss/1/">1</a></li>

                <li><a href="/discuss/2/">2</a></li>

                <li><a href="/discuss/3/">3</a></li>

                <li><a href="/discuss/4/">4</a></li>

                <li><a href="/discuss/5/">5</a></li>

                <li><a href="/discuss/2/">&gt;</a></li>
                <li><a href="/discuss/5/">&raquo;</a></li>
            </ul>
        </center>
        <form method="post" id="newpost">

            <div class="input-group">
                <span class="input-group-addon">标题</span>
                <input type="text" name="title" class="form-control">
            </div>
            <textarea data-ctrlenter="#post_submit" name="content" placeholder="这里填具体描述,你可以按Ctrl+Enter进行提交" style="margin-top:8px;height:150px"
                class="form-control"></textarea>
            <div class="input-group">
                <span class="input-group-addon">验证码：</span>
                <input name="checkcode" type="checkcode" class="form-control">
            </div>
            <img class="captcha" src="/checkcode.png">
            <button id="post_submit" style="margin-top:10px;" class="btn btn-default">提交</button>
        </form>
    </div>

    <?php
	$PageActive = '#discuss';
	require_once('Php/Page_Footer.php');
	?>

</body>

</html>