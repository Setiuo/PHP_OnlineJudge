<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once("Html_Head.php"); ?>

<?php
if (!isset($LandUser)) {
    header('Location: /Message.php?Msg=您没有登陆，无权访问');
    die();
}
if (!can_edit_contest($ConID)) {
    header('Location: /Message.php?Msg=您不是管理员，无权访问');
    die();
}

function deleteDir($path)
{
    if (is_dir($path)) {
        //扫描一个目录内的所有目录和文件并返回数组
        $dirs = scandir($path);

        foreach ($dirs as $dir) {
            //排除目录中的当前目录(.)和上一级目录(..)
            if ($dir != '.' && $dir != '..') {
                //如果是目录则递归子目录，继续操作
                $sonDir = $path . '/' . $dir;
                if (is_dir($sonDir)) {
                    //递归删除
                    deleteDir($sonDir);

                    //目录内的子目录和文件删除后删除空目录
                    @rmdir($sonDir);
                } else {

                    //如果是文件直接删除
                    @unlink($sonDir);
                }
            }
        }
        @rmdir($path);
    }
}

function get_between($input, $start, $end)
{
    $substr = substr($input, strlen($start) + strpos($input, $start), (strlen($input) - strpos($input, $end)) * (-1));
    return $substr;
}

function get_code_similar($code1, $code2)
{
    $i_code1 = str_replace(array("\r\n", "\r", "\n", " ", " "), "", $code1);
    $i_code2 = str_replace(array("\r\n", "\r", "\n", " ", " "), "", $code2);

    similar_text($i_code1, $i_code2, $percent);
    return $percent;
}
//
$sql = "SELECT count(*) AS value FROM `oj_constatus` WHERE `Status` = " . Accepted . " AND `ConID`=" . $ConID;
$rs = oj_mysql_query($sql);
$CorrectNum = oj_mysql_fetch_array($rs);
?>

<?php
$AC_Code_Num = 0;
$Similar_Code_Num = 0;

$AllCodeData = array();
$AllSimilarData = array();

$sql = "SELECT `Problem`, `Language`, `User`, `RunID`, `SubTime` FROM `oj_constatus` WHERE `ConID`= " . $ConID . " AND  `Status` = " . Accepted;
$statusData_result = oj_mysql_query($sql);

$start_time = microtime(true);

//是否启动moss检测
$standford_moss = false;
if (array_key_exists('moss', $_GET)) {
    if ($_GET['moss'] == 'true') {
        $standford_moss = true;
    }
}

