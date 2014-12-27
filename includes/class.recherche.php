<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Classe permettant la construction d'une requêtte pour la recherche
 *
 * @author Laurent Cardon <laurent.cardon@ropi.be>
 */
class Recherche {
    
    private $sql = "SELECT DISTINCT commerce.commercenom AS nom, commerce.idcommerce AS id, cataloguetypecommerce.cataloguetypecommercelabel AS type FROM commerce LEFT JOIN typecommerce ON commerce.idcommerce = typecommerce.typecommerce_commerceID LEFT JOIN cataloguetypecommerce ON typecommerce.typecommerce_cataloguetypecommerceID = cataloguetypecommerce.idcataloguetypecommerce LEFT JOIN adresses ON commerce.idcommerce = adresses.adressescommerceid LEFT JOIN commerceproduits ON commerce.idcommerce = commerceproduits.produitidcommerce WHERE adresses.adresses_catalogueadressesID = '3'";
    
    /**
     * Constructeur de la recherche
     * @param Int $type Critère sur le type (Ou 0 si ce critère ne doit pas être pris en compte)
     * @param Int $cp Critère sur le code postal (Ou 0 si ce critère ne doit pas être pris en compte)
     * @param String $adresse Critère sur la rue (Ou 0 si ce critère ne doit pas être pris en compte)
     * @param String $nom Critère sur le nom du commerce (Ou 0 si ce critère ne doit pas être pris en compte)
     * @param String $produit Critère sur le produit vendu par le commerce (Ou 0 si ce critère ne doit pas être pris en compte)
     * @return \Recherche Objet courant
     */
    public function __construct($type,$cp,$adresse,$nom,$produit) {
        $this->setAdresse($adresse);
        $this->setCodePostal($cp);
        $this->setNom($nom);
        $this->setProduit($produit);
        $this->setType($type);
        return $this;
    }
    
    /**
     * Retourne la requette SQL créée
     * 
     * @return String Requêtte SQL générée
     */
    public function getSql()
    {
        return $this->sql.";";
    }
    
    /**
     * Défini un critère sur le type de commerce
     * 
     * @param Int $type Id du type du commerce à chercher
     * @return Recherche Retourne l'objet courrant
     */
    public function setType($type){
        if($type!=0){
            $this->sql = $this->sql . " AND typecommerce.typecommerce_cataloguetypecommerceID = '".$type."' ";
        }
            return $this;
    }
    
    /**
     * Défini un critère sur le code postal de commerce
     * 
     * @param Int $cp Code postal du commerce à chercher
     * @return Recherche Retourne l'objet courrant
     */
    public function setCodePostal($cp){
        if($cp!=0){
            $this->sql = $this->sql . " AND adresses.adressescodepostal = '".$cp."' ";
        }
        return $this;
    } 
    
    /**
     * Défini un critère sur l'adresse du commerce
     * 
     * @param String $adresse Rue du commerce à chercher
     * @return Recherche Retourne l'objet courrant
     */
    public function setAdresse($adresse){
        if($adresse && is_string($adresse)){
            $this->sql = $this->sql . " AND adresses.adressesrue = '".$adresse."' ";
        }
        return $this;
    } 
    
    /**
     * Défini un critère sur le nom du commerce
     * 
     * @param String $nom Nom du commerce à chercher
     * @return Recherche Retourne l'objet courrant
     */
    public function setNom($nom){
        if($nom && is_string($nom)){
            $this->sql = $this->sql . " AND commerce.commercenom = '".$nom."' ";
        }
        return $this;
    }
    
    /**
     * Défini un critère sur le produit vendu par le commerce
     * 
     * @param String $produit Nom du produit vendu par le commerce
     * @return Recherche Retourne l'objet courrant
     */
    public function setProduit($produit){
        if($produit && is_string($produit)){
            $this->sql = $this->sql . " AND commerceproduits.produitnom = '".$produit."' ";
        }
            return $this;
    }
    
    /**
     * Retourne le tableau des résultats
     * 
     * @param PDO $bdd Objet PDO pour la connection avec la base de donnée
     * @return Array Tableau des résultats
     */
    public function getArray(PDO $bdd){
        $req = $bdd->prepare($this->getSQL());
        $req->execute();
        $tab = $req->fetchAll();
        $req->closeCursor();
        
        return $tab;
    }
}
