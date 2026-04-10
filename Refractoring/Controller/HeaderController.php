<?php
$isUserLoggedIn = isset($_SESSION["userid"]);
$isAdmin = isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === true;

require_once __DIR__ . "/../Vue/HeaderVue.php";
