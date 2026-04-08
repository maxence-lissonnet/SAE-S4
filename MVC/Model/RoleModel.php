<?php
class RoleModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAllRoles()
    {
        $stmt = $this->db->query("SELECT * FROM ROLE ORDER BY nom_role ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ROLE WHERE id_role = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createEmptyRole()
    {
        // On crée un rôle vide avec toutes les permissions à 0 (false)
        $stmt = $this->db->prepare("INSERT INTO ROLE (nom_role, p_log, p_boutique, p_reunion, p_utilisateur, p_grade, p_role, p_actualite, p_evenement, p_comptabilite, p_achat, p_moderation) VALUES ('Nouveau rôle', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateRole($id, $nom, $perms)
    {
        $stmt = $this->db->prepare("UPDATE ROLE SET nom_role = ?, p_log = ?, p_boutique = ?, p_reunion = ?, p_utilisateur = ?, p_grade = ?, p_role = ?, p_actualite = ?, p_evenement = ?, p_comptabilite = ?, p_achat = ?, p_moderation = ? WHERE id_role = ?");

        return $stmt->execute([
            $nom,
            $perms['p_log'],
            $perms['p_boutique'],
            $perms['p_reunion'],
            $perms['p_utilisateur'],
            $perms['p_grade'],
            $perms['p_role'],
            $perms['p_actualite'],
            $perms['p_evenement'],
            $perms['p_comptabilite'],
            $perms['p_achat'],
            $perms['p_moderation'],
            $id
        ]);
    }

    public function deleteRole($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ROLE WHERE id_role = ?");
        return $stmt->execute([$id]);
    }
}
