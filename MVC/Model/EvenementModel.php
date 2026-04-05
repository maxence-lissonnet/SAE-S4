<?php

class EvenementModel
{
    private $db;
    private $date;
    private $sql_date;

    public function __construct($db)
    {
        $this->db = $db;
        $this->date = getdate();
        $this->sql_date = $this->date["year"] . "-" . $this->date["mon"] . "-" . $this->date["mday"];
    }

    public function get_evenement_passe($nbShow)
    {

        $stmt = $this->db->prepare("SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement < CURDATE() ORDER BY date_evenement DESC LIMIT ?;");
        $stmt->execute([$nbShow]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_evenement_a_venir()
    {
        $stmt = $this->db->prepare("SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement >= CURDATE() ORDER BY date_evenement ASC ;");
        $stmt->execute([]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_derniers_evenements()
    {
        $stmt = $this->db->prepare("SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement >= ? ORDER BY date_evenement ASC LIMIT 2;");
        $stmt->execute([$this->sql_date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_place_disponible($eventid)
    {
        $stmt = $this->db->prepare("SELECT (EVENEMENT.places_evenement - (SELECT COUNT(*) FROM INSCRIPTION WHERE INSCRIPTION.id_evenement = EVENEMENT.id_evenement)) > 0 AS isPlaceDisponible FROM EVENEMENT WHERE EVENEMENT.id_evenement = ? ;");
        $stmt->execute([$eventid]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['isPlaceDisponible'];
    }

    public function get_inscription_status($eventid, $userid)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) > 0 AS isInscrit FROM INSCRIPTION WHERE id_evenement = ? AND id_membre = ?;");
        $stmt->execute([$eventid, $userid]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['isInscrit'];
    }


    public function get_event_by_id($eventid)
    {
        $stmt = $this->db->prepare("SELECT nom_evenement, xp_evenement, places_evenement, prix_evenement, reductions_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE id_evenement = ?");
        $stmt->execute([$eventid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_event_medias($eventid, $limit, $userid = null)
    {
        if ($userid) {
            $stmt = $this->db->prepare("SELECT url_media FROM MEDIA WHERE id_membre = ? AND id_evenement = ? ORDER BY date_media ASC LIMIT ?");
            $stmt->execute([$userid, $eventid, $limit]);
        } else {
            $stmt = $this->db->prepare("SELECT url_media FROM MEDIA WHERE id_evenement = ? ORDER BY date_media ASC LIMIT ?");
            $stmt->execute([$eventid, $limit]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_user_reduction($userid)
    {
        $stmt = $this->db->prepare(
            "SELECT reduction_grade FROM ADHESION 
         JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade
         WHERE id_membre = ? AND reduction_grade > 0 
         ORDER BY ADHESION.date_adhesion DESC LIMIT 1"
        );
        $stmt->execute([$userid]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? (1 - ($res["reduction_grade"] / 100)) : 1;
    }

    public function register_user_to_event($userid, $eventid, $final_price)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO INSCRIPTION (id_membre, id_evenement, date_inscription, paiement_inscription, prix_inscription)
         VALUES (?, ?, NOW(), 'WEB', ?)"
        );
        $stmt->execute([$userid, $eventid, $final_price]);

        $event = $this->get_event_by_id($eventid);
        $xp = $event['xp_evenement'];

        $stmt = $this->db->prepare("UPDATE MEMBRE SET xp_membre = xp_membre + ? WHERE id_membre = ?");
        $stmt->execute([$xp, $userid]);
    }
}
