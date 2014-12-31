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
        $req = new RequetteSelect('personnes',"MAX(typepersonnepersonne.typepersonnepersonne_cataloguetypepersonne) AS niveau");
        $req->where("idpersonnes", ":id")->where("passwordpersonnes", ":password");
        $req->leftJoin("typeperonne_idpersonnes", "typepersonnepersonne", "idpersonnes");
        
        $retour = self::execute($bdd, $req->getSQL(), array(":id"=>$id,":password"=>$password));
        
        $authorized = false;
        if(isset($retour[0]["niveau"]))
        {
            //L'utilisateur existe
            if($retour[0]["niveau"]>=$niveauRequis){
                // Le groupe de l'utilisateur est Ok, pas besoin de plus de vérifications
                $authorized = true;
            }elseif(isset($ressource) && isset($action) && $ressource->isProprietaire($id) && Parametres::getAutorisation($ressource, $action)){
                // Le propriétaire de la ressource à le droit d'effectuer l'action demandée
                $authorized = true;
            }
        }
        
        return $authorized;
    }
    
    static function executeSecureAdminRequest(PDO $bdd, $id, $password,  EditeurRequette $request, Ressource $ressource){
        return saveDB::executeSecureRequest($bdd, $id, $password, "9", $request, $ressource);
    }
    
    static function executeSecureCommercantRequest(PDO $bdd, $id, $password,  EditeurRequette $request, Ressource $ressource){
        return saveDB::executeSecureRequest($bdd, $id, $password, "6", $request, $ressource);
    }

    static function executeSecureRequest(PDO $bdd, $id, $password, $niveauRequis, EditeurRequette $request,  Ressource $ressource){
        if (saveDB::checkPermissions($bdd, $id, $password, $niveauRequis, $ressource, $request->getAction())){
            
        }else{
            return FALSE;
        }
    }
    
    static private function execute(PDO $bdd, $sql, $parametres = null){
        $stmt = $bdd->prepare($sql);
        $stmt->execute($parametres);
        
        $retour = $stmt->fetchAll();
        $stmt->closeCursor();
        
        return $retour;
    }
 
}
