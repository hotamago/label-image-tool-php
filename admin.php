<?php
include_once __DIR__ . "/compoments/auth/checker.php";
include_once __DIR__ . "/module/hotavn-database.php";
$database = new HotaVNDatabase();

if ($username != "20210751" && $username != "20215402") {
    header("Location: index.php");
    exit();
}

$images = $database->getAllImage();
$accounts = $database->getAllAccounts();

if (isset($_POST['download_label'])) {
    $file = fopen("label.txt", "w");
    foreach ($images as $image) {
        $info = json_decode($image['info'], true);
        $label = json_encode($info['numVote']);
        fwrite($file, $image['name'] . " " . $label . " " . $info['totalVote'] . "\n");
    }
    fclose($file);
    header("Location: label.txt");
    exit();
}
?>
<html>

<head>
    <title>Thống kê</title>
    <?php include_once "compoments/common/meta.php"; ?>
    <script>
        function showImange(id) {
            let image = document.getElementById("image-" + id);
            let link = images[id - 1]['url'];
            image.innerHTML = "<img width=\"100px\" src=\"" + link + "\" />";
        }
    </script>
</head>

<body>
    <?php include_once "layouts/menu.php"; ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h3 class="text-center">Tổng số ảnh</h3>
                <h4 class="text-center"><?php echo count($images); ?></h4>
            </div>
            <div class="col-md-6">
                <h3 class="text-center">Số tài khoản</h1>
                    <h4 class="text-center"><?php echo count($accounts); ?></h4>
            </div>
        </div>
        <!-- Nút tải nhãn -->
        <div class="row mt-5">
            <div class="col-md-12">
                <form action="admin.php" method="post">
                    <button type="submit" name="download_label" class="btn btn-primary">Download label</button>
                </form>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center">Thống kê tài khoản</h1>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Username</th>
                                <th>Số ảnh up</th>
                                <th>Số ảnh vote</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($accounts as $account) {
                                $images_vote = $database->getVoteByIdVoter($account['id']);
                                $images_account = $database->getImageByIdCollector($account['id']);
                                echo "<tr>";
                                echo "<td>" . $account['id'] . "</td>";
                                echo "<td>" . $account['username'] . "</td>";
                                echo "<td>" . count($images_account) . "</td>";
                                echo "<td>" . count($images_vote) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include_once "layouts/footer.php"; ?>

</html>