<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
set_time_limit(120);

include_once __DIR__ . "/../../compoments/auth/checker.php";
include_once __DIR__ . "/../../module/hotavn-database.php";
include_once __DIR__ . "/../../module/anti-xss.php";
// include_once __DIR__ . "/../../module/api/api-hota-storage.php";
include_once __DIR__ . '/../../module/hota-storage.php';

$popMessage = "";
if (isset($_POST['submit'])) {
    $database = new HotaVNDatabase();
    // $imgur = new ApiHotaStorage();
    $storage = new HotaStorage();

    $images = $_FILES['images'];
    // print_r($images);
    $count = count($images['name']);
    for ($i = 0; $i < $count; $i++) {
        $image = file_get_contents($images['tmp_name'][$i]);
        $nameImage = $images['name'][$i];

        $popMessage .= "<br/>File thứ " . $i . " (" . HotaAntiXss::htmlentities($nameImage) . "): ";

        // Vaildate image
        $allowed =  array('jpeg', 'jpg', "png");
        $ext = strtolower(pathinfo($nameImage, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $popMessage .= "<p style=\"color: red;\">File không hợp lệ, chỉ chấp nhận file ảnh có đuôi jpeg, jpg, png.</p>";
            continue;
        }

        // Size not over 5MB
        if ($images['size'][$i] > 5 * 1024 * 1024) {
            $popMessage .= "<p style=\"color: red;\">File không hợp lệ, chỉ chấp nhận file ảnh có dung lượng nhỏ hơn 5MB.</p>";
            continue;
        }

        // Vaildate with getimagesize
        if (getimagesize($images['tmp_name'][$i]) == false) {
            $popMessage .= "<p style=\"color: red;\">File không hợp lệ, chỉ chấp nhận file ảnh.</p>";
            continue;
        }

        // $response = $imgur->uploadImageShorten($image);
        $response = $storage->uploadFile($image, "jpg");
        if ($response['success']) {
            $response['link'] = $response['data']['link'];
        }
        if ($response['success']) {
            $pathEx = explode("/", $response['link']);
            $nameFile = $pathEx[count($pathEx) - 1];
            $database->addImage($response['link'], $nameFile, $idUser, json_encode([
                "totalVote" => 0,
                "numVote" => []
            ]));
            $popMessage .= "Thành công";
        } else {
            $popMessage .= "<p style=\"color: red;\">Lỗi từ hệ thống quản lý file: " . $response['message'] . "</p>";
        }
    }
}

echo $popMessage;
