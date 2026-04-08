<?php
class ActualiteModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_actualites($limit)
    {
        $stmt = $this->db->prepare("SELECT id_actualite, titre_actualite, date_actualite 
                                    FROM ACTUALITE 
                                    WHERE date_actualite <= NOW() 
                                    ORDER BY date_actualite DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_actualite_by_id($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ACTUALITE WHERE id_actualite = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllNewsAdmin()
    {
        $stmt = $this->db->query("SELECT * FROM ACTUALITE ORDER BY date_actualite DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEmptyNews()
    {
        $stmt = $this->db->prepare("INSERT INTO ACTUALITE (titre_actualite, contenu_actualite, date_actualite) VALUES ('Nouvelle actualité', 'Contenu par défaut', NOW())");
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateNews($id, $titre, $contenu, $date)
    {
        $stmt = $this->db->prepare("UPDATE ACTUALITE SET titre_actualite = ?, contenu_actualite = ?, date_actualite = ? WHERE id_actualite = ?");
        return $stmt->execute([$titre, $contenu, $date, $id]);
    }

    public function updateNewsImage($id, $filename)
    {
        $stmt = $this->db->prepare("UPDATE ACTUALITE SET image_actualite = ? WHERE id_actualite = ?");
        return $stmt->execute([$filename, $id]);
    }

    public function deleteNews($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ACTUALITE WHERE id_actualite = ?");
        return $stmt->execute([$id]);
    }
}
