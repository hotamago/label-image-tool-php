<?php
include_once __DIR__ . "/compoments/auth/checker.php";
include_once __DIR__ . "/module/hotavn-database.php";
$database = new HotaVNDatabase();

// Thống kê số lượng ảnh đã được up lên hiển thị dưới dạng danh sách
// Thống kê số lượng lượt vote của user

$images = $database->getImageByIdCollector($idUser);
$votes = $database->getVoteByIdVoter($idUser);
?>
<html>

<head>
    <title>Thống kê</title>
    <?php include_once "compoments/common/meta.php"; ?>
    <script>
        let images = <?php echo json_encode($images); ?>;

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
        <!-- Hiện 2 ô số lần lượt là: số lượng vote đã đóng góp, số lượng ảnh đã đóng góp -->
        <div class="row">
            <div class="col-md-6">
                <h3 class="text-center">Số lượng vote đã đóng góp</h3>
                <h4 class="text-center"><?php echo count($votes); ?></h4>
            </div>
            <div class="col-md-6">
                <h3 class="text-center">Số lượng ảnh đã đóng góp</h1>
                    <h4 class="text-center"><?php echo count($images); ?></h4>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center">Thống kê ảnh bạn up</h1>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Link ảnh</th>
                                <th>Số lượt vote</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($images as $image) {
                                $totalVote = json_decode($image['info'], true)['totalVote'];
                                echo "<tr>";
                                echo "<td>" . $i . "</td>";
                                echo "<td id=\"image-" . $i . "\"><button class=\"btn btn-primary\" type=\"button\" onclick=\"showImange(" . $i . ")\">show image</button></td>";
                                echo "<td>" . $totalVote . "</td>";
                                echo "</tr>";
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include_once "layouts/footer.php"; ?>

</html>