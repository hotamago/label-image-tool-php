<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
set_time_limit(120);

include_once __DIR__ . "/../../compoments/auth/checker.php";
include_once __DIR__ . "/../../module/hotavn-database.php";
// include_once __DIR__ . "/../../module/api/api-hota-storage.php";
include_once __DIR__ . '/../../module/hota-storage.php';

$popMessage = "";
if (isset($_POST['submit'])) {
    $database = new HotaVNDatabase();
    // $imgur = new ApiHotaStorage();
    $storage = new HotaStorage();

    $idImage = intval($_POST['id']);
    $image = $database->getImageById($idImage);
    if ($image) {
        // Check image by user
        if ($image['idCollector'] != $idUser) {
            $popMessage .= "<p style=\"color: red;\">Không có quyền xóa file này!</p>";
        } else {
            $nameFile = $image['name'];
            $popMessage .= "<br/>File (" . $nameFile . "): ";
            // $response = $imgur->deleteImage($nameFile);
            $response = $storage->deleteFile($nameFile);
            if ($response['success']) {
                $database->deleteImageById($image['id']);
                $popMessage .= "Thành công";
            } else {
                $popMessage .= "<p style=\"color: red;\">Thất bại</p>";
            }
        }
    } else {
        $popMessage .= "<p style=\"color: red;\">File không tồn tại</p>";
    }
}

echo $popMessage;
