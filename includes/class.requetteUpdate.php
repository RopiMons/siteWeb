<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Permet de créer des requettes de type "Select";
 *
 * @author Laurent Cardon <laurent.cardon@ropi.be>
 */
class RequetteUpdate extends EditeurRequette {

    private $champs = Array(); //Champs de la sélection

    public function __construct($table) {
        return parent::__construct('UPDATE', $table);
    }

    /**
     * Cette fonction retourne la requette SQL générée par l'objet
     * @return String La requette sql générée
     */
    public function getSQL() {
        $this->sql = "UPDATE " .$this->table . " SET ";
        foreach ($this->champs as $i => $tab){
            if($i>0){
                $this->sql.=", ";
            }
            $this->sql .= "$tab[0] = $tab[1]";
        }
        return parent::getSQL();
    }
    
    public function addValue($champ,$valeur){
        $this->champs[] = array($champ,$valeur);
        
        return $this;
    }

}
