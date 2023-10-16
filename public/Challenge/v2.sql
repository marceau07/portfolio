-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 23 Septembre 2019 à 22:40
-- Version du serveur :  10.1.38-MariaDB-0+deb9u1
-- Version de PHP :  7.0.33-0+deb9u3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bdsi62019mrodrigues`
--

-- --------------------------------------------------------

--
-- Structure de la table `ACTIVITE`
--

CREATE TABLE `ACTIVITE` (
  `codeAct` int(11) NOT NULL,
  `nomAct` varchar(255) NOT NULL,
  `descAct` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `ACTIVITE`
--

INSERT INTO `ACTIVITE` (`codeAct`, `nomAct`, `descAct`) VALUES
(22, 'oui', 'ef'),
(36, 'Activité01', 'Desc01'),
(41, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `CLIENT`
--

CREATE TABLE `CLIENT` (
  `idCli` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `dateNaiss` date NOT NULL,
  `cp` varchar(5) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `rue` varchar(255) NOT NULL,
  `tel` varchar(10) NOT NULL,
  `idRole` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `CLIENT`
--

INSERT INTO `CLIENT` (`idCli`, `email`, `mdp`, `nom`, `prenom`, `dateNaiss`, `cp`, `ville`, `rue`, `tel`, `idRole`) VALUES
(5, 'aa@aa.fr', '$2y$10$gJuMIQlglT97K68RbYV7G.0gS0JCHtgOY5dy.OlBDfvTFvrb5Isme', 'aa', 'aa', '2000-07-07', '62450', 'aa', 'aa', '0321504878', 1),
(7, 'romain.kata@laposte.net', '$2y$10$4Ovo9h7A/f1itO0BW/nESOzroibIsxpf7QpN.e/BqmgmezL.mmv7O', 'Kata', 'Romain', '2000-07-07', '62450', 'Warlencourt-Eaucourt', 'Eglise', '0321504878', 1),
(8, 'marceau0707@gmail.com', '$2y$10$/Q8w.anUcpzeesxa7geYhO9BXgws0.dh5jrA7H312snyYPQRDo7ka', 'RODRIGUES', 'Marceau', '1999-07-07', '62400', 'Bethune', 'Verquin', '0606060606', 2);

-- --------------------------------------------------------

--
-- Structure de la table `COMPORTER`
--

CREATE TABLE `COMPORTER` (
  `idAct` int(11) NOT NULL,
  `idPrest` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `DEMANDER`
--

CREATE TABLE `DEMANDER` (
  `dateDeb` datetime NOT NULL,
  `idCli` int(11) NOT NULL,
  `idPrest` int(11) NOT NULL,
  `dateFin` datetime NOT NULL,
  `idRegle` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `MATERIEL`
--

CREATE TABLE `MATERIEL` (
  `codeMateriel` int(11) NOT NULL,
  `nomMateriel` varchar(255) NOT NULL,
  `commentaire` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `MATERIEL`
--

INSERT INTO `MATERIEL` (`codeMateriel`, `nomMateriel`, `commentaire`) VALUES
(1, 'Seringue', 'Cest une seringue'),
(2, 'Marteau', 'Boom boom'),
(3, 'MACHINE IRM', 'elle se transporte (presque) facilement'),
(4, 'Stétoscope', 'Pour écouter les battements du coeur !');

-- --------------------------------------------------------

--
-- Structure de la table `POSSEDER`
--

CREATE TABLE `POSSEDER` (
  `codeAct` int(11) NOT NULL,
  `codeMat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `PRESTATION`
--

