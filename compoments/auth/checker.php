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
    setcookie("token", "", time() - 3600);
    header("Location: login.php");
}

// Inferface data
$idUser = $result['id'];
$username = $result['username'];
$curIdVote = $result['curIdVote'];
