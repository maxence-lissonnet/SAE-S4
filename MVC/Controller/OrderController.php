<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// 1. Sécurité
if (!isset($_SESSION["userid"])) {
    header("Location: index.php?page=login");
    exit();
}
if (empty($_SESSION['cart'])) {
    header("Location: index.php?page=cart");
    exit();
}

require_once __DIR__ . '/../Model/OrderModel.php';

// Initialisation (en supposant que $db est créé dans index.php, sinon on l'instancie)
if (!isset($db)) {
    $db_obj = new DB();
    $db = $db_obj; // Adaptable selon ton index.php
}

$orderModel = new OrderModel($db);

$userid = $_SESSION["userid"];
$cart = $_SESSION['cart'];
$product_ids = array_keys($cart);

// 2. Préparation des données (Articles et Réductions)
$products = $orderModel->getArticlesByIds($product_ids);
$reductionGrade = $orderModel->getUserDiscount($userid);

$hasDiscount = ($reductionGrade > 0);
$user_reduction_multiplier = 1 - ($reductionGrade / 100);

$cart_items = [];
$total = 0;
$totalWithReduc = 0;

// 3. Calculs et vérification des stocks
foreach ($products as $product) {
    $pId = $product['id_article'];
    $qtyInCart = $cart[$pId];

    // Sécurité : on limite la quantité au stock disponible
    if ($product['stock_article'] > 0 && $qtyInCart > $product['stock_article']) {
        $qtyInCart = $product['stock_article'];
        $_SESSION['cart'][$pId] = $qtyInCart; // Mise à jour de la session
    }

    $cart_items[$pId] = [
        'nom_article' => $product['nom_article'],
        'prix_article' => $product['prix_article'],
        'quantite' => $qtyInCart,
    ];

    $lineTotal = $product['prix_article'] * $qtyInCart;
    $total += $lineTotal;

    // Calcul de la réduction (seulement si l'article est éligible)
    if ($hasDiscount && !empty($product['reduction_article'])) {
        $totalWithReduc += $lineTotal * $user_reduction_multiplier;
    } else {
        $totalWithReduc += $lineTotal;
    }
}

// 4. Traitement du paiement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mode_paiement'])) {
    $mode_paiement = htmlspecialchars($_POST['mode_paiement']);

    // On boucle sur le panier final recalculé pour lancer la procédure stockée
    foreach ($cart_items as $product_id => $item) {
        $orderModel->createOrderLine($userid, $product_id, $item['quantite'], $mode_paiement);
    }

    // On vide le panier et on redirige avec un message de succès
    $_SESSION['cart'] = [];
    $_SESSION['message'] = "Commande réalisée avec succès !";
    $_SESSION['message_type'] = "success";

    header("Location: index.php?page=cart");
    exit();
}

// 5. Appel de la Vue
require_once __DIR__ . '/../Vue/OrderVue.php';
