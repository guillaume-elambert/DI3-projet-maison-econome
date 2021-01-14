DROP DATABASE IF EXISTS `3a_di_projet`;
CREATE DATABASE IF NOT EXISTS `3a_di_projet` CHARACTER SET utf8 COLLATE utf8_bin;
USE `3a_di_projet`;

CREATE USER IF NOT EXISTS `projet_bdd_3a`@`%` IDENTIFIED BY 'OTdWAeC4qiLaNmzT';
GRANT ALL PRIVILEGES ON `3a_di_projet`.* TO `projet_bdd_3a`@`%`;

CREATE TABLE Role (
   idRole INT AUTO_INCREMENT,
   libelleRole VARCHAR(50),
   PRIMARY KEY(idRole)
) AUTO_INCREMENT=2;

CREATE TABLE Region (
   idRegion INT AUTO_INCREMENT,
   nomRegion VARCHAR(50),
   PRIMARY KEY(idRegion)
) AUTO_INCREMENT=19;

CREATE TABLE Departement (
   codeDep VARCHAR(3) NOT NULL,
   nomDepartement VARCHAR(50),
   idRegion INT NOT NULL,
   PRIMARY KEY(codeDep),
   FOREIGN KEY(idRegion) REFERENCES Region(idRegion)
);

CREATE TABLE TypeAppartement (
   idTypeAppart INT AUTO_INCREMENT,
   nbMinPieces INT,
   libelleTypeAppart VARCHAR(50),
   PRIMARY KEY(idTypeAppart)
) AUTO_INCREMENT=5;

CREATE TABLE TypePiece (
   idTypePiece INT AUTO_INCREMENT,
   libelleTypePiece VARCHAR(50),
   PRIMARY KEY(idTypePiece)
) AUTO_INCREMENT=4;

CREATE TABLE DegreSecurite (
   idDegreSecurite INT AUTO_INCREMENT,
   libelleDegreSecurite VARCHAR(50),
   PRIMARY KEY(idDegreSecurite)
) AUTO_INCREMENT=2;

CREATE TABLE Substance_Energie (
   libelle VARCHAR(50),
   descriptionSubstance VARCHAR(50),
   valeurMinimale DECIMAL(15,2),
   valeurMaximale DECIMAL(15,2),
   valeurCritique DECIMAL(15,2),
   valeurIdeale DECIMAL(15,2),
   PRIMARY KEY(libelle)
);

CREATE TABLE TypeAppareil (
   idTypeAppareil INT AUTO_INCREMENT,
   libelleTypeAppareil VARCHAR(50),
   PRIMARY KEY(idTypeAppareil)
) AUTO_INCREMENT=8;

CREATE TABLE SubstanceNocive (
   libelle VARCHAR(50),
   PRIMARY KEY(libelle),
   FOREIGN KEY(libelle) REFERENCES Substance_Energie(libelle)
);

CREATE TABLE TypeEnergie (
   libelle VARCHAR(50),
   PRIMARY KEY(libelle),
   FOREIGN KEY(libelle) REFERENCES Substance_Energie(libelle)
);

CREATE TABLE Utilisateur (
   mail VARCHAR(50) NOT NULL,
   nomUtilisateur VARCHAR(50),
   prenomUtilisateur VARCHAR(50),
   dateNaissance DATE,
   motDePasse VARCHAR(255),
   dateCreation DATE,
   etat VARCHAR(50),
   idRole INT NOT NULL,
   PRIMARY KEY(mail),
   FOREIGN KEY(idRole) REFERENCES Role(idRole)
);

CREATE TABLE Ville (
   idVille INT AUTO_INCREMENT,
   nomVille VARCHAR(50),
   cp VARCHAR(5),
   codeDep VARCHAR(3) NOT NULL,
   PRIMARY KEY(idVille),
   FOREIGN KEY(codeDep) REFERENCES Departement(codeDep)
) AUTO_INCREMENT=36700;

CREATE TABLE Rue (
   idRue INT AUTO_INCREMENT,
   nomRue VARCHAR(50),
   idVille INT NOT NULL,
   PRIMARY KEY(idRue),
   FOREIGN KEY(idVille) REFERENCES Ville(idVille)
) AUTO_INCREMENT=36700;

CREATE TABLE Immeuble (
   idImmeuble INT AUTO_INCREMENT,
   numeroImmeuble VARCHAR(5),
   idRue INT NOT NULL,
   PRIMARY KEY(idImmeuble),
   FOREIGN KEY(idRue) REFERENCES Rue(idRue)
) AUTO_INCREMENT=4;

CREATE TABLE Appartement (
   idImmeuble INT,
   idAppartement INT NOT NULL,
   idDegreSecurite INT NOT NULL,
   idTypeAppart INT NOT NULL,
   PRIMARY KEY(idImmeuble, idAppartement),
   FOREIGN KEY(idImmeuble) REFERENCES Immeuble(idImmeuble),
   FOREIGN KEY(idDegreSecurite) REFERENCES DegreSecurite(idDegreSecurite),
   FOREIGN KEY(idTypeAppart) REFERENCES TypeAppartement(idTypeAppart)
);

