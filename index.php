<?php
include_once __DIR__ . "/compoments/auth/checker.php";
include_once __DIR__ . "/module/hotavn-database.php";
include_once __DIR__ . "/module/api/api-hota-storage.php";
$database = new HotaVNDatabase();

?>
<html>

<head>
    <title>HotaVN - CNN IMAGE</title>
    <?php include_once "compoments/common/meta.php"; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        const batchSize = 3;
        let totalImage = 0;
        let numImageActivate = 0;
        let files = [];

        function confirnExitTab() {
            return 'Are you sure you want to quit? Your images still uploading!';
        }

        $(document).ready(function() {
            $("#upload").click(async function() {
                if (numImageActivate > 0) return;
                $("#showLoading").show();
                $("#displayMessageBox").html("");
                files = $('#images')[0].files;

                window.onbeforeunload = confirnExitTab;

                numImageActivate = Math.floor(files.length / batchSize) + (files.length % batchSize == 0 ? 0 : 1);
                totalImage = numImageActivate;
                $("#numImageTotal").html(totalImage);
                $("#numImageDone").html(0);

                for (let i = 0; i < files.length; i += batchSize) {
                    let formData = new FormData();

                    for (let j = i; j < i + batchSize && j < files.length; j++) {
                        formData.append('images[]', files[j]);
                    }
                    formData.append('submit', true);

                    await $.ajax({
                        url: 'compoments/upfile/api.php',
                        method: 'post',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $("#displayMessageBox").html($("#displayMessageBox").html() + response);
                            numImageActivate--;
                            $("#numImageDone").html(totalImage - numImageActivate);
                            if (numImageActivate == 0) {
                                $("#showLoading").hide();
                                // Clear input file
                                $('#images').val("");
                                window.onbeforeunload = null;
                            }
                        }
                    });
                }
            });
        });
    </script>
</head>

<body>
    <?php
    include_once "layouts/menu.php";
    ?>

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1 class="text-center">Upload ảnh</h1>
                <form onsubmit="return false;">
                    <div class="form-group">
                        <label for="image">Ảnh</label>
                        <input type="file" name="images[]" id="images" class="form-control" required multiple accept=".jpg,.jpeg,.png">
                    </div>
                    <button type=" button" id="upload" class="btn btn-primary">Upload</button>
                    <br />
                </form>
            </div>
        </div>
    </div>

    <!-- Box show message from server -->
    <div class="container mt-2 message-box">
        <div class="row" id="showLoading" style="display:none;">
            <div class="col">
                <div class="spinner-border text-primary"></div>
                <button type="button" class="btn btn-primary">
                    Uploading <span class="badge bg-secondary" id="numImageDone">0</span> / <span class="badge bg-secondary" id="numImageTotal">0</span>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col" id="displayMessageBox">
                <span class='help-block'>Chọn nhiều ảnh để upload</span>
            </div>
        </div>

        <?php
        include_once "layouts/footer.php";
        ?>
</body>

</html>