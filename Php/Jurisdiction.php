<?php
define('JUR_ADMIN', 2);         //最高权限管理员
define('JUR_ONLYVIEWDATA', 1);  //可查看测试点

//是否是管理员
function is_admin()
{
    global $User_Jurisdicton;
    global $LandUser;
    return isset($LandUser) && ($User_Jurisdicton == JUR_ADMIN);
}

//可以查看测试点
function can_read_test()
{
    global $User_Jurisdicton;
    global $LandUser;

    return isset($LandUser) && ($User_Jurisdicton == JUR_ONLYVIEWDATA || $User_Jurisdicton == JUR_ADMIN);
}
//可以编辑题目
function can_edit_problem()
{
    global $User_Jurisdicton;
    global $LandUser;

    return isset($LandUser) && ($User_Jurisdicton == JUR_ADMIN);
}

//可以编辑比赛[举办比赛者拥有所有权限]
function can_edit_contest($ConID = 0)
{
    global $User_Jurisdicton;
    global $LandUser;

    $can = isset($LandUser) && ($User_Jurisdicton == JUR_ADMIN);

    if (!$can && $ConID) {
        $sql = "SELECT `Organizer` FROM `oj_contest` WHERE `ConID`=" . $ConID . " LIMIT 1";
        $res = oj_mysql_query($sql);
        $Organizer = oj_mysql_fetch_array($res);

        if ($Organizer['Organizer'] == $LandUser) {
            $can = true;
        }
    }

    return $can;
}

//可以查看日志文件
function can_read_log()
{
    global $User_Jurisdicton;
    global $LandUser;

    return isset($LandUser) && ($User_Jurisdicton == JUR_ADMIN);
}

//可以看他人代码
function can_read_code()
{
    global $User_Jurisdicton;
    global $LandUser;

    return isset($LandUser) && ($User_Jurisdicton == JUR_ADMIN);
}
