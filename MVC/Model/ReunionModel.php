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
        $stmt = $this->db->query("SELECT * FROM REUNION ORDER BY date_reunion DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMeetingById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM REUNION WHERE id_reunion = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMeeting($date, $filename)
    {
        $stmt = $this->db->prepare("INSERT INTO REUNION (date_reunion, fichier_reunion) VALUES (?, ?)");
        $stmt->execute([$date, $filename]);
        return $this->db->lastInsertId();
    }

    public function deleteMeeting($id)
    {
        $stmt = $this->db->prepare("DELETE FROM REUNION WHERE id_reunion = ?");
        return $stmt->execute([$id]);
    }
}
