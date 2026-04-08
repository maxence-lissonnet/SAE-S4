<?php
class ComptabiliteModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAllFiles()
    {
        $stmt = $this->db->query("SELECT * FROM COMPTABILITE ORDER BY date_comptabilite DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFileById($id)
    {
        $stmt = $this->db->prepare("SELECT url_comptabilite FROM COMPTABILITE WHERE id_comptabilite = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addFile($nom, $date, $filename)
    {
        $stmt = $this->db->prepare("INSERT INTO COMPTABILITE (nom_comptabilite, date_comptabilite, url_comptabilite) VALUES (?, ?, ?)");
        return $stmt->execute([$nom, $date, $filename]);
    }

    public function deleteFile($id)
    {
        $stmt = $this->db->prepare("DELETE FROM COMPTABILITE WHERE id_comptabilite = ?");
        return $stmt->execute([$id]);
    }
}
