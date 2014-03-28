<?php
function Entete($action,$id,$nom,$method="post"){
	switch($action)
	{
		case "self": $action_form=$_SERVER['PHP_SELF']; break;
	}
	$nom_pur=valideChaine($nom); //On prend un nom épuré
	$id_pur=valideChaine($id); //On prend un nom épuré
	$entete="<form name=\"".$nom_pur."\" id=\"".$id_pur."\" action=\"".$action_form."\" method=\"".$method."\">";
	return $entete;
}
function Label($nom,$nom_pur){
	
	$input="<label for=\"".$nom_pur."\">".$nom." : </label>";
	return $input;
}
function Input($nom,$type,$value="",$placeholder="",$class=""){
	$nom_pur=valideChaine($nom); //On prend un nom épuré
	$label=Label($nom,$nom_pur);//On crée d'abord le label
	$input="<input type=\"".$type."\" name=\"".$nom_pur."\" id=\"".$nom_pur."\" value=\"".$value."\" placeholder=\"".$placeholder."\"  class=\"".$class."\" />";
	return $label.$input;
}
function Textarea($nom,$value){
	$nom_pur=valideChaine($nom); //On prend un nom épuré
	$label=Label($nom,$nom_pur);//On crée d'abord le label
	$input="<br /><textarea name=\"".$nom_pur."\" id=\"".$nom_pur."\" />".$value."</textarea>";
	return $label.$input;
}
function Checkboxes($type,$nom,$arguments,$checked="",$align="<br />"){
	$nom_pur=valideChaine($nom); //On prend un nom épuré
	$nombre=(count($arguments)-1);//On compte le nombre d'arguments -1 parce que le for commence à 0
	$checkboxes="";
	for($i=0;$i<=$nombre;$i++)
	{
		$id=valideChaine($arguments[$i]);
		$label=Label($arguments[$i],$id);
		if($checked==$arguments[$i])
		$checkboxes.="<input type=\"".$type."\" name=\"".$nom_pur."\" id=\"".$id."\" value=\"".$arguments[$i]."\" checked/>".$label . $align;
		else
		$checkboxes.="<input type=\"".$type."\" name=\"".$nom_pur."\" id=\"".$id."\" value=\"".$arguments[$i]."\"/>".$label . $align;
	}
	return $checkboxes;
}
function Submit($nom){
	$nom_pur=valideChaine($nom);
	$submit="<input type='submit'  name=\"".$nom."\" id=\"".$nom_pur."\" />";
	return $submit;
}
function valideChaine($chaineNonValide)
{
 	$chaineNonValide = preg_replace('`\s+`', '_', trim($chaineNonValide));
 	$chaineNonValide = str_replace("'", "_", $chaineNonValide);
	$chaineNonValide = preg_replace('`_+`', '_', trim($chaineNonValide));
	$chaineValide=strtr($chaineNonValide,
		"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
        "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
  return ($chaineValide);
}


?>