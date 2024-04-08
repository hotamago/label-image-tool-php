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
        // Check if exit vote 0 or 1 or 2
        $listVoteTemp = json_decode($vote['listVote'], true);
        if (checkOrVote($listVoteTemp, [0, 1, 2, 3, 4, 7, 8])) {
            echo json_encode(array("status" => "error", "message" => "Exit vote"));
            exit();
        }

        // Update vote
        $database->updateListVoteById(json_encode($listVote), $vote['id']);

        // Update info image
        $diff = getDiffLabel($listVote, $listVoteTemp);
        for ($i = 0; $i < count($diff); $i++) {
            $keyLabel = "label-" . $diff[$i];
            if (!isset($infoImage['numVote'][$keyLabel])) {
                $infoImage['numVote'][$keyLabel] = 0;
            }
            $infoImage['numVote'][$keyLabel]++;
        }

        $diffDel = getDiffLabel($listVoteTemp, $listVote);
        for ($i = 0; $i < count($diffDel); $i++) {
            $keyLabel = "label-" . $diffDel[$i];
            $infoImage['numVote'][$keyLabel]--;
            if ($infoImage['numVote'][$keyLabel] == 0) {
                unset($infoImage['numVote'][$keyLabel]);
            }
        }
    } else {
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

        $image = $database->getImageByIdAndMinId($curIdVote);
        if (!$image) {
            $curIdVote++;
            $database->addCurIdVoteById($idUser);
        } else {
            // Check if image upload by user
            if ($image['idCollector'] == $idUser) {
                $curIdVote = $image['id'] + 1;
                $database->updateCurIdVoteById($curIdVote, $idUser);
                continue;
            }

            // Check if exit vote by idVoter and idImage
            $vote = $database->getVoteByIdVoterAndIdImage($idUser, $image['id']);
            if ($vote) {
                $curIdVote = $image['id'] + 1;
                $database->updateCurIdVoteById($curIdVote, $idUser);
                continue;
            }

            $imageCollector = $database->getAccountById($image['idCollector']);

            echo json_encode(array("status" => "success", "message" => "Get image success", "data" => $image, "username" => $imageCollector['username']));
            break;
        }
    } while (true);
} else if (isset($_POST['get-image-av'])) {
    $maxIdVote = $database->getMaxId()['maxid'];
    do {
        if ($curIdVoteAv > $maxIdVote || $curIdVoteAv > $curIdVote) {
            echo json_encode(array("status" => "error", "message" => "Don't have any image to vote :("));
            break;
        }

        $image = $database->getImageByIdAndMinId($curIdVoteAv);
        if (!$image) {
            $curIdVoteAv++;
            $database->updateCurIdVoteAvById($curIdVoteAv, $idUser);
        } else {
            // Check if image upload by user
            if ($image['idCollector'] == $idUser) {
                $curIdVoteAv = $image['id'] + 1;
                $database->updateCurIdVoteAvById($curIdVoteAv, $idUser);
                continue;
            }

            // Check if exit vote by idVoter and idImage
            $vote = $database->getVoteByIdVoterAndIdImage($idUser, $image['id']);
            if (!$vote) {
                echo json_encode(array("status" => "error", "message" => "Something went wrong, contact to admin to fix bug :("));
                break;
            }

            // Check if exit vote 0 or 1 or 2
            $listVote = json_decode($vote['listVote'], true);
            if (checkOrVote($listVote, [0, 1, 2, 3, 4, 7, 8])) {
                $curIdVoteAv = $image['id'] + 1;
                $database->updateCurIdVoteAvById($curIdVoteAv, $idUser);
                continue;
            }

            $imageCollector = $database->getAccountById($image['idCollector']);

            echo json_encode(array("status" => "success", "message" => "Get image success", "data" => $image, "username" => $imageCollector['username'], "listVote" => $listVote));
            break;
        }
    } while (true);
} else {
    // Show error not exit method
    echo json_encode(array("status" => "error", "message" => "Not exit method"));
}
