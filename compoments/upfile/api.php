<?php
include_once __DIR__ . "/../../compoments/auth/checker.php";
include_once __DIR__ . "/../../module/hotavn-database.php";
include_once __DIR__ . "/../../module/api/api-hota-storage.php";
include_once __DIR__ . "/../../module/anti-xss.php";
$database = new HotaVNDatabase();
$imgur = new ApiHotaStorage();

$popMessage = "";
if (isset($_POST['submit'])) {
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
            $popMessage .= "File không hợp lệ, chỉ chấp nhận file ảnh có đuôi jpeg, jpg, png.";
            continue;
        }

        // Size not over 5MB
        if ($images['size'][$i] > 5 * 1024 * 1024) {
            $popMessage .= "File không hợp lệ, chỉ chấp nhận file ảnh có dung lượng nhỏ hơn 5MB.";
            continue;
        }

        // Vaildate with getimagesize
        if (getimagesize($images['tmp_name'][$i]) == false) {
            $popMessage .= "File không hợp lệ, chỉ chấp nhận file ảnh.";
            continue;
        }

        $response = $imgur->uploadImageShorten($image);
        if ($response['success']) {
            $pathEx = explode("/", $response['link']);
            $nameFile = $pathEx[count($pathEx) - 1];
            $database->addImage($response['link'], $nameFile, $idUser, json_encode([
                "totalVote" => 0,
                "numVote" => []
            ]));
            $popMessage .= "Thành công";
        } else {
            $popMessage .= $response['message'];
        }
    }
}

echo $popMessage;
