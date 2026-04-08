<?php
// index.php
session_start();
require_once __DIR__ . "/Model/Database.php";
require_once __DIR__ . "/Model/AccueilModel.php";
require_once __DIR__ . "/Model/EvenementModel.php";
require_once __DIR__ . "/Model/ActualiteModel.php";
require_once __DIR__ . '/Model/BoutiqueModel.php';


$database = new DB();
$db = $database->connect();

$isLoggedIn = isset($_SESSION["userid"]);
$isAdmin = isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === true;

$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

switch ($page) {
    case 'accueil':
        require_once __DIR__ . "/Controller/AccueilController.php";
        break;

    case 'events':
        require_once __DIR__ . "/Controller/EvenementController.php";
        break;

    case 'event_detail':
        require_once __DIR__ . "/Controller/EvenementDetailController.php";
        break;

    case 'event_inscription':
        require_once __DIR__ . "/Controller/EvenenementInscriptionController.php";
        break;

    case 'actualite':
        require_once __DIR__ . "/Controller/ActualiteController.php";
        break;

    case 'actualiteDetail':
        require_once __DIR__ . "/Controller/ActualiteDetailController.php";
        break;

    case 'boutique':
        require_once __DIR__ . "/Controller/BoutiqueController.php";
        break;

    case 'cart':
        require_once __DIR__ . "/Controller/CartController.php";
        break;

    case 'add_cart':
        require_once __DIR__ . "/Controller/BoutiqueController.php";
        require_once __DIR__ . "/Controller/add_Cart.php";
        break;

    case 'login':
        require_once __DIR__ . "/Controller/LoginController.php";
        break;

    case 'about':
        require_once __DIR__ . "/Controller/AboutController.php";
        break;

    case 'grade':
        require_once __DIR__ . "/Controller/GradeController.php";
        break;

    default:
        http_response_code(404);
        echo "Page non trouvée";
        break;
}