CREATE TABLE `PRESTATION` (
  `idPrest` int(11) NOT NULL,
  `typePrest` varchar(255) NOT NULL,
  `montant` int(255) NOT NULL,
  `idAct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `PRESTATION`
--

INSERT INTO `PRESTATION` (`idPrest`, `typePrest`, `montant`, `idAct`) VALUES
(21, 'zjd', 300, 1);

-- --------------------------------------------------------

--
-- Structure de la table `REGLEMENT`
--

CREATE TABLE `REGLEMENT` (
  `idRegle` int(11) NOT NULL,
  `typeRegle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `REGLEMENT`
--

INSERT INTO `REGLEMENT` (`idRegle`, `typeRegle`) VALUES
(1, 'CB'),
(2, 'Cheque');

-- --------------------------------------------------------

--
-- Structure de la table `ROLE`
--

CREATE TABLE `ROLE` (
  `idRole` int(11) NOT NULL,
  `libelleRole` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `ROLE`
--

INSERT INTO `ROLE` (`idRole`, `libelleRole`) VALUES
(1, 'Client'),
(2, 'Administrateur');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `ACTIVITE`
--
ALTER TABLE `ACTIVITE`
  ADD PRIMARY KEY (`codeAct`);

--
-- Index pour la table `CLIENT`
--
ALTER TABLE `CLIENT`
  ADD PRIMARY KEY (`idCli`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idRole` (`idRole`);

--
-- Index pour la table `COMPORTER`
--
ALTER TABLE `COMPORTER`
  ADD PRIMARY KEY (`idAct`,`idPrest`),
  ADD KEY `idPrest` (`idPrest`);

--
-- Index pour la table `DEMANDER`
--
ALTER TABLE `DEMANDER`
  ADD PRIMARY KEY (`idCli`,`idPrest`),
  ADD KEY `idPrest` (`idPrest`),
  ADD KEY `idRegle` (`idRegle`);

--
-- Index pour la table `MATERIEL`
--
ALTER TABLE `MATERIEL`
  ADD PRIMARY KEY (`codeMateriel`);

--
-- Index pour la table `POSSEDER`
--
ALTER TABLE `POSSEDER`
  ADD PRIMARY KEY (`codeAct`,`codeMat`),
  ADD KEY `codeMat` (`codeMat`);

--
-- Index pour la table `PRESTATION`
--
ALTER TABLE `PRESTATION`
  ADD PRIMARY KEY (`idPrest`),
  ADD KEY `idAct` (`idAct`);

--
-- Index pour la table `REGLEMENT`
--
ALTER TABLE `REGLEMENT`
  ADD PRIMARY KEY (`idRegle`);

--
-- Index pour la table `ROLE`
--
ALTER TABLE `ROLE`
  ADD PRIMARY KEY (`idRole`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `ACTIVITE`
--
ALTER TABLE `ACTIVITE`
  MODIFY `codeAct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT pour la table `CLIENT`
--
ALTER TABLE `CLIENT`
  MODIFY `idCli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `MATERIEL`
--
ALTER TABLE `MATERIEL`
  MODIFY `codeMateriel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `PRESTATION`
--
ALTER TABLE `PRESTATION`
  MODIFY `idPrest` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT pour la table `REGLEMENT`
--
ALTER TABLE `REGLEMENT`
  MODIFY `idRegle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `ROLE`
--
ALTER TABLE `ROLE`
  MODIFY `idRole` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `CLIENT`
--
ALTER TABLE `CLIENT`
  ADD CONSTRAINT `CLIENT_ibfk_1` FOREIGN KEY (`idRole`) REFERENCES `ROLE` (`idRole`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `COMPORTER`
--
ALTER TABLE `COMPORTER`
  ADD CONSTRAINT `COMPORTER_ibfk_1` FOREIGN KEY (`idAct`) REFERENCES `ACTIVITE` (`codeAct`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `COMPORTER_ibfk_2` FOREIGN KEY (`idPrest`) REFERENCES `PRESTATION` (`idPrest`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `DEMANDER`
--
ALTER TABLE `DEMANDER`
  ADD CONSTRAINT `DEMANDER_ibfk_1` FOREIGN KEY (`idCli`) REFERENCES `CLIENT` (`idCli`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `DEMANDER_ibfk_2` FOREIGN KEY (`idPrest`) REFERENCES `PRESTATION` (`idPrest`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `DEMANDER_ibfk_3` FOREIGN KEY (`idRegle`) REFERENCES `REGLEMENT` (`idRegle`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `POSSEDER`
--
ALTER TABLE `POSSEDER`
  ADD CONSTRAINT `POSSEDER_ibfk_1` FOREIGN KEY (`codeMat`) REFERENCES `MATERIEL` (`codeMateriel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `POSSEDER_ibfk_2` FOREIGN KEY (`codeAct`) REFERENCES `ACTIVITE` (`codeAct`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
