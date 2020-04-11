<?php 
if ($f == 'check_for_updates') {
    $false = false;
    if (!is_dir('themes/wowonder')) {
        $false = true;
    }
    if (!is_dir('themes/wonderful') && $false == true) {
        $false = true;
    } else {
        $false = false;
    }
    if ($false == true) {
        $data['status']     = 400;
        $data['ERROR_NAME'] = 'It looks like you have renamed your themes, please rename them back to "wowonder", "wonderful" to use the auto update system, otherwise please update your site manually.';
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
