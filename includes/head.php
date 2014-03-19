<?php
session_start();
include("includes/class.user.php");
include("includes/class.newsmanager.php");
//include("includes/class.verif.php");
include("includes/class.form.php");


?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<title>Le ropi - <?php echo $titre_page ?></title>

<!--[if lte IE 8]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]--> 