-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 01 Avril 2015 à 16:12
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
  `date` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Contenu de la table `fiche`
--

INSERT INTO `fiche` (`id`, `id_utilisateur`, `id_etat`, `nb_justificatifs`, `date`) VALUES
(1, 1, 'CR', 0, '2015-03-01'),
(2, 1, 'RB', 0, '2015-02-01'),
(3, 1, 'RB', 0, '2015-01-01'),
(4, 1, 'RB', 0, '2014-12-01');

-- --------------------------------------------------------

--
-- Structure de la table `ligne_frais_forfait`
--

CREATE TABLE IF NOT EXISTS `ligne_frais_forfait` (
  `id_fiche` int(11) NOT NULL,
  `id_tyepfrais` varchar(3) COLLATE utf8_bin NOT NULL,
  `quantite` int(11) NOT NULL,
  `derniere_modif` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `ligne_frais_forfait`
--

INSERT INTO `ligne_frais_forfait` (`id_fiche`, `id_tyepfrais`, `quantite`, `derniere_modif`) VALUES
(1, 'ETP', 7, '2015-03-28'),
(1, 'KM', 245, '2015-03-28');

-- --------------------------------------------------------

--
-- Structure de la table `ligne_frais_horsforfait`
--

CREATE TABLE IF NOT EXISTS `ligne_frais_horsforfait` (
  `id_fiche` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `libelle` varchar(140) COLLATE utf8_bin NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `ligne_frais_horsforfait`
--

INSERT INTO `ligne_frais_horsforfait` (`id_fiche`, `montant`, `libelle`, `date`) VALUES
(1, 10, 'Burger King', '2015-03-27'),
(1, 15, 'Car Wash', '2015-03-25');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `login`, `mdp`, `adresse`, `cp`, `ville`, `date_embauche`, `type`) VALUES
(1, 'Blanc', 'Michel', 'michelb', '7e98ee275aa8e04e2890cbe0523156c8512530b1afa1493dcc0a7eb302a5651f', '2 Rue des Bronzés', 31500, 'Toulouse', '2003-02-13', 'vis'),
(2, 'Chazel', 'Marie-Anne', 'mariec', 'f75c752658645ad42bc43fab5385ad94d68f3fa9cea77573e288d54aee4ddd2d', '3 Rue des Bronzés', 31500, 'Toulouse', '2003-02-28', 'com'),
(3, 'Wayne', 'Bruce', 'brucew', '3ca8fb934ce56880cc73061369f4af09a6cce5e455a8e83366a96b0752bc6adc', 'Manoir Wayne', 133742, 'Gotham', '2001-09-09', 'vis');

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
 ADD PRIMARY KEY (`id_fiche`,`id_tyepfrais`), ADD KEY `fk_typefrais` (`id_tyepfrais`);

--
-- Index pour la table `ligne_frais_horsforfait`
--
ALTER TABLE `ligne_frais_horsforfait`
 ADD KEY `id_fiche` (`id_fiche`);

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
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
ADD CONSTRAINT `fk_fiche` FOREIGN KEY (`id_fiche`) REFERENCES `fiche` (`id`),
ADD CONSTRAINT `fk_typefrais` FOREIGN KEY (`id_tyepfrais`) REFERENCES `type_frais` (`id`);

--
-- Contraintes pour la table `ligne_frais_horsforfait`
--
ALTER TABLE `ligne_frais_horsforfait`
ADD CONSTRAINT `fk_fiche2` FOREIGN KEY (`id_fiche`) REFERENCES `fiche` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
