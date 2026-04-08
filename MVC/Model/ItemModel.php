<?php
class ItemModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAllItems()
    {
        $stmt = $this->db->query("SELECT * FROM ARTICLE");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ARTICLE WHERE id_article = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createEmptyItem()
    {
        // Crée un article par défaut et renvoie son ID (comme attendu par ton JS)
        $stmt = $this->db->prepare("INSERT INTO ARTICLE (nom_article, prix_article, categorie, stock_article, xp_article, reduction_article, image_article) VALUES ('Nouvel article', 1, 'Autre', -1, 10, 1, NULL)");
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateItem($id, $name, $price, $category, $stock, $xp, $reduction)
    {
        // PENSE À VÉRIFIER LE NOM DE LA COLONNE 'categorie' ICI AUSSI
        $stmt = $this->db->prepare("UPDATE ARTICLE SET nom_article = ?, prix_article = ?, categorie = ?, stock_article = ?, xp_article = ?, reduction_article = ? WHERE id_article = ?");
        return $stmt->execute([$name, $price, $category, $stock, $xp, $reduction, $id]);
    }

    public function updateItemImage($id, $filename)
    {
        $stmt = $this->db->prepare("UPDATE ARTICLE SET image_article = ? WHERE id_article = ?");
        return $stmt->execute([$filename, $id]);
    }

    public function deleteItem($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ARTICLE WHERE id_article = ?");
        return $stmt->execute([$id]);
    }
}