if ($standford_moss) {
    include("moss.php");
    $userid = 327879735;

    try {
        $moss = new MOSS($userid);
        $moss->setLanguage('cc');
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    deleteDir("temp_code/$ConID/");
    mkdir("temp_code/$ConID/", 0777, true);
}

while ($StatusData = oj_mysql_fetch_array($statusData_result)) {

    $sql = "SELECT `code` FROM `oj_judge_task` WHERE `runID`=" . $StatusData['RunID'] . " AND `contestID`=$ConID LIMIT 1";
    $rs = oj_mysql_query($sql);
    $row = oj_mysql_fetch_array($rs);

    $AllCodeData[$StatusData['User']][] =
        array(
            "User" => $StatusData['User'],
            "Problem" => $ProEngNum[$StatusData['Problem']],
            "Language" => $StatusData['Language'],
            "Code" => $row['code'],
            "SubmitTime" => $StatusData['SubTime'],
            "RunID" => $StatusData['RunID'],
        );

    if ($standford_moss) {
        $myfile = fopen("temp_code/$ConID/" . $StatusData['RunID'], "w");
        fwrite($myfile, $row['code']);
        fclose($myfile);

        try {
            $moss->addFile("temp_code/$ConID/" . $StatusData['RunID']);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    $AC_Code_Num++;
};

if ($standford_moss) {
    try {
        $result_href = trim($moss->send());
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    $result_html = file_get_contents($result_href);

    preg_match_all('/<A[^>]*([\s\S]*?)<\/A>/i', $result_html, $matches);
    $num = 1;
    foreach ($matches[1] as $iData) {
        if (strstr($iData, ">temp_code/$ConID/")) {
            $data_id = get_between($iData, "temp_code/$ConID/", '(');
            preg_match_all("/(?:\()(.*)(?:\))/i", $iData, $data_sim);

            if ($num % 2 == 1) {
                $first = intval($data_id);
                $sim_first = intval($data_sim[1][0]);
            } else {
                $second = intval($data_id);
                $sim_second = intval($data_sim[1][0]);

                $runID_Data[$first][$second] = $sim_first;
                $runID_Data[$second][$first] = $sim_second;
            }
            $num++;
        }
    }

    deleteDir("temp_code/$ConID/");
}

foreach ($AllCodeData as  $iUserData) {
    foreach ($iUserData as $iProblemData) {
        foreach ($AllCodeData as  $iOtherUserData) {
            foreach ($iOtherUserData as $iOtherProblemData) {

                if ($iOtherProblemData['User'] != $iProblemData['User'] && $iOtherProblemData['Problem'] == $iProblemData['Problem']) {

                    if ($iProblemData['SubmitTime'] < $iOtherProblemData['SubmitTime']) {
                        $code_main = $iProblemData['Code'];
                        $code_copy = $iOtherProblemData['Code'];

                        $code_main_user = $iProblemData['User'];
                        $code_copy_user = $iOtherProblemData['User'];

                        $runid_user = $iProblemData['RunID'];
                        $runid_copy = $iOtherProblemData['RunID'];
                    } else {
                        $code_main = $iOtherProblemData['Code'];
                        $code_copy = $iProblemData['Code'];

                        $code_main_user = $iOtherProblemData['User'];
                        $code_copy_user = $iProblemData['User'];

                        $runid_user = $iOtherProblemData['RunID'];
                        $runid_copy = $iProblemData['RunID'];
                    }

                    $iSimilar = 0;
                    if ($standford_moss) {
                        if (isset($runID_Data[$runid_user][$runid_copy]))
                            $iSimilar = $runID_Data[$runid_user][$runid_copy];
                    } else {
                        $iSimilar = round(get_code_similar($iProblemData['Code'], $iOtherProblemData['Code']), 2);
                    }

                    $iArray =  array(
                        'problem' => $iOtherProblemData['Problem'],
                        'similarity' =>  $iSimilar,
                        'code_main' => htmlspecialchars($code_main),
                        'code_copy' => htmlspecialchars($code_copy),
                        'code_main_user' => $code_main_user,
                        'code_copy_user' => $code_copy_user,
                        'runid_user' => $runid_user,
                        'runid_copy' => $runid_copy
                    );

                    if (!in_array($iArray, $AllSimilarData) && $iSimilar >= 70) {
                        $AllSimilarData[] = $iArray;
                        $Similar_Code_Num++;
                    }
                }
            }
        }
    }
}

function my_sort($a, $b)
{
    if ($a['similarity'] == $b['similarity']) {
        return $a['problem'] < $b['problem'] ? -1 : 1;
    }

    return $a['similarity'] > $b['similarity'] ? -1 : 1;
}

usort($AllSimilarData, "my_sort");

$end_time = microtime(true);
?>

<body>
    <?php require_once("Header.php"); ?>

    <link rel="stylesheet" href="/highlight/styles/default.css">

    <script>
        function Standford_Moss(conID) {
            if (confirm('访问Moss服务器不稳定，可能将消耗大量时间，并有概率检测失败。是否继续？')) {
                location.href = '/Contest/CodeSimilarity.php?moss=true&ConID=' + conID;
            }
        }

        function default_test(conID) {
            location.href = '/Contest/CodeSimilarity.php?ConID=' + conID;
        }
    </script>

    <div class="container">
        <div class="panel panel-default animated fadeInRight">
            <div id="contesthead" class="panel-heading" style="padding:0 0 0 0;">
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation"><a class="label label-success" href="javascript:Standford_Moss(<?php echo $ConID ?>)">Standford Moss 检测</a></li>
                    <li role="presentation"><a class="label label-warning" href="javascript:default_test(<?php echo $ConID ?>)">字符串匹配 检测</a></li>
                    <h4>&nbsp;</h4>

                </ul>
            </div>
            <div class="panel-body">
                <?php
                if ($standford_moss) {
                    echo '<a target="_Blank" style="color:red" href="' . $result_href . '">Standford Moss检测模式</a> 代码相似性检测：比赛' . $ConID;
                } else {
                    echo '<a style="color:red">字符串匹配检测模式</a> 代码相似性检测：比赛' . $ConID;
                }
                echo '<br/>';
                ?>

                <?php
                echo '程序耗时 ' . round($end_time - $start_time, 6) * 1000 . ' ms<br/>';
                echo 'AC代码 ' . $AC_Code_Num . ' 份， 相似代码 ' . $Similar_Code_Num . ' 份<br/>';

                $i_code_id = 0;
                foreach ($AllSimilarData as $iData) {
                    echo '<a id="smy_' . $i_code_id . '" href="#sim_' . ($i_code_id) . '">[' . $iData['problem'] . '] 相似度:' . $iData['similarity'] . '% [' . $iData['code_copy_user'] . '](#' . $iData['runid_copy'] . ') copy [' . $iData['code_main_user'] . '](#' . $iData['runid_user'] . ')</a><br>';
                    $i_code_id++;
                }
                echo '<hr>';
                $i_code_id = 0;
                foreach ($AllSimilarData as $iData) {
                    echo '<div id="sim_' . $i_code_id . '">';
                    echo '<a href="#smy_' . $i_code_id . '"><span class="glyphicon glyphicon-arrow-up"></span> 返回顶部</a><br>';
                    echo '题号:<a href="/Contest/Problem.php?ConID=' . $ConID . '&Problem=' . $iData['problem'] . '">' . $iData['problem'] . '</a><br>';
                    echo '相似度:' . ($iData['similarity'] == 100 ? ' <span class="label label-danger">完全相同</span>' : $iData['similarity'] . '%') . '<br>';
                    echo  $iData['code_copy_user'] . '(<a target="_Blank" href="/Contest/Detail.php?ConID=' . $ConID . '&RunID=' . $iData['runid_copy'] . '">#' . $iData['runid_copy'] . '</a>)may copy from ' . $iData['code_main_user'] . '(<a target="_Blank" href="/Contest/Detail.php?ConID=' . $ConID . '&RunID=' . $iData['runid_user'] . '">#' . $iData['runid_user'] . '</a>)<br>';
                    echo '<div class="row">';
                    echo '<div class="col-xs-6">';
                    echo '抄袭者:' . $iData['code_copy_user'] . '(<a target="_Blank" href="/Contest/Detail.php?ConID=' . $ConID . '&RunID=' . $iData['runid_copy'] . '">#' . $iData['runid_copy'] . '</a>)<br>';
                    echo '<pre class="padding-0"><code class="C++">' . $iData['code_copy'] . '</code></pre>';
                    echo '</div>';
                    echo '<div class="col-xs-6">';
                    echo '所有者:' . $iData['code_main_user'] . '(<a target="_Blank" href="/Contest/Detail.php?ConID=' . $ConID . '&RunID=' . $iData['runid_user'] . '">#' . $iData['runid_user'] . '</a>)<br>';
                    echo  '<pre class="padding-0"><code class="C++">' . $iData['code_main'] . '</code></pre>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<hr>';
                    $i_code_id++;
                }
                ?>
            </div>
        </div>
    </div>


    <?php
    $PageActive = "#admin";
    require_once('Footer.php');
    ?>

    <script src="/highlight/highlight.pack.js"></script>
    <script>
        hljs.initHighlightingOnLoad("C", "C++", "Java", "Python");
    </script>
</body>

</html>