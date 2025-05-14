<?php
require_once __DIR__ . '/../models/CompteManager.php';

class CompteController {
    private $compteManager;
    
    public function __construct() {
        $this->compteManager = new CompteManager();
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'numero';
        
        $comptes = $this->compteManager->getAll($page, 10, $search, $type, $orderBy);
        $totalComptes = $this->compteManager->count($search, $type);
        $totalPages = ceil($totalComptes / 10);
        $types = $this->compteManager->getTypes();
        
        require_once __DIR__ . '/../views/compte/index.php';
    }
    
    public function view($id) {
        $compte = $this->compteManager->getById($id);
        
        if (!$compte) {
            header('Location: index.php?action=index&error=compte_not_found');
            exit;
        }
        
        require_once __DIR__ . '/../views/compte/view.php';
    }
    
    public function create() {
        $types = $this->compteManager->getTypes();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $compte = new Compte();
            $compte->setNumero($_POST['numero'])
                  ->setTitulaire($_POST['titulaire'])
                  ->setType($_POST['type'])
                  ->setSolde($_POST['solde'])
                  ->setDateCreation(date('Y-m-d'))
                  ->setStatut('Actif');
            
            if ($this->compteManager->create($compte)) {
                header('Location: index.php?action=index&success=compte_created');
                exit;
            } else {
                $error = "Erreur lors de la création du compte";
            }
        }
        
        require_once __DIR__ . '/../views/compte/create.php';
    }
    
    public function edit($id) {
        $compte = $this->compteManager->getById($id);
        $types = $this->compteManager->getTypes();
        
        if (!$compte) {
            header('Location: index.php?action=index&error=compte_not_found');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $compte->setTitulaire($_POST['titulaire'])
                  ->setType($_POST['type'])
                  ->setSolde($_POST['solde'])
                  ->setStatut($_POST['statut']);
            
            if ($this->compteManager->update($compte)) {
                header('Location: index.php?action=index&success=compte_updated');
                exit;
            } else {
                $error = "Erreur lors de la mise à jour du compte";
            }
        }
        
        require_once __DIR__ . '/../views/compte/edit.php';
    }
    
    public function delete($id) {
        if ($this->compteManager->delete($id)) {
            header('Location: index.php?action=index&success=compte_deleted');
        } else {
            header('Location: index.php?action=index&error=delete_failed');
        }
        exit;
    }
}
?>