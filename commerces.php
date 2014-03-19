<?php
include("includes/db_connect.php");
include("includes/functions.php");
$titre_page=TitrePage($bdd,"","Les commerces adhérants au Ropi");
include("includes/head.php");
include("includes/menu.php");
$recherche = false;
if(isset($_POST["search"]))
{
	$recherche=true;
	$type=$_POST["type"];
	$codepostal=$_POST["codepostal"];
	$commerces = array();
	$stmt = $bdd->prepare('SELECT idcommerce FROM commerce WHERE commercestatus<>0');
	$stmt->execute(array());
	while($donnees=$stmt->fetch())
	{
		array_push($commerces,$donnees["idcommerce"]);
	}
	$stmt->closeCursor(); 
	
	//Si l'user a entré un type de commerce
	if($type != "0")
	{
		$stmt = $bdd->prepare('SELECT typecommerce_cataloguetypecommerceID, typecommerce_commerceID FROM typecommerce');
		$stmt->execute(array());
		while($donnees=$stmt->fetch())
		{
			//Si le type du commerce est différent du type cherché, on supprime
			if($donnees["typecommerce_cataloguetypecommerceID"]!=$type)
			{
				unset($commerces[array_search($donnees["typecommerce_commerceID"], $commerces)]);
			}
		}
		$stmt->closeCursor(); 
		foreach($commerces as $commerce)
		{
			$type_existe = false;
			$stmt = $bdd->prepare('SELECT typecommerce_commerceID FROM typecommerce WHERE typecommerce_commerceID=:id');
		    $stmt->execute(array("id"=>$commerce));
		    while($donnees=$stmt->fetch())
			{
				$type_existe = true;
			}
			$stmt->closeCursor();
			
			if ($type_existe == false) 
			{
				unset($commerces[array_search($commerce, $commerces)]);
			}
		}
	}
	
	//SI l'user a entré un nom de produit
	if($_POST["produit"]!="")
	{
		$produit=$_POST["produit"];
		//Je sélectionne tous les articles
		$stmt = $bdd->prepare('SELECT produitnom, produitdescription, produitidcommerce FROM commerceproduits');
		$stmt->execute(array());
		while($donnees=$stmt->fetch())
		{
			//J'explode chaque mot de la recherche
			$nom=strpos($donnees["produitnom"],$produit);
			$contenu = strpos($donnees["produitdescription"],$produit);
			if($nom == "" && $contenu="")
			{
				$arrays=explode(" ",$produit);
				$verif_match=false;
				foreach($arrays as &$array)
				{
					if(strlen($array)>=4)
					{
				    	$nom=strpos($donnees["produitnom"],$array);
			            $contenu = strpos($donnees["produitdescription"],$array);
			    		if($nom != "" || $contenu!="")
						{
							$verif_match=true;
						}
					}
				}
				if($verif_match==true)
				{
				    unset($commerces[array_search($donnees2["produitidcommerce"], $commerces)]);
				}
			}
			
		}
		$stmt->closeCursor(); 
	}
	
		//SI l'user a entré un nom de commerce
	if($_POST["commerce"]!="")
	{
		$verif_principale=false;
		$nom_comm=$_POST["commerce"];
		//Je sélectionne tous les commerce
		$stmt = $bdd->prepare('SELECT idcommerce,commercenom FROM commerce WHERE commercestatus<>0');
		$stmt->execute(array());
		while($donnees=$stmt->fetch())
		{
			if($nom_comm!=$donnees["commercenom"])
			{
				unset($commerces[array_search($donnees["idcommerce"], $commerces)]);
			}
			/*//J'explode chaque mot de la recherche
			$nom=strpos($donnees["commercenom"],$nom_comm);
			if($nom == "")
			{
				$arrays=explode(" ",$nom_comm);
				$verif_match=false;
				foreach($arrays as &$array)
				{
					if(strlen($array)>=4)
					{
				    	$nom=strpos($donnees["commercenom"],$array);
			    		if($nom != "")
						{
							$verif_match=true;
						}
					}
				}	
				if($verif_match==true)
				{
				    unset($commerces[array_search($donnees2["idcommerce"], $commerces)]);
				}
			
			}*/
			
		}
		$stmt->closeCursor(); 
	}
	
	if($_POST["rue"]!="")
	{
		$verif_principale=false;
		$rue=$_POST["rue"];
		//Je sélectionne tous les commerce
		$stmt = $bdd->prepare('SELECT adressesrue FROM adresses WHERE adresses_catalogueadressesID = 3');
		$stmt->execute(array());
		while($donnees=$stmt->fetch())
		{
			if($rue!=str_replace("'"," ",$donnees["adressesrue"]))
			{
				unset($commerces[array_search($donnees["idcommerce"], $commerces)]);
			}
			/*//J'explode chaque mot de la recherche
			$nom=strpos($donnees["commercenom"],$nom_comm);
			if($nom == "")
			{
				$arrays=explode(" ",$nom_comm);
				$verif_match=false;
				foreach($arrays as &$array)
				{
					if(strlen($array)>=4)
					{
				    	$nom=strpos($donnees["commercenom"],$array);
			    		if($nom != "")
						{
							$verif_match=true;
						}
					}
				}	
				if($verif_match==true)
				{
				    unset($commerces[array_search($donnees2["idcommerce"], $commerces)]);
				}
			
			}*/
			
		}
		$stmt->closeCursor(); 
	}


	
	
		//Si l'user a entré un CP
	if($codepostal != "0")
	{
		$stmt = $bdd->prepare('SELECT adressescodepostal,adressescommerceid FROM adresses WHERE adresses_catalogueadressesID = 3');
		$stmt->execute(array());
		while($donnees=$stmt->fetch())
		{
			if($donnees["adressescodepostal"]!=$codepostal)
			    unset($commerces[array_search($donnees["adressescommerceid"], $commerces)]);
		}
		$stmt->closeCursor(); 
		foreach($commerces as $commerce)
		{
			$adresse_3 = false;
			$stmt = $bdd->prepare('SELECT adresses_catalogueadressesID,adressescommerceid FROM adresses WHERE adressescommerceid=:id');
		    $stmt->execute(array("id"=>$commerce));
		    while($donnees=$stmt->fetch())
			{
				if($donnees["adresses_catalogueadressesID"]==3)
				{
					$adresse_3 = true;
				}
			}
			$stmt->closeCursor();
			if ($adresse_3 == false) unset($commerces[array_search($donnees["adressescommerceid"], $commerces)]);
		}
	}

}
?>
<div class="gris_clair">
     <div class="row-fluid corps">
         <div class="span4">
               <h2>Recherche</h2>			
                    
                    <form name="search" action="#" method="post">
						<fieldset>
                            <label for="type">Type de commerce : </label> 
                            <select title="type" name="type" id="type">
                                <option value="0">Aucun</option>
                                 <?php
								$stmt = $bdd->prepare('SELECT * FROM cataloguetypecommerce');
								$stmt->execute(array());
								while($donnees=$stmt->fetch())
								{
									if(isset($_POST["type"]))
									{
										if($_POST["type"]==$donnees["idcataloguetypecommerce"])
										    echo "<option value='".$donnees["idcataloguetypecommerce"]."' selected>".$donnees["cataloguetypecommercelabel"]."</option>";
										else
										    echo "<option value='".$donnees["idcataloguetypecommerce"]."'>".$donnees["cataloguetypecommercelabel"]."</option>";
									}
									else
									    echo "<option value='".$donnees["idcataloguetypecommerce"]."'>".$donnees["cataloguetypecommercelabel"]."</option>";
								}
								$stmt->closeCursor(); 
								?>
							</select>
                            <br />
                            <?php
							$produit=""; if(isset($_POST["produit"])!="") $produit=$_POST["produit"];
							$commerce=""; if(isset($_POST["commerce"])!="") $commerce=$_POST["commerce"];
							$rue=""; if(isset($_POST["rue"])!="") $rue=$_POST["rue"];
							
							$liste_commerces="";
							$stmt = $bdd->prepare('SELECT commercenom FROM commerce WHERE commercestatus<>0');
							$stmt->execute();
							while($donnees=$stmt->fetch())
							{
								if($liste_commerces!="")
								    $liste_commerces.= ",";
								
								$liste_commerces.= '"' . $donnees["commercenom"] . '"';
							}
							$stmt->closeCursor();
							
							$liste_articles="";
							$stmt = $bdd->prepare('SELECT produitnom  FROM commerceproduits');
							$stmt->execute();
							while($donnees=$stmt->fetch())
							{
								if($liste_articles!="")
								    $liste_articles.= ",";
								
								$liste_articles.= '"' . $donnees["produitnom"] . '"';
							}
							$stmt->closeCursor();
							
							$liste_rues="";
							$stmt = $bdd->prepare('SELECT adressesrue FROM adresses WHERE adresses_catalogueadressesID = 3');
							$stmt->execute();
							while($donnees=$stmt->fetch())
							{
								if($liste_rues!="")
								    $liste_rues.= ",";
								
								$liste_rues.= '"' . str_replace("'"," ",$donnees["adressesrue"]) . '"';
							}
							$stmt->closeCursor();
							 ?>
                            <label for="commerce">Nom du commerce : </label><input type="text" name="commerce" id="commerce" placeholder="Nom du commerce" value="<?=$commerce?>" data-provide="typeahead" data-items="4" data-source='[<?=$liste_commerces?>]' autocomplete="off"/>
                            
                            <br />
                            
                            <label for="produit">Nom d'un produit : </label><input type="text" name="produit" id="produit" placeholder="Nom d'un produit" value="<?=$produit?>" data-provide="typeahead" data-items="4" data-source='[<?=$liste_articles?>]' autocomplete="off"/>
                            
                            <br />
                            
                            
                            
                            <label for="produit">Nom de la rue : </label><input type="text" name="rue" id="rue" placeholder="rue" value="<?=$rue?>" data-provide="typeahead" data-items="4" data-source='[<?=$liste_rues?>]' autocomplete="off"/>
                            
                            <br />
                            <label for="codepostal">Code postal : </label>
                             <select title="codepostal" name="codepostal" id="codepostal">
                                <option value="0">Aucun</option>
                             <?php
							 
							 $cps=array();
							$stmt = $bdd->prepare('SELECT adressescodepostal FROM adresses WHERE adresses_catalogueadressesID = 3');
							$stmt->execute(array());
							while($donnees=$stmt->fetch())
							{
								$verif=true;
								foreach($cps as &$cp)
								{
									if($cp==$donnees["adressescodepostal"])
									{
										$verif=false;
									}
								}
								if($verif==true)
								{
									array_push($cps,$donnees["adressescodepostal"]);
									if(isset($_POST["codepostal"]))
									{
										if($_POST["codepostal"]==$donnees["adressescodepostal"])
										    echo "<option value='".$donnees["adressescodepostal"]."' selected>".$donnees["adressescodepostal"]."</option>";
										else echo "<option value='".$donnees["adressescodepostal"]."'>".$donnees["adressescodepostal"]."</option>";
										    
									}
									else
									echo "<option value='".$donnees["adressescodepostal"]."'>".$donnees["adressescodepostal"]."</option>";
								}
								
								
							}
							$stmt->closeCursor(); 
							?>
                            </select>

							<input type="submit" class="btn btn-large btn-info btn-info" value="Chercher" name="search" id="search" />
						</fieldset>
						
					</form>  
          </div>
          
          <div class="span8">
                <?php
				    
					if(isset($_GET["commerce"]))
					{
						$stmt = $bdd->prepare('SELECT * FROM commerce WHERE idcommerce = :id_comm AND commercestatus<>0');
						$stmt->execute(array("id_comm"=>$_GET["commerce"]));
						$donnees=$stmt->fetch();
						
						//echo '<img src="'.$donnees["commerceimage"].'"/><br />';
						echo "<br /><h1>".$donnees["commercenom"]."</h1><br /><br />";
						echo tronque($donnees["commercecontenu"]);
						echo '<br /><p><a href="commerces-afficher.php?id='.$_GET["commerce"].'">Voir la page du commerce</a></p>';
						$stmt->closeCursor(); 
						echo '<div class="clear"></div>';

					}
					elseif($recherche==true)
					{
						?>
                        <div id="txtHint"></div>
                        
                        <h2>Résultats de votre recherche</h2>
                        <table width="100%" class="table table-hover table-striped table-bordered">
                        <tr>
                        <thead><td>Nom du commerce</td><td>Type de commerce</td><td></td></thead>
                        <tbody>
                        </tr>
                        <?php
						$i=0;
						foreach($commerces as &$commerce)
						{
							echo "<tr>";
							$i++;
							$stmt = $bdd->prepare('SELECT commercenom FROM commerce WHERE idcommerce = :id_comm AND commercestatus<>0');
							$stmt->execute(array("id_comm"=>$commerce));
							$donnees=$stmt->fetch();
							echo '<td>' . $donnees["commercenom"]. '</td>';
							$stmt->closeCursor(); 
														
							$stmt = $bdd->prepare('SELECT typecommerce_cataloguetypecommerceID  
							FROM typecommerce WHERE typecommerce_commerceID = :id_comm');
							$stmt->execute(array("id_comm"=>$commerce));
							$donnees=$stmt->fetch();
							
							$stmt2 = $bdd->prepare('SELECT cataloguetypecommercelabel  
							FROM cataloguetypecommerce WHERE idcataloguetypecommerce = :type');
							$stmt2->execute(array("type"=>$donnees["typecommerce_cataloguetypecommerceID"]));
							$donnees2=$stmt2->fetch();
							
							echo '<td>' . $donnees["cataloguetypecommercelabel"]. '</td>';
							$stmt->closeCursor(); 
							$stmt2->closeCursor();
							echo '<td><button class="btn btn-info" onClick="showUser(this.value)" value="'.$commerce.'">Voir le commerce</button></td>';
							echo "</tr>"; 
						}
						?>
                        </tbody>
						</table>
						<?php
						if($i==0)
						{
							echo "<br /><strong>Aucun commerce ne correspond à vos critères de recherche</strong>";
						}
						else 
						    echo $i . " commerces correspondent à vos critères";
					}
					else
					{
						echo "<h2>Aucune recherche effectuée</h2>";
						echo "<p>Effectuez une recherche en utilisant le formulaire.</p>";
					}
					?>
          </div>
      </div>
  </div>
  
  <?php 
  include("includes/footer.php");?>
  <script src="js/jquery.js"></script>

  <script type="text/javascript" src="ajax.js"></script>
  
  <?php include("includes/pied.php");
   ?>