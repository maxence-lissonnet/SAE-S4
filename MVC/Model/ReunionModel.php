<?php

class ReunionModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAllMeetings()
    {
        $stmt = $this->db->query("SELECT id_reunion, date_reunion, fichier_reunion, id_membre FROM REUNION ORDER BY date_reunion DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMeetingById($id)
    {
        $stmt = $this->db->prepare("SELECT id_reunion, date_reunion, fichier_reunion, id_membre FROM REUNION WHERE id_reunion = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMeeting($date, $filename, $userId)
    {
        $stmt = $this->db->prepare("INSERT INTO REUNION (date_reunion, fichier_reunion, id_membre) VALUES (?, ?, ?)");
        $stmt->execute([$date, $filename, $userId]);
        return $this->db->lastInsertId();
    }

    public function deleteMeeting($id)
    {
        $stmt = $this->db->prepare("DELETE FROM REUNION WHERE id_reunion = ?");
        return $stmt->execute([$id]);
    }
}
