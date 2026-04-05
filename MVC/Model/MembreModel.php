<?php
class MembreModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_membre_by_email($email)
    {
        $stmt = $this->db->prepare("SELECT id_membre, email_membre, password_membre FROM MEMBRE WHERE email_membre = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function check_admin_permission($userid)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as nb_roles FROM ASSIGNATION WHERE id_membre = ?");
        $stmt->execute([$userid]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($res['nb_roles'] > 0);
    }
}
