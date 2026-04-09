<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

$defaultProductImage = 'Asset/image/boutique.png';
$apiFilesDirectory = __DIR__ . '/../API/files/';

$legacyImageMap = [
    'chocolat.jpg' => 'boutique/barre-chocolat.png',
    'bueno.jpg' => 'boutique/bueno.png',
    '_bueno_white.jpg' => 'boutique/bueno-white.jpg',
    'coca.jpg' => 'boutique/canette-coca.jpg',
    'coca_cherry.jpg' => 'boutique/coca-cherry.jpg',
    '_cafe.jpg' => 'boutique/formule-cafe.png',
    'jus_orange.jpg' => 'boutique/jus-orange.jpg',
    'lipton.jpg' => 'boutique/lipton-ice-tea.jpg',
    'chips.jpg' => 'boutique/snack-chips.jpg',
    'volvic.jpg' => 'boutique/volvic.jpg',
];

$productImageMap = [
    'barre de chocolat' => $legacyImageMap['chocolat.jpg'],
    'bueno' => $legacyImageMap['bueno.jpg'],
    'bueno white' => $legacyImageMap['_bueno_white.jpg'],
    'canette de coca' => $legacyImageMap['coca.jpg'],
    'coca cherry' => $legacyImageMap['coca_cherry.jpg'],
    'formule cafe' => $legacyImageMap['_cafe.jpg'],
    'jus d orange' => $legacyImageMap['jus_orange.jpg'],
    'lipton ice tea' => $legacyImageMap['lipton.jpg'],
    'snack chips' => $legacyImageMap['chips.jpg'],
    'volvic' => $legacyImageMap['volvic.jpg'],
];

$apiFileExists = static function ($relativePath) use ($apiFilesDirectory) {
    $relativePath = ltrim(str_replace('\\', '/', (string) $relativePath), '/');
    if ($relativePath === '') {
        return false;
    }

    return is_file($apiFilesDirectory . str_replace('/', DIRECTORY_SEPARATOR, $relativePath));
};

$toApiFileUrl = static function ($relativePath) {
    $relativePath = ltrim(str_replace('\\', '/', (string) $relativePath), '/');
    return 'API/files/' . str_replace('%2F', '/', rawurlencode($relativePath));
};

$resolveProductImage = static function ($imagePath, $productName) use ($defaultProductImage, $legacyImageMap, $productImageMap, $apiFileExists, $toApiFileUrl) {
    $imagePath = trim((string) $imagePath);
    $productName = strtolower(trim((string) $productName));

    if ($imagePath !== '') {
        $parsedPath = parse_url($imagePath, PHP_URL_PATH);
        $basename = $parsedPath ? basename($parsedPath) : basename($imagePath);

        if ($basename !== '' && $apiFileExists($basename)) {
            return $toApiFileUrl($basename);
        }

        if (!preg_match('#^https?://#i', $imagePath) && $apiFileExists($imagePath)) {
            return $toApiFileUrl($imagePath);
        }

        if ($basename !== '' && isset($legacyImageMap[$basename]) && $apiFileExists($legacyImageMap[$basename])) {
            return $toApiFileUrl($legacyImageMap[$basename]);
        }
    }

    if (isset($productImageMap[$productName]) && $apiFileExists($productImageMap[$productName])) {
        return $toApiFileUrl($productImageMap[$productName]);
    }

    return $defaultProductImage;
};

$products = array_map(
    static function ($product) use ($resolveProductImage) {
        $product['resolved_image_article'] = $resolveProductImage(
            $product['image_article'] ?? null,
            $product['nom_article'] ?? null
        );
        return $product;
    },
    $products
);

require_once __DIR__ . '/../Vue/CartVue.php';
