<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Cette classe permet d'éditer des requettes
 *
 * @author Laurent Cardon <laurent.cardon@ropi.be>
 */
abstract class EditeurRequette {

    private $action; //{INSERT,DELETE,UPDATE,SELECT}
    protected $table; // Table où aura lieu la requette SQL principale
    protected $sql; // Code SQL généré
    private $where; //Conditions

    /**
     * Cette fonction construit l'objet requette sur base de l'action sql à mener
     * 
     * @param String $action Action au sens de SQL pour la requette
     * @param String $table Table sur laquelle se déroulera cette action
     * @return \EditeurRequette|boolean
     */

    public function __construct($action, $table) {
        if ($action == 'DELETE' || $action == 'INSERT' || $action == 'SELECT' || $action == 'UPDATE') {
            $this->action = $action;
            $this->table = $table;
            return $this;
        } else {
            return false;
        }
    }

    /**
     * Ajouter une condition sur la requette
     * @param String $critere Champ critère pour le where
     * @param String $valeur Valeur du critère
     * @param String $tableCritere table du critère (Par défaut table donnée à l'initialisation)
     * @return \EditeurRequette
     */
    public function where($critere, $valeur, $tableCritere = null, $operateur = "=") {
        if (!$tableCritere) {
            $tableCritere = $this->table;
        }
        if (!$this->where) {
            $this->where = " WHERE ";
        } else {
            $this->where = $this->where . " AND ";
        }
        $this->where = $this->where . $tableCritere . "." . $critere . " ".$operateur." " . $valeur;

        return $this;
    }

    /**
     * Génère le code SQL en fonction des précédents critères
     * @return String retourne la requette SQL
     */
    public function getSQL(){
       return $this->sql = $this->sql . $this->where;
    }
    
    public function __toString() {
        return $this->getSQL();
    }
    
    public function getAction(){
        return $this->action;
    }
}
