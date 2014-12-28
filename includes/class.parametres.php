<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Classe de paramètres de l'application. Ces paramètres sont codés en dure en l'attente de les rendres dynamiques
 *
 * @author Laurent CARDON <laurent.cardon@ropi.be>
 * @static
 * 
 */
class Parametres {
    /**
     * Ces tableaux définissent si un propriétaire peux sur la ressource spécifiée ...
     * Sémantique array(Créer une ressource, Modifier sa ressource, Supprimer sa ressource )
     */
    static $commerce = array(true,true,true);
    static $default = array(false,false,false);
    
    /**
     * Il s'aggit ici des paramètres concernant l'upload de fichiers
     */
    static $uploadMaxSize = "1000000";
    static $uploadExtension = array('.png', '.gif', '.jpg', '.jpeg');
    static $uploadLogoFolder = "img/logos/";
    
    /**
     * Détermine si une Ressource peut-être modifiée
     * 
     * @static
     * @param Ressource $ressource La ressource pour laquelle l'autorisation doit-être vérifiée
     * @return bool Est-ce qu'un propriétaire de cette ressource peut effectuer cette action ?
     * 
     */
    static public function getAutorisationModification(Ressource $ressource){
        $tab = self::getTab($ressource);
        return $tab[1];
    }
    
     /**
     * Détermine si une Ressource peut-être créer
     * 
     * @static
     * @param Ressource $ressource La ressource pour laquelle l'autorisation doit-être vérifiée
     * @return bool Est-ce qu'un propriétaire de cette ressource peut effectuer cette action ?
     * 
     */
    static public function getAutorisationCreation(Ressource $ressource){
        $tab = self::getTab($ressource);
        return $tab[0];
    }
    
     /**
     * Détermine si une Ressource peut-être supprimée
     * 
     * @static
     * @param Ressource $ressource La ressource pour laquelle l'autorisation doit-être vérifiée
     * @return bool Est-ce qu'un propriétaire de cette ressource peut effectuer cette action ?
     * 
     */
    static public function getAutorisationSuppression(Ressource $ressource){
        $tab = self::getTab($ressource);
        return $tab[2];
    }
    
    /**
     * Retourne le tableau de paramètre en fonction de la Ressource passée
     * 
     * @static
     * @param Ressource $ressource La ressource pour laquelle on souhaite le tableau des paramètres
     * @return Array Tableau des paramètres adapté à la ressource
     * 
     */
    private static function getTab(Ressource $ressource){
        switch ($ressource->getType())
        {
            case "Commerce" : return self::$commerce; 
            default : return self::$default;
        }
    }
    
    /**
     * Retourn la tailler maximale uploadable
     * 
     * @return Int taille maximale d'upload
     */
    public static function getUploadMaxSize() {
        return self::$uploadMaxSize;
    }
    
    /**
     * Retourne les extenssions admissibles en upload
     * 
     * @return Array(String) Retourne les extenssions autorisées en upload
     */
    public static function getUploadExtension() {
        return self::$uploadExtension;
    }
    
    /**
     * Retourne l'emplacement d'upload des logos des commerçant
     * 
     * @return String Emplacement du stockage des logos commerçants
     */
    public static function getUploadLogoFolder() {
        return self::$uploadLogoFolder;
    }
    
    /**
     * Retourne l'emplacement d'upload des logos des commerçant pour une page située dans la zone d'admin
     * 
     * @return String Emplacement du stockage des logos commerçants
     */
    public static function getUploadLogoFolderAdmin() {
        return "../".self::$uploadLogoFolder;
    }
}
