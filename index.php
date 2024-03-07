<?php
include_once __DIR__ . "/compoments/auth/checker.php";
include_once __DIR__ . "/module/hotavn-database.php";
include_once __DIR__ . "/module/api/api-hota-storage.php";
include_once __DIR__ . "/module/anti-xss.php";
$database = new HotaVNDatabase();
$imgur = new ApiHotaStorage();

$isShowMessage = false;
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
    $isShowMessage = true;
}

?>
<html>

<head>
    <title>HotaVN - CNN IMAGE</title>
    <?php include_once "compoments/common/meta.php"; ?>
</head>

<body>
    <?php
    include_once "layouts/menu.php";
    ?>

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1 class="text-center">Upload ảnh</h1>
                <form action="index.php" method="post" enctype="multipart/form-data">
                    <div class="form-group
                    <?php
                    if ($isShowMessage) {
                        echo "has-error";
                    }
                    ?>
                    ">
                        <label for="image">Ảnh</label>
                        <input type="file" name="images[]" id="images" class="form-control" required multiple>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Upload</button>
                    <br />
                </form>
            </div>
        </div>
    </div>

    <!-- Box show message from server -->
    <div class="container mt-2">
        <div class="row message-box">
            <div class="col">
                <?php
                if ($isShowMessage) {
                    echo "<p>" . $popMessage . "</p>";
                } else {
                    echo "<span class='help-block'>Chọn nhiều ảnh để upload</span>";
                }
                ?>
            </div>
        </div>

        <?php
        include_once "layouts/footer.php";
        ?>
</body>

</html>