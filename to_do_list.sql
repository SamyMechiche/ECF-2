-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 22 jan. 2025 à 07:05
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `to_do_list`
--

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id_task` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `due_date` date NOT NULL,
  `completed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_task`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `task`
--

INSERT INTO `task` (`id_task`, `name`, `description`, `due_date`, `completed`) VALUES
(6, 'visio', 'visio avec Cédric le goat', '2025-01-21', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  `signing_date` datetime NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `name`, `email`, `password`, `signing_date`) VALUES
(1, 'samy', 'mechiche.samysm@gmail.com', '$2y$10$SL4YO/48eHlVh2Bad53y3OGkQpXvQ4jFQ9BZY4gjrcuJl54Ds8rjC', '2025-01-21 08:30:45'),
(2, 'test', 'test@mail.com', '$2y$10$cxbfa66edo4jzRa7hU.kW.7rzrqtpihU.Sxd640ZrgnD3suefjLnK', '2025-01-21 11:15:53'),
(3, 'thomas', 'thomasr1276@gmail.com', '$2y$10$45EkxasfBxieJuQgWBcAKOIl2en04NsJ5UxLr4JYZlxYvqkIqAvD6', '2025-01-21 11:16:14');

-- --------------------------------------------------------

--
-- Structure de la table `user_task`
--

DROP TABLE IF EXISTS `user_task`;
CREATE TABLE IF NOT EXISTS `user_task` (
  `id_user` int NOT NULL,
  `id_task` int NOT NULL,
  KEY `id_user` (`id_user`),
  KEY `id_task` (`id_task`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user_task`
--
ALTER TABLE `user_task`
  ADD CONSTRAINT `user_task_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_task_ibfk_2` FOREIGN KEY (`id_task`) REFERENCES `task` (`id_task`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
