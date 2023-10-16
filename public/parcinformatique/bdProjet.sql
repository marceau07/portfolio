-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 04 Novembre 2019 à 12:08
-- Version du serveur :  10.1.41-MariaDB-0+deb9u1
-- Version de PHP :  7.3.10-1+0~20191008.45+debian9~1.gbp365209

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bdProjet`
--

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `idEmploye` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nomEmploye` varchar(255) NOT NULL,
  `prenomEmploye` varchar(255) NOT NULL,
  `idRole` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `employe`
--

INSERT INTO `employe` (`idEmploye`, `email`, `mdp`, `nomEmploye`, `prenomEmploye`, `idRole`) VALUES
(0, 'N/A', '', 'N/A', '', 1),
(6, 'romain.kata@laposte.net', '$2y$10$bzIQKUGDXbGIvtSUaqFLjekgMT7BnExRK05Y1MBp4017YooaBdTUi', 'KATA', 'Romain', 1),
(7, 'marceau0707@gmail.com', '$2y$10$dNR3dM3drklB2mbhLPqE1eAHH1RtSUsSVP2jqR1qwCKujOwkNC0He', 'RODRIGUES', 'Marceau', 2);

-- --------------------------------------------------------

--
-- Structure de la table `fonction`
--

CREATE TABLE `fonction` (
  `idFonction` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `fonction`
--

INSERT INTO `fonction` (`idFonction`, `titre`) VALUES
(1, 'Admin Systeme'),
(2, 'Admin Reseau'),
(3, 'Maintenance'),
(6, 'Employée de Test ');

-- --------------------------------------------------------

--
-- Structure de la table `ordinateur`
--

CREATE TABLE `ordinateur` (
  `idOrdinateur` int(11) NOT NULL,
  `ip` varchar(15) CHARACTER SET latin1 NOT NULL,
  `mac` varchar(255) CHARACTER SET latin1 NOT NULL,
  `reseau` varchar(1) CHARACTER SET latin1 NOT NULL,
  `os` int(11) NOT NULL,
  `statut` int(11) NOT NULL,
  `employe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ordinateur`
--

INSERT INTO `ordinateur` (`idOrdinateur`, `ip`, `mac`, `reseau`, `os`, `statut`, `employe`) VALUES
(1, '192.168.1.1', 'E1-63-D9-AE-C0-2C', '1', 1, 1, 6),
(2, '192.168.1.2', 'E1-63-D9-AE-C0-2E', '1', 1, 4, 7),
(3, '192.168.1.3', 'E1-63-D9-AE-C4-2C', '1', 2, 2, 0),
(4, '192.168.1.4', 'E1-63-19-AE-C0-2C', '1', 2, 3, 0),
(5, '192.168.1.5', 'A1-63-D9-AE-C0-2C', '1', 1, 1, 0),
(11, '192.168.1.6', 'E5-7C-FE-5D-4D-C6', '1', 1, 2, 0),
(12, '192.168.1.7', '1A-A1-2F-1E-B9-03', '1', 1, 2, 0),
(13, '192.168.1.8', 'C2-F6-61-68-EE-F0', '1', 1, 2, 0),
(14, '192.168.1.9', 'DC-DB-0F-4B-D4-E5', '1', 1, 3, 0),
(15, '192.168.1.10', 'FF-86-15-F8-89-20', '1', 2, 3, 0);

-- --------------------------------------------------------

--
-- Structure de la table `os`
--

CREATE TABLE `os` (
  `idOs` int(11) NOT NULL,
  `nomOs` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `os`
--

INSERT INTO `os` (`idOs`, `nomOs`) VALUES
(1, 'Windows'),
(2, 'Linux');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `idRole` int(11) NOT NULL,
  `descriptionRole` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `role`
--

INSERT INTO `role` (`idRole`, `descriptionRole`) VALUES
(1, 'Utilisateur'),
(2, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `script`
--

CREATE TABLE `script` (
  `idScript` int(11) NOT NULL,
  `nomScript` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `descScript` varchar(255) NOT NULL,
  `idOs` int(11) NOT NULL,
  `fichierScript` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `script`
--

INSERT INTO `script` (`idScript`, `nomScript`, `version`, `descScript`, `idOs`, `fichierScript`) VALUES
(13, 'Script', '1.0', 'Script d\'essai', 1, 'Script.sh'),
(21, 'dsfds', '12', 'sqcs', 2, 'nmap.sh');

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

CREATE TABLE `statut` (
  `idStatut` int(11) NOT NULL,
  `nomStatut` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `statut`
--

INSERT INTO `statut` (`idStatut`, `nomStatut`) VALUES
(1, 'En ligne'),
(2, 'Hors ligne'),
(3, 'Maintenance en Cours'),
(4, 'Problème');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(100) CHARACTER SET latin1 NOT NULL,
  `fonction` int(11) NOT NULL,
  `mdpUtilisateur` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`idEmploye`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idRole` (`idRole`);

--
-- Index pour la table `fonction`
--
ALTER TABLE `fonction`
  ADD PRIMARY KEY (`idFonction`);

--
-- Index pour la table `ordinateur`
--
ALTER TABLE `ordinateur`
  ADD PRIMARY KEY (`idOrdinateur`),
  ADD KEY `os` (`os`),
  ADD KEY `status` (`statut`),
  ADD KEY `employe` (`employe`);

--
-- Index pour la table `os`
--
ALTER TABLE `os`
  ADD PRIMARY KEY (`idOs`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`idRole`);

--
-- Index pour la table `script`
--
ALTER TABLE `script`
  ADD PRIMARY KEY (`idScript`),
  ADD KEY `idOs` (`idOs`);

--
-- Index pour la table `statut`
--
ALTER TABLE `statut`
  ADD PRIMARY KEY (`idStatut`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD KEY `fonction` (`fonction`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `employe`
--
ALTER TABLE `employe`
  MODIFY `idEmploye` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `fonction`
--
ALTER TABLE `fonction`
  MODIFY `idFonction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `ordinateur`
--
ALTER TABLE `ordinateur`
  MODIFY `idOrdinateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `os`
--
ALTER TABLE `os`
  MODIFY `idOs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `idRole` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `script`
--
ALTER TABLE `script`
  MODIFY `idScript` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT pour la table `statut`
--
ALTER TABLE `statut`
  MODIFY `idStatut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `employe_ibfk_1` FOREIGN KEY (`idRole`) REFERENCES `role` (`idRole`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ordinateur`
--
ALTER TABLE `ordinateur`
  ADD CONSTRAINT `ordinateur_ibfk_1` FOREIGN KEY (`os`) REFERENCES `os` (`idOs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ordinateur_ibfk_2` FOREIGN KEY (`statut`) REFERENCES `statut` (`idStatut`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ordinateur_ibfk_3` FOREIGN KEY (`employe`) REFERENCES `employe` (`idEmploye`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `script`
--
ALTER TABLE `script`
  ADD CONSTRAINT `script_ibfk_1` FOREIGN KEY (`idOs`) REFERENCES `os` (`idOs`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`fonction`) REFERENCES `fonction` (`idFonction`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
