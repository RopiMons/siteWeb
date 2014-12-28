<?php
include("includes/db_connect.php");
include("includes/class.parametres.php");
$q=$_GET["q"];
$stmt = $bdd->prepare('SELECT commercenom,commercecontenu,commerceurl,commercelogo FROM commerce WHERE idcommerce =:id');
$stmt->execute(array("id"=>$q));
$donnees=$stmt->fetch();
$nom = $donnees['commercenom'];
$contenu = $donnees['commercecontenu'];
$url = $donnees["commerceurl"];
$image = Parametres::getUploadLogoFolder().$donnees["commercelogo"];;
$stmt->closeCursor();

$stmt = $bdd->prepare('SELECT * FROM adresses WHERE adressescommerceid =:id AND adresses_catalogueadressesID = 3');
$stmt->execute(array("id"=>$q));
$donnees=$stmt->fetch();
$rue=$donnees["adressesrue"];
$numero = $donnees["adressesnumero"];
$cp = $donnees["adressescodepostal"];
$localite = $donnees["adresseslocalite"];
$pays = $donnees["adressespays"];
$stmt->closeCursor();

$stmt = $bdd->prepare('SELECT * FROM telephones WHERE telephone_idcommerce =:id ');
$stmt->execute(array("id"=>$q));
$donnees=$stmt->fetch();
$tel=$donnees["telephone_commerce"];
$stmt->closeCursor();

$produits_liste="";
$stmt = $bdd->prepare('SELECT * FROM commerceproduits WHERE produitidcommerce =:id ');
$stmt->execute(array("id"=>$q));
while($donnees=$stmt->fetch())
{
        $description = strip_tags($donnees["produitdescription"]);
        $produits_liste.= '<li><a class="produit" rel="tooltip" href="#" data-original-title="'.$description.'">'.$donnees["produitnom"]."</a></li>";
}
$stmt->closeCursor();






include("includes/functions.php");
$titre_page=TitrePage($bdd,"",$nom);
include("includes/head.php");
include("includes/menu.php");
?>
<div class="gris_clair">
     <div class="row-fluid corps">
         <div class="span12">
             <?php echo '<img class="text-center" src="'.$image.'" style="max-height:150px"/>';?>
          </div>
      </div>
      
      <div class="row-fluid corps">
         <div class="span6">
             
             <?php echo "<h1 class='text-center'>". $nom . "</h1>";?>
          </div>
      </div>
      
      
      <div class="row-fluid corps">
         <div class="span5">
         
             <?php  echo  $contenu ?>
             
             <h5>Adresse et contact</h5>
             <?php 
			 if($numero!="" && $rue!="")
			     echo "<p>". $numero . ", " . $rue . "<br/>" . $cp . " " . $localite."</p>";
			 else
			     echo "<p>Aucune adresse n'a été fournie.</p>";
			 if($tel!="")
			     echo "<p>Téléphone : "  . $tel . "</p>";
				 
			  ?>
          </div>
          <div class="span2">
          <?php if($produits_liste!="")
		  {
             echo '<h3>Exemples de produits</h3>';
             echo "<ul>". $produits_liste . "</ul>";
		  }
		  
			 
		  ?> 
             
          </div>
          <div class="span5">
             <?php $coords=getXmlCoordsFromAdress($numero . " " . $rue . " " . $cp . " " . $localite . " " . $pays);
			  ?>
              <div id="map-canvas" style="min-height:200px;" class="embed-box"></div>

             
          </div>
      </div>
      <div class="row-fluid corps">
         <div class="span12">
             <?php if($url)
			 {
				 echo '<a href="'.$url.' class="btn btn-large" title="Site du commerce">Voir le site du commerce</a>';
			 }?>
          </div>
      </div>
  </div>
  
  <?php 
  function getXmlCoordsFromAdress($address)
{
$coords=array();
$base_url="http://maps.googleapis.com/maps/api/geocode/xml?";
// ajouter &region=FR si ambiguité (lieu de la requete pris par défaut)
$request_url = $base_url . "address=" . urlencode($address).'&sensor=false';
$xml = simplexml_load_file($request_url) or die("url not loading");
//print_r($xml);
$coords['lat']=$coords['lon']='';
$coords['status'] = $xml->status ;
if($coords['status']=='OK')
{
 $coords['lat'] = $xml->result->geometry->location->lat ;
 $coords['lon'] = $xml->result->geometry->location->lng ;
}
return $coords;
}
  include("includes/footer.php");?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script>
function initialize() {
  var myLatlng = new google.maps.LatLng(<?php echo $coords["lat"].','.$coords["lon"] ?>);
  var mapOptions = {
    zoom: 15,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Hello World!'
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
    <script type="text/javascript">
    $(function () {
        $("[rel='tooltip']").tooltip({placement:'right'});
        $(".produit").click(function(){alert($(this).attr("data-original-title"));});
    });
    
</script>
  <?php include("includes/pied.php");
   ?>