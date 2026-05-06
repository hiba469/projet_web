-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 05, 2026 at 07:41 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bijouterie`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`) VALUES
(1, 'Bagues'),
(2, 'Colliers'),
(3, 'Bracelets'),
(4, 'Boucles d\'oreilles');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `adresse` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`) VALUES
(1, 'Ben Salah', 'Mohammed', 'Mohamed@gmail.com', '123', '123456789', 'aaabbbccc'),
(3, 'Ben Ammar', 'Syrine', 'Syrine@gmail.com', '456', '12123123', 'aaabbbcccddd');

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int NOT NULL AUTO_INCREMENT,
  `id_client` int NOT NULL,
  `date` date NOT NULL,
  `montant` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_client` (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ligne_commande`
--

DROP TABLE IF EXISTS `ligne_commande`;
CREATE TABLE IF NOT EXISTS `ligne_commande` (
  `id_ligne` int NOT NULL AUTO_INCREMENT,
  `id_commande` int NOT NULL,
  `id_produit` int NOT NULL,
  PRIMARY KEY (`id_ligne`),
  KEY `id_produit` (`id_produit`),
  KEY `id_commande` (`id_commande`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `description` varchar(20) NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `categorie` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categorie` (`categorie`),
  KEY `categorie_2` (`categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
-- --------------------------------------------------------
--
-- Table structure for table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id_panier` int NOT NULL AUTO_INCREMENT,
  `id_client` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_panier`),
  KEY `id_client` (`id_client`),
  KEY `id_produit` (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `description`, `prix`, `image`, `categorie`) VALUES
(1, 'Bague Aurore', 'Or blanc 18 carats e', 1, 'https://www.marc-orian.com/dw/image/v2/BCQS_PRD/on/demandware.static/-/Sites-THOM_CATALOG/default/dw', 1),
(2, 'Alliance Éclat', 'Anneau en or jaune b', 950, 'https://img.edenly.com/pt/100/atelier-20492-alliance-4-mm-or-jaune-brosse-18-carats-r__20492_1.webp', 1),
(3, 'Solitaire Éternité', 'Diamant pur sur mont', 4500, 'https://images.unsplash.com/photo-1768423685978-42fe24ec39a0?q=80&w=880&auto=format&fit=crop&ixlib=r', 1),
(4, 'Collier Goutte', 'Pendentif cristal et', 350, 'https://storage.gra.cloud.ovh.net/v1/AUTH_ca60d77ddd5e42749d7c4c17f753540d/ugm-data/pictures/bijouca', 2),
(5, 'Sautoir Majesté', 'Perles de culture et', 200, 'https://images.bibelotandco.fr/uploads/images/collier-fausse-perles-de-culture-en-chute-fermoir-meta', 2),
(6, 'Pendentif Cœur', 'Médaille gravée en o', 750, 'https://www.aismee.fr/bibliotheque/775/medaillon-coeur-photo-or-detail-780-1.jpg', 2),
(7, 'Jonc Infini ', 'Bracelet rigide en o', 850, 'https://asset.swarovski.com/images/$size_1450/t_swa103/b_rgb:ffffff,c_scale,dpr_1.0,f_auto,w_375/567', 3),
(8, 'Manchette Royale', 'Argent ciselé', 200, 'https://images.unsplash.com/photo-1632816307542-6a707d8a1c3c?q=80&w=996&auto=format&fit=crop&ixlib=r', 3),
(9, 'Gourmette Chic', 'Maillons larges en o', 1000, 'https://castafiore.fr/cdn/shop/products/bracelet-gourmette-grosse-maille-en-or-jaune-665974.jpg?v=16', 3),
(10, 'Pendantes Rubis', 'Pierres précieuses s', 2, 'https://img.joomcdn.net/f1240c9214ac8c07e7ddeb367ee08e95db39915c_original.jpeg', 4),
(11, 'Créoles Soleil', 'Grandes boucles doré', 200, 'https://www.delamode.fr/cdn/shop/files/boucles-d-oreilles-double-soleil-dore-martelee-5-5cm.jpg?v=17', 4),
(12, 'Puces Étoiles', 'Petits diamants disc', 600, 'https://www.hauthentic.com/wp-content/uploads/boucle-d-oreille-diamant-0.70-carat-platine-950-H0273E', 2),
(13, 'Khomsa Filigrane', 'En fils d\'argent fin', 180, 'https://lemondedivine.com/1905-large_default/collier-rayhane-main-de-fatma-en-argent-925.jpg', 2),
(14, 'Deux colliers fins', 'Or et perles ', 500, 'https://plus.unsplash.com/premium_photo-1681276169450-4504a2442173?q=80&w=400&auto=format&fit=crop', 2),
(15, 'Collier Argent', 'Collier croissant mo', 98, 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&q=80&w=400', 2),
(16, 'Bracelet Rihana', 'Perles blancs ', 40, 'https://plus.unsplash.com/premium_photo-1681276168324-a6f1958aa191?q=80&w=400&auto=format&fit=crop', 3),
(19, 'Boucles Perles Bleu ', 'Boucles d\'oreilles d', 120, 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&q=80&w=400', 4);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD CONSTRAINT `ligne_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ligne_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`categorie`) REFERENCES `categorie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
