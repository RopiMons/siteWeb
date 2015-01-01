<?php
function Verif($champ,$nom,$taillemin=0,$taillemax=1000000000,$type="",$regexp="",$confirmation="",$optionnel="",$bdd=null,$parametres=null){
	$retour="";
	$verif=TailleMin($champ, $taillemin);
	if($verif==false) $retour.="Vous devez entrer au moins " . $taillemin . " caractères pour le champ \"" . $nom . "\".<br />";
	$verif=TailleMax($champ, $taillemax);
	if($verif=false) $retour.="Vous devez entrer au maximum " . $taillemax . " caractères pour le champ \"" . $nom . "\".<br />";
	
                
        if($optionnel!="optionnel" || $optionnel=="optionnel" && strlen($champ!=0))
	{
		//Si on a besoin d'un regexp figurant parmis les types
		if($type!="") 
		{
			$verif=Type($champ,$type);
			if($verif==false &&$type=="email") $retour.= "Le champ \"" . $nom . "\" doit être une adresse E-mail valide.<br />";
			if($verif==false &&$type=="int") $retour.= "Le champ \"" . $nom . "\" doit être composé uniquement de chiffres.<br />";
			elseif($verif==false&&$type=="alpha") $retour.="Le champ \"" . $nom ."\"doit être composé uniquement de lettres.<br />";
		}
		//Si on a besoin d'un regexp mais qui ne figure pas parmis les types
		if($regexp!="")if(!RegExp($champ,$regexp)) $retour.="Le champ \"" . $nom . "\" n'est pas correct.<br />"; 
	}
	
        if(($type=="pseudo" || $type=="email") && isset($parametres) && isset($bdd)){
            if($type=="pseudo"){
                $row = "personnespseudo";
            }else{
                $row = "mailpersonnes";
            }
            $requette = new RequetteSelect("personnes","COUNT(personnes.nompersonnes) AS nb");
            $re = saveDB::execute($bdd, $requette->where($row,":val")->where("idpersonnes", ":id",null,"<>"),$parametres);
            
            if($re[0]["nb"]!=0){
                $retour = "$nom  $champ existe déjà ! Merci d'en choisir un autre";               
            }
        }elseif($type=="pseudo"){
            $retour = " Impossible de vérifier '$nom' $champ. Merci de signaler cette erreure au webmaster";
        }
        
	//Si on veut vérifier que deux champs sont identiques
	if($confirmation!="") 
	{
		$verif=Identique($champ,$confirmation,$nom);
		if($verif==false) $retour.= "La confirmation du champ \"" . $nom . "\" ne correspond pas.<br />";
	}
	
	return($retour=="")? true : $retour;
}
function TailleMin($champ, $taillemin){
	$taille=strlen($champ);
	return ($taille < $taillemin) ? false : true;
}
function TailleMax($champ, $taillemax){
	$taille=strlen($champ);
	return ($taille > $taillemax) ? false : true;
}
function Type($champ,$type){
	switch($type)
	{
		case "email":
			return RegExp($champ,"^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$^");
		break;
		case "url":
			return RegExp($champ,"#^(((http|https)://)?(www\.)?)+(([a-zA-Z0-9\._-]+\.[a-zA-Z]{2,6}))$#");
		break;
		case "int":
			return (is_numeric($champ)) ? true : false;
		break;
		case "alpha":
			return RegExp($champ,"[^A-Za-z]");
		break;
	}
}
function RegExp($champ,$regexp=""){
	return(preg_match($regexp,$champ));
}
function Identique($champ1,$champ2, $nom_champ){
	return($champ1==$champ2) ? true : false;
}
function ValideForm($champs){
	$nombre=(count($champs)-1);
	$message="";
	for($i=0;$i<=$nombre;$i++)
	{
		if($champs[$i]!=1)
			$message.=$champs[$i];
	}
	return($message=="")?true:$message;
}
?>