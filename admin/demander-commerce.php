<?php
session_start();
include("../includes/db_connect.php");
include("../includes/class.user.php");
include("../includes/functions.php");
include("../includes/class.verif.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd,$_SESSION["id"],$_SESSION["password"],$_SESSION["niveau"],3);


$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
<a class="current">Proposer un commerce ou un produit</a>';

function Status($status)
{
	switch ($status)
	{
		case 1: return "Actif";
		    break;
	}
}


include("includes/header.php");

?>
	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Demande de recherche de commerce ou de produits</h3></header>
			<div class="module_content">
            	Dans cette page vous pouvezdemander la recherche d'un type de commerce adhérant au ropi ou d'un type de produit.
                
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		
		<article class="module width_full">
		<header><h3 class="tabs_involved">Vos propositions de commerce ou de produits</h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			
			<thead> 
				
			</thead> 
			<tbody> 
            <form name="maforme" action="demander-commerce.php" enctype="multipart/form-data">
            	<?php
				$i=0;
				$stmt = $bdd->prepare('SELECT * FROM compers WHERE compers_personnesID = :id');
				$stmt->execute(array("id"=>$_SESSION["id"]));
					

				?>	
					 <br />
					<label for="commerce" style="width:130px;display:block;float:left;padding-left:5px" >Type de commerce : </label>
					<input type="text" name="typecommerce" id="typecommerce" placeholder="Type de commerce" 	value="" data-provide="typeahead" data-items="4" />
                         
                     <br />
                     <br />   
                     <label for="produit" style="width:130px;display:block;float:left;padding-left:5px">Type de produit : </label>
                     <input type="text" name="Typeproduit" id="Typeproduit" placeholder="Type de produit" value="" data-provide="typeahead" data-items="4" />
                     
                     <br><br>
					 <br />
                     <input type="submit" value="envoyer" name="envoyer" id="envoyer">
                      <br><br>
					 <br />
					<?php
                   
				   
				   
				 					
						
					if(isset($_REQUEST["envoyer"])) {
							
					  $produit=(isset($_REQUEST["Typeproduit"])!="") ? $_REQUEST["Typeproduit"] : '';					  
				      $commerce=(isset($_REQUEST["typecommerce"])!="") ? $_REQUEST["typecommerce"] : '';					 
					 					  							
					  $req = $bdd->prepare('INSERT INTO proposition (personne_id,produit,commerce, dates) VALUES (:personne_id,:produit,:commerce,CURDATE())');					
					  $req->execute(array('personne_id'=>$_SESSION['id'],
                 						   'produit'=>$produit,
										   'commerce'=>$commerce									   
									));
					}				
				
				
				?>
                </form>
			</tbody> 
			
          
            
			</div><!-- end of #tab1 -->			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
        		
			<footer>
			</footer>
		</article><!-- end of messages article -->

   		<div class="clear"></div>
		
			
                
                
		
		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>