-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Lun 11 Mai 2015 à 16:47
-- Version du serveur :  5.6.20-log
-- Version de PHP :  5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `gsb`
--

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

CREATE TABLE IF NOT EXISTS `etat` (
  `id` varchar(3) COLLATE utf8_bin NOT NULL,
  `libelle` varchar(32) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `etat`
--

INSERT INTO `etat` (`id`, `libelle`) VALUES
('CL', 'Saisie clôturée'),
('CR', 'Saisie en cours'),
('RB', 'Remboursée'),
('VA', 'Validée, paiement en attente');

-- --------------------------------------------------------

--
-- Structure de la table `fiche`
--

CREATE TABLE IF NOT EXISTS `fiche` (
`id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_etat` varchar(3) COLLATE utf8_bin NOT NULL,
  `nb_justificatifs` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_forfait` float NOT NULL,
  `total_hforfait` float NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=23 ;

--
-- Contenu de la table `fiche`
--

INSERT INTO `fiche` (`id`, `id_utilisateur`, `id_etat`, `nb_justificatifs`, `date`, `total_forfait`, `total_hforfait`) VALUES
(1, 1, 'CL', 0, '2015-03-01 00:00:00', 1706.9, 25),
(2, 1, 'RB', 0, '2015-02-01 00:00:00', 0, 0),
(3, 1, 'RB', 0, '2015-01-01 00:00:00', 0, 0),
(4, 1, 'RB', 0, '2014-12-01 00:00:00', 0, 0),
(6, 1, 'RB', 0, '2014-11-01 00:00:00', 0, 0),
(7, 1, 'RB', 0, '2014-10-01 00:00:00', 0, 0),
(8, 1, 'RB', 0, '2014-09-01 00:00:00', 0, 0),
(13, 3, 'CL', 0, '2015-04-10 13:08:22', 0, 0),
(15, 1, 'CL', 0, '2015-04-10 13:10:02', 0, 0),
(16, 4, 'CL', 0, '2015-03-03 13:14:56', 0, 0),
(17, 2, 'CL', 0, '2015-04-10 13:15:43', 0, 0),
(18, 3, 'CL', 0, '2015-04-29 15:12:27', 0, 0),
(19, 4, 'CL', 0, '2015-04-29 15:21:03', 0, 0),
(20, 1, 'CR', 0, '2015-05-05 10:30:33', 0, 0),
(21, 4, 'CR', 0, '2015-05-07 15:06:37', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_frais_forfait`
--

CREATE TABLE IF NOT EXISTS `ligne_frais_forfait` (
`id` int(11) NOT NULL,
  `id_fiche` int(11) NOT NULL,
  `id_typefrais` enum('ETP','KM','NUI','REP') COLLATE utf8_bin NOT NULL,
  `quantite` int(11) NOT NULL,
  `derniere_modif` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=15 ;

--
-- Contenu de la table `ligne_frais_forfait`
--

INSERT INTO `ligne_frais_forfait` (`id`, `id_fiche`, `id_typefrais`, `quantite`, `derniere_modif`) VALUES
(1, 1, 'ETP', 7, '2015-03-28 00:00:00'),
(2, 1, 'KM', 245, '2015-03-28 00:00:00'),
(3, 1, 'NUI', 2, '2015-04-02 00:00:00'),
(4, 1, 'REP', 25, '2015-04-02 00:00:00'),
(5, 15, 'ETP', 25, '0000-00-00 00:00:00'),
(9, 20, 'ETP', 20, '2015-05-11 12:07:42'),
(12, 20, 'KM', 10, '2015-05-11 12:12:10'),
(11, 20, 'NUI', 48, '2015-05-11 15:43:56'),
(13, 20, 'REP', 20, '2015-05-11 13:18:40');

-- --------------------------------------------------------

--
-- Structure de la table `ligne_frais_horsforfait`
--

CREATE TABLE IF NOT EXISTS `ligne_frais_horsforfait` (
`id` int(11) NOT NULL,
  `id_fiche` int(11) NOT NULL,
  `montant` float NOT NULL,
  `libelle` varchar(140) COLLATE utf8_bin NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=22 ;

--
-- Contenu de la table `ligne_frais_horsforfait`
--

INSERT INTO `ligne_frais_horsforfait` (`id`, `id_fiche`, `montant`, `libelle`, `date`) VALUES
(1, 1, 10, 'Burger King', '2015-03-27'),
(2, 1, 15, 'Car Wash', '2015-03-25'),
(9, 20, 10, 'Burger King', '2015-05-22'),
(15, 20, 25, 'Parc Astérix', '2015-05-11'),
(16, 20, 15, 'Disneyland', '2015-05-14'),
(17, 20, 15, 'Disneyland 2', '2015-05-15'),
(19, 21, 13, 'Burger King', '2015-05-23'),
(20, 21, 1.95, 'Quick', '2015-05-22'),
(21, 21, 50, 'KFC', '2015-05-30');

-- --------------------------------------------------------

--
-- Structure de la table `type_frais`
--

CREATE TABLE IF NOT EXISTS `type_frais` (
  `id` varchar(3) COLLATE utf8_bin NOT NULL,
  `libelle` varchar(32) COLLATE utf8_bin NOT NULL,
  `montant` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `type_frais`
--

INSERT INTO `type_frais` (`id`, `libelle`, `montant`) VALUES
('ETP', 'Etape', 110),
('KM', 'Kilomètre', 0.62),
('NUI', 'Nuit', 80),
('REP', 'Repas', 25);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
`id` int(11) NOT NULL,
  `nom` varchar(32) COLLATE utf8_bin NOT NULL,
  `prenom` varchar(32) COLLATE utf8_bin NOT NULL,
  `login` varchar(32) COLLATE utf8_bin NOT NULL,
  `mdp` varchar(64) COLLATE utf8_bin NOT NULL,
  `adresse` varchar(64) COLLATE utf8_bin NOT NULL,
  `cp` int(11) NOT NULL,
  `ville` varchar(16) COLLATE utf8_bin NOT NULL,
  `date_embauche` date NOT NULL DEFAULT '0000-00-00',
  `type` enum('vis','com') COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `login`, `mdp`, `adresse`, `cp`, `ville`, `date_embauche`, `type`) VALUES
(1, 'Blanc', 'Michel', 'michelb', '7e98ee275aa8e04e2890cbe0523156c8512530b1afa1493dcc0a7eb302a5651f', '2 Rue des Bronzés', 31500, 'Toulouse', '2003-02-13', 'vis'),
(2, 'Chazel', 'Marie-Anne', 'mariec', '26429a356b1d25b7d57c0f9a6d5fed8a290cb42374185887dcd2874548df0779', '3 Rue des Bronzés', 31500, 'Toulouse', '2003-02-28', 'com'),
(3, 'Wayne', 'Bruce', 'brucew', '3ca8fb934ce56880cc73061369f4af09a6cce5e455a8e83366a96b0752bc6adc', 'Manoir Wayne', 133742, 'Gotham', '2001-09-09', 'vis'),
(4, 'Hamadi', 'Sofiane', 'sofianh', '26429a356b1d25b7d57c0f9a6d5fed8a290cb42374185887dcd2874548df0779', '2 Rue du Hentai', 123456, 'Tokyo', '2015-09-01', 'vis');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `etat`
--
ALTER TABLE `etat`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fiche`
--
ALTER TABLE `fiche`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `date` (`date`), ADD KEY `id_utilisateur` (`id_utilisateur`), ADD KEY `id_etat` (`id_etat`);

--
-- Index pour la table `ligne_frais_forfait`
--
ALTER TABLE `ligne_frais_forfait`
 ADD PRIMARY KEY (`id_fiche`,`id_typefrais`), ADD UNIQUE KEY `id` (`id`), ADD KEY `id_fiche` (`id_fiche`), ADD KEY `fk_frais_id` (`id_typefrais`);

--
-- Index pour la table `ligne_frais_horsforfait`
--
ALTER TABLE `ligne_frais_horsforfait`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD KEY `id_fiche` (`id_fiche`);

--
-- Index pour la table `type_frais`
--
ALTER TABLE `type_frais`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `fiche`
--
ALTER TABLE `fiche`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT pour la table `ligne_frais_forfait`
--
ALTER TABLE `ligne_frais_forfait`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT pour la table `ligne_frais_horsforfait`
--
ALTER TABLE `ligne_frais_horsforfait`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `fiche`
--
ALTER TABLE `fiche`
ADD CONSTRAINT `fk_etat` FOREIGN KEY (`id_etat`) REFERENCES `etat` (`id`),
ADD CONSTRAINT `fk_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `ligne_frais_forfait`
--
ALTER TABLE `ligne_frais_forfait`
ADD CONSTRAINT `fk_ficheid` FOREIGN KEY (`id_fiche`) REFERENCES `fiche` (`id`);

--
-- Contraintes pour la table `ligne_frais_horsforfait`
--
ALTER TABLE `ligne_frais_horsforfait`
ADD CONSTRAINT `fk_fiche2` FOREIGN KEY (`id_fiche`) REFERENCES `fiche` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
