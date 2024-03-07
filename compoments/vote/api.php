<?php
include_once __DIR__ . "/../../compoments/auth/checker.php";
include_once __DIR__ . "/../../module/hotavn-database.php";
$database = new HotaVNDatabase();

if (isset($_POST['vote'])) {
    $idVoter = $idUser;
    $idImage = intval($_POST['idImage']);
    $listVote = explode(",", $_POST['listVote']);
    for ($i = 0; $i < count($listVote); $i++) {
        $listVote[$i] = intval($listVote[$i]);
    }

    // Check exit image
    $image = $database->getImageById($idImage);
    $infoImage = json_decode($image['info'], true);
    if (!$image) {
        echo json_encode(array("status" => "error", "message" => "Not exit image"));
        exit();
    }

    // Check exit vote by idVoter and idImage
    $vote = $database->getVoteByIdVoterAndIdImage($idVoter, $idImage);
    if ($vote) {
        echo json_encode(array("status" => "error", "message" => "Exit vote"));
        exit();
    }

    // Add vote
    $database->addVote($idVoter, $idImage, json_encode($listVote));
    $database->addCurIdVoteById($idUser);

    // Update info image
    $infoImage['totalVote']++;
    for ($i = 0; $i < count($listVote); $i++) {
        $keyLabel = "label-" . $listVote[$i];
        if (!isset($infoImage['numVote'][$keyLabel])) {
            $infoImage['numVote'][$keyLabel] = 0;
        }
        $infoImage['numVote'][$keyLabel]++;
    }
    $database->updateInfoImageById(json_encode($infoImage), $idImage);

    echo json_encode(array("status" => "success", "message" => "Vote success"));
} else if (isset($_POST['get-image'])) {
    $maxIdVote = $database->getMaxId()['maxid'];
    do {
        if ($curIdVote > $maxIdVote) {
            echo json_encode(array("status" => "error", "message" => "Don't have any image to vote :("));
            break;
        }

        $image = $database->getImageById($curIdVote);
        if (!$image) {
            $curIdVote++;
            $database->addCurIdVoteById($idUser);
        } else {
            // Check if image upload by user
            if ($image['idCollector'] == $idUser) {
                $curIdVote++;
                $database->addCurIdVoteById($idUser);
                continue;
            }

            // Check if exit vote by idVoter and idImage
            $vote = $database->getVoteByIdVoterAndIdImage($idUser, $image['id']);
            if ($vote) {
                $curIdVote++;
                $database->addCurIdVoteById($idUser);
                continue;
            }

            echo json_encode(array("status" => "success", "message" => "Get image success", "data" => $image));
            break;
        }
    } while (true);
} else {
    // Show error not exit method
    echo json_encode(array("status" => "error", "message" => "Not exit method"));
}
