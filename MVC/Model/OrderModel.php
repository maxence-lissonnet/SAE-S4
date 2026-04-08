<?php
class OrderModel
{
    private $db;

    public function __construct($pdo)
    {
        // On stocke l'objet PDO
        $this->db = $pdo;
    }

    /**
     * Récupère les détails d'une liste d'articles à partir de leurs IDs
     */
    public function getArticlesByIds(array $product_ids)
    {
        if (empty($product_ids)) return [];

        // On crée autant de "?" qu'il y a d'articles dans le panier (ex: "?, ?, ?")
        $placeholders = implode(",", array_fill(0, count($product_ids), "?"));

        $query = "SELECT * FROM ARTICLE WHERE id_article IN ($placeholders)";
        $stmt = $this->db->prepare($query);

        // On exécute la requête en lui passant directement le tableau d'IDs
        $stmt->execute($product_ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si l'utilisateur a un grade et retourne le pourcentage de réduction
     */
    public function getUserDiscount($userId)
    {
        $stmt = $this->db->prepare("
            SELECT reduction_grade FROM ADHESION 
            INNER JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade 
            WHERE ADHESION.id_membre = ? AND reduction_grade > 0
        ");
        $stmt->execute([$userId]);
        $adherant = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($adherant)) {
            return floatval($adherant["reduction_grade"]);
        }
        return 0; // Pas de réduction
    }

    /**
     * Enregistre une ligne de commande via la procédure stockée
     */
    public function createOrderLine($userId, $productId, $quantity, $paymentMode)
    {
        $stmt = $this->db->prepare("CALL achat_article(?, ?, ?, ?)");
        return $stmt->execute([$userId, $productId, $quantity, $paymentMode]);
    }
}
