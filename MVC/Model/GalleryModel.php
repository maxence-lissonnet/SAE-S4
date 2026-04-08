<?php
class GalleryModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }


    public function getEventName($eventId)
    {
        $stmt = $this->db->prepare("SELECT nom_evenement FROM EVENEMENT WHERE id_evenement = ?");
        $stmt->execute([$eventId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nom_evenement'] : 'Événement inconnu';
    }


    public function getUserEventMedia($userId, $eventId, $limit)
    {
        $stmt = $this->db->prepare("SELECT id_media, url_media FROM MEDIA WHERE id_membre = ? AND id_evenement = ? ORDER BY date_media ASC LIMIT ?");

        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $eventId, PDO::PARAM_INT);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserAllMedia($userId, $limit)
    {
        $stmt = $this->db->prepare("SELECT id_media, url_media FROM MEDIA WHERE id_membre = ? ORDER BY date_media ASC LIMIT ?");

        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMedia($fileName, $date, $userId, $eventId)
    {
        $stmt = $this->db->prepare("INSERT INTO MEDIA (url_media, date_media, id_membre, id_evenement) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$fileName, $date, $userId, $eventId]);
    }

    public function getMediaUrl($mediaId, $eventId)
    {
        $stmt = $this->db->prepare("SELECT url_media FROM MEDIA WHERE id_media = ? AND id_evenement = ?");
        $stmt->execute([$mediaId, $eventId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['url_media'] : null;
    }

    public function deleteMedia($mediaId, $eventId)
    {
        $stmt = $this->db->prepare("DELETE FROM MEDIA WHERE id_media = ? AND id_evenement = ?");
        return $stmt->execute([$mediaId, $eventId]);
    }
}
