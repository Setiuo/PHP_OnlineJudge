<?php
//关闭警告
//ini_set("display_errors", "Off");

session_start();

define("SQL_USER", "onlinejudge");
define("SQL_PASSWORD", "sql_password");
define("SQL_BASE", "onlinejujdge");

function LoadData()
{
    global $WebName;
    global $WebTitle;
    global $WebHtmlTitle;
    global $Maintain;
    global $NowTime;
    global $JudgeMac_1;
    global $JudgeMac_2;
    global $JudgeAllRun_1;
    global $JudgeAllRun_2;
    global $JudgeMacRunID;
    global $con;
    global $OJ_Version;

    //设置版本号，用来更新客户端缓存资源
    $OJ_Version = 4;

    //设置时区
    date_default_timezone_set('Asia/Shanghai');
    $NowTime = 1000 * time();

    $con = mysqli_connect('127.0.0.1', SQL_USER, SQL_PASSWORD, SQL_BASE); //数据库用户名，密码

    if (!$con && $_SERVER['PHP_SELF'] != "/Message.php")
        header('Location: /Message.php?Msg=数据库连接失败');
    else if ($con)
        mysqli_query($con, 'set names utf8');

    $sql = "SELECT * FROM `oj_data` LIMIT 1";
    $result = oj_mysql_query($sql);
    $row = oj_mysql_fetch_array($result);

    $WebName = $row['oj_name'];
    $WebTitle = $row['oj_title'];
    $WebHtmlTitle = $row['oj_html_title'];
    $JudgeMac_1 = $row['oj_EvaMacState_1'];
    $JudgeMac_2 = $row['oj_EvaMacState_2'];
    $JudgeMacRunID = $row['oj_runid'];
    $JudgeAllRun_1 = $row['oj_allrun_1'];
    $JudgeAllRun_2 = $row['oj_allrun_2'];

    $Maintain = $row['maintain'];
}
LoadData();

if ($Maintain == 1 && $_SERVER['PHP_SELF'] != "/Maintain.php") {
    header('Location: /Maintain.php');
    die();
}

//封装mysqli的query操作
function oj_mysql_query($sql)
{
    //global $mysqli;
    //return $mysqli->query($sql);
    global $con;
    if ($con) {
        return mysqli_query($con, $sql);
    }
}

//封装mysqli的fetch_array操作
function oj_mysql_fetch_array($result)
{
    //global $mysqli;
    //return $mysqli->fetch_assoc($result);
    if ($result)
        return mysqli_fetch_array($result, MYSQLI_ASSOC);
}

//封装mysqli的fetch_array操作
function oj_mysql_fetch_array_num($result)
{
    //global $mysqli;
    //return $mysqli->fetch_row($result);
    return mysqli_fetch_array($result, MYSQLI_NUM);
}

//关闭数据库
function oj_mysql_close()
{
    global $con;
    if ($con) {
        mysqli_close($con);
    }
    //global $mysqli;
    //$mysqli->close();
}

//取用户名颜色样式
function GetUserColor($Fight)
{
    if ($Fight >= 3500)
        return '"myuser-base myuser-legendary"';
    else if ($Fight >= 3200)
        return '"myuser-base myuser-red"';
    else if ($Fight >= 2900)
        return '"myuser-base myuser-fire"';
    else if ($Fight >= 2700)
        return '"myuser-base myuser-orange"';
    else if ($Fight >= 2450)
        return '"myuser-base myuser-yellow"';
    else if ($Fight >= 2200)
        return '"myuser-base myuser-purple"';
    else if ($Fight >= 1900)
        return '"myuser-base myuser-violet"';
    else if ($Fight >= 1750)
        return '"myuser-base myuser-blue"';
    else if ($Fight >= 1600)
        return '"myuser-base myuser-cyan"';
    else if ($Fight > 0)
        return '"myuser-base myuser-green"';
    else
        return '"myuser-base myuser-gray"';
}

//获取小尾巴和战斗力
function get_user_tailsAndFight($Name)
{
    $sql = "SELECT `fight`, `tails` FROM `oj_user` WHERE `name`='" . $Name . "' LIMIT 1";
    $rs = oj_mysql_query($sql);
    $row = oj_mysql_fetch_array($rs);

    return $row;
}

//核对验证码
function check_captcha($code, $time)
{
    $result = false;

    if (isset($_SESSION['captcha_code']) && isset($_SESSION['captcha_time'])) {
        if ($_SESSION['captcha_code'] == $code &&  $time - $_SESSION['captcha_time'] <= 1000 * 60 * 3) {
            $result = true;
        }
    }

    //验证后删除Session中的验证码，防止暴力破解
    unset($_SESSION['captcha_code']);
    unset($_SESSION['captcha_time']);

    return $result;
}

global $LandUser;
global $User_Jurisdiction;
$User_Jurisdiction = 0;

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    $SessionID = session_id();

    $sql = "SELECT `name`,`jurisdiction` FROM `oj_user` WHERE `name`='" . $_SESSION['username'] . "' AND `password`='" . $_SESSION['password'] . "' AND `sessionid`='" . $SessionID . "' LIMIT 1";
    $rs = mysqli_query($con, $sql);
    $row = oj_mysql_fetch_array($rs);

    if (isset($row) && $row) {
        $LandUser = $row['name'];
        $User_Jurisdiction = $row['jurisdiction'];
    } else {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600);
    }
}

//权限验证
require_once("Jurisdiction.php");

const Wating = 0;
const Pending = 1;
const Compiling = 2;
const Running = 3;
const Accepted = 4;
const PresentationError = 5;
const TimeLimitExceeded = 6;
const MemoryLimitExceeded = 7;
const WrongAnswer = 8;
const RuntimeError = 9;
const OutputLimitExceeded = 10;
const CompileError = 11;
const SystemError = 12;

$AllStatusName = array("Wating", "Pending", "Compiling", "Running", "Accepted", "Presentation Error", "Time Limit Exceeded", "Memory Limit Exceeded", "Wrong Answer", "Runtime Error", "Output Limit Exceeded", "Compile Error", "System Error");
$AllStatusCName = array("等待分配", "等待评测", "正在编译", "正在运行", "测试通过", "描述错误", "时间超限", "内存超限", "答案错误", "运行错误", "输出超限", "编译错误", "系统错误");
