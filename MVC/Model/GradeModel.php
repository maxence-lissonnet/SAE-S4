<?php
class GradeModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // --- Section Affichage ---

    public function getAllGrades()
    {
        $stmt = $this->db->prepare("SELECT * FROM GRADE WHERE deleted = false ORDER BY prix_grade");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getGradeById($id_grade)
    {
        $stmt = $this->db->prepare("SELECT * FROM GRADE WHERE id_grade = ?");
        $stmt->execute([$id_grade]);
        return $stmt->fetch();
    }

    // --- Section Adhésion / Possession ---

    public function checkUserOwnership($gradeId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT 1 FROM GRADE 
            INNER JOIN ADHESION ON GRADE.id_grade = ADHESION.id_grade 
            INNER JOIN MEMBRE ON ADHESION.id_membre = MEMBRE.id_membre 
            WHERE GRADE.id_grade = ? AND MEMBRE.id_membre = ?
        ");
        $stmt->execute([$gradeId, $userId]);
        return $stmt->fetch();
    }

    public function getCurrentAdhesion($userid)
    {
        $stmt = $this->db->prepare("SELECT * FROM ADHESION WHERE id_membre = ?");
        $stmt->execute([$userid]);
        return $stmt->fetch();
    }

    public function subscribeToGrade($userid, $id_grade, $prix, $modePaiement)
    {
        $stmt = $this->db->prepare("
            INSERT INTO ADHESION (id_membre, id_grade, prix_adhesion, paiement_adhesion, date_adhesion) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$userid, $id_grade, $prix, $modePaiement]);
    }

    public function unsubscribeFromGrade($userid)
    {
        $stmt = $this->db->prepare("DELETE FROM ADHESION WHERE id_membre = ?");
        return $stmt->execute([$userid]);
    }
}
