<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (isset($_POST['captcha'])) {
    if (!check_captcha($_POST['captcha'], $NowTime)) {
        echo json_encode("{status: 5}");
        oj_mysql_close();
        die();
    }
}

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
    $user = htmlspecialchars(addslashes(trim($_POST["username"])));
    $psw = addslashes(trim($_POST["password"]));
    $email = htmlspecialchars(addslashes(trim($_POST["email"])));

    if (mb_strlen($psw, "utf-8") < 6 || mb_strlen($psw, "utf-8") > 12) {
        echo json_encode("{status: 4}");
        oj_mysql_close();
        die();
    }

    $md5pw = md5(md5($psw) . 'md5pwkey');

    $sql = "SELECT `Name` FROM `oj_user` WHERE `Name` = '$user' LIMIT 1";
    $result = oj_mysql_query($sql);
    $hava = mysqli_num_rows($result);

    if ($hava) {
        echo json_encode("{status: 2}");
        oj_mysql_close();
        die();
    }

    $SessionID = session_id();

    $sql = 'INSERT INTO oj_user(`name`, `uid`, `password`, `jurisdicton`, `signature`, `email`, `regtime`, `logtime`, `fight`, `skin`, `sessionid`) values("' . $user . '", 3, "' . $md5pw . '", 0, "", "' . $email . '", "' . date("Y-m-d H:i:s") . '", "' . date("Y-m-d H:i:s") . '", 1650, "spacelab", "' . $SessionID . '")';
    $result = oj_mysql_query($sql);

    if ($result) {
        $_SESSION['username']  = $user;
        $_SESSION['password']  = $md5pw;

        echo json_encode("{status: 0}");
    } else {
        echo json_encode("{status: 1}");
    }
} else {
    echo json_encode("{status: 3}");
}
oj_mysql_close();
