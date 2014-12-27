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
     * ->
     * 
     * Attention, pour ajouter une Ressource, il faut égallement mettre à jour la classe Paramètre
     * 
     * @param String $type Type de la ressource passée {Commerce,}
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
        $req = $bdd->prepare("SELECT compers_personnesID WHERE compers_personnesID = :id");
        $req->execute(array(
            "id"=>$id,
        ));
        
        if($donnees = $req->fetch()){
            $this->idProprietaire = $donnees["compers_personnesID"];
        }
        
        $req->closeCursor();
    }
    
}
