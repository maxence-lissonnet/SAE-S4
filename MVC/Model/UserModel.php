<?php
class UserModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    //Inscription et verifications

    public function emailExists($email)
    {
        $stmt = $this->db->prepare("SELECT id_membre FROM MEMBRE WHERE email_membre = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    public function createUser($lname, $fname, $mail, $hashedPassword, $defaultPP)
    {
        $stmt = $this->db->prepare("CALL creationCompte(?, ?, ?, ?, ?)");
        return $stmt->execute([$lname, $fname, $mail, $hashedPassword, $defaultPP]);
    }


    //Lecture du compte et de ses infos

    public function getUserInfo($userId)
    {
        $stmt = $this->db->prepare("
            SELECT pp_membre, xp_membre, prenom_membre, nom_membre, email_membre, tp_membre, discord_token_membre, nom_grade, image_grade 
            FROM MEMBRE 
            LEFT JOIN ADHESION ON MEMBRE.id_membre = ADHESION.id_membre 
            LEFT JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade 
            WHERE MEMBRE.id_membre = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    //Modification du compte

    public function updateProfilePicture($fileName, $userId)
    {
        $stmt = $this->db->prepare("UPDATE MEMBRE SET pp_membre = ? WHERE id_membre = ?");
        return $stmt->execute([$fileName, $userId]);
    }

    public function checkEmailExistsForOtherUser($email, $userId)
    {
        $stmt = $this->db->prepare("SELECT id_membre FROM MEMBRE WHERE email_membre = ? AND id_membre != ?");
        $stmt->execute([$email, $userId]);
        return $stmt->fetch() !== false;
    }

    public function updatePersonalInfo($name, $lastName, $mail, $tp, $userId)
    {
        $stmt = $this->db->prepare("UPDATE MEMBRE SET prenom_membre = ?, nom_membre = ?, email_membre = ?, tp_membre = ? WHERE id_membre = ?");
        return $stmt->execute([$name, $lastName, $mail, $tp, $userId]);
    }


    //Sécurité et mot de passe

    public function getUserPassword($userId)
    {
        $stmt = $this->db->prepare("SELECT password_membre FROM MEMBRE WHERE id_membre = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['password_membre'] : null;
    }

    public function updatePassword($hashedPassword, $userId)
    {
        $stmt = $this->db->prepare("UPDATE MEMBRE SET password_membre = ? WHERE id_membre = ?");
        return $stmt->execute([$hashedPassword, $userId]);
    }


    //Historique d'achats

    public function getPurchaseHistory($userId, $viewAll = false)
    {
        $sql = "
            SELECT type_transaction, element, quantite, montant, mode_paiement, date_transaction, 
            CASE WHEN recupere = 1 THEN 'Récupéré' ELSE 'Non récupéré' END AS statut 
            FROM HISTORIQUE_COMPLET 
            WHERE id_membre = ? 
            ORDER BY date_transaction DESC
        ";

        if (!$viewAll) {
            $sql .= " LIMIT 6";
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    //Supression du compte
    public function deleteAccount($userId)
    {
        $stmt1 = $this->db->prepare("DELETE FROM ADHESION WHERE id_membre = ?");
        $stmt1->execute([$userId]);
        $stmt2 = $this->db->prepare("DELETE FROM MEDIA WHERE id_membre = ?");
        $stmt2->execute([$userId]);
        $stmtFinal = $this->db->prepare("DELETE FROM MEMBRE WHERE id_membre = ?");
        return $stmtFinal->execute([$userId]);
    }
}
