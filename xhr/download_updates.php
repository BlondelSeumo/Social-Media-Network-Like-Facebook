<?php 
if ($f == 'download_updates') {
    if (Wo_CheckMainSession($hash_id) === true) {
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false
            )
        );
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
