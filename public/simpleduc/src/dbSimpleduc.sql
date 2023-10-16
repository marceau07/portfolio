-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mar 19 Novembre 2019 à 12:02
-- Version du serveur :  10.1.41-MariaDB-0+deb9u1
-- Version de PHP :  7.3.10-1+0~20191008.45+debian9~1.gbp365209

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dbSimpleduc`
--

-- --------------------------------------------------------

--
-- Structure de la table `CONTACT`
--

CREATE TABLE `CONTACT` (
  `idContact` int(11) NOT NULL,
  `lastNameContact` varchar(255) NOT NULL,
  `firstNameContact` varchar(255) NOT NULL,
  `telContact` varchar(10) NOT NULL,
  `mailContact` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `CONTACT`
--

INSERT INTO `CONTACT` (`idContact`, `lastNameContact`, `firstNameContact`, `telContact`, `mailContact`) VALUES
(2, 'Rodrigues', 'Marceau', '0321504896', 'marceau0707@gmail.com'),
(3, 'KATIN', 'Romain', '0606060606', 'romain.kata@laposte.net');

-- --------------------------------------------------------

--
-- Structure de la table `CONTRACT`
--

CREATE TABLE `CONTRACT` (
  `idContract` int(11) NOT NULL,
  `labelContract` varchar(255) NOT NULL,
  `dateSignatureContract` datetime NOT NULL,
  `dateBegProject` datetime NOT NULL,
  `dateEndProject` datetime NOT NULL,
  `costProject` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `CONTRACT`
--

INSERT INTO `CONTRACT` (`idContract`, `labelContract`, `dateSignatureContract`, `dateBegProject`, `dateEndProject`, `costProject`) VALUES
(1, 'lol', '2019-11-12 00:00:00', '2019-11-13 00:00:00', '2019-11-15 00:00:00', 10),
(3, 'lol', '4216-06-15 00:00:00', '2045-06-05 00:00:00', '0001-05-04 00:00:00', 150),
(4, 'ui', '4000-08-05 00:00:00', '0564-01-05 00:00:00', '0000-00-00 00:00:00', 12),
(5, 'aaa', '0000-00-00 00:00:00', '1998-01-24 00:00:00', '1998-01-24 00:00:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `EMPLOYEE`
--

CREATE TABLE `EMPLOYEE` (
  `idEmployee` int(11) NOT NULL,
  `lastNameEmployee` varchar(255) NOT NULL,
  `firstNameEmployee` varchar(255) NOT NULL,
  `cityEmployee` varchar(255) NOT NULL,
  `levelEmployee` int(11) NOT NULL,
  `idTeamEmployee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `EMPLOYEE`
--

INSERT INTO `EMPLOYEE` (`idEmployee`, `lastNameEmployee`, `firstNameEmployee`, `cityEmployee`, `levelEmployee`, `idTeamEmployee`) VALUES
(1, 'KATIN', 'Gromain', 'ouarlencourre ocourt', 5, 3),
(3, 'AcHoUrI', 'lUdWiN', 'Arras', 0, NULL),
(4, 'ACHOURI', 'Ludivine', 'Capbreton', 510, 5);

-- --------------------------------------------------------

--
-- Structure de la table `EMPLOYEE_SKILL`
--

