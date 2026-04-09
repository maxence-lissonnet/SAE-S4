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
        $sql = "
            SELECT *
            FROM (
                SELECT
                    'Commande' AS type_transaction,
                    A.nom_article AS element,
                    C.qte_commande AS quantite,
                    C.prix_commande AS montant,
                    C.paiement_commande AS mode_paiement,
                    C.date_commande AS date_transaction,
                    M.nom_membre,
                    M.prenom_membre
                FROM COMMANDE C
                INNER JOIN ARTICLE A ON A.id_article = C.id_article
                INNER JOIN MEMBRE M ON M.id_membre = C.id_membre

                UNION ALL

                SELECT
                    'Adhesion' AS type_transaction,
                    G.nom_grade AS element,
                    1 AS quantite,
                    AD.prix_adhesion AS montant,
                    AD.paiement_adhesion AS mode_paiement,
                    AD.date_adhesion AS date_transaction,
                    M.nom_membre,
                    M.prenom_membre
                FROM ADHESION AD
                INNER JOIN GRADE G ON G.id_grade = AD.id_grade
                INNER JOIN MEMBRE M ON M.id_membre = AD.id_membre

                UNION ALL

                SELECT
                    'Inscription' AS type_transaction,
                    E.nom_evenement AS element,
                    1 AS quantite,
                    I.prix_inscription AS montant,
                    I.paiement_inscription AS mode_paiement,
                    I.date_inscription AS date_transaction,
                    M.nom_membre,
                    M.prenom_membre
                FROM INSCRIPTION I
                INNER JOIN EVENEMENT E ON E.id_evenement = I.id_evenement
                INNER JOIN MEMBRE M ON M.id_membre = I.id_membre
            ) AS historique
            ORDER BY date_transaction DESC
        ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
