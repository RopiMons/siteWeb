<?php
$q=$_GET["q"];

include("includes/db_connect.php");
	

$stmt = $bdd->prepare('SELECT commercenom,commercecontenu FROM commerce WHERE idcommerce =:id');
$stmt->execute(array("id"=>$q));
$donnees=$stmt->fetch();
$nom = $donnees['commercenom'];
$contenu = $donnees['commercecontenu'];
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


//$coords=getXmlCoordsFromAdress($numero . " " . $rue . " " . $cp . " " . $localite . " " . $pays);





 echo "<h1>" . $nom . "</h1>";
 echo '<div class="row-fluid">
           <div class="span8">
		       '.$contenu.'
		   </div>
		   <div class="span4">
		   </div>';

/*echo '<div id="map-canvas" style="height: 400px; width:400px;"></div>';
echo '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>';
echo ' <script>
function initialize() {
  var myLatlng = new google.maps.LatLng('.$coords["lat"].','.$coords["lon"].");
  var mapOptions = {
    zoom: 13,
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

    </script>";*/
echo "</div>";
echo '<div class="row-fluid">
        <div class="span6">
		    <h4>Adresse</h4>
			'. $numero . " " . $rue . " " . $cp . " " . $localite . " " . $pays . '
	    </div>
	    <div class="span6">
		    <a href="commerce.php?q='.$q.'" class="btn btn-info btn-large" > Voir le commerce en détails </a>
	    </div>
	</div>
	';



/* function getXmlCoordsFromAdress($address)
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
*/
?>