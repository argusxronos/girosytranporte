/*
SQLyog Ultimate v9.33 GA
MySQL - 5.0.51a : Database - bdtransportenuevo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`bdtransportenuevo` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `bdtransportenuevo`;

/*Table structure for table `pasaje_pagado` */

DROP TABLE IF EXISTS `pasaje_pagado`;

CREATE TABLE `pasaje_pagado` (
  `id_pasaje_pagado` int(11) NOT NULL auto_increment,
  `id_record` int(11) default NULL,
  `cliente` varchar(60) default NULL,
  `fecha_creacion` date default NULL,
  `hora_creacion` time default NULL,
  `usuario_crea` varchar(50) default NULL,
  `agencia_crea` varchar(50) default NULL,
  `detalle` varchar(150) default NULL,
  `monto` float default NULL,
  `nro_guia_interna` int(11) default NULL,
  `fecha_viaje` date default NULL,
  `hora_viaje` time default NULL,
  `origen_agencia` varchar(50) default NULL,
  `destino_agencia` varchar(50) default NULL,
  `nro_pasaje` varchar(50) default NULL,
  PRIMARY KEY  (`id_pasaje_pagado`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
