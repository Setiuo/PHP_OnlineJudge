<?php
header('Content-Type:application/json; charset=gbk');

require_once("LoadData.php");

$runID = intval($_POST['RunID']);

$sql = "SELECT `Status`,`UseTime`,`UseMemory` FROM `oj_status` WHERE `RunID`='" . $runID . "' LIMIT 1";
$rs = oj_mysql_query($sql);
$row = oj_mysql_fetch_array($rs);

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

$Status = '<a class="label ' . $iClass . '" href="/Detail.php?RunID=' . $runID . '">' . $AllStatusCName[$iStatic] . ' ' . $AllStatusName[$iStatic] . '</a>';

$data = "{status:'" . $Status . "',useTime:'" . $iUseTime . "',useMemory:'" . $iUseMemory . "'}";                           //组合成json格式数据
echo json_encode($data);                                                //输出json数据
oj_mysql_close();
