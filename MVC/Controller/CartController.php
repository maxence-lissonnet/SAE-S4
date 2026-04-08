<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once 'cart_class.php';
require_once __DIR__ . '/../Model/CartModel.php';


$cartModel = new CartModel($db);
$cart = new cart($db);

$ids = !empty($_SESSION['cart']) ? array_keys($_SESSION['cart']) : [];
$products = $cartModel->getProductsByIds($ids);

$totalAvecReduc = null;
$reductionPercent = 0;

if (!empty($_SESSION['userid']) && !empty($products)) {
    $reductionPercent = $cartModel->getUserReduction($_SESSION['userid']);

    if ($reductionPercent > 0) {
        $totalAvecReduc = 0;
        $coeff = 1 - ($reductionPercent / 100);

        foreach ($products as $product) {
            $qte = $_SESSION['cart'][$product['id_article']];
            if (!empty($product['reduction_article'])) {
                $totalAvecReduc += ($product['prix_article'] * $qte) * $coeff;
            } else {
                $totalAvecReduc += ($product['prix_article'] * $qte);
            }
        }
    }
}

require_once __DIR__ . "/../Vue/CartVue.php";
