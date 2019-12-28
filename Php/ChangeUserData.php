<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

if (isset($LandUser) && $_POST["Motto"]) {
    $iCss = htmlspecialchars(addslashes(trim($_POST["Css"])));
    $iMotto = htmlspecialchars(addslashes(trim($_POST["Motto"])));
    $iRepassword = addslashes(trim($_POST["Repassword"]));
    $iOldPassWord = addslashes(trim($_POST["Oldpassword"]));
    $iNewpassword = addslashes(trim($_POST["Newpassword"]));
    $iTails = htmlspecialchars(addslashes(trim($_POST["Tails"])));

    $sql = "UPDATE `oj_user` SET `signature`='" . $iMotto . "', `skin`='" . $iCss . "', `tails`='" .  $iTails . "' WHERE `name`='" . $LandUser . "'";
    oj_mysql_query($sql);

    if ($_POST["Oldpassword"] != "") {
        $md5_oldpw = md5(md5($iOldPassWord) . 'md5pwkey');
        $md5_newpw = md5(md5($iNewpassword) . 'md5pwkey');

        $sql = "SELECT `Name`,`Password` FROM `oj_user` WHERE `Name` = '$LandUser' AND `PassWord` = '$md5_oldpw' LIMIT 1";
        $result = oj_mysql_query($sql);
        $num = mysqli_num_rows($result);
        if ($num) {
            if ($iNewpassword != "") {
                if ($iNewpassword == $iRepassword) {
                    $sql = "UPDATE `oj_user` SET `PassWord`='" . $md5_newpw . "' WHERE `name`='" . $LandUser . "'";
                    oj_mysql_query($sql);

                    echo json_encode("{status: 0}");
                } else {
                    echo json_encode("{status: 1}");
                }
            } else {
                echo  json_encode("{status: 2}");
            }
        } else {
            echo  json_encode("{status: 3}");
        }
    } else {
        echo  json_encode("{status: 0}");
    }
} else {
    json_encode("{status: 4}");
}
oj_mysql_close();
