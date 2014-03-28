<?php
include("includes/db_connect.php");
include("includes/functions.php");
$titre_page=TitrePage($bdd,6);
$page = AffPage($bdd,6); // + Titre de la page
include("includes/head.php");
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

<?php
$liste_commerce="";
$stmt = $bdd->prepare('SELECT * FROM adresses WHERE adresses_catalogueadressesID=3');
$stmt->execute();
while($donnees=$stmt->fetch())
{
    $stmt2 = $bdd->prepare('SELECT * FROM commerce WHERE idcommerce=:id');
    $stmt2->execute(array("id"=>$donnees["adressescommerceid"]));
    $donnees2=$stmt2->fetch();
    $nomcommerce = $donnees2["commercenom"];
    $stmt2->closeCursor();
	
	$coords=getXmlCoordsFromAdress($donnees["adressesnumero"] . ' ' . $donnees["adressesrue"] . ' ' . $donnees["adressescodepostal"].' '.$donnees["adresseslocalite"].' ' .$donnees["adressespays"]);
	
	if($liste_commerce!="")
	    $liste_commerce.=",";
	$liste_commerce.="['".$nomcommerce."',".$coords['lat'].",".$coords['lon']."]";
}
$stmt->closeCursor();
?>



<?php
include("includes/menu.php");
?>
<div class="gris_clair">
     <div class="row-fluid corps">
         <div class="span12">
             <?php echo $page; ?>						
            <div id="map" style="width:100%;height: 300px;"></div>

          </div>
      </div>
  </div>
  
  <?php 
  
  
  include("includes/footer.php");
  
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
?>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">
    var locations = [
      <?=$liste_commerce?>
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 13,
      center: new google.maps.LatLng(50.454608, 3.952521),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  </script>  <script src="js/jquery.js"></script>
  <?php include("includes/pied.php");
   ?>