<?php
class Compte {
    private $id;
    private $numero;
    private $titulaire;
    private $type;
    private $solde;
    private $dateCreation;
    private $statut;
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getNumero() {
        return $this->numero;
    }
    
    public function getTitulaire() {
        return $this->titulaire;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getSolde() {
        return $this->solde;
    }
    
    public function getDateCreation() {
        return $this->dateCreation;
    }
    
    public function getStatut() {
        return $this->statut;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function setNumero($numero) {
        $this->numero = $numero;
        return $this;
    }
    
    public function setTitulaire($titulaire) {
        $this->titulaire = $titulaire;
        return $this;
    }
    
    public function setType($type) {
        $this->type = $type;
        return $this;
    }
    
    public function setSolde($solde) {
        $this->solde = $solde;
        return $this;
    }
    
    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;
        return $this;
    }
    
    public function setStatut($statut) {
        $this->statut = $statut;
        return $this;
    }
    
    // Méthodes métier
    public function estBloque() {
        return $this->statut === 'Bloqué';
    }
    
    public function formaterSolde() {
        return number_format($this->solde, 0, ',', ' ') . ' FCFA';
    }
    
    public function formaterDate() {
        $date = new DateTime($this->dateCreation);
        return $date->format('d/m/Y');
    }
}
?>