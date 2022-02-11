/*
SQLyog Ultimate v9.33 GA
MySQL - 5.0.51a 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `vales` (
	`id_vale` int (11),
	`motivo` varchar (750),
	`monto` float ,
	`empleado` varchar (150),
	`fecha_crea` date ,
	`hora_crea` time ,
	`u_crea` varchar (150),
	`agencia` varchar (150)
); 
insert into `vales` (`id_vale`, `motivo`, `monto`, `empleado`, `fecha_crea`, `hora_crea`, `u_crea`, `agencia`) values('1','PASAJES Y PASEO','32','RICHARD MELGAR H.','2012-08-31','10:38:08','RICARDO MATOS','HUANCAYO-PRINCIPAL');
insert into `vales` (`id_vale`, `motivo`, `monto`, `empleado`, `fecha_crea`, `hora_crea`, `u_crea`, `agencia`) values('2','pasajes','5','richard','2012-08-31','11:50:29','RICARDO MATOS','HUANCAYO-PRINCIPAL');
insert into `vales` (`id_vale`, `motivo`, `monto`, `empleado`, `fecha_crea`, `hora_crea`, `u_crea`, `agencia`) values('3','PASAJES Y PASEO','6','JUANITO','2012-08-31','11:52:19','RICARDO MATOS','HUANCAYO-PRINCIPAL');
insert into `vales` (`id_vale`, `motivo`, `monto`, `empleado`, `fecha_crea`, `hora_crea`, `u_crea`, `agencia`) values('4','PASAJES Y PASEOS ','35','JUANITO','2012-08-31','11:56:26','RICARDO MATOS','HUANCAYO-PRINCIPAL');
insert into `vales` (`id_vale`, `motivo`, `monto`, `empleado`, `fecha_crea`, `hora_crea`, `u_crea`, `agencia`) values('5','PASAJES PARA RECOGER IMPRESORAS A BRAIN SERVICES Y BIATICOS PARA ALMUERZO\r\n\r\n','123.23','RICHARD MELGAR H.','2012-08-31','12:43:39','RICARDO MATOS','HUANCAYO-PRINCIPAL');
insert into `vales` (`id_vale`, `motivo`, `monto`, `empleado`, `fecha_crea`, `hora_crea`, `u_crea`, `agencia`) values('6','GFJHFH','23','GJHFHF','2012-08-31','12:54:45','RICARDO MATOS','HUANCAYO-PRINCIPAL');
