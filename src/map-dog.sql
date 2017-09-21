-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 10 Septembre 2017 à 20:25
-- Version du serveur :  10.1.16-MariaDB
-- Version de PHP :  7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `map-dog`
--

-- --------------------------------------------------------

--
-- Structure de la table `dog`
--

CREATE TABLE `dog` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(32) NOT NULL,
  `avatar` char(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Structure de la table `map`
--

CREATE TABLE `map` (
  `id` int(10) UNSIGNED NOT NULL,
  `lat` float NOT NULL DEFAULT '0',
  `lng` float NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `marker`
--

CREATE TABLE `marker` (
  `id` int(10) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `totem` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `totem`
--

CREATE TABLE `totem` (
  `id` int(10) NOT NULL,
  `name` char(64) NOT NULL,
  `description` varchar(128) NOT NULL,
  `dog` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `dog`
--
ALTER TABLE `dog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `map`
--
ALTER TABLE `map`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `marker`
--
ALTER TABLE `marker`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `totem` (`totem`),
  ADD UNIQUE KEY `lat` (`lat`,`lng`);

--
-- Index pour la table `totem`
--
ALTER TABLE `totem`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `dog` (`dog`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `dog`
--
ALTER TABLE `dog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;
--
-- AUTO_INCREMENT pour la table `marker`
--
ALTER TABLE `marker`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `totem`
--
ALTER TABLE `totem`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `map`
--
ALTER TABLE `map`
  ADD CONSTRAINT `map_ibfk_1` FOREIGN KEY (`id`) REFERENCES `dog` (`id`);

--
-- Contraintes pour la table `marker`
--
ALTER TABLE `marker`
  ADD CONSTRAINT `marker_ibfk_1` FOREIGN KEY (`totem`) REFERENCES `totem` (`id`);

--
-- Contraintes pour la table `totem`
--
ALTER TABLE `totem`
  ADD CONSTRAINT `totem_ibfk_1` FOREIGN KEY (`dog`) REFERENCES `dog` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
