-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.6.21 - MySQL Community Server (GPL)
-- SO del servidor:              Win32
-- HeidiSQL Versión:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando estructura de base de datos para tfgunir
DROP DATABASE IF EXISTS `tfgunir`;
CREATE DATABASE IF NOT EXISTS `tfgunir` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci */;
USE `tfgunir`;


-- Volcando estructura para tabla tfgunir.tfg_analysis
DROP TABLE IF EXISTS `tfg_analysis`;
CREATE TABLE IF NOT EXISTS `tfg_analysis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `sha1` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `md5` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `sha256` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  `filename` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `size` int(11) NOT NULL,
  `muestra` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `pwd_muestra` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '0: EXE, 1: APK',
  `sections` text COLLATE utf8_spanish_ci COMMENT 'EXE',
  `dlls` text COLLATE utf8_spanish_ci COMMENT 'EXE',
  `code` text COLLATE utf8_spanish_ci COMMENT 'EXE',
  `manifest_path` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'APK',
  `permisos` text COLLATE utf8_spanish_ci COMMENT 'APK',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tfgunir.tfg_options
DROP TABLE IF EXISTS `tfg_options`;
CREATE TABLE IF NOT EXISTS `tfg_options` (
  `name` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `value` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  UNIQUE KEY `Índice 1` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tfgunir.tfg_users
DROP TABLE IF EXISTS `tfg_users`;
CREATE TABLE IF NOT EXISTS `tfg_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  `password` varchar(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  `salt` varchar(16) COLLATE utf8_spanish_ci DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Índice 2` (`username`),
  UNIQUE KEY `Índice 3` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- La exportación de datos fue deseleccionada.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
