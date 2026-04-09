<?php
class BoutiqueModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_filtered_products($searchTerm, $filters, $orderBy)
    {
        $query = "SELECT * FROM ARTICLE";
        $whereClauses = ["deleted = false"];
        $params = [];

        if (!empty($searchTerm)) {
            $whereClauses[] = "LOWER(nom_article) LIKE ?";
            $params[] = '%' . strtolower($searchTerm) . '%';
        }

        if (!empty($filters)) {
            $placeholders = implode(", ", array_fill(0, count($filters), "?"));
            $whereClauses[] = "categorie_article IN ($placeholders)";
            $params = array_merge($params, $filters);
        }

        if (!empty($whereClauses)) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $sortMapping = [
            "price_asc"  => "prix_article ASC",
            "price_desc" => "prix_article DESC",
            "name_asc"   => "nom_article ASC",
            "name_desc"  => "nom_article DESC"
        ];
        $query .= " ORDER BY " . ($sortMapping[$orderBy] ?? "nom_article ASC");

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_categories()
    {
        $stmt = $this->db->query("SELECT DISTINCT categorie_article FROM ARTICLE WHERE deleted = false AND categorie_article IS NOT NULL AND categorie_article != '' AND categorie_article != 'Pas de categorie'");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
