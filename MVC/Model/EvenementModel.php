<?php

class EvenementModel
{
    private $db;
    private $date;
    private $sql_date;
    private $eventColumns = null;

    public function __construct($db)
    {
        $this->db = $db;
        $this->date = getdate();
        $this->sql_date = $this->date["year"] . "-" . $this->date["mon"] . "-" . $this->date["mday"];
    }

    private function getEventColumns()
    {
        if ($this->eventColumns !== null) {
            return $this->eventColumns;
        }

        $stmt = $this->db->query("SHOW COLUMNS FROM EVENEMENT");
        $this->eventColumns = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), "Field");

        return $this->eventColumns;
    }

    private function hasEventColumn($column)
    {
        return in_array($column, $this->getEventColumns(), true);
    }

    public function get_evenement_passe($nbShow)
    {
        $stmt = $this->db->prepare("SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement < CURDATE() ORDER BY date_evenement DESC LIMIT ?;");
        $stmt->execute([$nbShow]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_evenement_a_venir()
    {
        $stmt = $this->db->prepare("SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement >= CURDATE() ORDER BY date_evenement DESC;");
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
        $stmt = $this->db->prepare("SELECT CASE WHEN EVENEMENT.places_evenement < 0 THEN 1 ELSE (EVENEMENT.places_evenement - (SELECT COUNT(*) FROM INSCRIPTION WHERE INSCRIPTION.id_evenement = EVENEMENT.id_evenement)) > 0 END AS isPlaceDisponible FROM EVENEMENT WHERE EVENEMENT.id_evenement = ?;");
        $stmt->execute([$eventid]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC)["isPlaceDisponible"];
    }

    public function get_inscription_status($eventid, $userid)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) > 0 AS isInscrit FROM INSCRIPTION WHERE id_evenement = ? AND id_membre = ?;");
        $stmt->execute([$eventid, $userid]);
        return $stmt->fetch(PDO::FETCH_ASSOC)["isInscrit"];
    }

    public function get_event_by_id($eventid)
    {
        $fields = [
            "nom_evenement",
            "xp_evenement",
            "places_evenement",
            "prix_evenement",
            "reductions_evenement",
            "lieu_evenement",
            "date_evenement",
        ];

        $fields[] = $this->hasEventColumn("image_evenement")
            ? "image_evenement"
            : "NULL AS image_evenement";

        $fields[] = $this->hasEventColumn("description_evenement")
            ? "description_evenement"
            : "'' AS description_evenement";

        $stmt = $this->db->prepare("SELECT " . implode(", ", $fields) . " FROM EVENEMENT WHERE id_evenement = ?");
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

    private function normalizeEventPaymentMethod($paymentMethod)
    {
        return match ($paymentMethod) {
            'carte_credit' => 'carte_credit',
            'paypal' => 'paypal',
            'especes' => 'especes',
            default => 'web',
        };
    }

    public function register_user_to_event($userid, $eventid, $final_price, $paymentMethod = 'web')
    {
        $paymentMethod = $this->normalizeEventPaymentMethod($paymentMethod);
        $startedTransaction = !$this->db->inTransaction();

        if ($startedTransaction) {
            $this->db->beginTransaction();
        }

        try {
            $stmt = $this->db->prepare(
                "INSERT INTO INSCRIPTION (id_membre, id_evenement, date_inscription, paiement_inscription, prix_inscription)
             VALUES (?, ?, NOW(), ?, ?)"
            );
            $stmt->execute([$userid, $eventid, $paymentMethod, $final_price]);

            $event = $this->get_event_by_id($eventid);
            $xp = $event["xp_evenement"];

            $stmt = $this->db->prepare("UPDATE MEMBRE SET xp_membre = xp_membre + ? WHERE id_membre = ?");
            $stmt->execute([$xp, $userid]);

            if ($startedTransaction) {
                $this->db->commit();
            }
        } catch (Throwable $e) {
            if ($startedTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function unregister_user_from_event($userid, $eventid)
    {
        $startedTransaction = !$this->db->inTransaction();

        if ($startedTransaction) {
            $this->db->beginTransaction();
        }

        try {
            $stmt = $this->db->prepare("SELECT id_membre FROM INSCRIPTION WHERE id_evenement = ? AND id_membre = ?");
            $stmt->execute([$eventid, $userid]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                throw new RuntimeException("Inscription introuvable.");
            }

            $event = $this->get_event_by_id($eventid);
            if (!$event) {
                throw new RuntimeException("Evenement introuvable.");
            }

            $stmt = $this->db->prepare("DELETE FROM INSCRIPTION WHERE id_evenement = ? AND id_membre = ?");
            $stmt->execute([$eventid, $userid]);

            $xp = max(0, (int) ($event["xp_evenement"] ?? 0));
            if ($xp > 0) {
                $stmt = $this->db->prepare("UPDATE MEMBRE SET xp_membre = GREATEST(xp_membre - ?, 0) WHERE id_membre = ?");
                $stmt->execute([$xp, $userid]);
            }

            if ($startedTransaction) {
                $this->db->commit();
            }

            return true;
        } catch (Throwable $e) {
            if ($startedTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function getAllEvents()
    {
        $stmt = $this->db->query("SELECT * FROM EVENEMENT ORDER BY date_evenement DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEmptyEvent()
    {
        $columns = ["nom_evenement", "xp_evenement", "places_evenement", "prix_evenement", "lieu_evenement", "date_evenement", "reductions_evenement"];
        $placeholders = ["?", "?", "?", "?", "?", "NOW()", "?"];
        $params = ["Nouvel evenement", 10, -1, 0, "", 1];

        if ($this->hasEventColumn("description_evenement")) {
            array_splice($columns, 1, 0, ["description_evenement"]);
            array_splice($placeholders, 1, 0, ["?"]);
            array_splice($params, 1, 0, [""]);
        }

        $stmt = $this->db->prepare(
            "INSERT INTO EVENEMENT (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")"
        );
        $stmt->execute($params);
        return $this->db->lastInsertId();
    }

    public function updateEvent($id, $nom, $desc, $xp, $places, $prix, $lieu, $date, $reductions)
    {
        $assignments = [
            "nom_evenement = ?",
            "xp_evenement = ?",
            "places_evenement = ?",
            "prix_evenement = ?",
            "lieu_evenement = ?",
            "date_evenement = ?",
            "reductions_evenement = ?",
        ];
        $params = [$nom, $xp, $places, $prix, $lieu, $date, $reductions];

        if ($this->hasEventColumn("description_evenement")) {
            array_splice($assignments, 1, 0, ["description_evenement = ?"]);
            array_splice($params, 1, 0, [$desc]);
        }

        $params[] = $id;
        $stmt = $this->db->prepare("UPDATE EVENEMENT SET " . implode(", ", $assignments) . " WHERE id_evenement = ?");
        return $stmt->execute($params);
    }

    public function updateEventImage($id, $filename)
    {
        if (!$this->hasEventColumn("image_evenement")) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE EVENEMENT SET image_evenement = ? WHERE id_evenement = ?");
        return $stmt->execute([$filename, $id]);
    }

    public function deleteEvent($id)
    {
        $stmt = $this->db->prepare("DELETE FROM EVENEMENT WHERE id_evenement = ?");
        return $stmt->execute([$id]);
    }

    public function deleteItemsByEventId($eventId)
    {
        $stmt = $this->db->prepare("DELETE FROM ARTICLE WHERE id_evenement = ?");
        return $stmt->execute([$eventId]);
    }
}
