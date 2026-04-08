<?php
class HistoriqueModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAllPurchases()
    {
        // On récupère tout l'historique en joignant la table MEMBRE pour avoir le nom et prénom
        $sql = "
            SELECT H.type_transaction, H.element, H.quantite, H.montant, H.mode_paiement, H.date_transaction, M.nom_membre, M.prenom_membre 
            FROM HISTORIQUE_COMPLET H
            JOIN MEMBRE M ON H.id_membre = M.id_membre
            ORDER BY H.date_transaction DESC
        ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
