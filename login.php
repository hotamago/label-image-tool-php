<?php
include_once __DIR__ . "/module/hotavn-database.php";
$database = new HotaVNDatabase();

session_start();
// if (isset($_COOKIE['token'])) {
//     header("Location: index.php");
// }

$isLogin = false;
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $result = $database->getAccountByUsername($username);
    if ($result) {
        $token = $result['id'] . ";" . sha1($result['username']);
        setcookie('token', $token, time() + 24 * 3600, '/');
        header("Location: index.php");
    } else {
        $isLogin = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <?php include_once "compoments/common/meta.php"; ?>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1 class="text-center">Login</h1>
                <form action="login.php" method="post">
                    <div class="form-group
                    <?php
                    if ($isLogin) {
                        echo "has-error";
                    }
                    ?>
                    ">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                        <?php
                        if ($isLogin) {
                            echo "<span class='help-block'>Username is not exist</span>";
                        }
                        ?>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    include_once "layouts/footer.php";
    ?>
</body>

</html>