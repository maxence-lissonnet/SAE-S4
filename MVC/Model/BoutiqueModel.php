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

        // Recherche par nom
        if (!empty($searchTerm)) {
            $whereClauses[] = "nom_article LIKE ?";
            $params[] = '%' . $searchTerm . '%';
        }

        // Filtres par catégorie
        if (!empty($filters)) {
            $placeholders = implode(", ", array_fill(0, count($filters), "?"));
            $whereClauses[] = "categorie_article IN ($placeholders)";
            $params = array_merge($params, $filters);
        }

        if (!empty($whereClauses)) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        // Gestion du tri
        $sortMapping = [
            "price_asc"  => "prix_article ASC",
            "price_desc" => "prix_article DESC",
            "name_asc"   => "nom_article ASC",
            "name_desc"  => "nom_article DESC"
        ];
        $query .= " ORDER BY " . ($sortMapping[$orderBy] ?? "nom_article ASC");

        // --- CORRECTION ICI : Utilisation du vrai PDO au lieu de select() ---
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
