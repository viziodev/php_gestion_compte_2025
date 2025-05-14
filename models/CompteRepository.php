<?php
require_once 'Compte.php';
require_once __DIR__ . '/../config/database.php';

class CompteManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll($page = 1, $limit = 10, $search = '', $type = '', $orderBy = 'numero') {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT * FROM comptes WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (numero LIKE :search OR titulaire LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        if (!empty($type)) {
            $query .= " AND type = :type";
            $params[':type'] = $type;
        }
        
        $query .= " ORDER BY $orderBy";
        $query .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $comptes = [];
        while ($row = $stmt->fetch()) {
            $compte = new Compte();
            $compte->setId($row['id'])
                  ->setNumero($row['numero'])
                  ->setTitulaire($row['titulaire'])
                  ->setType($row['type'])
                  ->setSolde($row['solde'])
                  ->setDateCreation($row['date_creation'])
                  ->setStatut($row['statut']);
            
            $comptes[] = $compte;
        }
        
        return $comptes;
    }
    
    public function count($search = '', $type = '') {
        $query = "SELECT COUNT(*) as total FROM comptes WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (numero LIKE :search OR titulaire LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        if (!empty($type)) {
            $query .= " AND type = :type";
            $params[':type'] = $type;
        }
        
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['total'];
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM comptes WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch();
        
        if (!$row) {
            return null;
        }
        
        $compte = new Compte();
        $compte->setId($row['id'])
              ->setNumero($row['numero'])
              ->setTitulaire($row['titulaire'])
              ->setType($row['type'])
              ->setSolde($row['solde'])
              ->setDateCreation($row['date_creation'])
              ->setStatut($row['statut']);
        
        return $compte;
    }
    
    public function create(Compte $compte) {
        $stmt = $this->db->prepare("INSERT INTO comptes (numero, titulaire, type, solde, date_creation, statut) 
                                   VALUES (:numero, :titulaire, :type, :solde, :date_creation, :statut)");
        
        $stmt->bindValue(':numero', $compte->getNumero());
        $stmt->bindValue(':titulaire', $compte->getTitulaire());
        $stmt->bindValue(':type', $compte->getType());
        $stmt->bindValue(':solde', $compte->getSolde());
        $stmt->bindValue(':date_creation', $compte->getDateCreation());
        $stmt->bindValue(':statut', $compte->getStatut());
        
        return $stmt->execute();
    }
    
    public function update(Compte $compte) {
        $stmt = $this->db->prepare("UPDATE comptes 
                                   SET titulaire = :titulaire, 
                                       type = :type, 
                                       solde = :solde, 
                                       statut = :statut 
                                   WHERE id = :id");
        
        $stmt->bindValue(':id', $compte->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':titulaire', $compte->getTitulaire());
        $stmt->bindValue(':type', $compte->getType());
        $stmt->bindValue(':solde', $compte->getSolde());
        $stmt->bindValue(':statut', $compte->getStatut());
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM comptes WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function getTypes() {
        $stmt = $this->db->query("SELECT DISTINCT type FROM comptes ORDER BY type");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>