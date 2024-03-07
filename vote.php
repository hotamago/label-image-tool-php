<?php
include_once __DIR__ . "/compoments/auth/checker.php";
include_once __DIR__ . "/module/hotavn-database.php";
$database = new HotaVNDatabase();

$listTypeVote = array("Không đạt chất lượng", "Hồ Gươm", "Hồ Tây", "Tháp rùa", "Cầu Thê Húc", "Bưu Điện", "Vườn Hoa", "chùa trấn quốc", "Đền Quán Thánh", "Khách Sạn", "Công Viên Nước");
?>
<html>

<head>
    <title>Vote</title>
    <?php include "compoments/common/meta.php"; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        const listTypeVote = <?php echo json_encode($listTypeVote); ?>;
        let curIdImage = -1;

        function getImage() {
            $.ajax({
                url: "compoments/vote/api.php",
                type: "POST",
                data: {
                    "get-image": true
                },
                success: function(response) {
                    console.log(response);
                    var data = JSON.parse(response);
                    if (data.status == "success") {
                        $("#imgShow").attr("src", data.data.url);
                        curIdImage = data.data.id;
                    } else if (data.status == "error") {
                        alert(data.message);
                        $("#imgShow").hide();
                        $("#noImageAvailable").show();
                        curIdImage = -1;
                    }
                }
            });
        }

        function resetRadio() {
            for (var i = 0; i < listTypeVote.length; i++) {
                $("#votecheck-" + i).prop("checked", false);
            }
        }

        $(document).ready(function() {
            getImage();

            $("#vote-btn").click(function() {
                var listVote = [];
                for (var i = 0; i < listTypeVote.length; i++) {
                    if ($("#votecheck-" + i).is(":checked")) {
                        listVote.push(i);
                    }
                }

                if (listVote.length == 0) {
                    alert("Chưa chọn loại vote");
                    return;
                }

                if (curIdImage == -1) {
                    alert("Hiện chưa có ảnh nào để vote, hãy f5 để check xem có ảnh mới không nhé ^_^");
                    return;
                }

                $.ajax({
                    url: "compoments/vote/api.php",
                    type: "POST",
                    data: {
                        "vote": true,
                        "idImage": curIdImage,
                        "listVote": listVote.join(",")
                    },
                    success: function(response) {
                        console.log(response);
                        var data = JSON.parse(response);
                        if (data.status == "success") {
                            alert(data.message);
                            resetRadio();
                            getImage();
                        } else if (data.status == "error") {
                            alert(data.message);
                        }
                    }
                });
            });
        });
    </script>
</head>

<body>
    <?php include "layouts/menu.php"; ?>

    <div class="container">
        <h1 class="text-center">Vote</h1>
        <div class="row mt-1">
            <div class="col-lg-8 col-md-12 text-center">
                <img id="imgShow" src="" alt="Image" class="img-thumbnail" style="width: 100%; max-width: 500px;">
                <p id="noImageAvailable" style="display:none;">Hiện tại chưa có ảnh để vote!</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="mt-3">
                    <form onsubmit="return false;">
                        <!-- Radio checkbox -->
                        <?php
                        foreach ($listTypeVote as $key => $value) {
                        ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="votecheck-<?php echo $key; ?>" value="<?php echo $key; ?>">
                                <label class="form-check-label"><?php echo $value; ?></label>
                            </div>
                        <?php
                        }
                        ?>
                        <button id="vote-btn" class="btn btn-primary mt-2">Vote</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include "layouts/footer.php"; ?>
</body>

</html>