<?php
class AccueilModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_podium_xp()
    {
        $stmt = $this->db->query("SELECT prenom_membre, xp_membre, pp_membre FROM MEMBRE ORDER BY xp_membre DESC LIMIT 3");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
