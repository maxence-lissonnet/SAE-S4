<?php
class CartModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getProductsByIds($ids)
    {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("SELECT * FROM ARTICLE WHERE id_article IN ($placeholders)");
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }

    public function getUserReduction($userId)
    {
        $stmt = $this->db->prepare("
            SELECT reduction_grade FROM ADHESION 
            INNER JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade 
            WHERE ADHESION.id_membre = ? AND reduction_grade > 0
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ? floatval($result['reduction_grade']) : 0;
    }

    public function productExists($id_article)
    {
        $stmt = $this->db->prepare("SELECT id_article FROM ARTICLE WHERE id_article = ?");
        $stmt->execute([$id_article]);
        // Retourne true si l'article existe, false sinon
        return $stmt->fetch() !== false;
    }
}
