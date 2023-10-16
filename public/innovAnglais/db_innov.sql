-- phpMyAdmin SQL Dump
-- version 4.6.6deb4+deb9u2
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Sam 09 Octobre 2021 à 15:30
-- Version du serveur :  10.1.48-MariaDB-0+deb9u2
-- Version de PHP :  7.3.21-1+0~20200807.66+debian9~1.gbp18a1c2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `db_innov`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonnement`
--

CREATE TABLE `abonnement` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nb_fois` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `abonnement`
--

INSERT INTO `abonnement` (`id`, `libelle`, `nb_fois`) VALUES
(1, 'Mensuel', 1),
(2, 'Trimestriel', 3),
(3, 'Semestriel', 6),
(4, 'Annuel', 12);

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`id`, `libelle`) VALUES
(2, 'Verbe'),
(3, 'Nom'),
(4, 'Adjectif'),
(5, 'La ferme');

-- --------------------------------------------------------

--
-- Structure de la table `date`
--

CREATE TABLE `date` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `effectuer`
--

CREATE TABLE `effectuer` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `date_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resultat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `effectuer`
--

INSERT INTO `effectuer` (`id`, `test_id`, `date_id`, `user_id`, `resultat`) VALUES
(1, 1, 2147483647, 9, '7'),
(2, 1, 2147483647, 9, ''),
(3, 1, 2147483647, 9, ''),
(4, 13, 2147483647, 2, ''),
(5, 1, 2147483647, 1, ''),
(6, 1, 2147483647, 9, ''),
(7, 4, 2147483647, 9, ''),
(8, 1, 2147483647, 9, ''),
(9, 1, 2147483647, 9, ''),
(10, 1, 2147483647, 9, ''),
(11, 1, 2147483647, 9, '');

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `entreprise`
--

INSERT INTO `entreprise` (`id`, `nom`) VALUES
(1, 'Pas d\'entreprise'),
(2, 'IBM'),
(3, 'CGI'),
(4, 'Capgemini');

-- --------------------------------------------------------

--
-- Structure de la table `login_attempt`
--

CREATE TABLE `login_attempt` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `username` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `login_attempt`
--

INSERT INTO `login_attempt` (`id`, `ip_address`, `date`, `username`) VALUES
(10, 'fd42:1b19:67cc:abcb:216:3eff:fe7b:a5f5', '2021-10-08 00:44:48', 'login4017'),
(11, 'fd42:1b19:67cc:abcb:216:3eff:fe7b:a5f5', '2021-10-08 00:55:15', 'login4017'),
(12, 'fd42:1b19:67cc:abcb:216:3eff:fe7b:a5f5', '2021-10-08 01:01:13', 'login4017'),
(13, 'fd42:1b19:67cc:abcb:216:3eff:fe7b:a5f5', '2021-10-08 21:24:21', 'login4017'),
(14, 'fd42:1b19:67cc:abcb:216:3eff:fe7b:a5f5', '2021-10-09 10:52:19', 'login4017'),
(15, 'fd42:1b19:67cc:abcb:216:3eff:fe7b:a5f5', '2021-10-09 12:23:12', 'login4017');

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20200310083612', '2020-03-10 08:36:34'),
('20200322231108', '2020-03-22 23:11:22'),
('20200323144721', '2020-03-23 14:51:00'),
('20200323144840', '2020-03-23 14:51:01'),
('20200323145054', '2020-03-23 14:51:01'),
('20200323145156', '2020-03-23 14:52:01'),
('20200323152430', '2020-03-23 15:24:43'),
('20200405000143', '2020-04-05 00:05:17'),
('20200405002752', '2020-04-05 00:29:27'),
('20200405003130', '2020-04-05 00:31:54'),
('20200405003546', '2020-04-05 00:35:59'),
('20200405005412', '2020-04-05 00:54:39'),
('20210925155955', '2021-09-25 16:00:09');

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

CREATE TABLE `niveau` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `niveau`
--

INSERT INTO `niveau` (`id`, `libelle`) VALUES
(5, 'A0'),
(6, 'A1'),
(7, 'A2'),
(8, 'B1'),
(13, 'B2'),
(21, 'C1'),
(22, 'C2');

-- --------------------------------------------------------

--
-- Structure de la table `niveau_user`
--

CREATE TABLE `niveau_user` (
  `id` int(11) NOT NULL,
  `libelle_level_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `niveau_user`
