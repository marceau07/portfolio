-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Sam 18 Mai 2019 à 01:14
-- Version du serveur :  10.1.26-MariaDB-0+deb9u1
-- Version de PHP :  7.0.19-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bdLegendarium`
--

-- --------------------------------------------------------

--
-- Structure de la table `acheter`
--

CREATE TABLE `acheter` (
  `email` varchar(170) NOT NULL,
  `idLivre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `auteur`
--

CREATE TABLE `auteur` (
  `id` int(11) NOT NULL,
  `Nom` varchar(255) NOT NULL,
  `Prenom` varchar(255) NOT NULL,
  `Biographie` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `auteur`
--

INSERT INTO `auteur` (`id`, `Nom`, `Prenom`, `Biographie`) VALUES
(1, 'Rownling', 'J.K', 'Bonsoir');

-- --------------------------------------------------------

--
-- Structure de la table `disponibilite`
--

CREATE TABLE `disponibilite` (
  `id` tinyint(1) NOT NULL,
  `libelle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `disponibilite`
--

INSERT INTO `disponibilite` (`id`, `libelle`) VALUES
(0, 'Indisponible'),
(1, 'En cours d\'approvisionnement'),
(2, 'En stock');

-- --------------------------------------------------------

--
-- Structure de la table `ecrire`
--

CREATE TABLE `ecrire` (
  `idLivre` int(11) NOT NULL,
  `idAuteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `editer`
--

CREATE TABLE `editer` (
  `idEditeur` int(11) NOT NULL,
  `idLivre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `editeur`
--

CREATE TABLE `editeur` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `editeur`
--

INSERT INTO `editeur` (`id`, `libelle`) VALUES
(1, 'HACHETTE');

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `genre`
--

INSERT INTO `genre` (`id`, `libelle`) VALUES
(0, 'Policier'),
(1, 'Fantastique'),
(2, 'Horreur'),
(3, 'Science-Fiction'),
(4, 'Historique');

-- --------------------------------------------------------

--
-- Structure de la table `horaires`
--

CREATE TABLE `horaires` (
  `idJour` int(11) NOT NULL,
  `libelleJour` varchar(10) NOT NULL,
  `AMDeb` text,
  `AMFin` text,
  `PMDeb` text,
  `PMFin` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `horaires`
--

INSERT INTO `horaires` (`idJour`, `libelleJour`, `AMDeb`, `AMFin`, `PMDeb`, `PMFin`) VALUES
(1, 'Lundi', NULL, NULL, NULL, NULL),
(2, 'Mardi', '10h00', '12h30', '14h00', '19h00'),
(3, 'Mercredi', '10h00', '12h30', '14h00', '19h00'),
(4, 'Jeudi', '10h00', '12h30', '14h00', '19h00'),
(5, 'Vendredi', '10h00', '12h30', '14h00', '19h00'),
(6, 'Samedi', '10h00', '12h30', '14h00', '19h00'),
(7, 'Dimanche', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `jeux`
--

CREATE TABLE `jeux` (
  `idJeu` int(11) NOT NULL,
  `libelleJeu` varchar(255) NOT NULL,
  `etat` tinyint(1) DEFAULT NULL,
  `nbJoueurMax` int(2) NOT NULL,
  `dateEvenement` date NOT NULL,
  `idRoleJeu` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `jouer`
--

CREATE TABLE `jouer` (
  `email` varchar(170) NOT NULL,
  `idJeu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

CREATE TABLE `livres` (
  `idLivre` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `isbn` int(11) NOT NULL,
  `synopsis` text,
  `prix` float NOT NULL,
  `quantite` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `idDisponibilite` tinyint(1) NOT NULL,
  `idGenre` int(11) NOT NULL,
  `idAuteur` int(11) NOT NULL,
  `idEditeur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `livres`
--

INSERT INTO `livres` (`idLivre`, `titre`, `isbn`, `synopsis`, `prix`, `quantite`, `photo`, `idDisponibilite`, `idGenre`, `idAuteur`, `idEditeur`) VALUES
(1, 'hsd', 51654, 'sdjkbnj', 5, 5, '23e754f6.jpg', 2, 1, 1, 1),
(4, 'Essai', 4, 'Essai Syno', 5, 5, 'Assassin_s_Creed_e_Unity2019_4_19_1_30_30.jpg', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `roleJeu`
--

CREATE TABLE `roleJeu` (
  `idRoleJeu` int(1) NOT NULL,
  `libelleRoleJeu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `idRole` int(1) NOT NULL,
  `libelleRole` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `roles`
--

INSERT INTO `roles` (`idRole`, `libelleRole`) VALUES
(0, 'Client'),
(1, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `email` varchar(170) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `telephone` varchar(10) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `idRole` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `email`, `nom`, `prenom`, `pseudo`, `telephone`, `mdp`, `photo`, `idRole`) VALUES
(10, 'marceau070799@gmail.com', 'Rodrigues', 'Marceau', 'seauMar', '0638265641', '$2y$10$20Pyo99wRU1gOhd9ZA0SIOclGtvWpOPe68IAOHmeMwJs5EGpMG0Wa', NULL, 0),
(13, 'marceau0707@gmail.com', 'RODRIGUES', 'Marceau', 'seauMar', '0638265641', '$2y$10$j81i5OOt1GfO8p7vaRjRGuw49ZsY0PiUK6uHhuVWyJocqlqhPLKDq', 'proxy.duckduckgo.com.png', 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `acheter`
--
ALTER TABLE `acheter`
  ADD PRIMARY KEY (`email`,`idLivre`);

--
-- Index pour la table `auteur`
--
ALTER TABLE `auteur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `disponibilite`
--
ALTER TABLE `disponibilite`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ecrire`
--
ALTER TABLE `ecrire`
  ADD PRIMARY KEY (`idLivre`,`idAuteur`);

--
-- Index pour la table `editer`
--
ALTER TABLE `editer`
  ADD PRIMARY KEY (`idEditeur`,`idLivre`);

--
-- Index pour la table `editeur`
--
ALTER TABLE `editeur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `horaires`
--
ALTER TABLE `horaires`
  ADD PRIMARY KEY (`idJour`);

--
-- Index pour la table `jeux`
--
ALTER TABLE `jeux`
  ADD PRIMARY KEY (`idJeu`),
  ADD KEY `#idRoleJeu` (`idRoleJeu`);

--
-- Index pour la table `jouer`
--
ALTER TABLE `jouer`
  ADD PRIMARY KEY (`email`,`idJeu`),
  ADD KEY `#idJeu` (`idJeu`);

--
-- Index pour la table `livres`
--
ALTER TABLE `livres`
  ADD PRIMARY KEY (`idLivre`),
  ADD UNIQUE KEY `ISBN` (`isbn`),
  ADD KEY `#idGenre` (`idGenre`),
  ADD KEY `#idAuteur` (`idAuteur`),
  ADD KEY `#idDisponibilité` (`idDisponibilite`),
  ADD KEY `idEditeur` (`idEditeur`);

--
-- Index pour la table `roleJeu`
--
ALTER TABLE `roleJeu`
  ADD PRIMARY KEY (`idRoleJeu`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idRole`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `#idRole` (`idRole`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `auteur`
--
ALTER TABLE `auteur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `editeur`
--
ALTER TABLE `editeur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `horaires`
--
ALTER TABLE `horaires`
  MODIFY `idJour` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `jeux`
--
ALTER TABLE `jeux`
  MODIFY `idJeu` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `livres`
--
ALTER TABLE `livres`
  MODIFY `idLivre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
