<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'cart_class.php';
require_once __DIR__ . '/../Model/CartModel.php';

$cart = new cart($db);
$cartModel = new CartModel($db);

$json = ['error' => true];
$isAjax = (
    (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
    (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
);

if (isset($_GET['id'])) {
    $productId = (int) $_GET['id'];

    if ($cartModel->productExists($productId)) {
        $cart->add($productId);

        $json['error'] = false;
        $json['total'] = $cart->total();
        $json['count'] = $cart->count();
        $json['message'] = "Le produit a bien ete ajoute a votre panier.";
    } else {
        $json['message'] = "Ce produit n'existe pas.";
    }
} else {
    $json['message'] = "Vous n'avez pas selectionne de produit a ajouter au panier.";
}

if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}

$_SESSION['message'] = $json['message'];
$_SESSION['message_type'] = $json['error'] ? 'error' : 'success';

$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=boutique';
header('Location: ' . $redirect);
exit;