CREATE TABLE Piece (
   idImmeuble INT,
   idAppartement INT,
   idPiece INT NOT NULL,
   nomPiece VARCHAR(50),
   idTypePiece INT NOT NULL,
   PRIMARY KEY(idImmeuble, idAppartement, idPiece),
   FOREIGN KEY(idImmeuble, idAppartement) REFERENCES Appartement(idImmeuble, idAppartement),
   FOREIGN KEY(idTypePiece) REFERENCES TypePiece(idTypePiece)
);

CREATE TABLE Appareil (
   idAppareil INT AUTO_INCREMENT,
   libelleAppareil VARCHAR(50),
   etat DECIMAL(1,0),
   descriptionPosition VARCHAR(30),
   idImmeuble INT NOT NULL,
   idAppartement INT NOT NULL,
   idPiece INT NOT NULL,
   idTypeAppareil INT NOT NULL,
   PRIMARY KEY(idAppareil),
   FOREIGN KEY(idImmeuble, idAppartement, idPiece) REFERENCES Piece(idImmeuble, idAppartement, idPiece),
   FOREIGN KEY(idTypeAppareil) REFERENCES TypeAppareil(idTypeAppareil)
) AUTO_INCREMENT=4;

CREATE TABLE Video (
   idVideo INT AUTO_INCREMENT,
   nomVideo VARCHAR(50),
   fichierVideo VARCHAR(50),
   idAppareil INT NOT NULL,
   PRIMARY KEY(idVideo),
   FOREIGN KEY(idAppareil) REFERENCES Appareil(idAppareil)
) AUTO_INCREMENT=0;

CREATE TABLE Louer (
   idImmeuble INT,
   idAppartement INT,
   debutLocation DATE,
   finLocation DATE,
   mail VARCHAR(50) NOT NULL,
   PRIMARY KEY(idImmeuble, idAppartement, debutLocation),
   FOREIGN KEY(idImmeuble, idAppartement) REFERENCES Appartement(idImmeuble, idAppartement),
   FOREIGN KEY(mail) REFERENCES Utilisateur(mail)
);

CREATE TABLE Posseder (
   idImmeuble INT,
   debutPossession DATE,
   finPossession DATE,
   mail VARCHAR(50) NOT NULL,
   PRIMARY KEY(idImmeuble, debutPossession),
   FOREIGN KEY(idImmeuble) REFERENCES Immeuble(idImmeuble),
   FOREIGN KEY(mail) REFERENCES Utilisateur(mail)
);

CREATE TABLE Necessiter (
   idTypeAppart INT,
   idTypePiece INT,
   nbPiece INT,
   PRIMARY KEY(idTypeAppart, idTypePiece),
   FOREIGN KEY(idTypeAppart) REFERENCES TypeAppartement(idTypeAppart),
   FOREIGN KEY(idTypePiece) REFERENCES TypePiece(idTypePiece)
);

CREATE TABLE Consommer (
   libelle VARCHAR(50),
   idTypeAppareil INT,
   consommationHoraire DECIMAL(15,2),
   PRIMARY KEY(libelle, idTypeAppareil),
   FOREIGN KEY(libelle) REFERENCES TypeEnergie(libelle),
   FOREIGN KEY(idTypeAppareil) REFERENCES TypeAppareil(idTypeAppareil)
);

CREATE TABLE Emettre (
   libelle VARCHAR(50),
   idTypeAppareil INT,
   emissionHoraire DECIMAL(15,2),
   PRIMARY KEY(libelle, idTypeAppareil),
   FOREIGN KEY(libelle) REFERENCES SubstanceNocive(libelle),
   FOREIGN KEY(idTypeAppareil) REFERENCES TypeAppareil(idTypeAppareil)
);

CREATE TABLE HistoriqueFonctionnement (
   idImmeuble INT,
   idAppartement INT,
   idPiece INT,
   debutFonctionnement DATETIME,
   finFonctionnement DATETIME,
   idAppareil INT NOT NULL,
   PRIMARY KEY(idImmeuble, idAppartement, idPiece, debutFonctionnement),
   FOREIGN KEY(idImmeuble, idAppartement, idPiece) REFERENCES Piece(idImmeuble, idAppartement, idPiece),
   FOREIGN KEY(idAppareil) REFERENCES Appareil(idAppareil)
);


/* DELIMITER IDENTIFIANT RELATIF TABLE APPARTEMENT */
DELIMITER $$

CREATE TRIGGER id_relatif_appartement
    BEFORE INSERT
    ON appartement FOR EACH ROW
BEGIN
    DECLARE maxNbAppart int;
    SELECT MAX(idAppartement) INTO maxNbAppart FROM appartement WHERE idImmeuble = new.idImmeuble;

    IF maxNbAppart IS NULL THEN
        SET maxNbAppart := 1;
    ELSE
    	SET maxNbAppart := maxNbAppart + 1;
    END IF;
    
    SET new.idAppartement = maxNbAppart;
END$$    

DELIMITER ;