<?php
require_once 'cart_class.php';

$boutiqueModel = new BoutiqueModel($db);
$cart = new cart($db);

$filters = [];
$orderBy = "name_asc";
$searchTerm = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['reset'])) {
        $filters = $_POST['category'] ?? [];
        $orderBy = $_POST['sort'] ?? "name_asc";
        $searchTerm = $_POST['search'] ?? "";
    }
}

$products = $boutiqueModel->get_filtered_products($searchTerm, $filters, $orderBy);

require_once __DIR__ . '/../Vue/BoutiqueVue.php';