CREATE TABLE `EMPLOYEE_SKILL` (
  `idEmployee` int(11) NOT NULL,
  `idSkill` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `EMPLOYEE_TEAM`
--

CREATE TABLE `EMPLOYEE_TEAM` (
  `idEmployee` int(11) NOT NULL,
  `idTeam` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `EMPLOYEE_TEAM`
--

INSERT INTO `EMPLOYEE_TEAM` (`idEmployee`, `idTeam`) VALUES
(1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `FIRM`
--

CREATE TABLE `FIRM` (
  `idFirm` int(11) NOT NULL,
  `nameFirm` varchar(250) DEFAULT NULL,
  `cityFirm` varchar(255) NOT NULL,
  `zipCodeFirm` varchar(255) NOT NULL,
  `streetFirm` varchar(255) NOT NULL,
  `telFirm` varchar(10) NOT NULL,
  `faxFirm` varchar(10) NOT NULL,
  `idContactFirm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `FIRM`
--

INSERT INTO `FIRM` (`idFirm`, `nameFirm`, `cityFirm`, `zipCodeFirm`, `streetFirm`, `telFirm`, `faxFirm`, `idContactFirm`) VALUES
(9, 'Les Ateliers d\'Eaucourt', 'Warlencourt-Eaucourt', '62450', '5', '0321504896', '0321508459', 2),
(10, 'Lycée Guy Mollet', 'Arras', '62000', 'Bocquet Flochel', '0606060606', '0303030303', 3);

-- --------------------------------------------------------

--
-- Structure de la table `MODULE`
--

CREATE TABLE `MODULE` (
  `idModule` int(11) NOT NULL,
  `labelModule` varchar(255) NOT NULL,
  `descModule` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `MODULE`
--

INSERT INTO `MODULE` (`idModule`, `labelModule`, `descModule`) VALUES
(1, 'Le Module', 'C\'est le Module');

-- --------------------------------------------------------

--
-- Structure de la table `MODULE_TASK`
--

CREATE TABLE `MODULE_TASK` (
  `idModule` int(11) NOT NULL,
  `idTask` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `MODULE_TASK`
--

INSERT INTO `MODULE_TASK` (`idModule`, `idTask`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `PROJECT`
--

CREATE TABLE `PROJECT` (
  `idProject` int(11) NOT NULL,
  `nameProject` varchar(255) NOT NULL,
  `descProject` varchar(255) NOT NULL,
  `idContractProject` int(11) NOT NULL,
  `idModuleProject` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `PROJECT`
--

INSERT INTO `PROJECT` (`idProject`, `nameProject`, `descProject`, `idContractProject`, `idModuleProject`) VALUES
(1, 'Projet1', 'C\'est un projet', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `ROLE`
--

CREATE TABLE `ROLE` (
  `idRole` int(11) NOT NULL,
  `labelRole` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `ROLE`
--

INSERT INTO `ROLE` (`idRole`, `labelRole`) VALUES
(1, 'Client'),
(2, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `SKILL`
--

CREATE TABLE `SKILL` (
  `idSkill` int(11) NOT NULL,
  `nameSkill` varchar(255) NOT NULL,
  `descSkill` varchar(255) NOT NULL,
  `versionSkill` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `SKILL`
--

INSERT INTO `SKILL` (`idSkill`, `nameSkill`, `descSkill`, `versionSkill`) VALUES
(3, 'Compétence', 'lol', 'lol');

-- --------------------------------------------------------

--
-- Structure de la table `SPECIFICATION`
--

CREATE TABLE `SPECIFICATION` (
  `idContractSpecification` int(11) NOT NULL,
  `idFirmSpecification` int(11) NOT NULL,
  `fileSpecification` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `SPECIFICATION`
--

INSERT INTO `SPECIFICATION` (`idContractSpecification`, `idFirmSpecification`, `fileSpecification`) VALUES
(1, 9, '');

-- --------------------------------------------------------

--
-- Structure de la table `TASK`
--

CREATE TABLE `TASK` (
  `idTask` int(11) NOT NULL,
  `labelTask` varchar(255) NOT NULL,
  `descTask` varchar(255) NOT NULL,
  `idTeamTask` int(11) NOT NULL,
  `costTask` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `TASK`
--

INSERT INTO `TASK` (`idTask`, `labelTask`, `descTask`, `idTeamTask`, `costTask`) VALUES
(1, 'Rien', 'R', 4, 50),
(2, 'Rien2', 'R', 4, 100);

-- --------------------------------------------------------

--
-- Structure de la table `TASK_EMPLOYEE`
--

CREATE TABLE `TASK_EMPLOYEE` (
  `idTask` int(11) NOT NULL,
  `idEmployee` int(11) NOT NULL,
  `dateBeg` datetime NOT NULL,
  `dateEnd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `TEAM`
--

CREATE TABLE `TEAM` (
  `idTeam` int(11) NOT NULL,
  `nameTeam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `TEAM`
--

INSERT INTO `TEAM` (`idTeam`, `nameTeam`) VALUES
(3, 'Equipeo'),
(4, 'Equipe 1'),
(5, 'Equipe 3');

-- --------------------------------------------------------

--
-- Structure de la table `USER`
--

CREATE TABLE `USER` (
  `idUser` int(11) NOT NULL,
  `emailUser` varchar(100) NOT NULL,
  `passwordUser` varchar(255) NOT NULL,
  `lastNameUser` varchar(255) NOT NULL,
  `firstNameUser` varchar(255) NOT NULL,
  `idRoleUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `USER`
--

INSERT INTO `USER` (`idUser`, `emailUser`, `passwordUser`, `lastNameUser`, `firstNameUser`, `idRoleUser`) VALUES
(1, 'marceau0707@gmail.com', '$2y$10$Q7lNtYCveEKkRzLM.YZABOADoHh/YVJ/4Pa7ZTG3.pzF69O4JDhzS', 'RODRIGUES', 'Marceau', 2),
(2, 'ludivine@gmail.com', '$2y$10$sPMTIj8m6qcPsJiLrcrZcevk7Dg.Db4rFijPBXFkrNk4Q2/fI/.6i', 'ludivine', 'achouri', 2),
(3, 'romain.kata@laposte.net', '$2y$10$dEJHNso9o90GE6cmXF4iqe6FD7PKQ/1mKDz5EGJaQjLhPXTxOmIcG', 'Kata', 'Romain', 2),
(4, 'aaa.aa@laposte.net', '$2y$10$1kqBEvm10AFzN.YCz8MfROdcqm322OAIPEY9OZ8.6psGDH.nHJc2.', 'aaa', 'aaaa', 1),
(7, 'lu@gmail.com', '$2y$10$NzWoz3uG91AEAqJkPDGNYOpsizkDPIGAIMGVWl8NfsxmhtglvsKom', 'a', 'achouri', 2);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `CONTACT`
--
ALTER TABLE `CONTACT`
  ADD PRIMARY KEY (`idContact`);

--
-- Index pour la table `CONTRACT`
--
ALTER TABLE `CONTRACT`
  ADD PRIMARY KEY (`idContract`);

--
-- Index pour la table `EMPLOYEE`
--
ALTER TABLE `EMPLOYEE`
  ADD PRIMARY KEY (`idEmployee`),
  ADD KEY `idTeamEmployee` (`idTeamEmployee`);

--
-- Index pour la table `EMPLOYEE_SKILL`
--
ALTER TABLE `EMPLOYEE_SKILL`
  ADD PRIMARY KEY (`idEmployee`,`idSkill`),
  ADD KEY `idSkill` (`idSkill`);

--
-- Index pour la table `EMPLOYEE_TEAM`
--
ALTER TABLE `EMPLOYEE_TEAM`
  ADD PRIMARY KEY (`idEmployee`,`idTeam`),
  ADD KEY `idTeam` (`idTeam`);

--
-- Index pour la table `FIRM`
--
ALTER TABLE `FIRM`
  ADD PRIMARY KEY (`idFirm`),
  ADD KEY `idContactFirm` (`idContactFirm`);

--
-- Index pour la table `MODULE`
--
ALTER TABLE `MODULE`
  ADD PRIMARY KEY (`idModule`);

--
-- Index pour la table `MODULE_TASK`
--
ALTER TABLE `MODULE_TASK`
  ADD PRIMARY KEY (`idModule`,`idTask`),
  ADD KEY `idTask` (`idTask`);

--
-- Index pour la table `PROJECT`
--
ALTER TABLE `PROJECT`
  ADD PRIMARY KEY (`idProject`),
  ADD KEY `idContratProject` (`idContractProject`),
  ADD KEY `idModuleProject` (`idModuleProject`);

--
-- Index pour la table `ROLE`
--
ALTER TABLE `ROLE`
  ADD PRIMARY KEY (`idRole`);

--
-- Index pour la table `SKILL`
--
ALTER TABLE `SKILL`
  ADD PRIMARY KEY (`idSkill`);

--
-- Index pour la table `SPECIFICATION`
--
ALTER TABLE `SPECIFICATION`
  ADD PRIMARY KEY (`idContractSpecification`,`idFirmSpecification`),
  ADD KEY `idFirmSpecification` (`idFirmSpecification`);

--
-- Index pour la table `TASK`
--
ALTER TABLE `TASK`
  ADD PRIMARY KEY (`idTask`),
  ADD KEY `idTeamTask` (`idTeamTask`);

--
-- Index pour la table `TASK_EMPLOYEE`
--
ALTER TABLE `TASK_EMPLOYEE`
  ADD PRIMARY KEY (`idTask`,`idEmployee`),
  ADD KEY `idEmployee` (`idEmployee`);

--
-- Index pour la table `TEAM`
--
ALTER TABLE `TEAM`
  ADD PRIMARY KEY (`idTeam`);

--
-- Index pour la table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `emailUser` (`emailUser`),
  ADD KEY `idRoleUser` (`idRoleUser`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `CONTACT`
--
ALTER TABLE `CONTACT`
  MODIFY `idContact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `CONTRACT`
--
ALTER TABLE `CONTRACT`
  MODIFY `idContract` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `EMPLOYEE`
--
ALTER TABLE `EMPLOYEE`
  MODIFY `idEmployee` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `FIRM`
--
ALTER TABLE `FIRM`
  MODIFY `idFirm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `MODULE`
--
ALTER TABLE `MODULE`
  MODIFY `idModule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `PROJECT`
--
ALTER TABLE `PROJECT`
  MODIFY `idProject` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `ROLE`
--
ALTER TABLE `ROLE`
  MODIFY `idRole` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `SKILL`
--
ALTER TABLE `SKILL`
  MODIFY `idSkill` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `TASK`
--
ALTER TABLE `TASK`
  MODIFY `idTask` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `TEAM`
--
ALTER TABLE `TEAM`
  MODIFY `idTeam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `USER`
--
ALTER TABLE `USER`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `EMPLOYEE`
--
ALTER TABLE `EMPLOYEE`
  ADD CONSTRAINT `EMPLOYEE_ibfk_1` FOREIGN KEY (`idTeamEmployee`) REFERENCES `TEAM` (`idTeam`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `EMPLOYEE_SKILL`
--
ALTER TABLE `EMPLOYEE_SKILL`
  ADD CONSTRAINT `EMPLOYEE_SKILL_ibfk_1` FOREIGN KEY (`idEmployee`) REFERENCES `EMPLOYEE` (`idEmployee`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `EMPLOYEE_SKILL_ibfk_2` FOREIGN KEY (`idSkill`) REFERENCES `SKILL` (`idSkill`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `EMPLOYEE_TEAM`
--
ALTER TABLE `EMPLOYEE_TEAM`
  ADD CONSTRAINT `EMPLOYEE_TEAM_ibfk_1` FOREIGN KEY (`idTeam`) REFERENCES `TEAM` (`idTeam`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `EMPLOYEE_TEAM_ibfk_2` FOREIGN KEY (`idEmployee`) REFERENCES `EMPLOYEE` (`idEmployee`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `FIRM`
--
ALTER TABLE `FIRM`
  ADD CONSTRAINT `FIRM_ibfk_1` FOREIGN KEY (`idContactFirm`) REFERENCES `CONTACT` (`idContact`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `MODULE_TASK`
--
ALTER TABLE `MODULE_TASK`
  ADD CONSTRAINT `MODULE_TASK_ibfk_1` FOREIGN KEY (`idTask`) REFERENCES `TASK` (`idTask`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `MODULE_TASK_ibfk_2` FOREIGN KEY (`idModule`) REFERENCES `MODULE` (`idModule`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `PROJECT`
--
ALTER TABLE `PROJECT`
  ADD CONSTRAINT `PROJECT_ibfk_1` FOREIGN KEY (`idContractProject`) REFERENCES `CONTRACT` (`idContract`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `PROJECT_ibfk_2` FOREIGN KEY (`idModuleProject`) REFERENCES `MODULE` (`idModule`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `SPECIFICATION`
--
ALTER TABLE `SPECIFICATION`
  ADD CONSTRAINT `SPECIFICATION_ibfk_1` FOREIGN KEY (`idContractSpecification`) REFERENCES `CONTRACT` (`idContract`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SPECIFICATION_ibfk_2` FOREIGN KEY (`idFirmSpecification`) REFERENCES `FIRM` (`idFirm`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `TASK`
--
ALTER TABLE `TASK`
  ADD CONSTRAINT `TASK_ibfk_1` FOREIGN KEY (`idTeamTask`) REFERENCES `TEAM` (`idTeam`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `TASK_EMPLOYEE`
--
ALTER TABLE `TASK_EMPLOYEE`
  ADD CONSTRAINT `TASK_EMPLOYEE_ibfk_1` FOREIGN KEY (`idEmployee`) REFERENCES `EMPLOYEE` (`idEmployee`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TASK_EMPLOYEE_ibfk_2` FOREIGN KEY (`idTask`) REFERENCES `TASK` (`idTask`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `USER`
--
ALTER TABLE `USER`
  ADD CONSTRAINT `USER_ibfk_1` FOREIGN KEY (`idRoleUser`) REFERENCES `ROLE` (`idRole`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
