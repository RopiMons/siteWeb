<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Cette classe permet d'éxecuter des requettes sur la DB en vérifiant qu'elles peuvent-être executées
 *
 * @author Laurent Cardon <laurent.cardon@ropi.be>
 */
class saveDB {
    
    static function checkPermissions(PDO $bdd, $id, $password, $niveauRequis,  Ressource $ressource = null, $action = null){
       
        $authorized = false;
        
        if($niveau = self::getUserLevel($bdd, $id, $password))
        {
            //L'utilisateur existe
            if($niveau>=$niveauRequis){
                // Le groupe de l'utilisateur est Ok, pas besoin de plus de vérifications
                $authorized = true;
            }elseif(isset($ressource) && isset($action) && $ressource->isProprietaire($id) && Parametres::getAutorisation($ressource, $action)){
                // Le propriétaire de la ressource à le droit d'effectuer l'action demandée
                $authorized = true;
            }
        }
        
        return $authorized;
    }
    
    static function executeSecureAdminRequest(PDO $bdd, $session, Ressource $ressource, EditeurRequette $request, $parametres){
        return saveDB::executeSecureRequest($bdd, $session, "9", $ressource, $request, $parametres);
    }
    
    static function executeSecureCommercantRequest(PDO $bdd, $session, Ressource $ressource, EditeurRequette $request, $parametres = NULL){
        return saveDB::executeSecureRequest($bdd, $session, "6", $ressource, $request, $parametres);
    }

    static function executeSecureRequest(PDO $bdd, $session, $niveauRequis, Ressource $ressource, EditeurRequette $request, $parametres = NULL ){
        if (self::checkPermissions($bdd, $session["id"], $session["password"], $niveauRequis, $ressource, $request->getAction())){
            return self::execute($bdd, $request->getSQL(), $parametres);
        }else{
            return FALSE;
        }
    }
    
    static function getUserLevel($bdd,$id,$password){
        $req = new RequetteSelect('personnes',"MAX(typepersonnepersonne.typepersonnepersonne_cataloguetypepersonne) AS niveau");
        $req->where("idpersonnes", ":id")->where("passwordpersonnes", ":password");
        $req->leftJoin("typeperonne_idpersonnes", "typepersonnepersonne", "idpersonnes");
        
        $retour = self::execute($bdd, $req->getSQL(), array(":id"=>$id,":password"=>$password));
        
        if(isset($retour[0]['niveau'])){
            return $retour[0]['niveau'];
        }else{
            return false;
        }
    }
    
    static function getUserLevelBySession(PDO $bdd, Array $session){
        return self::getUserLevel($bdd, $session['id'], $session['password']);
    }
    
    static private function execute(PDO $bdd, $sql, $parametres = null){
        $stmt = $bdd->prepare($sql);
        $stmt->execute($parametres);
        
        $retour = $stmt->fetchAll();
        $stmt->closeCursor();
        
        return $retour;
    }
 
}
