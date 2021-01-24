-- MariaDB dump 10.17  Distrib 10.4.11-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: di_3a_projet
-- ------------------------------------------------------
-- Server version	10.4.11-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appareil`
--

DROP TABLE IF EXISTS `appareil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appareil` (
  `idAppareil` int(11) NOT NULL,
  `libelleAppareil` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `etat` tinyint(1) DEFAULT NULL,
  `descriptionPosition` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `idImmeuble` int(11) NOT NULL,
  `idAppartement` int(11) NOT NULL,
  `idPiece` int(11) NOT NULL,
  `idTypeAppareil` int(11) NOT NULL,
  PRIMARY KEY (`idAppareil`,`idImmeuble`,`idAppartement`,`idPiece`,`idTypeAppareil`),
  KEY `idImmeuble` (`idImmeuble`,`idAppartement`,`idPiece`),
  KEY `idTypeAppareil` (`idTypeAppareil`),
  CONSTRAINT `appareil_ibfk_1` FOREIGN KEY (`idImmeuble`, `idAppartement`, `idPiece`) REFERENCES `piece` (`idImmeuble`, `idAppartement`, `idPiece`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `appareil_ibfk_2` FOREIGN KEY (`idTypeAppareil`) REFERENCES `typeappareil` (`idTypeAppareil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `id_relatif_appareil_insert` BEFORE INSERT ON `appareil` FOR EACH ROW BEGIN
    DECLARE maxNbAppareil INT;
    SELECT MAX(idAppareil) INTO maxNbAppareil FROM appareil WHERE idImmeuble = new.idImmeuble AND idAppartement = new.idAppartement AND idPiece = new.idPiece;

    IF maxNbAppareil IS NULL THEN
        SET maxNbAppareil := 1;
    ELSE
    	SET maxNbAppareil := maxNbAppareil + 1;
    END IF;
    
    SET new.idAppareil = maxNbAppareil;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `insert_appareil_allume` AFTER INSERT ON `appareil` FOR EACH ROW BEGIN
    IF new.etat = 1 THEN
        INSERT INTO historiquefonctionnement 
		(idImmeuble,  	 idAppartement,  	idPiece,  	 idAppareil, 	 debutFonctionnement) VALUES
		(new.idImmeuble, new.idAppartement, new.idPiece, new.idAppareil, NOW()				);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_etat_appareil` BEFORE UPDATE ON `appareil` FOR EACH ROW BEGIN
    
    DECLARE etatAppareil TINYINT(1);
    
    SELECT etat INTO etatAppareil
    FROM appareil 
    WHERE idImmeuble = new.idImmeuble
    AND idAppartement = new.idAppartement
    AND idPiece = new.idPiece
    AND idAppareil = new.idAppareil;

    IF new.etat != etatAppareil THEN
      IF new.etat = 1 THEN

        INSERT INTO historiquefonctionnement 
        (idImmeuble,  	 idAppartement,  	idPiece,  	 idAppareil, 	 debutFonctionnement) VALUES
        (new.idImmeuble, new.idAppartement, new.idPiece, new.idAppareil, NOW()				);

      ELSE

        UPDATE historiquefonctionnement
        SET finFonctionnement = NOW() 
        WHERE idImmeuble = new.idImmeuble
        AND idAppartement = new.idAppartement
        AND idPiece = new.idPiece
        AND idAppareil = new.idAppareil
        AND finFonctionnement IS NULL;


      END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `id_relatif_appareil_update` BEFORE UPDATE ON `appareil` FOR EACH ROW BEGIN
    DECLARE maxNbAppareil INT;
    SELECT MAX(idAppareil) INTO maxNbAppareil FROM appareil WHERE idImmeuble = new.idImmeuble AND idAppartement = new.idAppartement AND idPiece = new.idPiece;

    IF maxNbAppareil IS NULL THEN
        SET maxNbAppareil := 1;
    ELSE
    	SET maxNbAppareil := maxNbAppareil + 1;
    END IF;
    
    SET new.idAppareil = maxNbAppareil;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `appartement`
--

DROP TABLE IF EXISTS `appartement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appartement` (
  `idImmeuble` int(11) NOT NULL,
  `idAppartement` int(11) NOT NULL,
  `idDegreSecurite` int(11) NOT NULL,
  `idTypeAppart` int(11) NOT NULL,
  PRIMARY KEY (`idImmeuble`,`idAppartement`),
  KEY `idDegreSecurite` (`idDegreSecurite`),
  KEY `idTypeAppart` (`idTypeAppart`),
  CONSTRAINT `appartement_ibfk_1` FOREIGN KEY (`idImmeuble`) REFERENCES `immeuble` (`idImmeuble`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `appartement_ibfk_2` FOREIGN KEY (`idDegreSecurite`) REFERENCES `degresecurite` (`idDegreSecurite`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `appartement_ibfk_3` FOREIGN KEY (`idTypeAppart`) REFERENCES `typeappartement` (`idTypeAppart`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `id_relatif_appartement_insert` BEFORE INSERT ON `appartement` FOR EACH ROW BEGIN
    DECLARE maxNbAppart int;
    SELECT MAX(idAppartement) INTO maxNbAppart FROM appartement WHERE idImmeuble = new.idImmeuble;

    IF maxNbAppart IS NULL THEN
        SET maxNbAppart := 1;
    ELSE
    	SET maxNbAppart := maxNbAppart + 1;
    END IF;
    
    SET new.idAppartement = maxNbAppart;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `id_relatif_appartement_update` BEFORE UPDATE ON `appartement` FOR EACH ROW BEGIN
    DECLARE maxNbAppart int;
    SELECT MAX(idAppartement) INTO maxNbAppart FROM appartement WHERE idImmeuble = new.idImmeuble;

    IF maxNbAppart IS NULL THEN
        SET maxNbAppart := 1;
    ELSE
    	SET maxNbAppart := maxNbAppart + 1;
    END IF;
    
    SET new.idAppartement = maxNbAppart;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `consommer`
--

DROP TABLE IF EXISTS `consommer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consommer` (
  `libelle` varchar(50) COLLATE utf8_bin NOT NULL,
  `idTypeAppareil` int(11) NOT NULL,
  `consommationHoraire` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`libelle`,`idTypeAppareil`),
  KEY `idTypeAppareil` (`idTypeAppareil`),
  CONSTRAINT `consommer_ibfk_1` FOREIGN KEY (`libelle`) REFERENCES `typeenergie` (`libelle`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `consommer_ibfk_2` FOREIGN KEY (`idTypeAppareil`) REFERENCES `typeappareil` (`idTypeAppareil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `degresecurite`
