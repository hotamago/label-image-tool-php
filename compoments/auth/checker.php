<?php
include_once __DIR__ . "/../../module/hotavn-database.php";
// Check login
$database = new HotaVNDatabase();
session_start();

if (!isset($_COOKIE['token'])) {
    header("Location: login.php");
}
$token = $_COOKIE['token'];
$token = explode(";", $token);
$result = $database->getAccountById($token[0]);
if (!$result || sha1($result['username']) != $token[1]) {
    // Remove cookie
    unset($_COOKIE['token']);
    setcookie('token', '', -1);
    header("Location: login.php");
}

// Inferface data
$idUser = $result['id'];
$username = $result['username'];
$curIdVote = $result['curIdVote'];
$curIdVoteAv = $result['curIdVoteAv'];

// Helper function
function checkOrVote($listVote, $ids)
{
    for ($i = 0; $i < count($listVote); $i++) {
        if (in_array($listVote[$i], $ids)) {
            return true;
        }
    }
    return false;
}
function checkOrKey($listVote, $ids)
{
    for ($i = 0; $i < count($ids); $i++) {
        if (array_key_exists($ids[$i], $listVote)) {
            return true;
        }
    }
    return false;
}
function getDiffLabel($listVote1, $listVote2)
{
    $diff = array_diff($listVote1, $listVote2);
    return array_values($diff);
}
