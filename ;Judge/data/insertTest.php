<?php
$con = mysqli_connect('127.0.0.1', 'openjudge', 'sqlieIAjVBw02', 'openjudge');
mysqli_query($con, 'set names utf8');

for ($problemID = 1000; $problemID <= 1046; $problemID++) {
    $base_name_in = '';
    $base_value_in = '';
    $base_name_out = '';
    $base_value_out = '';
    $test_num = 0;

    for ($testID = 1; $testID <= 50; $testID++) {
        $in_path = $problemID . '/' . $problemID . '_' . $testID . '.in';
        $out_path = $problemID . '/' . $problemID . '_' . $testID . '.out';

        if (file_exists($in_path)) {
            $value_in = addslashes(file_get_contents($in_path));
            $base_name_in .= ', `test' . $testID . '_in`';
            $base_value_in .= ', "' . $value_in . '"';
        }
        if (file_exists($out_path)) {
            $value_out = addslashes(file_get_contents($out_path));
            $base_name_out .= ', `test' . $testID . '_out`';
            $base_value_out .= ', "' .  $value_out . '"';
        }

        if (file_exists($in_path) || file_exists($out_path)) {
            $test_num++;
        }
    }


    $sql = 'INSERT INTO `oj_problem_test` (`problemID`, `testNum`' . $base_name_in . $base_name_out . ') VALUES(' . $problemID . ', ' . $test_num . $base_value_in . $base_value_out . ')';
    $result = mysqli_query($con, $sql);
    if ($result)
        echo 'Finish ' . $problemID . '<br />';
    else
        echo 'Error ' . $problemID . '<br />';
}

mysqli_close($con);
