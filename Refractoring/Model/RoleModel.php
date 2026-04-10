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
        $stmt = $this->db->prepare("INSERT INTO ROLE (nom_role, p_log_role, p_boutique_role, p_reunion_role, p_utilisateur_role, p_grade_role, p_roles_role, p_actualite_role, p_evenements_role, p_comptabilite_role, p_achats_role, p_moderation_role) VALUES ('Nouveau rôle', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateRole($id, $nom, $perms)
    {
        $stmt = $this->db->prepare("UPDATE ROLE SET nom_role = ?, p_log_role = ?, p_boutique_role = ?, p_reunion_role = ?, p_utilisateur_role = ?, p_grade_role = ?, p_roles_role = ?, p_actualite_role = ?, p_evenements_role = ?, p_comptabilite_role = ?, p_achats_role = ?, p_moderation_role = ? WHERE id_role = ?");

        return $stmt->execute([
            $nom,
            isset($perms['p_log_role']) && $perms['p_log_role'],
            isset($perms['p_boutique_role']) && $perms['p_boutique_role'],
            isset($perms['p_reunion_role']) && $perms['p_reunion_role'],
            isset($perms['p_utilisateur_role']) && $perms['p_utilisateur_role'],
            isset($perms['p_grade_role']) && $perms['p_grade_role'],
            isset($perms['p_roles_role']) && $perms['p_roles_role'],
            isset($perms['p_actualite_role']) && $perms['p_actualite_role'],
            isset($perms['p_evenements_role']) && $perms['p_evenements_role'],
            isset($perms['p_comptabilite_role']) && $perms['p_comptabilite_role'],
            isset($perms['p_achats_role']) && $perms['p_achats_role'],
            isset($perms['p_moderation_role']) && $perms['p_moderation_role'],
            $id
        ]);
    }

    public function deleteRole($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ROLE WHERE id_role = ?");
        return $stmt->execute([$id]);
    }
}
