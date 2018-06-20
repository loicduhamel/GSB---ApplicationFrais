-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 01, 2018 at 11:45 PM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `AppliFrais`
--

-- --------------------------------------------------------

--
-- Table structure for table `cabinet`
--

CREATE TABLE `cabinet` (
  `id` int(11) NOT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `code_postal` varchar(5) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `etat`
--

CREATE TABLE `etat` (
  `id` char(2) NOT NULL,
  `libelle` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `etat`
--

INSERT INTO `etat` (`id`, `libelle`) VALUES
('CL', 'Saisie clôturée'),
('CR', 'Fiche créée, saisie en cours'),
('RB', 'Remboursée'),
('VA', 'Validée et mise en paiement');

-- --------------------------------------------------------

--
-- Table structure for table `fiche_frais`
--

CREATE TABLE `fiche_frais` (
  `mois` int(11) NOT NULL,
  `nb_justificatifs` int(11) DEFAULT NULL,
  `montant_valide` float DEFAULT NULL,
  `date_modif` date DEFAULT NULL,
  `id_visiteur` int(11) NOT NULL,
  `id_etat` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fiche_frais`
--

INSERT INTO `fiche_frais` (`mois`, `nb_justificatifs`, `montant_valide`, `date_modif`, `id_visiteur`, `id_etat`) VALUES
(3, NULL, 34.1, '2018-04-30', 62, 'CR'),
(4, NULL, 7.44, '2018-04-19', 62, 'CR');

-- --------------------------------------------------------

--
-- Table structure for table `frais_forfait`
--

CREATE TABLE `frais_forfait` (
  `id` char(3) NOT NULL,
  `libelle` char(20) DEFAULT NULL,
  `montant` decimal(5,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `frais_forfait`
--

INSERT INTO `frais_forfait` (`id`, `libelle`, `montant`) VALUES
('ETP', 'Forfait Etape', '110'),
('KM', 'Frais Kilométrique', '1'),
('NUI', 'Nuitée Hôtel', '80'),
('REP', 'Repas Restaurant', '25');

-- --------------------------------------------------------

--
-- Table structure for table `ligne_frais_forfait`
--

CREATE TABLE `ligne_frais_forfait` (
  `id` int(11) NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `date_depense` date DEFAULT NULL,
  `mois` int(11) NOT NULL,
  `id_visiteur` int(11) NOT NULL,
  `id_frais_forfait` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ligne_frais_forfait`
--

INSERT INTO `ligne_frais_forfait` (`id`, `quantite`, `description`, `date_depense`, `mois`, `id_visiteur`, `id_frais_forfait`) VALUES
(1, 15, 'déplacement', '2018-03-28', 3, 62, 'KM'),
(2, 12, 'trajet Lille-Paris', '2018-04-19', 4, 62, 'KM'),
(3, 2, 'Séjour Séoul', '2018-04-19', 4, 62, 'NUI'),
(7, 3, 'Paris Lille', '2018-04-18', 4, 62, 'ETP');

-- --------------------------------------------------------

--
-- Table structure for table `ligne_frais_hors_forfait`
--

CREATE TABLE `ligne_frais_hors_forfait` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `date_frais_hors_forfait` date DEFAULT NULL,
  `montant` decimal(10,0) DEFAULT NULL,
  `mois` int(11) NOT NULL,
  `id_visiteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ligne_frais_hors_forfait`
--

INSERT INTO `ligne_frais_hors_forfait` (`id`, `libelle`, `date_frais_hors_forfait`, `montant`, `mois`, `id_visiteur`) VALUES
(1, 'Achat matériel', '2018-04-11', '152', 4, 62);

-- --------------------------------------------------------

--
-- Table structure for table `medecin`
--

CREATE TABLE `medecin` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `id_cabinet` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `description` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `description`) VALUES
(1, 'Administrateur'),
(2, 'Comptable'),
(3, 'Visiteur');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `code_postal` varchar(25) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `date_embauche` date DEFAULT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `email` varchar(25) DEFAULT NULL,
  `id_medecin` int(11) DEFAULT NULL,
  `id_role` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `login`, `password`, `version`, `code_postal`, `ville`, `date_embauche`, `adresse`, `telephone`, `email`, `id_medecin`, `id_role`) VALUES
(61, 'Admin', 'Admin', 'aadmin', 'administrateur', 1, '00001', 'City', '0001-01-01', 'Address', '0000000000', NULL, NULL, 1),
(62, 'Vis', 'Vis', 'vvisit', 'visiteur', 1, '00001', 'City', '0001-01-01', 'Address', '0000000000', NULL, NULL, 3),
(63, 'Compt', 'Compt', 'ccompt', 'comptable', 1, '00001', 'City', '0001-01-01', 'Address', '0000000000', NULL, NULL, 2),
(64, 'Villechalane', 'Louis', 'lvillachane', 'jux7g', 1, '46000', 'Cahors', '2005-12-21', '8 rue des Charmes', NULL, NULL, NULL, 3),
(65, 'Andre', 'David', 'dandre', 'oppg5', 1, '46200', 'Lalbenque', '1998-11-23', '1 rue Petit', NULL, NULL, NULL, 3),
(66, 'Bedos', 'Christian', 'cbedos', 'gmhxd', 1, '46250', 'Montcuq', '1995-01-12', '1 rue Peranud', NULL, NULL, NULL, 3),
(67, 'Tusseau', 'Louis', 'ltusseau', 'ktp3s', 1, '46123', 'Gramat', '2000-05-01', '22 rue des Ternes', NULL, NULL, NULL, 3),
(68, 'Bentot', 'Pascal', 'pbentot', 'doyw1', 1, '46512', 'Bessines', '1992-07-09', '11 allée des Cerises', NULL, NULL, NULL, 3),
(69, 'Bioret', 'Luc', 'lbioret', 'hrjfs', 1, '46000', 'Cahors', '1998-05-11', '1 Avenue gambetta', NULL, NULL, NULL, 3),
(70, 'Bunisset', 'Francis', 'fbunisset', '4vbnd', 1, '93100', 'Montreuil', '1987-10-21', '10 rue des Perles', NULL, NULL, NULL, 3),
(71, 'Bunisset', 'Denise', 'dbunisset', 's1y1r', 1, '75019', 'paris', '2010-12-05', '23 rue Manin', NULL, NULL, NULL, 3),
(72, 'Cacheux', 'Bernard', 'bcacheux', 'uf7r3', 1, '75017', 'Paris', '2009-11-12', '114 rue Blanche', NULL, NULL, NULL, 3),
(73, 'Cadic', 'Eric', 'ecadic', '6u8dc', 1, '75011', 'Paris', '2008-09-23', '123 avenue de la République', NULL, NULL, NULL, 3),
(74, 'Charoze', 'Catherine', 'ccharoze', 'u817o', 1, '75019', 'Paris', '2005-11-12', '100 rue Petit', NULL, NULL, NULL, 3),
(75, 'Clepkens', 'Christophe', 'cclepkens', 'bw1us', 1, '93230', 'Romainville', '2003-08-11', '12 allée des Anges', NULL, NULL, NULL, 3),
(76, 'Cottin', 'Vincenne', 'vcottin', '2hoh9', 1, '93100', 'Monteuil', '2001-11-18', '36 rue Des Roches', NULL, NULL, NULL, 3),
(77, 'Daburon', 'François', 'fdaburon', '7oqpv', 1, '94000', 'Créteil', '2002-02-11', '13 rue de Chanzy', NULL, NULL, NULL, 3),
(78, 'De', 'Philippe', 'pde', 'gk9kx', 1, '94000', 'Créteil', '2010-12-14', '13 rue Barthes', NULL, NULL, NULL, 3),
(79, 'Debelle', 'Michel', 'mdebelle', 'od5rt', 1, '93210', 'Rosny', '2006-11-23', '181 avenue Barbusse', NULL, NULL, NULL, 3),
(80, 'Debelle', 'Jeanne', 'jdebelle', 'nvwqq', 1, '44000', 'Nantes', '2000-05-11', '134 allée des Joncs', NULL, NULL, NULL, 3),
(81, 'Debroise', 'Michel', 'mdebroise', 'sghkb', 1, '44000', 'Nantes', '2001-04-17', '2 Bld Jourdain', NULL, NULL, NULL, 3),
(82, 'Desmarquest', 'Nathalie', 'ndesmarquest', 'f1fob', 1, '45000', 'Orléans', '2005-11-12', '14 Place d Arc', NULL, NULL, NULL, 3),
(83, 'Desnost', 'Pierre', 'pdesnost', '4k2o5', 1, '23200', 'Guéret', '2001-02-05', '16 avenue des Cèdres', NULL, NULL, NULL, 3),
(84, 'Dudouit', 'Frédéric', 'fdudouit', '44im8', 1, '23120', 'GrandBourg', '2000-08-01', '18 rue de l église', NULL, NULL, NULL, 3),
(85, 'Duncombe', 'Claude', 'cduncombe', 'qf77j', 1, '23100', 'La souteraine', '1987-10-10', '19 rue de la tour', NULL, NULL, NULL, 3),
(86, 'Enault-Pascreau', 'Céline', 'cenault', 'y2qdu', 1, '23200', 'Gueret', '1995-09-01', '25 place de la gare', NULL, NULL, NULL, 3),
(87, 'Eynde', 'Valérie', 'veynde', 'i7sn3', 1, '13015', 'Marseille', '1999-11-01', '3 Grand Place', NULL, NULL, NULL, 3),
(88, 'Finck', 'Jacques', 'jfinck', 'mpb3t', 1, '13002', 'Marseille', '2001-11-10', '10 avenue du Prado', NULL, NULL, NULL, 3),
(89, 'Frémont', 'Fernande', 'ffremont', 'xs5tq', 1, '13012', 'Allauh', '1998-10-01', '4 route de la mer', NULL, NULL, NULL, 3),
(90, 'Gest', 'Alain', 'agest', 'dywvt', 1, '13025', 'Berre', '1985-11-01', '30 avenue de la mer', NULL, NULL, NULL, 3),
(91, 'Admin', 'Admin', 'aadmin', 'administrateur', 1, '00001', 'City', '0001-01-01', 'Address', '0000000000', NULL, NULL, 1),
(92, 'Vis', 'Vis', 'vvisit', 'visiteur', 1, '00001', 'City', '0001-01-01', 'Address', '0000000000', NULL, NULL, 3),
(93, 'Compt', 'Compt', 'ccompt', 'comptable', 1, '00001', 'City', '0001-01-01', 'Address', '0000000000', NULL, NULL, 2),
(94, 'Villechalane', 'Louis', 'lvillachane', 'jux7g', 1, '46000', 'Cahors', '2005-12-21', '8 rue des Charmes', NULL, NULL, NULL, 3),
(95, 'Andre', 'David', 'dandre', 'oppg5', 1, '46200', 'Lalbenque', '1998-11-23', '1 rue Petit', NULL, NULL, NULL, 3),
(96, 'Bedos', 'Christian', 'cbedos', 'gmhxd', 1, '46250', 'Montcuq', '1995-01-12', '1 rue Peranud', NULL, NULL, NULL, 3),
(97, 'Tusseau', 'Louis', 'ltusseau', 'ktp3s', 1, '46123', 'Gramat', '2000-05-01', '22 rue des Ternes', NULL, NULL, NULL, 3),
(98, 'Bentot', 'Pascal', 'pbentot', 'doyw1', 1, '46512', 'Bessines', '1992-07-09', '11 allée des Cerises', NULL, NULL, NULL, 3),
(99, 'Bioret', 'Luc', 'lbioret', 'hrjfs', 1, '46000', 'Cahors', '1998-05-11', '1 Avenue gambetta', NULL, NULL, NULL, 3),
(100, 'Bunisset', 'Francis', 'fbunisset', '4vbnd', 1, '93100', 'Montreuil', '1987-10-21', '10 rue des Perles', NULL, NULL, NULL, 3),
(101, 'Bunisset', 'Denise', 'dbunisset', 's1y1r', 1, '75019', 'paris', '2010-12-05', '23 rue Manin', NULL, NULL, NULL, 3),
(102, 'Cacheux', 'Bernard', 'bcacheux', 'uf7r3', 1, '75017', 'Paris', '2009-11-12', '114 rue Blanche', NULL, NULL, NULL, 3),
(103, 'Cadic', 'Eric', 'ecadic', '6u8dc', 1, '75011', 'Paris', '2008-09-23', '123 avenue de la République', NULL, NULL, NULL, 3),
(104, 'Charoze', 'Catherine', 'ccharoze', 'u817o', 1, '75019', 'Paris', '2005-11-12', '100 rue Petit', NULL, NULL, NULL, 3),
(105, 'Clepkens', 'Christophe', 'cclepkens', 'bw1us', 1, '93230', 'Romainville', '2003-08-11', '12 allée des Anges', NULL, NULL, NULL, 3),
(106, 'Cottin', 'Vincenne', 'vcottin', '2hoh9', 1, '93100', 'Monteuil', '2001-11-18', '36 rue Des Roches', NULL, NULL, NULL, 3),
(107, 'Daburon', 'François', 'fdaburon', '7oqpv', 1, '94000', 'Créteil', '2002-02-11', '13 rue de Chanzy', NULL, NULL, NULL, 3),
(108, 'De', 'Philippe', 'pde', 'gk9kx', 1, '94000', 'Créteil', '2010-12-14', '13 rue Barthes', NULL, NULL, NULL, 3),
(109, 'Debelle', 'Michel', 'mdebelle', 'od5rt', 1, '93210', 'Rosny', '2006-11-23', '181 avenue Barbusse', NULL, NULL, NULL, 3),
(110, 'Debelle', 'Jeanne', 'jdebelle', 'nvwqq', 1, '44000', 'Nantes', '2000-05-11', '134 allée des Joncs', NULL, NULL, NULL, 3),
(111, 'Debroise', 'Michel', 'mdebroise', 'sghkb', 1, '44000', 'Nantes', '2001-04-17', '2 Bld Jourdain', NULL, NULL, NULL, 3),
(112, 'Desmarquest', 'Nathalie', 'ndesmarquest', 'f1fob', 1, '45000', 'Orléans', '2005-11-12', '14 Place d Arc', NULL, NULL, NULL, 3),
(113, 'Desnost', 'Pierre', 'pdesnost', '4k2o5', 1, '23200', 'Guéret', '2001-02-05', '16 avenue des Cèdres', NULL, NULL, NULL, 3),
(114, 'Dudouit', 'Frédéric', 'fdudouit', '44im8', 1, '23120', 'GrandBourg', '2000-08-01', '18 rue de l église', NULL, NULL, NULL, 3),
(115, 'Duncombe', 'Claude', 'cduncombe', 'qf77j', 1, '23100', 'La souteraine', '1987-10-10', '19 rue de la tour', NULL, NULL, NULL, 3),
(116, 'Enault-Pascreau', 'Céline', 'cenault', 'y2qdu', 1, '23200', 'Gueret', '1995-09-01', '25 place de la gare', NULL, NULL, NULL, 3),
(117, 'Eynde', 'Valérie', 'veynde', 'i7sn3', 1, '13015', 'Marseille', '1999-11-01', '3 Grand Place', NULL, NULL, NULL, 3),
(118, 'Finck', 'Jacques', 'jfinck', 'mpb3t', 1, '13002', 'Marseille', '2001-11-10', '10 avenue du Prado', NULL, NULL, NULL, 3),
(119, 'Frémont', 'Fernande', 'ffremont', 'xs5tq', 1, '13012', 'Allauh', '1998-10-01', '4 route de la mer', NULL, NULL, NULL, 3),
(120, 'Gest', 'Alain', 'agest', 'dywvt', 1, '13025', 'Berre', '1985-11-01', '30 avenue de la mer', NULL, NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `visite`
--

CREATE TABLE `visite` (
  `id` int(11) NOT NULL,
  `rendez_vous` tinyint(1) DEFAULT NULL,
  `heure_arrivee` time DEFAULT NULL,
  `heure_debut_entretien` time DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `date_visite` date DEFAULT NULL,
  `id_medecin` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cabinet`
--
ALTER TABLE `cabinet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `etat`
--
ALTER TABLE `etat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fiche_frais`
--
ALTER TABLE `fiche_frais`
  ADD PRIMARY KEY (`mois`,`id_visiteur`),
  ADD KEY `fiche_frais_ibfk_1` (`id_etat`),
  ADD KEY `fiche_frais_ibfk_2` (`id_visiteur`);

--
-- Indexes for table `frais_forfait`
--
ALTER TABLE `frais_forfait`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ligne_frais_forfait`
--
ALTER TABLE `ligne_frais_forfait`
  ADD PRIMARY KEY (`id`,`mois`,`id_visiteur`),
  ADD KEY `ligne_frais_forfait_ibfk_1` (`id_visiteur`,`mois`),
  ADD KEY `ligne_frais_forfait_ibfk_2` (`id_frais_forfait`);

--
-- Indexes for table `ligne_frais_hors_forfait`
--
ALTER TABLE `ligne_frais_hors_forfait`
  ADD PRIMARY KEY (`id`,`mois`,`id_visiteur`),
  ADD KEY `ligne_frais_hors_forfait_ibfk_1` (`id_visiteur`,`mois`);

--
-- Indexes for table `medecin`
--
ALTER TABLE `medecin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_medecin_id_cabinet` (`id_cabinet`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_utilisateur_id_medecin` (`id_medecin`),
  ADD KEY `FK_utilisateur_id_role` (`id_role`);

--
-- Indexes for table `visite`
--
ALTER TABLE `visite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_visite_id_medecin` (`id_medecin`),
  ADD KEY `FK_visite_id_utilisateur` (`id_utilisateur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cabinet`
--
ALTER TABLE `cabinet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ligne_frais_forfait`
--
ALTER TABLE `ligne_frais_forfait`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `ligne_frais_hors_forfait`
--
ALTER TABLE `ligne_frais_hors_forfait`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `medecin`
--
ALTER TABLE `medecin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT for table `visite`
--
ALTER TABLE `visite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `fiche_frais`
--
ALTER TABLE `fiche_frais`
  ADD CONSTRAINT `fiche_frais_ibfk_1` FOREIGN KEY (`id_etat`) REFERENCES `etat` (`id`),
  ADD CONSTRAINT `fiche_frais_ibfk_2` FOREIGN KEY (`id_visiteur`) REFERENCES `utilisateur` (`id`);

--
-- Constraints for table `ligne_frais_forfait`
--
ALTER TABLE `ligne_frais_forfait`
  ADD CONSTRAINT `ligne_frais_forfait_ibfk_1` FOREIGN KEY (`id_visiteur`,`mois`) REFERENCES `fiche_frais` (`id_visiteur`, `mois`),
  ADD CONSTRAINT `ligne_frais_forfait_ibfk_2` FOREIGN KEY (`id_frais_forfait`) REFERENCES `frais_forfait` (`id`);

--
-- Constraints for table `ligne_frais_hors_forfait`
--
ALTER TABLE `ligne_frais_hors_forfait`
  ADD CONSTRAINT `ligne_frais_hors_forfait_ibfk_1` FOREIGN KEY (`id_visiteur`,`mois`) REFERENCES `fiche_frais` (`id_visiteur`, `mois`);

--
-- Constraints for table `medecin`
--
ALTER TABLE `medecin`
  ADD CONSTRAINT `FK_medecin_id_cabinet` FOREIGN KEY (`id_cabinet`) REFERENCES `cabinet` (`id`);

--
-- Constraints for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_utilisateur_id_medecin` FOREIGN KEY (`id_medecin`) REFERENCES `medecin` (`id`),
  ADD CONSTRAINT `FK_utilisateur_id_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`),
  ADD CONSTRAINT `fk_idrole` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`);

--
-- Constraints for table `visite`
--
ALTER TABLE `visite`
  ADD CONSTRAINT `FK_visite_id_medecin` FOREIGN KEY (`id_medecin`) REFERENCES `medecin` (`id`),
  ADD CONSTRAINT `FK_visite_id_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
