<?php
header('Content-Type:application/json; charset=gbk');

if (!can_edit_problem()) {
    echo json_encode('{"uploaded":0,"error": "test"}');
    return;
}

if ($_FILES["upload"]["error"] > 0) {
    echo json_encode('{"uploaded":0,"error": "test"}');
} else {
    $file_name = time() . $_FILES["upload"]["name"];
    $file_folder = '/images/' . $file_name;
    if (file_exists("../upload/" . $file_name)) {
        echo json_encode('{"uploaded":0,"error": "test"}');
    } else {

        move_uploaded_file($_FILES["upload"]["tmp_name"], "../images/" . $file_name);
        echo json_encode('{"uploaded": 1,"fileName": "' . $file_name . '","url": "' . $file_folder . '"}');
    }
}
