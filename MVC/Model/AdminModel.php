<?php
class AdminModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUserPermissions($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM LISTE_PERMISSIONS WHERE id_membre = ?");
        $stmt->execute([$userId]);
        $permissions = $stmt->fetch(PDO::FETCH_ASSOC);

        return $permissions ?: [];
    }

    public function getPanelDefinitions()
    {
        return [
            [
                'key' => 'chat',
                'label' => 'Chat',
                'icon' => 'chat.svg',
                'permission' => null,
            ],
            [
                'key' => 'boutique',
                'label' => 'Boutique',
                'icon' => 'boutique.svg',
                'permission' => 'p_boutique',
            ],
            [
                'key' => 'utilisateurs',
                'label' => 'Utilisateurs',
                'icon' => 'users.svg',
                'permission' => 'p_utilisateur',
            ],
            [
                'key' => 'grades',
                'label' => 'Grades',
                'icon' => 'grades.svg',
                'permission' => 'p_grade',
            ],
            [
                'key' => 'evenements',
                'label' => 'Evenements',
                'icon' => 'events.svg',
                'permission' => 'p_evenement',
            ],
            [
                'key' => 'comptabilite',
                'label' => 'Comptabilite',
                'icon' => 'comptabilite.svg',
                'permission' => 'p_comptabilite',
            ],
            [
                'key' => 'reunions',
                'label' => 'Reunions',
                'icon' => 'reunions.svg',
                'permission' => 'p_reunion',
            ],
            [
                'key' => 'roles',
                'label' => 'Roles',
                'icon' => 'roles.svg',
                'permission' => 'p_role',
            ],
            [
                'key' => 'actualites',
                'label' => 'Actualites',
                'icon' => 'actualite.svg',
                'permission' => 'p_actualite',
            ],
            [
                'key' => 'history',
                'label' => 'Historique d\'achats',
                'icon' => 'history.svg',
                'permission' => 'p_boutique',
            ],
            [
                'key' => 'logs',
                'label' => 'Logs du serveur',
                'icon' => 'logs.svg',
                'permission' => 'p_log',
            ],
        ];
    }

    public function getAuthorizedPanels($permissions)
    {
        return array_values(array_filter(
            $this->getPanelDefinitions(),
            static function ($panel) use ($permissions) {
                $permission = $panel['permission'];
                return $permission === null || !empty($permissions[$permission]);
            }
        ));
    }

    public function isPanelAuthorized($panelKey, $permissions)
    {
        if ($panelKey === 'unauthorized') {
            return true;
        }

        foreach ($this->getPanelDefinitions() as $panel) {
            if ($panel['key'] !== $panelKey) {
                continue;
            }

            return $panel['permission'] === null || !empty($permissions[$panel['permission']]);
        }

        return false;
    }
}
