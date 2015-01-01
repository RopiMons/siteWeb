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
class RequetteSelect extends EditeurRequette {

    private $champ; //Champs de la sélection
    private $join; // Jointures

    public function __construct($table, $champ = null) {
        if ($champ) {
            if (is_array($champ)) {
                foreach ($champ as $i => $ch) {
                    if ($i > 0) {
                        $this->champ = $this->champ . ", ";
                    }
                        $this->champ = $this->champ . $ch;
                }
            } else {
                $this->champ =  $champ;
            }
        } else {
            $this->champ = " *";
        }
        return parent::__construct('SELECT', $table);
    }

    /**
     * Permet de réaliser une jointure gauche
     * @param String $critereLocal Critère sur la table locale de jointure
     * @param String $tableLocale Table à jointer
     * @param String $critereDistant Critère sur la table d'origine
     * @param String $tableDistante Table d'origine (Défaut, table passée à l'initialisation)
     * @return \EditeurRequette Objet modifié
     */
    public function leftJoin($critereLocal, $tableLocale, $critereDistant, $tableDistante = null) {
        return $this->join($critereLocal, $tableLocale, $critereDistant, $tableDistante, 'LEFT JOIN');
    }
    
    /**
     * Permet de réaliser une jointure naturelle
     * @param String $critereLocal Critère sur la table locale de jointure
     * @param String $tableLocale Table à jointer
     * @param String $critereDistant Critère sur la table d'origine
     * @param String $tableDistante Table d'origine (Défaut, table passée à l'initialisation)
     * @return \EditeurRequette Objet modifié
     */
    public function innerJoin($critereLocal, $tableLocale, $critereDistant, $tableDistante = null) {
        return $this->join($critereLocal, $tableLocale, $critereDistant, $tableDistante, 'INNER JOIN');
    }
    
    private function join($critereLocal, $tableLocale, $critereDistant, $tableDistante = null, $instruction = "INNER JOIN"){
        if (!$tableDistante) {
            $tableDistante = $this->table;
        }
        $this->join = $this->join . " " . $instruction . " " . $tableLocale . " ON " . $tableLocale . "." . $critereLocal . " = " . $tableDistante . "." . $critereDistant;

        return $this;
    }

    /**
     * Cette fonction retourne la requette SQL générée par l'objet
     * @return String La requette sql générée
     */
    public function getSQL() {
        $this->sql = "SELECT " .$this->champ . " FROM " . $this->table . $this->join;
        return parent::getSQL();
    }

}