--

DROP TABLE IF EXISTS `degresecurite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `degresecurite` (
  `idDegreSecurite` int(11) NOT NULL AUTO_INCREMENT,
  `libelleDegreSecurite` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idDegreSecurite`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `departement`
--

DROP TABLE IF EXISTS `departement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departement` (
  `codeDep` varchar(3) COLLATE utf8_bin NOT NULL,
  `nomDepartement` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `idRegion` int(11) NOT NULL,
  PRIMARY KEY (`codeDep`),
  KEY `idRegion` (`idRegion`),
  CONSTRAINT `departement_ibfk_1` FOREIGN KEY (`idRegion`) REFERENCES `region` (`idRegion`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `emettre`
--

DROP TABLE IF EXISTS `emettre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emettre` (
  `libelle` varchar(50) COLLATE utf8_bin NOT NULL,
  `idTypeAppareil` int(11) NOT NULL,
  `emissionHoraire` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`libelle`,`idTypeAppareil`),
  KEY `idTypeAppareil` (`idTypeAppareil`),
  CONSTRAINT `emettre_ibfk_1` FOREIGN KEY (`libelle`) REFERENCES `substancenocive` (`libelle`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `emettre_ibfk_2` FOREIGN KEY (`idTypeAppareil`) REFERENCES `typeappareil` (`idTypeAppareil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `historiquefonctionnement`
--

DROP TABLE IF EXISTS `historiquefonctionnement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historiquefonctionnement` (
  `idImmeuble` int(11) NOT NULL,
  `idAppartement` int(11) NOT NULL,
  `idPiece` int(11) NOT NULL,
  `debutFonctionnement` datetime NOT NULL,
  `finFonctionnement` datetime DEFAULT NULL,
  `idAppareil` int(11) NOT NULL,
  PRIMARY KEY (`idImmeuble`,`idAppartement`,`idPiece`,`idAppareil`,`debutFonctionnement`),
  CONSTRAINT `historiquefonctionnement_ibfk_1` FOREIGN KEY (`idImmeuble`, `idAppartement`, `idPiece`) REFERENCES `piece` (`idImmeuble`, `idAppartement`, `idPiece`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `historiquefonctionnement_ibfk_2` FOREIGN KEY (`idImmeuble`, `idAppartement`, `idPiece`, `idAppareil`) REFERENCES `appareil` (`idImmeuble`, `idAppartement`, `idPiece`, `idAppareil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `immeuble`
--

DROP TABLE IF EXISTS `immeuble`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `immeuble` (
  `idImmeuble` int(11) NOT NULL AUTO_INCREMENT,
  `numeroImmeuble` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `idRue` int(11) NOT NULL,
  PRIMARY KEY (`idImmeuble`),
  KEY `idRue` (`idRue`),
  CONSTRAINT `immeuble_ibfk_1` FOREIGN KEY (`idRue`) REFERENCES `rue` (`idRue`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5853 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `louer`
--

DROP TABLE IF EXISTS `louer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `louer` (
  `idImmeuble` int(11) NOT NULL,
  `idAppartement` int(11) NOT NULL,
  `debutLocation` date NOT NULL,
  `finLocation` date DEFAULT NULL,
  `mail` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idImmeuble`,`idAppartement`,`debutLocation`),
  KEY `mail` (`mail`),
  CONSTRAINT `louer_ibfk_1` FOREIGN KEY (`idImmeuble`, `idAppartement`) REFERENCES `appartement` (`idImmeuble`, `idAppartement`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `louer_ibfk_2` FOREIGN KEY (`mail`) REFERENCES `utilisateur` (`mail`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `necessiter`
--

DROP TABLE IF EXISTS `necessiter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `necessiter` (
  `idTypeAppart` int(11) NOT NULL,
  `idTypePiece` int(11) NOT NULL,
  `nbPiece` int(11) DEFAULT NULL,
  PRIMARY KEY (`idTypeAppart`,`idTypePiece`),
  KEY `idTypePiece` (`idTypePiece`),
  CONSTRAINT `necessiter_ibfk_1` FOREIGN KEY (`idTypeAppart`) REFERENCES `typeappartement` (`idTypeAppart`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `necessiter_ibfk_2` FOREIGN KEY (`idTypePiece`) REFERENCES `typepiece` (`idTypePiece`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `piece`
--

DROP TABLE IF EXISTS `piece`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `piece` (
  `idImmeuble` int(11) NOT NULL,
  `idAppartement` int(11) NOT NULL,
  `idPiece` int(11) NOT NULL,
  `nomPiece` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `idTypePiece` int(11) NOT NULL,
  PRIMARY KEY (`idImmeuble`,`idAppartement`,`idPiece`),
  KEY `idTypePiece` (`idTypePiece`),
  CONSTRAINT `piece_ibfk_1` FOREIGN KEY (`idImmeuble`, `idAppartement`) REFERENCES `appartement` (`idImmeuble`, `idAppartement`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `piece_ibfk_2` FOREIGN KEY (`idTypePiece`) REFERENCES `typepiece` (`idTypePiece`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `id_relatif_piece_insert` BEFORE INSERT ON `piece` FOR EACH ROW BEGIN
    DECLARE maxNbPiece int;
    SELECT MAX(idPiece) INTO maxNbPiece FROM piece WHERE idImmeuble = new.idImmeuble AND idAppartement = new.idAppartement;

    IF maxNbPiece IS NULL THEN
        SET maxNbPiece := 1;
    ELSE
    	SET maxNbPiece := maxNbPiece + 1;
    END IF;
    
    SET new.idPiece = maxNbPiece;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `id_relatif_piece_update` BEFORE UPDATE ON `piece` FOR EACH ROW BEGIN
    DECLARE maxNbPiece int;
    SELECT MAX(idPiece) INTO maxNbPiece FROM piece WHERE idImmeuble = new.idImmeuble AND idAppartement = new.idAppartement;

    IF maxNbPiece IS NULL THEN
        SET maxNbPiece := 1;
    ELSE
    	SET maxNbPiece := maxNbPiece + 1;
    END IF;
    
    SET new.idPiece = maxNbPiece;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `posseder`
--

DROP TABLE IF EXISTS `posseder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posseder` (
  `idImmeuble` int(11) NOT NULL,
  `debutPossession` date NOT NULL,
  `finPossession` date DEFAULT NULL,
  `mail` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idImmeuble`,`debutPossession`),
  KEY `mail` (`mail`),
  CONSTRAINT `posseder_ibfk_1` FOREIGN KEY (`idImmeuble`) REFERENCES `immeuble` (`idImmeuble`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `posseder_ibfk_2` FOREIGN KEY (`mail`) REFERENCES `utilisateur` (`mail`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `region`
--

DROP TABLE IF EXISTS `region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `region` (
  `idRegion` int(11) NOT NULL AUTO_INCREMENT,
  `nomRegion` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idRegion`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `idRole` int(11) NOT NULL AUTO_INCREMENT,
  `libelleRole` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idRole`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rue`
--

DROP TABLE IF EXISTS `rue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rue` (
  `idRue` int(11) NOT NULL AUTO_INCREMENT,
  `nomRue` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `idVille` int(11) NOT NULL,
  PRIMARY KEY (`idRue`),
  KEY `idVille` (`idVille`),
  CONSTRAINT `rue_ibfk_1` FOREIGN KEY (`idVille`) REFERENCES `ville` (`idVille`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36701 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `substance_energie`
--

DROP TABLE IF EXISTS `substance_energie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `substance_energie` (
  `libelle` varchar(50) COLLATE utf8_bin NOT NULL,
  `descriptionSubstance` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `valeurMinimale` decimal(15,2) DEFAULT NULL,
  `valeurMaximale` decimal(15,2) DEFAULT NULL,
  `valeurCritique` decimal(15,2) DEFAULT NULL,
  `valeurIdeale` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`libelle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `substancenocive`
--

DROP TABLE IF EXISTS `substancenocive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `substancenocive` (
  `libelle` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`libelle`),
  CONSTRAINT `substancenocive_ibfk_1` FOREIGN KEY (`libelle`) REFERENCES `substance_energie` (`libelle`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `typeappareil`
--

DROP TABLE IF EXISTS `typeappareil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `typeappareil` (
  `idTypeAppareil` int(11) NOT NULL AUTO_INCREMENT,
  `libelleTypeAppareil` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idTypeAppareil`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `typeappartement`
--

DROP TABLE IF EXISTS `typeappartement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `typeappartement` (
  `idTypeAppart` int(11) NOT NULL AUTO_INCREMENT,
  `nbMinPieces` int(11) DEFAULT NULL,
  `libelleTypeAppart` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idTypeAppart`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `typeenergie`
--

DROP TABLE IF EXISTS `typeenergie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `typeenergie` (
  `libelle` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`libelle`),
  CONSTRAINT `typeenergie_ibfk_1` FOREIGN KEY (`libelle`) REFERENCES `substance_energie` (`libelle`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `typepiece`
--

DROP TABLE IF EXISTS `typepiece`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `typepiece` (
  `idTypePiece` int(11) NOT NULL AUTO_INCREMENT,
  `libelleTypePiece` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idTypePiece`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateur` (
  `mail` varchar(50) COLLATE utf8_bin NOT NULL,
  `nomUtilisateur` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `prenomUtilisateur` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `dateNaissance` date DEFAULT NULL,
  `motDePasse` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `dateCreation` date DEFAULT NULL,
  `etat` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `idRole` int(11) NOT NULL,
  PRIMARY KEY (`mail`),
  KEY `idRole` (`idRole`),
  CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`idRole`) REFERENCES `role` (`idRole`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `idVideo` int(11) NOT NULL AUTO_INCREMENT,
  `nomVideo` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `fichierVideo` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `idAppareil` int(11) NOT NULL,
  PRIMARY KEY (`idVideo`),
  KEY `idAppareil` (`idAppareil`),
  CONSTRAINT `video_ibfk_1` FOREIGN KEY (`idAppareil`) REFERENCES `appareil` (`idAppareil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ville`
--

DROP TABLE IF EXISTS `ville`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ville` (
  `idVille` int(11) NOT NULL AUTO_INCREMENT,
  `nomVille` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `cp` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `codeDep` varchar(3) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idVille`),
  KEY `codeDep` (`codeDep`),
  CONSTRAINT `ville_ibfk_1` FOREIGN KEY (`codeDep`) REFERENCES `departement` (`codeDep`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36701 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-24 21:03:17
