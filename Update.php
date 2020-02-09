<?php
require_once('Php/LoadData.php');

for ($i = 1000; $i <= 1073; $i++) {
    $sql = "SELECT * FROM `oj_problem_test` WHERE `problemID` = $i";
    $res = oj_mysql_query($sql);

    if ($res) {
        $problemData = oj_mysql_fetch_array($res);

        $problemID = $i;

        for ($j = 1; $j <= 100; $j++) {
            $baseName_IN = "test" . $j . "_in";
            $baseName_OUT = "test" . $j . "_out";

            $testID = $j;
            $input = $problemData[$baseName_IN];
            $output = $problemData[$baseName_OUT];;

            if ($input != "" || $output != "") {
                $sql2 = "INSERT INTO oj_problem_data(problemID, testID, input, output) VALUE($problemID, $testID, '$input', '$output') ON DUPLICATE KEY UPDATE `input`= '$input', `output`='$output'";
                $res2 = oj_mysql_query($sql2);

                if ($res2) {
                    echo $i . '_' . $j . ': success! <br/>';
                }
            }
        }
    }
}
