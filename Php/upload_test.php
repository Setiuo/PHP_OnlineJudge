<?php
header('Content-Type:application/json; charset=gbk');

if ($_FILES["file"]["error"] > 0) { } else {

    $file_name = $_FILES["file"]["name"];

    if (file_exists("../Judge/" . $file_name)) { } else {

        move_uploaded_file($_FILES["file"]["tmp_name"], "../images/" . $file_name);
    }
}
