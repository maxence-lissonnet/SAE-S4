<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once 'cart_class.php';
require_once __DIR__ . '/../Model/CartModel.php';


$cart = new cart($db);
$cartModel = new CartModel($db);

$json = array('error' => true);

if (isset($_GET['id'])) {
    $productId = (int)$_GET['id'];

    if ($cartModel->productExists($productId)) {

        $cart->add($productId);

        $json['error'] = false;
        $json['total'] = $cart->total();
        $json['count'] = $cart->count();
        $json['message'] = "Le produit a bien été ajouté à votre panier";
    } else {
        $json['message'] = "Ce produit n'existe pas.";
    }
} else {
    $json['message'] = "Vous n'avez pas sélectionné de produit à ajouter au panier.";
}

header('Content-Type: application/json');
