<?php
namespace User;

use \DBManager;

class UserManager
{
    public function __construct($as)
    {
        $this->db = DBManager::get();
        $this->user = $as;
    }
    public function add() {}
    public function subType($data)
    {
        switch ($data["type"]) {
            case 'Admin': return new Admin($data);
            case 'Member': return new Member($data);
            default: return new Guest($data);
        }
    }
    public function login($emailhash, $passwordhash)
    {
        $query = $this->db->prepare('SELECT type, id, name FROM users WHERE emailhash LIKE ? AND passwordhash LIKE ?');

        $query->execute([$emailhash,$passwordhash]);
        $users = $query->fetchAll();
        $query->closeCursor();
        if (count($users) != 1) { throw new \Exception("Utilisateur non trouvé"); }
        $user = $this->subtype($users[0]);
        return $user;
    }
    public function create(string $name, string $emailhash, string $passwordhash)
    {
        $query = $this->db->prepare('SELECT COUNT(*) count FROM users WHERE name LIKE ? OR emailhash LIKE ?');
        $query->execute([$name, $emailhash]);
        $found = $query->fetchColumn();
        if($found) {
            throw new \Exception("Utilisateur déjà enregistré !");
        }
        $query = $this->db->prepare("
            INSERT INTO users(name, emailhash, passwordhash)
            VALUES (?, ?, ?)");
        $answer = $query->execute([$name, $emailhash, $passwordhash]);
        return $this->login($emailhash, $passwordhash);
    }
    public function disconnect() {}
}