--

INSERT INTO `niveau_user` (`id`, `libelle_level_user`) VALUES
(1, 'A1'),
(2, 'A2'),
(4, 'B1'),
(5, 'B2'),
(7, 'C1'),
(8, 'C2'),
(9, 'A0\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `payer`
--

CREATE TABLE `payer` (
  `id` int(11) NOT NULL,
  `abonnement_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `date_deb` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `niveau_id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '<i class="fas fa-book-open" style="color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;"></i>'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `test`
--

INSERT INTO `test` (`id`, `theme_id`, `niveau_id`, `libelle`, `icone`) VALUES
(1, 1, 5, 'La ferme', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(2, 3, 8, 'L\'informatique', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(3, 6, 22, 'La Famille', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(4, 1, 5, 'Milieu aquatique', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(5, 1, 7, 'Dans les zoo', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(6, 3, 8, 'L\'informatique', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(7, 6, 21, 'La Famille', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(8, 1, 6, 'aéeaée', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(9, 1, 6, 'Les Animaux', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(10, 3, 13, 'L\'informatique', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(11, 6, 21, 'La Famille', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(12, 5, 5, 'aéeaée', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(13, 2, 5, 'Les Animaux', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(14, 3, 8, 'L\'informatique', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(15, 6, 21, 'La Famille', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(16, 1, 5, 'aéeaée', '<i class=\"fas fa-book-open\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>');

-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

CREATE TABLE `theme` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '<i class="fas fa-book-open" style="color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;"></i>'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `theme`
--

INSERT INTO `theme` (`id`, `libelle`, `icone`) VALUES
(1, 'Animaux', '<i class=\"fas fa-paw\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(2, 'Vacances', '<i class=\"fas fa-umbrella-beach\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(3, 'Informatique', '<i class=\"fas fa-laptop-code\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(4, 'Ecole', '<i class=\"fas fa-book\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(5, 'Maison', '<i class=\"fas fa-home\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>'),
(6, 'Famille', '<i class=\"fas fa-users\" style=\"color:#489CBE;position: absolute;bottom: -30px;left: -20px;font-size: 13em;filter: brightness(0) invert(1);opacity: 0.13;z-index: auto;\"></i>');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `entreprise_id` int(11) DEFAULT NULL,
  `niveau_id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `n_mdp` int(11) DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `entreprise_id`, `niveau_id`, `username`, `roles`, `password`, `last_name_user`, `first_name_user`, `age`, `email`, `n_mdp`, `reset_token`) VALUES
(1, 1, 8, 'admin', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$r6oTs7r54u6DVrBOcBQtng$wvW8ynzrnn9oZtcdlXFcoOKkGhKX+C2b5K6do8Z5uUg', 'admin', 'admin', 19, 'admin@admin.com', NULL, NULL),
(2, 2, 5, 'login4018', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$grx+cBbajPz/d/T7l9YMvw$ZCSYv/hy2af8VpdHbQahyYLjK0HGEYcN4Lqasgi3aJ0', 'KATA', 'Romain', 20, 'login4018@login4018.com', NULL, NULL),
(5, 1, 4, 'Romain', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$0+yJ9G33cJLvmvIWq8JuqQ$Lh5kIpUD8AM3iAQrBHZyqFIwcYLZ/xU1BuQbMkaGmCA', 'KATA', 'Romain', 20, 'romain.kata@laposte.net', NULL, NULL),
(6, 1, 7, 'Lachouri', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$vT5+QD3o5D1Dg5i1xrLeLw$JlkFynqAvWzKrhq/fBVK0gpbwxJsQXM8foOVjupP12Y', 'ACHOURI', 'Ludivine', 20, 'ludivine.achouri@orange.fr', NULL, NULL),
(7, 1, 5, 'romainkata.sio@gmail.com', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$YpQtgXQC6FWsstgM9Tslcw$Z1tSSZjml090mxw9DbhDoiHG8epfbJvgLEsIxe8lzaY', 'Kata', 'Romain', 20, 'romainkata.sio@gmail.com', NULL, NULL),
(9, 2, 2, 'login4017', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$bCkMr4eQooJQEONLLt971Q$GXGt2jKyrCjtIok9lSfUcbmKgmG+ZZiWKWj16bk3+Kg', 'login4017', 'login4017', 21, 'symfony4.4017@gmail.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `role_id_id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vocabulaire`
--

CREATE TABLE `vocabulaire` (
  `id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `libelle_fr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `vocabulaire`
--

INSERT INTO `vocabulaire` (`id`, `theme_id`, `categorie_id`, `libelle_fr`, `libelle_en`) VALUES
(1, 1, 3, 'Cheval', 'Horse'),
(2, 1, 2, 'Nager', 'Swim'),
(3, 1, 3, 'Chien', 'Dog'),
(4, 1, 2, 'Courir', 'Run'),
(5, 3, 2, 'Développer', 'Develop'),
(6, 3, 3, 'Ordinateur', 'Computer'),
(7, 3, 4, 'Puissant', 'Powerful'),
(8, 3, 3, 'Ecran', 'Monitor'),
(9, 6, 3, 'Père', 'Dad'),
(10, 6, 2, 'Jouer', 'Play'),
(11, 6, 4, 'Uni', ''),
(12, 6, 3, 'Mère', 'Mother');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `date`
--
ALTER TABLE `date`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `effectuer`
--
ALTER TABLE `effectuer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_985281501E5D0459` (`test_id`),
  ADD KEY `IDX_98528150A76ED395` (`user_id`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `login_attempt`
--
ALTER TABLE `login_attempt`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `niveau`
--
ALTER TABLE `niveau`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `niveau_user`
--
ALTER TABLE `niveau_user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `payer`
--
ALTER TABLE `payer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_41CB5B99F1D74413` (`abonnement_id`),
  ADD KEY `IDX_41CB5B99FB88E14F` (`utilisateur_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D87F7E0C59027487` (`theme_id`),
  ADD KEY `IDX_D87F7E0CB3E9C81` (`niveau_id`);

--
-- Index pour la table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD KEY `IDX_8D93D649A4AEAFEA` (`entreprise_id`),
  ADD KEY `IDX_8D93D649B3E9C81` (`niveau_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1D1C63B388987678` (`role_id_id`);

--
-- Index pour la table `vocabulaire`
--
ALTER TABLE `vocabulaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DB1ADE7D59027487` (`theme_id`),
  ADD KEY `IDX_DB1ADE7DBCF5E72D` (`categorie_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `abonnement`
--
ALTER TABLE `abonnement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `date`
--
ALTER TABLE `date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `effectuer`
--
ALTER TABLE `effectuer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `login_attempt`
--
ALTER TABLE `login_attempt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `niveau`
--
ALTER TABLE `niveau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT pour la table `niveau_user`
--
ALTER TABLE `niveau_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `payer`
--
ALTER TABLE `payer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `vocabulaire`
--
ALTER TABLE `vocabulaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `effectuer`
--
ALTER TABLE `effectuer`
  ADD CONSTRAINT `FK_985281501E5D0459` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`),
  ADD CONSTRAINT `FK_98528150A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `payer`
--
ALTER TABLE `payer`
  ADD CONSTRAINT `FK_41CB5B99F1D74413` FOREIGN KEY (`abonnement_id`) REFERENCES `abonnement` (`id`),
  ADD CONSTRAINT `FK_41CB5B99FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `FK_D87F7E0C59027487` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`id`),
  ADD CONSTRAINT `FK_D87F7E0CB3E9C81` FOREIGN KEY (`niveau_id`) REFERENCES `niveau` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649A4AEAFEA` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprise` (`id`),
  ADD CONSTRAINT `FK_8D93D649B3E9C81` FOREIGN KEY (`niveau_id`) REFERENCES `niveau_user` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_1D1C63B388987678` FOREIGN KEY (`role_id_id`) REFERENCES `role` (`id`);

--
-- Contraintes pour la table `vocabulaire`
--
ALTER TABLE `vocabulaire`
  ADD CONSTRAINT `FK_DB1ADE7D59027487` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`id`),
  ADD CONSTRAINT `FK_DB1ADE7DBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
