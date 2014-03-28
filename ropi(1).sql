-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Client: localhost:3306
-- Généré le: Ven 16 Août 2013 à 20:52
-- Version du serveur: 5.5.29-0ubuntu0.12.04.1
-- Version de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `ropi`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

CREATE TABLE IF NOT EXISTS `adresses` (
  `idadresses` varchar(20) NOT NULL,
  `adresses_personnesID` varchar(20) DEFAULT NULL,
  `adresses_catalogueadressesID` int(11) DEFAULT NULL,
  `adressesrue` varchar(45) DEFAULT NULL,
  `adressesnumero` varchar(6) DEFAULT NULL,
  `adressescodepostal` varchar(5) DEFAULT NULL,
  `adresseslocalite` varchar(45) DEFAULT NULL,
  `adressespays` varchar(45) DEFAULT NULL,
  `adressescommerceid` varchar(20) NOT NULL,
  PRIMARY KEY (`idadresses`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `calendrier`
--

CREATE TABLE IF NOT EXISTS `calendrier` (
  `id_calendrier` int(10) NOT NULL AUTO_INCREMENT,
  `titre_calendrier` varchar(128) NOT NULL,
  `description_calendrier` text NOT NULL,
  `date_calendrier` varchar(32) NOT NULL,
  `heure_calendrier` varchar(10) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_calendrier`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `catalogueadresses`
--

CREATE TABLE IF NOT EXISTS `catalogueadresses` (
  `idcatalogueadresses` int(11) NOT NULL AUTO_INCREMENT,
  `catalogueadresselabel` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idcatalogueadresses`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `catalogueadresses`
--

INSERT INTO `catalogueadresses` (`idcatalogueadresses`, `catalogueadresselabel`) VALUES
(1, 'Domicile'),
(2, 'Facturation'),
(3, 'Commerce'),
(4, 'Courier'),
(5, 'Livraison');

-- --------------------------------------------------------

--
-- Structure de la table `cataloguetelephones`
--

CREATE TABLE IF NOT EXISTS `cataloguetelephones` (
  `idcataloguetelephones` int(11) NOT NULL AUTO_INCREMENT,
  `cataloguetelephonelabel` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idcataloguetelephones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Contenu de la table `cataloguetelephones`
--

INSERT INTO `cataloguetelephones` (`idcataloguetelephones`, `cataloguetelephonelabel`) VALUES
(1, 'gsm'),
(2, 'maison'),
(3, 'commerce');

-- --------------------------------------------------------

--
-- Structure de la table `cataloguetypecommerce`
--

CREATE TABLE IF NOT EXISTS `cataloguetypecommerce` (
  `idcataloguetypecommerce` int(11) NOT NULL AUTO_INCREMENT,
  `cataloguetypecommercelabel` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idcataloguetypecommerce`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `cataloguetypecommerce`
--

INSERT INTO `cataloguetypecommerce` (`idcataloguetypecommerce`, `cataloguetypecommercelabel`) VALUES
(1, 'Librairie'),
(2, 'CafÃ©'),
(3, 'Restaurant');

-- --------------------------------------------------------

--
-- Structure de la table `cataloguetypersonne`
--

CREATE TABLE IF NOT EXISTS `cataloguetypersonne` (
  `idcataloguetypersonne` int(11) NOT NULL,
  `cataloguetypersonnelabel` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idcataloguetypersonne`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `cataloguetypersonne`
--

INSERT INTO `cataloguetypersonne` (`idcataloguetypersonne`, `cataloguetypersonnelabel`) VALUES
(1, 'membre'),
(3, 'commercant'),
(9, 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `commerce`
--

CREATE TABLE IF NOT EXISTS `commerce` (
  `idcommerce` varchar(20) NOT NULL,
  `commercenom` varchar(60) DEFAULT NULL,
  `commercestatus` varchar(10) DEFAULT NULL,
  `commerce_adresse_id` int(11) DEFAULT NULL,
  `commerce_compersID` int(11) DEFAULT NULL,
  `commerceurl` varchar(45) DEFAULT NULL,
  `commercelogo` varchar(128) NOT NULL,
  `commerceimage` varchar(128) NOT NULL,
  `commercevaleurpublicitaire` int(11) DEFAULT NULL,
  `commercecontenu` text NOT NULL,
  PRIMARY KEY (`idcommerce`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `commerceproduits`
--

CREATE TABLE IF NOT EXISTS `commerceproduits` (
  `idproduit` int(11) NOT NULL AUTO_INCREMENT,
  `produitnom` varchar(64) NOT NULL,
  `produitdescription` text NOT NULL,
  `produitidcommerce` varchar(20) NOT NULL,
  PRIMARY KEY (`idproduit`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Structure de la table `compers`
--

CREATE TABLE IF NOT EXISTS `compers` (
  `idcompers` int(11) NOT NULL AUTO_INCREMENT,
  `compers_personnesID` varchar(20) DEFAULT NULL,
  `compers_commerceID` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idcompers`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Structure de la table `filleul`
--

CREATE TABLE IF NOT EXISTS `filleul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `rue` varchar(150) NOT NULL,
  `numero` varchar(5) NOT NULL,
  `localite` varchar(150) NOT NULL,
  `cp` int(6) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `parrain_id` varchar(50) NOT NULL,
  `dates` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Structure de la table `galerie`
--

CREATE TABLE IF NOT EXISTS `galerie` (
  `id_image` int(10) NOT NULL AUTO_INCREMENT,
  `galerie_id` int(10) NOT NULL,
  `url_image` varchar(256) NOT NULL,
  `date_image` varchar(64) NOT NULL,
  `titre_image` varchar(256) NOT NULL,
  `description_image` text NOT NULL,
  PRIMARY KEY (`id_image`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(64) NOT NULL,
  `liens` varchar(512) NOT NULL,
  `location` int(2) NOT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `menus_liens`
--

CREATE TABLE IF NOT EXISTS `menus_liens` (
  `id_lien` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `titre` varchar(64) NOT NULL,
  `url` varchar(128) NOT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id_lien`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id_news` int(64) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `titre` varchar(128) NOT NULL,
  `auteur` varchar(32) NOT NULL,
  `ip_auteur` varchar(32) NOT NULL,
  `date_post` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `premier_titre` varchar(128) NOT NULL,
  `nb_vues` int(128) NOT NULL DEFAULT '0',
  `tags` varchar(64) NOT NULL,
  `categorie` varchar(32) NOT NULL,
  `visible` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_news`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `news_cat`
--

CREATE TABLE IF NOT EXISTS `news_cat` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(32) NOT NULL,
  `visible` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `news_parametres`
--

CREATE TABLE IF NOT EXISTS `news_parametres` (
  `id` int(1) NOT NULL,
  `nb_news_par_page` int(10) NOT NULL,
  `nb_news_aff_complet` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `news_parametres`
--

INSERT INTO `news_parametres` (`id`, `nb_news_par_page`, `nb_news_aff_complet`) VALUES
(1, 10, 1);

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id_page` int(10) NOT NULL AUTO_INCREMENT,
  `titre` varchar(64) NOT NULL,
  `text` text NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `date_post` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_auteur` varchar(30) NOT NULL,
  `auteur` varchar(32) NOT NULL,
  `last_modif` varchar(32) NOT NULL DEFAULT 'Aucune',
  `type` varchar(1) NOT NULL DEFAULT '0' COMMENT 'Par défaut : visible',
  PRIMARY KEY (`id_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `pages`
--

INSERT INTO `pages` (`id_page`, `titre`, `text`, `visible`, `date_post`, `ip_auteur`, `auteur`, `last_modif`, `type`) VALUES
(2, 'Nous aider', '<p><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ac congue risus. Suspendisse tempus vulputate justo, a rutrum risus aliquet sit amet. Curabitur eget condimentum dui. Suspendisse potenti. Phasellus eget purus a justo blandit scelerisque in ut mi. Donec sed fermentum ipsum. Sed eget tincidunt magna. Integer diam ipsum, pretium placerat pretium eget, sagittis nec justo. Phasellus at posuere augue. Integer congue, enim ut vestibulum tincidunt, massa lorem dignissim augue, eu mollis purus lectus quis libero. Nunc et aliquet odio. Vivamus eu odio nulla. Morbi ut velit quam. Cras rhoncus enim ut massa dapibus consectetur.</span></p>', 0, '2013-02-24 14:48:43', '127.0.0.1', 'BloodKalan', 'Aucune', '0'),
(4, 'L''Ã©quipe', '<table style="height: 38px; width: 100%;" border="1">\r\n<tbody>\r\n<tr>\r\n<td rowspan="2">Image</td>\r\n<td>Nom Pr&eacute;nom</td>\r\n</tr>\r\n<tr>\r\n<td><span>Proin nec sem in nisl luctus ultricies ac in felis. Proin non tempus erat.</span></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="width: 100%;" border="1">\r\n<tbody>\r\n<tr>\r\n<td rowspan="2">Image</td>\r\n<td>Nom Pr&eacute;nom</td>\r\n</tr>\r\n<tr>\r\n<td>Proin nec sem in nisl luctus ultricies ac in felis. Proin non tempus erat.<br /><br /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="width: 100%;" border="1">\r\n<tbody>\r\n<tr>\r\n<td rowspan="2">Image</td>\r\n<td>Nom Pr&eacute;nom</td>\r\n</tr>\r\n<tr>\r\n<td>Proin nec sem in nisl luctus ultricies ac in felis. Proin non tempus erat.<br /><br /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', 0, '2013-02-25 00:52:00', '127.0.0.1', 'BloodKalan', 'Aucune', '0'),
(5, 'Pourquoi adhÃ©rer ?', '<h1>Titre header H1<br /><br /></h1>\r\n<h2>Titre header H2<br /><br /></h2>\r\n<h3>Titre header H3<br /><br /></h3>\r\n<h4>Titre header H4<br /><br /></h4>\r\n<h5>Titre header H5<br /><br /></h5>\r\n<h6>Titre header H6</h6>\r\n<p>&nbsp;Taille de la police : <span style="font-size: xx-small;">Taille 1</span> -&nbsp;<span style="font-size: x-small;">Taille 2</span> -&nbsp;<span style="font-size: small;">Taille 3</span> -&nbsp;<span style="font-size: medium;">Taille 4</span> -&nbsp;<span style="font-size: large;">Taille 5</span> -&nbsp;<span style="font-size: x-large;">Taille 6</span> -&nbsp;<span style="font-size: xx-large;">Taille 7</span></p>\r\n<p><strong>Gras&nbsp;</strong>- <em>Italique&nbsp;</em>- <span style="text-decoration: underline;">soulign&eacute;&nbsp;</span>- <span style="text-decoration: line-through;">Barr&eacute;</span>&nbsp;- <sub>indice</sub> - <sup>exposant</sup> -&nbsp;<img title="Cool" src="includes/tiny_mce/plugins/emotions/img/smiley-cool.gif" alt="Cool" border="0" /><img title="En pleurs" src="includes/tiny_mce/plugins/emotions/img/smiley-cry.gif" alt="En pleurs" border="0" /><img title="Embarrass&eacute;" src="includes/tiny_mce/plugins/emotions/img/smiley-embarassed.gif" alt="Embarrass&eacute;" border="0" /><img title="Pied de nez" src="includes/tiny_mce/plugins/emotions/img/smiley-foot-in-mouth.gif" alt="Pied de nez" border="0" /><img title="D&eacute;&ccedil;u" src="includes/tiny_mce/plugins/emotions/img/smiley-frown.gif" alt="D&eacute;&ccedil;u" border="0" /><img title="Innocent" src="includes/tiny_mce/plugins/emotions/img/smiley-innocent.gif" alt="Innocent" border="0" /><img title="Bisou" src="includes/tiny_mce/plugins/emotions/img/smiley-kiss.gif" alt="Bisou" border="0" /><img title="Rigolant" src="includes/tiny_mce/plugins/emotions/img/smiley-laughing.gif" alt="Rigolant" border="0" /><img title="Avare" src="includes/tiny_mce/plugins/emotions/img/smiley-money-mouth.gif" alt="Avare" border="0" /><img title="Bouche cousue" src="includes/tiny_mce/plugins/emotions/img/smiley-sealed.gif" alt="Bouche cousue" border="0" /><img title="Sourire" src="includes/tiny_mce/plugins/emotions/img/smiley-smile.gif" alt="Sourire" border="0" /><img title="Surpris" src="includes/tiny_mce/plugins/emotions/img/smiley-surprised.gif" alt="Surpris" border="0" /><img title="Langue tir&eacute;e" src="includes/tiny_mce/plugins/emotions/img/smiley-tongue-out.gif" alt="Langue tir&eacute;e" border="0" /><img title="Incertain" src="includes/tiny_mce/plugins/emotions/img/smiley-undecided.gif" alt="Incertain" border="0" /><img title="Clin d''&oelig;il" src="includes/tiny_mce/plugins/emotions/img/smiley-wink.gif" alt="Clin d''&oelig;il" border="0" /><img title="Criant" src="includes/tiny_mce/plugins/emotions/img/smiley-yell.gif" alt="Criant" border="0" /></p>\r\n<p>Liste</p>\r\n<ul style="list-style-type: disc;">\r\n<li>&Eacute;lement 1</li>\r\n<li>&Eacute;lement 2</li>\r\n<li>&Eacute;lement 3</li>\r\n</ul>\r\n<ul style="list-style-type: circle;">\r\n<li>&Eacute;lement 1</li>\r\n<li>&Eacute;lement 2</li>\r\n<li>&Eacute;lement 3</li>\r\n</ul>\r\n<ul style="list-style-type: square;">\r\n<li>&Eacute;lement 1</li>\r\n<li>&Eacute;lement 2</li>\r\n<li>&Eacute;lement 3</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<ol>\r\n<li>&Eacute;lement 1</li>\r\n<li>&Eacute;lement 2</li>\r\n<li>&Eacute;lement 3</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<ol style="list-style-type: lower-greek;">\r\n<li>&Eacute;lement 1</li>\r\n<li>&Eacute;lement 2</li>\r\n<li>&Eacute;lement 3</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<ol style="list-style-type: lower-roman;">\r\n<li>&Eacute;lement 1</li>\r\n<li>&Eacute;lement 2</li>\r\n<li>&Eacute;lement 3</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<ol style="list-style-type: upper-alpha;">\r\n<li>&Eacute;lement 1</li>\r\n<li>&Eacute;lement 2</li>\r\n<li>&Eacute;lement 3</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<ol style="list-style-type: upper-roman;">\r\n<li>&Eacute;lement 1</li>\r\n<li>&Eacute;lement 2</li>\r\n<li>&Eacute;lement 3</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<p style="margin-left: 30px;">&nbsp;Texte indent&eacute;</p>\r\n<p style="margin-left: 30px;">&nbsp;</p>\r\n<blockquote>\r\n<p>Citation</p>\r\n</blockquote>\r\n<p>&nbsp;25-02-2013&nbsp;03:15:08</p>\r\n<p>Couleurs <span style="color: #ff0000;">rouge</span> <span style="color: #000080;">bleu</span> <span style="color: #008000;">vert</span> <span style="color: #ff6600;">orange</span> <span style="color: #800080;">violet </span>...</p>\r\n<p>Surlignement &nbsp;<span style="background-color: #ff0000;">rouge</span> <span style="background-color: #99ccff;">bleu</span> <span style="background-color: #00ff00;">vert</span>&nbsp;<span style="background-color: #ffff00;">jaune&nbsp;</span><span style="background-color: #800080;">violet</span></p>\r\n<table style="height: 92px; width: 771px;" border="1">\r\n<tbody>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Proin nec sem in nisl luctus ultricies ac in felis. Proin non tempus erat. Vivamus vitae neque eros. Quisque vitae fermentum nisl. Cras posuere tortor ut ligula tempus ornare. In sed neque sed dui placerat aliquet. Nunc quis leo at leo congue elementum. Praesent gravida euismod magna, et posuere neque mollis sit amet. Morbi vitae tincidunt nisi. Nam vel est leo. Morbi vel libero lacus, id tincidunt turpis. Sed sed diam vel neque ultricies malesuada id in nisl. Integer vitae risus nisl, vitae porta nisl. Fusce facilisis tellus eget massa pharetra blandit. Suspendisse mauris velit, auctor eu scelerisque in, congue at nisi.</p>', 0, '2013-02-25 11:44:57', '127.0.0.1', 'BloodKalan', '2013-02-25 11:44:57', '0'),
(6, 'En pratique', '<p><span>Duis accumsan dictum varius. Mauris eu accumsan lacus. Curabitur mollis blandit ante, id venenatis odio elementum ut. Vestibulum arcu arcu, ultricies ut laoreet lacinia, viverra nec dui. Praesent arcu enim, eleifend non consectetur ut, bibendum sed diam. Sed et est dolor. Pellentesque luctus, nisi ut sollicitudin consequat, enim ligula semper velit, at adipiscing odio arcu eu neque. Nulla sit amet lectus erat. Curabitur scelerisque pulvinar diam, eget viverra lacus elementum vitae. Donec porttitor gravida sem. Ut ipsum mauris, egestas id accumsan non, sodales in risus. Aliquam auctor interdum dui, quis tincidunt leo dignissim vel. Donec et sem quam. Vestibulum ipsum neque, gravida eget cursus a, varius eget risus.</span></p>', 0, '2013-02-25 01:52:09', '127.0.0.1', 'BloodKalan', 'Aucune', '0'),
(7, 'La charte', '<p><span>la charte In interdum, nisi sit amet volutpat tempus, sapien lacus pharetra libero, eu vulputate nulla diam sit amet tortor. Quisque mi libero, pellentesque id porta sed, sodales sed est. Sed gravida fermentum justo vel lacinia. Nunc eu congue nibh. Vivamus nunc velit, egestas eget luctus id, sodales viverra lorem. Etiam facilisis, neque sodales venenatis egestas, augue lacus pulvinar felis, eget vulputate elit purus eu sem. Donec vitae felis ac urna ultricies gravida quis ac metus. Sed sem tortor, feugiat nec vestibulum vitae, bibendum et tellus. In nec purus dolor. Mauris est nibh, porta id semper vitae, lobortis quis ligula. Morbi quis tincidunt ligula.</span></p>', 0, '2013-07-14 10:00:28', '127.0.0.1', 'BloodKalan', '2013-07-14 12:00:28', '0'),
(8, 'Page d''accueil : texte de prÃ©sentation', '<h1>Lorem ipsum dolor sit amet</h1>\r\n<p>&nbsp;</p>\r\n<p><span>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed.</span></p>', 0, '2013-07-05 20:28:58', '127.0.0.1', 'BloodKalan', '2013-07-05 22:28:58', '0'),
(9, 'Calendrier', '<p><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ac congue risus. Suspendisse tempus vulputate justo, a rutrum risus aliquet sit amet. Curabitur eget condimentum dui. Suspendisse potenti. Phasellus eget purus a justo blandit scelerisque in ut mi. Donec sed fermentum ipsum. Sed eget tincidunt magna. Integer diam ipsum, pretium placerat pretium eget, sagittis nec justo. Phasellus at posuere augue. Integer congue, enim ut vestibulum tincidunt, massa lorem dignissim augue, eu mollis purus lectus quis libero. Nunc et aliquet odio. Vivamus eu odio nulla. Morbi ut velit quam. Cras rhoncus enim ut massa dapibus consectetur.</span></p>\r\n<p style="margin-left: 30px;">azzara</p>', 0, '2013-03-21 10:31:49', '127.0.0.1', 'BloodKalan', '2013-03-21 10:31:49', '0'),
(10, 'La carte', '<p>Lorem Ipsum Dolor sit amet consectetur</p>', 0, '2013-03-21 23:59:51', '127.0.0.1', 'bloodkalan', 'Aucune', '0'),
(11, 'Presse', '<p>La liste des articles de presse :&nbsp;</p>', 0, '2013-06-27 09:51:53', '127.0.0.1', 'bloodkalan', 'Aucune', '0'),
(12, 'Outils de communication', '<p><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc semper auctor felis sit amet volutpat. Vestibulum et ligula erat. Aenean velit sapien, dignissim quis est sit amet, sollicitudin vulputate nulla. Morbi fringilla diam in odio commodo vehicula. Mauris placerat turpis ipsum, sed rhoncus ipsum tempor in. Integer porttitor purus sit amet dui vestibulum, eu tempus ante vulputate. Proin in elementum eros, eget venenatis ligula. Nam aliquam lorem quis lorem ornare consectetur. Aliquam turpis metus, consequat at nulla quis, interdum aliquam est. Integer luctus semper nisl, non ullamcorper nunc consectetur vitae. Nunc at leo ac eros pulvinar auctor.</span></p>', 0, '2013-07-01 23:21:53', '127.0.0.1', 'bloodkalan', 'Aucune', '0'),
(13, 'Conventions', '<p><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc semper auctor felis sit amet volutpat. Vestibulum et ligula erat. Aenean velit sapien, dignissim quis est sit amet, sollicitudin vulputate nulla. Morbi fringilla diam in odio commodo vehicula. Mauris placerat turpis ipsum, sed rhoncus ipsum tempor in. Integer porttitor purus sit amet dui vestibulum, eu tempus ante vulputate. Proin in elementum eros, eget venenatis ligula. Nam aliquam lorem quis lorem ornare consectetur. Aliquam turpis metus, consequat at nulla quis, interdum aliquam est. Integer luctus semper nisl, non ullamcorper nunc consectetur vitae. Nunc at leo ac eros pulvinar auctor.</span></p>', 0, '2013-07-01 23:24:36', '127.0.0.1', 'bloodkalan', 'Aucune', '0'),
(14, 'Comptes rendus', '<p><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc semper auctor felis sit amet volutpat. Vestibulum et ligula erat. Aenean velit sapien, dignissim quis est sit amet, sollicitudin vulputate nulla. Morbi fringilla diam in odio commodo vehicula. Mauris placerat turpis ipsum, sed rhoncus ipsum tempor in. Integer porttitor purus sit amet dui vestibulum, eu tempus ante vulputate. Proin in elementum eros, eget venenatis ligula. Nam aliquam lorem quis lorem ornare consectetur. Aliquam turpis metus, consequat at nulla quis, interdum aliquam est. Integer luctus semper nisl, non ullamcorper nunc consectetur vitae. Nunc at leo ac eros pulvinar auctor.</span></p>', 0, '2013-07-01 23:24:47', '127.0.0.1', 'bloodkalan', 'Aucune', '0'),
(15, 'ComptabilitÃ©', '<p><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc semper auctor felis sit amet volutpat. Vestibulum et ligula erat. Aenean velit sapien, dignissim quis est sit amet, sollicitudin vulputate nulla. Morbi fringilla diam in odio commodo vehicula. Mauris placerat turpis ipsum, sed rhoncus ipsum tempor in. Integer porttitor purus sit amet dui vestibulum, eu tempus ante vulputate. Proin in elementum eros, eget venenatis ligula. Nam aliquam lorem quis lorem ornare consectetur. Aliquam turpis metus, consequat at nulla quis, interdum aliquam est. Integer luctus semper nisl, non ullamcorper nunc consectetur vitae. Nunc at leo ac eros pulvinar auctor.</span></p>', 0, '2013-07-01 23:24:58', '127.0.0.1', 'bloodkalan', 'Aucune', '0'),
(16, 'Statut de l''ASBL', '<p><span>Ceci est le status de l''ASBL....Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc semper auctor felis sit amet volutpat. Vestibulum et ligula erat. Aenean velit sapien, dignissim quis est sit amet, sollicitudin vulputate nulla. Morbi fringilla diam in odio commodo vehicula. Mauris placerat turpis ipsum, sed rhoncus ipsum tempor in. Integer porttitor purus sit amet dui vestibulum, eu tempus ante vulputate. Proin in elementum eros, eget venenatis ligula. Nam aliquam lorem quis lorem ornare consectetur. Aliquam turpis metus, consequat at nulla quis, interdum aliquam est. Integer luctus semper nisl, non ullamcorper nunc consectetur vitae. Nunc at leo ac eros pulvinar auctor.</span></p>', 0, '2013-07-13 16:21:09', '127.0.0.1', 'bloodkalan', '2013-07-13 18:21:09', '0'),
(17, 'VIDEO ACCUEIL', '<p><iframe src="http://player.vimeo.com/video/8747975?color=73879f" frameborder="0" width="320" height="240"></iframe></p>', 0, '2013-07-05 20:12:30', '91.176.58.139', '51d6da5c4d883', 'Aucune', '0');

-- --------------------------------------------------------

--
-- Structure de la table `personnes`
--

CREATE TABLE IF NOT EXISTS `personnes` (
  `idpersonnes` varchar(30) NOT NULL,
  `nompersonnes` varchar(45) DEFAULT NULL,
  `prenompersonnes` varchar(45) DEFAULT NULL,
  `mailpersonnes` varchar(45) DEFAULT NULL,
  `passwordpersonnes` varchar(45) DEFAULT NULL,
  `personnespseudo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idpersonnes`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `personnes`
--

INSERT INTO `personnes` (`idpersonnes`, `nompersonnes`, `prenompersonnes`, `mailpersonnes`, `passwordpersonnes`, `personnespseudo`) VALUES
('', 'commerce', NULL, NULL, 'commerce', NULL),
('51d6da5c4d883', 'admin', 'admin', 'admin@ropi.be', 'e10adc3949ba59abbe56e057f20f883e', 'admin'),
('bloodkalan', 'Keller', 'Geoffrey', 'geo.blondiau@gmail.com', '739ae4b42b51d0157d9477b0ab90a629', 'bloodkalan');

-- --------------------------------------------------------

--
-- Structure de la table `proposition`
--

CREATE TABLE IF NOT EXISTS `proposition` (
  `idproposition` int(11) NOT NULL AUTO_INCREMENT,
  `personne_id` int(11) NOT NULL,
  `produit` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `commerce` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dates` date NOT NULL,
  PRIMARY KEY (`idproposition`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Structure de la table `ropi`
--

CREATE TABLE IF NOT EXISTS `ropi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ropi_circulation` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `ropi`
--

INSERT INTO `ropi` (`id`, `ropi_circulation`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `statistiques`
--

CREATE TABLE IF NOT EXISTS `statistiques` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `id_date` varchar(10) NOT NULL,
  `vues` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Contenu de la table `statistiques`
--

INSERT INTO `statistiques` (`id`, `id_date`, `vues`) VALUES
(1, '21/12/12', 90),
(2, '22/12/12', 2),
(3, '23/12/12', 19),
(4, '24/12/12', 31),
(5, '25/12/12', 13),
(6, '26/12/12', 9),
(7, '27/12/12', 14),
(8, '28/12/12', 16),
(9, '29/12/12', 1),
(10, '30/12/12', 1),
(11, '31/12/12', 1),
(12, '01/01/13', 1),
(13, '02/01/13', 1),
(14, '03/01/13', 1),
(15, '04/01/13', 1),
(16, '05/01/13', 1),
(17, '06/01/13', 1),
(18, '11/01/13', 3),
(19, '13/01/13', 4),
(20, '14/01/13', 1),
(21, '21/01/13', 1),
(22, '12/02/13', 4),
(23, '17/02/13', 1),
(24, '19/02/13', 1),
(25, '20/02/13', 1),
(26, '22/02/13', 1),
(27, '23/02/13', 1);

-- --------------------------------------------------------

--
-- Structure de la table `telephones`
--

CREATE TABLE IF NOT EXISTS `telephones` (
  `idtelephones` int(11) NOT NULL AUTO_INCREMENT,
  `telephone_commerce` varchar(16) NOT NULL,
  `telephone_gsm` varchar(16) NOT NULL,
  `telephone_idcommerce` varchar(32) NOT NULL,
  PRIMARY KEY (`idtelephones`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `typecommerce`
--

CREATE TABLE IF NOT EXISTS `typecommerce` (
  `idtypecommerce` int(11) NOT NULL AUTO_INCREMENT,
  `typecommerce_cataloguetypecommerceID` int(11) DEFAULT NULL,
  `typecommerce_commerceID` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idtypecommerce`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `typecommerce`
--

INSERT INTO `typecommerce` (`idtypecommerce`, `typecommerce_cataloguetypecommerceID`, `typecommerce_commerceID`) VALUES
(1, 2, 'comm51d78d6e0b4f1'),
(2, NULL, 'comm51deeb2b9426b');

-- --------------------------------------------------------

--
-- Structure de la table `typepersonnepersonne`
--

CREATE TABLE IF NOT EXISTS `typepersonnepersonne` (
  `idcatalogue_type_personne` int(11) NOT NULL AUTO_INCREMENT,
  `typeperonne_idpersonnes` varchar(32) DEFAULT NULL,
  `typepersonnepersonne_cataloguetypepersonne` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcatalogue_type_personne`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `typepersonnepersonne`
--

INSERT INTO `typepersonnepersonne` (`idcatalogue_type_personne`, `typeperonne_idpersonnes`, `typepersonnepersonne_cataloguetypepersonne`) VALUES
(1, 'bloodkalan', 9);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
