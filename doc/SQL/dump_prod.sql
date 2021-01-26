-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 26 jan. 2021 à 10:41
-- Version du serveur :  5.7.32-35-log
-- Version de PHP : 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `csunvb_csu`
--

-- --------------------------------------------------------

--
-- Structure de la table `bases`
--

CREATE TABLE `bases` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `bases`
--

INSERT INTO `bases` (`id`, `name`) VALUES
(5, 'La Vallée-de-Joux'),
(4, 'Payerne'),
(3, 'Saint-Loup'),
(2, 'Ste-Croix'),
(1, 'Yverdon');

-- --------------------------------------------------------

--
-- Structure de la table `batches`
--

CREATE TABLE `batches` (
  `id` int(11) NOT NULL,
  `number` varchar(45) NOT NULL,
  `state` varchar(45) NOT NULL DEFAULT 'new',
  `drug_id` int(11) NOT NULL,
  `base_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `batches`
--

INSERT INTO `batches` (`id`, `number`, `state`, `drug_id`, `base_id`) VALUES
(1, '123123', 'used', 1, 2),
(2, '654654', 'new', 1, 2),
(3, '545654', 'new', 1, 2),
(4, '231654', 'inuse', 1, 2),
(5, '879645', 'inuse', 1, 3),
(6, '231288', 'used', 2, 3),
(7, '231355', 'used', 2, 3),
(8, '213546', 'inuse', 2, 4),
(9, '465465', 'new', 2, 4),
(10, '222222', 'new', 2, 2),
(11, '555555', 'used', 3, 2),
(13, '213215', 'inuse', 3, 2),
(14, '789555', 'new', 3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `drugs`
--

CREATE TABLE `drugs` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `drugs`
--

INSERT INTO `drugs` (`id`, `name`) VALUES
(1, 'Fentanyl'),
(2, 'Morphine'),
(3, 'Temesta');

-- --------------------------------------------------------

--
-- Structure de la table `drugsheets`
--

CREATE TABLE `drugsheets` (
  `id` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `base_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `drugsheets`
--

INSERT INTO `drugsheets` (`id`, `week`, `status_id`, `base_id`) VALUES
(23, 2102, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `drugsheet_use_batch`
--

CREATE TABLE `drugsheet_use_batch` (
  `id` int(11) NOT NULL,
  `drugsheet_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `drugsheet_use_batch`
--

INSERT INTO `drugsheet_use_batch` (`id`, `drugsheet_id`, `batch_id`) VALUES
(109, 23, 4),
(110, 23, 8),
(111, 23, 13);

-- --------------------------------------------------------

--
-- Structure de la table `drugsheet_use_nova`
--

CREATE TABLE `drugsheet_use_nova` (
  `id` int(11) NOT NULL,
  `drugsheet_id` int(11) NOT NULL,
  `nova_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `drugsheet_use_nova`
--

INSERT INTO `drugsheet_use_nova` (`id`, `drugsheet_id`, `nova_id`) VALUES
(51, 23, 3),
(52, 23, 5);

-- --------------------------------------------------------

--
-- Structure de la table `drugsignatures`
--

CREATE TABLE `drugsignatures` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `day` int(11) NOT NULL,
  `drugsheet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `novachecks`
--

CREATE TABLE `novachecks` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) DEFAULT NULL,
  `drug_id` int(11) NOT NULL,
  `nova_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `drugsheet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `novachecks`
--

INSERT INTO `novachecks` (`id`, `date`, `start`, `end`, `drug_id`, `nova_id`, `user_id`, `drugsheet_id`) VALUES
(673, '2020-10-26 00:00:00', 6, 6, 1, 3, 101, 23),
(674, '2020-10-26 00:00:00', 6, 6, 1, 5, 102, 23),
(675, '2020-10-26 00:00:00', 6, 6, 2, 3, 102, 23),
(676, '2020-10-26 00:00:00', 6, 6, 2, 5, 103, 23),
(677, '2020-10-26 00:00:00', 6, 6, 3, 3, 104, 23),
(678, '2020-10-26 00:00:00', 5, 5, 3, 5, 105, 23),
(679, '2020-10-27 00:00:00', 6, 6, 1, 3, 106, 23),
(680, '2020-10-27 00:00:00', 6, 6, 1, 5, 107, 23),
(681, '2020-10-27 00:00:00', 6, 6, 2, 3, 107, 23),
(682, '2020-10-27 00:00:00', 6, 5, 2, 5, 106, 23),
(683, '2020-10-27 00:00:00', 6, 6, 3, 3, 105, 23),
(684, '2020-10-27 00:00:00', 6, 6, 3, 5, 104, 23),
(685, '2020-10-28 00:00:00', 4, NULL, 1, 3, 103, 23),
(686, '2020-10-28 00:00:00', 6, NULL, 1, 5, 103, 23),
(687, '2020-10-28 00:00:00', 6, NULL, 2, 3, 113, 23),
(688, '2020-10-28 00:00:00', 6, NULL, 2, 5, 112, 23),
(689, '2020-10-28 00:00:00', 6, NULL, 3, 3, 111, 23),
(690, '2020-10-28 00:00:00', 6, NULL, 3, 5, 112, 23);

-- --------------------------------------------------------

--
-- Structure de la table `novas`
--

CREATE TABLE `novas` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `novas`
--

INSERT INTO `novas` (`id`, `number`) VALUES
(1, 31),
(2, 32),
(3, 33),
(4, 35),
(5, 36),
(11, 43),
(6, 57),
(7, 58),
(8, 75),
(9, 76),
(10, 77);

-- --------------------------------------------------------

--
-- Structure de la table `pharmachecks`
--

CREATE TABLE `pharmachecks` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) DEFAULT NULL,
  `batch_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `drugsheet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pharmachecks`
--

INSERT INTO `pharmachecks` (`id`, `date`, `start`, `end`, `batch_id`, `user_id`, `drugsheet_id`) VALUES
(5150, '2020-10-26 00:00:00', 12, 11, 4, 104, 23),
(5151, '2020-10-26 00:00:00', 8, 8, 8, 108, 23),
(5152, '2020-10-26 00:00:00', 6, 4, 13, 113, 23),
(5153, '2020-10-27 00:00:00', 11, 11, 4, 117, 23),
(5154, '2020-10-27 00:00:00', 8, 7, 8, 108, 23),
(5155, '2020-10-27 00:00:00', 4, 4, 13, 102, 23),
(5156, '2020-10-28 00:00:00', 11, NULL, 4, 107, 23),
(5157, '2020-10-28 00:00:00', 7, NULL, 8, 106, 23),
(5158, '2020-10-28 00:00:00', 4, NULL, 13, 104, 23);

-- --------------------------------------------------------

--
-- Structure de la table `restocks`
--

CREATE TABLE `restocks` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `quantity` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `nova_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `restocks`
--

INSERT INTO `restocks` (`id`, `date`, `quantity`, `batch_id`, `nova_id`, `user_id`) VALUES
(5, '2020-10-26 00:00:00', 1, 4, 5, 102),
(6, '2020-10-26 00:00:00', 2, 13, 3, 113);

-- --------------------------------------------------------

--
-- Structure de la table `shiftactions`
--

CREATE TABLE `shiftactions` (
  `id` int(11) NOT NULL,
  `text` varchar(45) NOT NULL,
  `shiftsection_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shiftactions`
--

INSERT INTO `shiftactions` (`id`, `text`, `shiftsection_id`) VALUES
(1, 'Radios', 1),
(2, 'Détecteurs CO', 1),
(3, 'Téléphones', 1),
(4, 'Gt info avisé', 1),
(5, 'Annonce 144', 1),
(6, 'Plein essence', 2),
(7, 'Opérationnel', 2),
(8, 'Rdv garage', 2),
(9, 'Gt vhc avisé', 2),
(10, '50chf présents', 2),
(11, 'Problèmes d\'interventions hors véhicules', 2),
(12, 'Info trafic consulté', 3),
(13, 'Report des infos trafic sur plan de secteur', 3),
(14, 'Accueil étudiant ou stagiaire', 3),
(15, 'Lecture journal de bord ', 3),
(16, 'Problème et responsable Gt avisé', 3),
(17, 'Centrale propre', 4),
(18, 'Tâches du jour effectuées', 4),
(19, 'Dimanche ', 4);

-- --------------------------------------------------------

--
-- Structure de la table `shiftchecks`
--

CREATE TABLE `shiftchecks` (
  `id` int(11) NOT NULL,
  `day` tinyint(1) NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `shiftsheet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shiftaction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `shiftcomments`
--

CREATE TABLE `shiftcomments` (
  `id` int(11) NOT NULL,
  `message` varchar(200) NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `carryOn` tinyint(1) NOT NULL DEFAULT '0',
  `endOfCarryOn` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `shiftsheet_id` int(11) NOT NULL,
  `shiftaction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `shiftmodels`
--

CREATE TABLE `shiftmodels` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `suggested` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shiftmodels`
--

INSERT INTO `shiftmodels` (`id`, `name`, `suggested`) VALUES
(1, 'Vide', 1),
(2, 'Classic', 1),
(3, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `shiftmodel_has_shiftaction`
--

CREATE TABLE `shiftmodel_has_shiftaction` (
  `id` int(11) NOT NULL,
  `shiftaction_id` int(11) NOT NULL,
  `shiftmodel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shiftmodel_has_shiftaction`
--

INSERT INTO `shiftmodel_has_shiftaction` (`id`, `shiftaction_id`, `shiftmodel_id`) VALUES
(1, 1, 2),
(20, 1, 3),
(2, 2, 2),
(21, 2, 3),
(3, 3, 2),
(22, 3, 3),
(4, 4, 2),
(5, 5, 2),
(6, 6, 2),
(7, 7, 2),
(8, 8, 2),
(9, 9, 2),
(10, 10, 2),
(11, 11, 2),
(12, 12, 2),
(13, 13, 2),
(14, 14, 2),
(15, 15, 2),
(16, 16, 2),
(17, 17, 2),
(18, 18, 2),
(19, 19, 2);

-- --------------------------------------------------------

--
-- Structure de la table `shiftsections`
--

CREATE TABLE `shiftsections` (
  `id` int(11) NOT NULL,
  `title` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shiftsections`
--

INSERT INTO `shiftsections` (`id`, `title`) VALUES
(1, 'Centrale & Tâches'),
(2, 'Ecrans de communication & Trafic'),
(3, 'Matériel & Télécom'),
(4, 'Véhicules & Interventions');

-- --------------------------------------------------------

--
-- Structure de la table `shiftsheets`
--

CREATE TABLE `shiftsheets` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `shiftmodel_id` int(11) NOT NULL,
  `base_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `dayboss_id` int(11) DEFAULT NULL,
  `nightboss_id` int(11) DEFAULT NULL,
  `dayteammate_id` int(11) DEFAULT NULL,
  `nightteammate_id` int(11) DEFAULT NULL,
  `daynova_id` int(11) DEFAULT NULL,
  `nightnova_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shiftsheets`
--

INSERT INTO `shiftsheets` (`id`, `date`, `shiftmodel_id`, `base_id`, `status_id`, `dayboss_id`, `nightboss_id`, `dayteammate_id`, `nightteammate_id`, `daynova_id`, `nightnova_id`) VALUES
(4, '2021-01-04 00:00:00', 2, 3, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '2021-01-05 00:00:00', 2, 2, 3, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `slug` varchar(25) NOT NULL,
  `displayname` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `status`
--

INSERT INTO `status` (`id`, `slug`, `displayname`) VALUES
(1, 'blank', 'En préparation'),
(2, 'open', 'Actif'),
(3, 'close', 'Clôturé'),
(4, 'reopen', 'En correction'),
(5, 'archive', 'Archivé');

-- --------------------------------------------------------

--
-- Structure de la table `todos`
--

CREATE TABLE `todos` (
  `id` int(11) NOT NULL,
  `todothing_id` int(11) NOT NULL,
  `todosheet_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `done_at` datetime DEFAULT NULL,
  `day_of_week` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `todos`
--

INSERT INTO `todos` (`id`, `todothing_id`, `todosheet_id`, `user_id`, `value`, `done_at`, `day_of_week`) VALUES
(91, 34, 23, 102, NULL, NULL, 1),
(92, 22, 23, 110, NULL, NULL, 1),
(93, 28, 23, 107, NULL, NULL, 1),
(94, 23, 23, 101, NULL, NULL, 1),
(95, 21, 23, 102, NULL, NULL, 1),
(96, 36, 23, 108, NULL, NULL, 1),
(97, 31, 23, 114, NULL, NULL, 1),
(98, 35, 23, 104, NULL, NULL, 1),
(99, 38, 23, 100, NULL, NULL, 1),
(100, 24, 23, 114, NULL, NULL, 1),
(101, 29, 23, 109, '12', NULL, 1),
(102, 32, 23, 104, NULL, NULL, 1),
(103, 39, 23, 106, NULL, NULL, 1),
(104, 34, 23, 115, NULL, NULL, 2),
(105, 22, 23, 104, NULL, NULL, 2),
(106, 28, 23, 115, NULL, NULL, 2),
(107, 23, 23, 103, NULL, NULL, 2),
(108, 21, 23, 116, NULL, NULL, 2),
(109, 36, 23, 100, NULL, NULL, 2),
(110, 31, 23, NULL, NULL, NULL, 2),
(111, 35, 23, 109, NULL, NULL, 2),
(112, 38, 23, 116, NULL, NULL, 2),
(113, 24, 23, 103, NULL, NULL, 2),
(114, 29, 23, 1, '32', NULL, 2),
(115, 32, 23, 109, NULL, NULL, 2),
(116, 39, 23, 107, NULL, NULL, 2),
(117, 34, 23, 105, NULL, NULL, 3),
(118, 22, 23, 103, NULL, NULL, 3),
(119, 28, 23, 103, NULL, NULL, 3),
(120, 23, 23, 115, NULL, NULL, 3),
(121, 21, 23, 1, NULL, NULL, 3),
(122, 36, 23, 104, NULL, NULL, 3),
(123, 31, 23, 112, NULL, NULL, 3),
(124, 35, 23, 107, NULL, NULL, 3),
(125, 38, 23, 1, NULL, NULL, 3),
(126, 24, 23, NULL, NULL, NULL, 3),
(127, 29, 23, 105, NULL, NULL, 3),
(128, 32, 23, 112, NULL, NULL, 3),
(129, 39, 23, 101, NULL, NULL, 3),
(130, 34, 23, 101, NULL, NULL, 4),
(131, 22, 23, 114, NULL, NULL, 4),
(132, 28, 23, 107, NULL, NULL, 4),
(133, 23, 23, 1, NULL, NULL, 4),
(134, 21, 23, NULL, NULL, NULL, 4),
(135, 36, 23, 111, NULL, NULL, 4),
(136, 31, 23, 111, NULL, NULL, 4),
(137, 35, 23, 107, NULL, NULL, 4),
(138, 38, 23, 117, NULL, NULL, 4),
(139, 24, 23, 117, NULL, NULL, 4),
(140, 29, 23, 103, NULL, NULL, 4),
(141, 32, 23, 112, NULL, NULL, 4),
(142, 39, 23, 107, NULL, NULL, 4),
(143, 34, 23, 1, NULL, NULL, 5),
(144, 22, 23, 107, NULL, NULL, 5),
(145, 28, 23, 110, NULL, NULL, 5),
(146, 23, 23, 106, NULL, NULL, 5),
(147, 21, 23, 104, NULL, NULL, 5),
(148, 36, 23, 103, NULL, NULL, 5),
(149, 31, 23, 1, NULL, NULL, 5),
(150, 35, 23, NULL, NULL, NULL, 5),
(151, 38, 23, 115, NULL, NULL, 5),
(152, 24, 23, 115, NULL, NULL, 5),
(153, 29, 23, 117, NULL, NULL, 5),
(154, 32, 23, 115, NULL, NULL, 5),
(155, 39, 23, 112, NULL, NULL, 5),
(156, 34, 23, 107, NULL, NULL, 6),
(157, 22, 23, 109, NULL, NULL, 6),
(158, 28, 23, 110, NULL, NULL, 6),
(159, 23, 23, 103, NULL, NULL, 6),
(160, 21, 23, 103, NULL, NULL, 6),
(161, 36, 23, 115, NULL, NULL, 6),
(162, 31, 23, 109, NULL, NULL, 6),
(163, 35, 23, 103, NULL, NULL, 6),
(164, 38, 23, 100, NULL, NULL, 6),
(165, 24, 23, 109, NULL, NULL, 6),
(166, 29, 23, 105, NULL, NULL, 6),
(167, 32, 23, 114, NULL, NULL, 6),
(168, 39, 23, 108, NULL, NULL, 6),
(169, 34, 23, 116, NULL, NULL, 7),
(170, 22, 23, 116, NULL, NULL, 7),
(171, 28, 23, 103, NULL, NULL, 7),
(172, 23, 23, 101, NULL, NULL, 7),
(173, 21, 23, 102, NULL, NULL, 7),
(174, 36, 23, 104, NULL, NULL, 7),
(175, 31, 23, 110, NULL, NULL, 7),
(176, 35, 23, NULL, NULL, NULL, 7),
(177, 38, 23, 117, NULL, NULL, 7),
(178, 24, 23, 117, NULL, NULL, 7),
(179, 29, 23, 109, NULL, NULL, 7),
(181, 39, 23, 115, NULL, NULL, 7),
(182, 30, 23, 107, '12', NULL, 2),
(183, 30, 23, 109, '12', NULL, 3),
(184, 30, 23, 111, NULL, NULL, 4),
(185, 25, 23, NULL, NULL, NULL, 4),
(186, 37, 23, 106, NULL, NULL, 5),
(187, 27, 23, 1, NULL, NULL, 5),
(188, 33, 23, 108, NULL, NULL, 7),
(189, 26, 23, 109, NULL, NULL, 7);

-- --------------------------------------------------------

--
-- Structure de la table `todosheets`
--

CREATE TABLE `todosheets` (
  `id` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `base_id` int(11) NOT NULL,
  `template_name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `todosheets`
--

INSERT INTO `todosheets` (`id`, `week`, `status_id`, `base_id`, `template_name`) VALUES
(23, 2101, 4, 2, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `todothings`
--

CREATE TABLE `todothings` (
  `id` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `daything` tinyint(4) NOT NULL DEFAULT '1',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1: done/not done\\n2: has a value',
  `display_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `todothings`
--

INSERT INTO `todothings` (`id`, `description`, `daything`, `type`, `display_order`) VALUES
(21, 'Changer Bac chariot de nettoyage', 1, 1, 5),
(22, 'Check Ambulance et Communication', 1, 1, 2),
(23, 'Check bibliothèque', 1, 1, 4),
(24, 'Check de nuit ', 0, 1, 21),
(25, 'Commande mat et commande pharma.', 1, 1, 6),
(26, 'Commande O2', 0, 1, 25),
(27, 'Contrôle niveau véhicule', 1, 1, 8),
(28, 'Contrôle stupéfiants + Date perf. Chaudes', 1, 1, 3),
(29, 'Contrôle stupéfiants Nova .... (Morphine X4, Fentanyl X6)', 0, 2, 22),
(30, 'Désinfection + Inventaire hebdo Nova ....', 1, 2, 11),
(31, 'Tâches spécifiques de jour', 1, 1, 13),
(32, 'Tâches spécifiques de nuit', 0, 1, 23),
(33, 'Envoi feuille STUP hebdo à gt pharmacie', 1, 1, 9),
(34, 'Fax 144 Transmission', 1, 1, 1),
(35, 'Formation', 1, 1, 14),
(36, 'Nettoyage centrale et garage', 1, 1, 10),
(37, 'Rangement mat', 1, 1, 7),
(38, 'Remise locaux ambulances ', 1, 1, 15),
(39, 'Remise locaux Transmission', 0, 1, 24),
(40, 'Tâches selon nécessité', 1, 1, 12);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `initials` varchar(45) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` tinyint(4) NOT NULL,
  `firstconnect` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `initials`, `password`, `admin`, `firstconnect`) VALUES
(1, 'Admin', 'istrateur', 'ANN', '$2y$10$QFeM.bl6VdZdGXJaPvSodeCKPRvFJZgmYeU/ZVnYt0p/LbZDNuvhy', 1, 1),
(100, 'Michelle', 'Dutoit', 'MDT', '$2y$10$i0cgyQlhtTl4Gp1eHX1GK.37umWwI9mqWsXHqTQLjFWIyt5e7J6nS', 0, 0),
(101, 'Antonio', 'Casaburi', 'ACI', '$2y$10$NtDXutN9baamLrMugoAdAODxW5ot9.ImKn9NomZNMZocELkymDFvC', 1, 0),
(102, 'Xavier', 'Carrel', 'XCL', '$2y$10$QcssFUbiDCWC.1ggh3UYOukKcN2zqYF/LuraET75yLNMHU1kPNqfa', 1, 0),
(103, 'Thierry', 'Billieux', 'TBX', '$2y$10$KOto6XQdNqRZjoK.yXNNZ.29ycB311mHI.QM3DNJlVyoZRPFgNPbS', 1, 0),
(104, 'Michaël', 'Gogniat', 'MGT', '$2y$10$6JjX6WpKdgRZ44PQj.5C2.9mO2CeAekcKngNmvRh9ttX9mSyO8LGu', 1, 0),
(105, 'Alexandre', 'Ricart', 'ART', '$2y$10$Q3dtk3OvhkJHjBiuLl2ukOnCAEs8r3WcjcQviojttd4v6VKY1AR4i', 1, 0),
(106, 'Vicky', 'Butty', 'VBY', '$2y$10$CK5N2VR7ZP3RyBxjinMgOuykaOKX8ytkNs/LaKIctn9WxrT5yqBHm', 1, 0),
(107, 'Daniel', 'Gamper', 'DGR', '$2y$10$AnCKGXEuBUYu1jWshFMDyu6PHn1SYDyOFKjOQAIjAy3bxa24g83UK', 1, 0),
(108, 'Alexandre', 'Dubrulle', 'ADE', '$2y$10$Ui2vHE1RfzVi4felXpBy2eXe9L0D3/PvKo9U1H3HhoNaaWTGnPqJK', 1, 0),
(109, 'Jeremy', 'Failloubaz', 'JFZ', '$2y$10$9hqBdAUpPVkmJMU3ocr9b.PUQBki4LddZ5tClAlfacu4DEwCV4lfq', 1, 0),
(110, 'Loïc', 'Failloubaz', 'LFZ', '$2y$10$0JMmA/nY7hOAZYH83buGbuQyCEfg3jRZfK7H8daB8bO22gxbP8D6u', 1, 0),
(111, 'Paola', 'Costa', 'PCA', '$2y$10$WOKAmjULG5nQbC/y88NQU.YOK6XHjMKFg.WPrkMs5qvc4DqMl5k4S', 1, 0),
(112, 'Philippe', 'Michel', 'PML', '$2y$10$2VfVqMAibraMuJWzMLSiLeBDXOnM9Lig7uapBb2iToqiFhylytM2O', 0, 0),
(113, 'Laurent', 'Pedroli', 'LPI', '$2y$10$ARMvzj7acmGDIzoBBNRghObQLpSf3FUKm7nN4n8MpranEVlHOq.eq', 0, 0),
(114, 'Damaris', 'Bourgeois', 'DMS', '$2y$10$enagKYdNGrztWs1pHSLB/.QaupoFkHc9hOCa9LoyjwWZpGvlKtYZ6', 0, 1),
(115, 'Laurent', 'Scheurer', 'LSR', '$2y$10$yyM/oFu8x.3Sfqrl4WrJUuVuTHVO/QDWAsm/dvco715c8ph1qk1Om', 0, 0),
(116, 'Galien', 'Wolfer', 'GWR', '$2y$10$wPiLR73utWWTt1DajuAQTuG50lcJFkemE9IvEgez16Ykau0p3L3Ca', 0, 1),
(117, 'Damaris ', 'Bourgeois', 'DBS', '$2y$10$3Cdjk8G095JgQjPqjZP6l.uFrbkF0/SF65UHCRZ/BKwdStrCLOXlK', 0, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bases`
--
ALTER TABLE `bases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Index pour la table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number_UNIQUE` (`number`),
  ADD KEY `fk_batches_drugs_idx` (`drug_id`),
  ADD KEY `fk_batches_bases1_idx` (`base_id`);

--
-- Index pour la table `drugs`
--
ALTER TABLE `drugs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Index pour la table `drugsheets`
--
ALTER TABLE `drugsheets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drugSHEETUNIQ` (`week`,`base_id`),
  ADD KEY `fk_drugsheets_bases1_idx` (`base_id`),
  ADD KEY `fk_drugsheets_status1` (`status_id`);

--
-- Index pour la table `drugsheet_use_batch`
--
ALTER TABLE `drugsheet_use_batch`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_use` (`drugsheet_id`,`batch_id`),
  ADD KEY `fk_drugsheet_use_batch_drugsheets1_idx` (`drugsheet_id`),
  ADD KEY `fk_drugsheet_use_batch_batches1_idx` (`batch_id`);

--
-- Index pour la table `drugsheet_use_nova`
--
ALTER TABLE `drugsheet_use_nova`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_use` (`drugsheet_id`,`nova_id`),
  ADD KEY `fk_drugsheet_use_nova_drugsheets1_idx` (`drugsheet_id`),
  ADD KEY `fk_drugsheet_use_nova_novas1_idx` (`nova_id`);

--
-- Index pour la table `drugsignatures`
--
ALTER TABLE `drugsignatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_drugsignatures_drugsheets1_idx` (`drugsheet_id`),
  ADD KEY `fk_drugsignatures_users1_idx` (`user_id`);

--
-- Index pour la table `novachecks`
--
ALTER TABLE `novachecks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_novachecks_drugs1_idx` (`drug_id`),
  ADD KEY `fk_novachecks_novas1_idx` (`nova_id`),
  ADD KEY `fk_novachecks_users1_idx` (`user_id`),
  ADD KEY `fk_novachecks_drugsheets1_idx` (`drugsheet_id`);

--
-- Index pour la table `novas`
--
ALTER TABLE `novas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number_UNIQUE` (`number`);

--
-- Index pour la table `pharmachecks`
--
ALTER TABLE `pharmachecks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pharmachecks_batches1_idx` (`batch_id`),
  ADD KEY `fk_pharmachecks_users1_idx` (`user_id`),
  ADD KEY `fk_pharmachecks_drugsheets1_idx` (`drugsheet_id`);

--
-- Index pour la table `restocks`
--
ALTER TABLE `restocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_restocks_batches1_idx` (`batch_id`),
  ADD KEY `fk_restocks_novas1_idx` (`nova_id`),
  ADD KEY `fk_restocks_users1_idx` (`user_id`);

--
-- Index pour la table `shiftactions`
--
ALTER TABLE `shiftactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_shift_lines_shift_sections1_idx` (`shiftsection_id`);

--
-- Index pour la table `shiftchecks`
--
ALTER TABLE `shiftchecks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_shiftChecks_shiftSheets1_idx` (`shiftsheet_id`),
  ADD KEY `fk_shiftChecks_users1_idx` (`user_id`),
  ADD KEY `fk_shiftChecks_shiftActions1_idx` (`shiftaction_id`);

--
-- Index pour la table `shiftcomments`
--
ALTER TABLE `shiftcomments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_comments_users1_idx` (`user_id`),
  ADD KEY `fk_comments_shiftSheets1_idx` (`shiftsheet_id`),
  ADD KEY `fk_comments_shiftActions1_idx` (`shiftaction_id`);

--
-- Index pour la table `shiftmodels`
--
ALTER TABLE `shiftmodels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idshiftmodels_UNIQUE` (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Index pour la table `shiftmodel_has_shiftaction`
--
ALTER TABLE `shiftmodel_has_shiftaction`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shiftmodelscol_has_shiftactions_UNIQUE` (`id`),
  ADD UNIQUE KEY `uniqueactionpermodel` (`shiftaction_id`,`shiftmodel_id`),
  ADD KEY `fk_shiftactions_has_shiftmodels_shiftmodels1_idx` (`shiftmodel_id`),
  ADD KEY `fk_shiftactions_has_shiftmodels_shiftactions1_idx` (`shiftaction_id`);

--
-- Index pour la table `shiftsections`
--
ALTER TABLE `shiftsections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title_UNIQUE` (`title`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Index pour la table `shiftsheets`
--
ALTER TABLE `shiftsheets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq` (`base_id`,`date`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_shiftsheets_bases1_idx` (`base_id`),
  ADD KEY `fk_shiftSheets_status1_idx` (`status_id`),
  ADD KEY `fk_shiftSheets_users1_idx` (`dayboss_id`),
  ADD KEY `fk_shiftSheets_users2_idx` (`nightboss_id`),
  ADD KEY `fk_shiftSheets_users3_idx` (`dayteammate_id`),
  ADD KEY `fk_shiftSheets_users4_idx` (`nightteammate_id`),
  ADD KEY `fk_shiftSheets_novas1_idx` (`daynova_id`),
  ADD KEY `fk_shiftSheets_novas2_idx` (`nightnova_id`),
  ADD KEY `fk_shiftsheets_shiftmodels1_idx` (`shiftmodel_id`);

--
-- Index pour la table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Index pour la table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_todoitems_todotexts1_idx` (`todothing_id`),
  ADD KEY `fk_todoitems_todosheets1_idx` (`todosheet_id`),
  ADD KEY `fk_todoitems_users1_idx` (`user_id`);

--
-- Index pour la table `todosheets`
--
ALTER TABLE `todosheets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `model_name_UNIQUE` (`template_name`),
  ADD KEY `fk_todosheets_bases1_idx` (`base_id`),
  ADD KEY `fk_todosheets_status1` (`status_id`);

--
-- Index pour la table `todothings`
--
ALTER TABLE `todothings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `text_UNIQUE` (`description`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `initials_UNIQUE` (`initials`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bases`
--
ALTER TABLE `bases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `batches`
--
ALTER TABLE `batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `drugs`
--
ALTER TABLE `drugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `drugsheets`
--
ALTER TABLE `drugsheets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `drugsheet_use_batch`
--
ALTER TABLE `drugsheet_use_batch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT pour la table `drugsheet_use_nova`
--
ALTER TABLE `drugsheet_use_nova`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pour la table `drugsignatures`
--
ALTER TABLE `drugsignatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `novachecks`
--
ALTER TABLE `novachecks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=691;

--
-- AUTO_INCREMENT pour la table `novas`
--
ALTER TABLE `novas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `pharmachecks`
--
ALTER TABLE `pharmachecks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5159;

--
-- AUTO_INCREMENT pour la table `restocks`
--
ALTER TABLE `restocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `shiftactions`
--
ALTER TABLE `shiftactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `shiftchecks`
--
ALTER TABLE `shiftchecks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `shiftcomments`
--
ALTER TABLE `shiftcomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `shiftmodels`
--
ALTER TABLE `shiftmodels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `shiftmodel_has_shiftaction`
--
ALTER TABLE `shiftmodel_has_shiftaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `shiftsections`
--
ALTER TABLE `shiftsections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `shiftsheets`
--
ALTER TABLE `shiftsheets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `todos`
--
ALTER TABLE `todos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=386;

--
-- AUTO_INCREMENT pour la table `todosheets`
--
ALTER TABLE `todosheets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `todothings`
--
ALTER TABLE `todothings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `batches`
--
ALTER TABLE `batches`
  ADD CONSTRAINT `fk_batches_bases1` FOREIGN KEY (`base_id`) REFERENCES `bases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_batches_drugs` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `drugsheets`
--
ALTER TABLE `drugsheets`
  ADD CONSTRAINT `fk_drugsheets_bases1` FOREIGN KEY (`base_id`) REFERENCES `bases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_drugsheets_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `drugsheet_use_batch`
--
ALTER TABLE `drugsheet_use_batch`
  ADD CONSTRAINT `fk_drugsheet_use_batch_batches1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_drugsheet_use_batch_drugsheets1` FOREIGN KEY (`drugsheet_id`) REFERENCES `drugsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `drugsheet_use_nova`
--
ALTER TABLE `drugsheet_use_nova`
  ADD CONSTRAINT `fk_drugsheet_use_nova_drugsheets1` FOREIGN KEY (`drugsheet_id`) REFERENCES `drugsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_drugsheet_use_nova_novas1` FOREIGN KEY (`nova_id`) REFERENCES `novas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `drugsignatures`
--
ALTER TABLE `drugsignatures`
  ADD CONSTRAINT `fk_drugsignatures_drugsheets1` FOREIGN KEY (`drugsheet_id`) REFERENCES `drugsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_drugsignatures_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `novachecks`
--
ALTER TABLE `novachecks`
  ADD CONSTRAINT `fk_novachecks_drugs1` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_novachecks_drugsheets1` FOREIGN KEY (`drugsheet_id`) REFERENCES `drugsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_novachecks_novas1` FOREIGN KEY (`nova_id`) REFERENCES `novas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_novachecks_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `pharmachecks`
--
ALTER TABLE `pharmachecks`
  ADD CONSTRAINT `fk_pharmachecks_batches1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pharmachecks_drugsheets1` FOREIGN KEY (`drugsheet_id`) REFERENCES `drugsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pharmachecks_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `restocks`
--
ALTER TABLE `restocks`
  ADD CONSTRAINT `fk_restocks_batches1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_restocks_novas1` FOREIGN KEY (`nova_id`) REFERENCES `novas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_restocks_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `shiftactions`
--
ALTER TABLE `shiftactions`
  ADD CONSTRAINT `fk_shift_lines_shift_sections1` FOREIGN KEY (`shiftsection_id`) REFERENCES `shiftsections` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `shiftchecks`
--
ALTER TABLE `shiftchecks`
  ADD CONSTRAINT `fk_shiftChecks_shiftActions1` FOREIGN KEY (`shiftaction_id`) REFERENCES `shiftactions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftChecks_shiftSheets1` FOREIGN KEY (`shiftsheet_id`) REFERENCES `shiftsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftChecks_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `shiftcomments`
--
ALTER TABLE `shiftcomments`
  ADD CONSTRAINT `fk_comments_shiftActions1` FOREIGN KEY (`shiftaction_id`) REFERENCES `shiftactions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comments_shiftSheets1` FOREIGN KEY (`shiftsheet_id`) REFERENCES `shiftsheets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comments_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `shiftmodel_has_shiftaction`
--
ALTER TABLE `shiftmodel_has_shiftaction`
  ADD CONSTRAINT `fk_shiftactions_has_shiftmodels_shiftactions1` FOREIGN KEY (`shiftaction_id`) REFERENCES `shiftactions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftactions_has_shiftmodels_shiftmodels1` FOREIGN KEY (`shiftmodel_id`) REFERENCES `shiftmodels` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `shiftsheets`
--
ALTER TABLE `shiftsheets`
  ADD CONSTRAINT `fk_shiftSheets_novas1` FOREIGN KEY (`daynova_id`) REFERENCES `novas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftSheets_novas2` FOREIGN KEY (`nightnova_id`) REFERENCES `novas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftSheets_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftSheets_users1` FOREIGN KEY (`dayboss_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftSheets_users2` FOREIGN KEY (`nightboss_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftSheets_users3` FOREIGN KEY (`dayteammate_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftSheets_users4` FOREIGN KEY (`nightteammate_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftsheets_bases1` FOREIGN KEY (`base_id`) REFERENCES `bases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shiftsheets_shiftmodels1` FOREIGN KEY (`shiftmodel_id`) REFERENCES `shiftmodels` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `todos`
--
ALTER TABLE `todos`
  ADD CONSTRAINT `fk_todoitems_todosheets1` FOREIGN KEY (`todosheet_id`) REFERENCES `todosheets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_todoitems_todotexts1` FOREIGN KEY (`todothing_id`) REFERENCES `todothings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_todoitems_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `todosheets`
--
ALTER TABLE `todosheets`
  ADD CONSTRAINT `fk_todosheets_bases1` FOREIGN KEY (`base_id`) REFERENCES `bases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_todosheets_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
