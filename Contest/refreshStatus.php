<?php
header('Content-Type:application/json; charset=gbk');

require_once("../Php/LoadData.php");

if (array_key_exists('RunID', $_POST)) {
    $runID = intval($_POST['RunID']);

    $sql = "SELECT `ConID`,`Status`,`UseTime`,`UseMemory` FROM `oj_constatus` WHERE `RunID`='" . $runID . "' LIMIT 1";
    $rs = oj_mysql_query($sql);

    if ($rs) {
        $row = oj_mysql_fetch_array($rs);

        $iConID = $row['ConID'];
        $iStatic = $row['Status'];
        $iUseTime = $row['UseTime'];
        $iUseMemory = $row['UseMemory'];

        $iClass = 'label-primary';

        if ($iStatic == Accepted)
            $iClass = 'label-success';
        else if ($iStatic == CompileError)
            $iClass = 'label-warning';
        else if ($iStatic > Accepted)
            $iClass = 'label-danger';

        $Status = '<a class="label ' . $iClass . '" href="/Contest/Detail.php?ConID=' . $iConID . '&RunID=' . $runID . '">' . $AllStatusCName[$iStatic] . ' ' . $AllStatusName[$iStatic] . '</a>';

        //组合成json格式数据
        $data = "{status:'" . $Status . "',useTime:'" . $iUseTime . "',useMemory:'" . $iUseMemory . "'}";
        //输出json数据
        echo json_encode($data);
    }
}
oj_mysql_close();
