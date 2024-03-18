<?php
include_once __DIR__ . "/compoments/auth/checker.php";
include_once __DIR__ . "/module/hotavn-database.php";
$database = new HotaVNDatabase();

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

$listTypeVote = array("Không đạt chất lượng", "Hồ Gươm", "Hồ Tây", "Tháp rùa", "Cầu Thê Húc", "Bưu Điện", "Vườn Hoa", "chùa trấn quốc", "Đền Quán Thánh", "Khách Sạn", "Công Viên Nước");
$numVoteAnalytics = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$labelWithMaxVote = 0;

for ($i = 0; $i < count($images); $i++) {
    $info = json_decode($images[$i]['info'], true);
    // $info['numVote'] is an array with key is label-x
    for ($j = 0; $j < count($listTypeVote); $j++) {
        $key = "label-" . $j;
        if (isset($info['numVote'][$key])) {
            $numVoteAnalytics[$j] += $info['numVote'][$key];
        }
    }
}

for ($i = 0; $i < count($numVoteAnalytics); $i++) {
    $labelWithMaxVote = max($labelWithMaxVote, $numVoteAnalytics[$i]);
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
        <!-- Thống kế vote nhãn -->
        <div class="row">
            <?php
            for ($i = 0; $i < count($listTypeVote); $i++) {
                $type = $listTypeVote[$i];
            ?>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <h5 class="text-center"><?php echo $type; ?></h5>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="progress">
                                <div class="progress-bar" style="width:<?php echo $numVoteAnalytics[$i] / $labelWithMaxVote * 100; ?>%"></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <p class="text-center"><?php echo $numVoteAnalytics[$i]; ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
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