<?php
header('Content-Type:application/json; charset=utf-8');
require_once("LoadData.php");

if (!isset($_POST["username"]) || !isset($_POST["password"])) {
    echo json_encode("{status: 1}");
    oj_mysql_close();
    die();
}

if (!isset($_POST["captcha"])) {
    echo json_encode("{status: 2}");
    oj_mysql_close();
    die();
}

if (!check_captcha($_POST["captcha"], $NowTime)) {
    echo json_encode("{status: 3}");
    oj_mysql_close();
    die();
}

$user = addslashes(trim($_POST["username"]));
$psw = addslashes(trim($_POST["password"]));

$data = "{status: 1}";

if ($user == "" || $psw == "") {
    $data = "{status: 1}";

    unset($_SESSION['username']); //注销用户登陆
    unset($_SESSION['password']);
} else {
    $md5pw = md5(md5($psw) . 'md5pwkey');

    //核实数据库中的用户信息
    $sql = "SELECT `Name`,`Password` FROM `oj_user` WHERE `Name` = '$user' AND `PassWord` = '$md5pw' LIMIT 1";
    $result = oj_mysql_query($sql);

    //查找失败
    if (!$result) {
        $data = "{status: 1}";
    } else {
        $num = mysqli_num_rows($result);

        if ($num) {
            //设置登陆信息
            $row = oj_mysql_fetch_array_num($result);
            $_SESSION['username']  = $row[0];
            $_SESSION['password']  = $md5pw;

            $SessionID = session_id();
            //更新数据库中的登陆日期
            $time = date('Y-m-d H:i:s');
            $sql = "UPDATE `oj_user` SET `logtime`='$time', `sessionid`='" . $SessionID . "' WHERE `name`='$row[0]'";
            oj_mysql_query($sql);

            $data = "{status: 0}";
        } else {
            unset($_SESSION['username']);
            unset($_SESSION['password']);

            $data = "{status: 1}";
        }
    }

    echo json_encode($data);
}
oj_mysql_close();
