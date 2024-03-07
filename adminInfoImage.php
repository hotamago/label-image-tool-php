<?php
include_once __DIR__ . "/compoments/auth/checker.php";
include_once __DIR__ . "/module/hotavn-database.php";
$database = new HotaVNDatabase();

if ($username != "20210751" && $username != "20215402") {
    header("Location: index.php");
    exit();
}

$message = "";
$image = null;
if (isset($_POST['search'])) {
    $nameImage = $_POST['nameImage'];
    $image = $database->getImageByName($nameImage);
    if (!$image) {
        $message = "Không tìm thấy ảnh";
    } else {
        $infoImage = json_decode($image['info'], true);
    }
}

$votes = $database->getVoteByIdImage($image['id']);

$listTypeVote = array("Không đạt chất lượng", "Hồ Gươm", "Hồ Tây", "Tháp rùa", "Cầu Thê Húc", "Bưu Điện", "Vườn Hoa", "chùa trấn quốc", "Đền Quán Thánh", "Khách Sạn", "Công Viên Nước");

?>
<html>

<head>
    <title>Thống kê</title>
    <?php include_once "compoments/common/meta.php"; ?>
</head>

<body>
    <?php include_once "layouts/menu.php"; ?>
    <div class="container mt-5">
        <div class="row mt-5">
            <div class="col-md-12">
                <form action="adminInfoImage.php" method="post">
                    <div class="form-group">
                        <label for="idImage">Name of image</label>
                        <input type="text" name="nameImage" id="nameImage" class="form-control" required>
                    </div>
                    <button type="submit" name="search" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>

        <div class="container">

            <?php
            if (!$image) {
            ?>
                <div class="row mt-1">
                    <div class="col-lg-8 col-md-12 text-center">
                        <p><?php echo $message; ?></p>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <div class="row mt-1">
                    <div class="col-lg-8 col-md-12 text-center">
                        <img src="<?php echo $image['url']; ?>" alt="Image" class="img-thumbnail" style="width: 100%; max-width: 500px;">
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="mt-3">
                            <?php
                            foreach ($listTypeVote as $key => $value) {
                            ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="votecheck-<?php echo $key; ?>" value="<?php echo $key; ?>">
                                    <label class="form-check-label"><?php echo $value . ": " . (array_key_exists('label-' . $key, $infoImage['numVote']) ? $infoImage['numVote']['label-' . $key] : 0); ?></label>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3 message-box">
                    <div class="col">
                        <p>Tổng vote: <?php echo $infoImage['totalVote']; ?></p>
                        <p>MSSV up: <?php echo $database->getAccountById($image['idCollector'])['username'] ?></p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h1 class="text-center">Thống kê vote</h1>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>MSSV</th>
                                    <th>Vote data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($votes as $vote) {
                                    $account = $database->getAccountById($vote['idVoter']);
                                    echo "<tr>";
                                    echo "<td>" . $i . "</td>";
                                    echo "<td>" . $account['username'] . "</td>";
                                    echo "<td>" . $vote['listVote'] . "</td>";
                                    echo "</tr>";
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <?php include_once "layouts/footer.php"; ?>
</body>

</html>