<?php
    session_start();
    require "includes/functions.php";

    $sortBy = (isset($_GET["sort"]) && $_GET["sort"] === "score") ? "score" : "name";
    $board = sortLeaderboard(loadLeaderboard(), $sortBy);

    $pageTitle = "Leaderboard";
    include "includes/header.php";
?>