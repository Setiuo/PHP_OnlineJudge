<?php
header('Content-Type:application/json; charset=gbk');
require_once("LoadData.php");

if (!can_edit_problem()) {
    echo ('{"uploaded":0,"error": "test"}');
    die();
}

if ($_FILES["upload"]["error"] > 0) {
    echo ('{"uploaded":0,"error": "test"}');
} else {
    $file_name = time() . $_FILES["upload"]["name"];
    $file_folder = "/images/" . $file_name;
    if (file_exists("../upload/" . $file_name)) {
        echo ('{"uploaded":0,"error": "test"}');
    } else {

        move_uploaded_file($_FILES["upload"]["tmp_name"], "../images/" . $file_name);
        echo ('{"uploaded": 1,"fileName": "' . $file_name . '","url": "' . $file_folder . '"}');
    }
}
