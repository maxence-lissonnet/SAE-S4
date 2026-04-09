<?php

class InscriptionModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllInscriptions()
    {
        $stmt = $this->db->query(
            "SELECT
                INSCRIPTION.id_membre,
                INSCRIPTION.id_evenement,
                INSCRIPTION.date_inscription,
                INSCRIPTION.paiement_inscription,
                INSCRIPTION.prix_inscription,
                EVENEMENT.nom_evenement,
                EVENEMENT.date_evenement,
                EVENEMENT.lieu_evenement,
                MEMBRE.nom_membre,
                MEMBRE.prenom_membre,
                MEMBRE.email_membre
            FROM INSCRIPTION
            INNER JOIN EVENEMENT ON INSCRIPTION.id_evenement = EVENEMENT.id_evenement
            INNER JOIN MEMBRE ON INSCRIPTION.id_membre = MEMBRE.id_membre
            ORDER BY EVENEMENT.date_evenement DESC, INSCRIPTION.date_inscription DESC, MEMBRE.nom_membre ASC, MEMBRE.prenom_membre ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
