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
                                    ORDER BY date_actualite ASC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_actualite_by_id($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ACTUALITE WHERE id_actualite = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
