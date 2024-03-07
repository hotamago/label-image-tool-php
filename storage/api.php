<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/module/man-storage.php';

$API_KEY = "label-image-tool-5646430829";
if (isset($_REQUEST['data'])) {
    $api_key_request = trim($_SERVER['HTTP_API_KEY']);
    if ($api_key_request == $API_KEY) {
        $storage = new ManStorage();
        $data = base64_decode($_REQUEST['data']);
        $type = $_REQUEST['type'];
        $response = $storage->uploadFile($data, $type);
        if ($response['success']) {
            $response['data']['link'] = $response['data']['link'];
        }
        echo json_encode($response);
        http_response_code(200);
        exit();
    } else http_response_code(403);
} else if (isset($_REQUEST['system'])) {
    $api_key_request = trim($_SERVER['HTTP_API_KEY']);
    if ($api_key_request == $API_KEY) {
        $storage = new ManStorage();
        echo json_encode(array('number of files' => $storage->filesCount(), "size" => $storage->folderSize()));
        http_response_code(200);
        exit();
    } else http_response_code(403);
} else http_response_code(400);
