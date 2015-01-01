<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Cette classe caractérise une ressource et en gère les autorisations de modifications et d'accès
 *
 * @author Laurent CARDON <laurent.cardon@ropi.be>
 * 
 */
class Ressource {
    private $idProprietaire; // Id du propriétaire de la ressource.
    private $type; // Type de la ressource
    
    /**
     * Constructeur de la classe Ressource. Il permet de trouver l'ID du propriétaire types de Ressources supportée :
     * -> Commerce
     * -> Personne
     * 
     * Attention, pour ajouter une Ressource, il faut égallement mettre à jour la classe Paramètre
     * 
     * @param String $type Type de la ressource passée {Commerce, Personne}
     * @param String $id Id de la ressource passée en paramètre
     * @param PDO $bdd Objet PDO permettant la connection à la base de donnée
     * 
     * @return Ressource retourne l'objet crée
     */
    public function __construct($type,$id,PDO $bdd) {
        $this->type = $type;
        
        switch ($type)
        {
            case "Commerce" : $this->initCommerce($id,$bdd); break;
            case "Personne" : $this->initPersonne($id,$bdd); break;
        }
        
        return $this;
    }
    
    /**
     * Permet de retrouver le propriétaire d'un Commerce
     * @param String $id ID du commerce
     * @param PDO $bdd Objet PDO pour la connection à la base de donnée
     * @return null Met à jour la variable $propriétaire de l'objet
     */
    private function initCommerce($id,PDO $bdd){
        $req = new RequetteSelect("compers","compers_personnesID AS id");
        $req->where("compers_commerceID", ":id");
        
        $this->init($bdd, $req, array(":id"=>$id));
    }
    
    private function initPersonne($id, PDO $bdd){
        $this->idProprietaire = $id;
    }
    
    /**
     * Retourne le type de la ressource
     * 
     * @return String Retourne en toute lettre le type de la Ressource
     */
    public function getType(){
        return $this->type;
    }
    
    /**
     * Retourne l'id du propeiétaire de la ressource
     * 
     * @return String retourne l'ID du propriétaire de la ressource
     */
    public function getProprietaire(){
        return $this->idProprietaire;
    }
    
    /**
     * Détermine si l'id passé en paramètre correspond à celui du propriétaire de la Ressource
     * 
     * @param String $id Id du candidat propriétaire
     * @return bool Retourne si oui ou non l'id est celui du propriétaire   
     */
    public function isProprietaire($id){
        return $id == $this->idProprietaire;
    }
    
    private function init(PDO $bdd,  EditeurRequette $request, Array $parametres){
        if($donnees = saveDB::execute($bdd, $request->getSQL(),$parametres)){
            $this->idProprietaire = $donnees["id"];
        }
        return $this;
    }
}
