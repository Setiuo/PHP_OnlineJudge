<?php
define('JUR_ADMIN', 2);         //最高权限管理员
define('JUR_ONLYVIEWDATA', 1);  //可查看测试点

/*
x & (1) 查看测试点
x & (1<<1) 编辑题目
x & (1<<2) 编辑比赛
x & (1<<3) 查看日志
x & (1<<4) 查看他人代码
x & (1<<5) 基本管理员
x & (1<<6) 最高管理员
*/

//是否是最高管理员
function is_admin_max()
{
    global $User_Jurisdiction;
    global $LandUser;
    return isset($LandUser) && ($User_Jurisdiction & (1 << 6));
}

//是否是管理员
function is_admin()
{
    global $User_Jurisdiction;
    global $LandUser;
    return isset($LandUser) && ($User_Jurisdiction & (1 << 5) || is_admin_max());
}

//可以查看测试点
function can_read_test()
{
    global $User_Jurisdiction;
    global $LandUser;

    return isset($LandUser) && ($User_Jurisdiction & (1) || can_edit_problem() || is_admin());
}
//可以编辑题目
function can_edit_problem()
{
    global $User_Jurisdiction;
    global $LandUser;

    return isset($LandUser) && ($User_Jurisdiction & (1 << 1) || is_admin());
}

//可以编辑比赛[举办比赛者拥有所有权限]
function can_edit_contest($ConID = 0)
{
    global $User_Jurisdiction;
    global $LandUser;

    $can = isset($LandUser) && ($User_Jurisdiction & (1 << 2) || is_admin());

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
    global $User_Jurisdiction;
    global $LandUser;

    return isset($LandUser) && ($User_Jurisdiction & (1 << 3) || is_admin());
}

//可以看他人代码
function can_read_code()
{
    global $User_Jurisdiction;
    global $LandUser;

    return isset($LandUser) && ($User_Jurisdiction & (1 << 4) || is_admin());
}
