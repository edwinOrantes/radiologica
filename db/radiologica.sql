-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.28-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para db_clinica_radiologica
CREATE DATABASE IF NOT EXISTS `db_clinica_radiologica` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */;
USE `db_clinica_radiologica`;

-- Volcando estructura para tabla db_clinica_radiologica.tbl_accesos
CREATE TABLE IF NOT EXISTS `tbl_accesos` (
  `idAcceso` int(11) NOT NULL AUTO_INCREMENT,
  `nombreAcceso` varchar(50) NOT NULL,
  `descripcionAcceso` text NOT NULL,
  `estadoAcceso` int(11) NOT NULL,
  `fechaAcceso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idAcceso`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_accesos: ~0 rows (aproximadamente)
INSERT INTO `tbl_accesos` (`idAcceso`, `nombreAcceso`, `descripcionAcceso`, `estadoAcceso`, `fechaAcceso`) VALUES
	(1, 'Administrador', 'Acceso total al sistema.', 1, '2021-04-29 15:43:54');

-- Volcando estructura para tabla db_clinica_radiologica.tbl_cajas
CREATE TABLE IF NOT EXISTS `tbl_cajas` (
  `idCaja` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `nombreCaja` text NOT NULL,
  `numeroCaja` int(11) NOT NULL,
  `codigoCaja` text NOT NULL,
  `tipoEstablecimiento` varchar(4) NOT NULL,
  `estadoCaja` int(11) NOT NULL DEFAULT 1,
  `creadaCaja` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idCaja`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_cajas: ~7 rows (aproximadamente)
INSERT INTO `tbl_cajas` (`idCaja`, `idUsuario`, `nombreCaja`, `numeroCaja`, `codigoCaja`, `tipoEstablecimiento`, `estadoCaja`, `creadaCaja`) VALUES
	(1, 8, 'Hospital Orellana', 1, 'HO', 'M001', 1, '2024-12-06 21:30:00'),
	(2, 1, 'Unidad Hemodialisis', 2, 'UH', 'M001', 1, '2024-12-12 16:35:24'),
	(3, 24, 'Hoospital Orellana', 1, 'HO', 'M001', 1, '2025-03-31 17:09:58'),
	(4, 25, 'Hoospital Orellana', 1, 'HO', 'M001', 1, '2025-03-31 17:09:58'),
	(5, 23, 'Hoospital Orellana', 1, 'HO', 'M001', 1, '2025-03-31 17:09:58'),
	(6, 5, 'Hoospital Orellana', 1, 'HO', 'M001', 1, '2025-03-31 17:09:58'),
	(7, 13, 'Hoospital Orellana', 1, 'HO', 'M001', 1, '2025-03-31 17:09:58');

-- Volcando estructura para tabla db_clinica_radiologica.tbl_consulta
CREATE TABLE IF NOT EXISTS `tbl_consulta` (
  `idConsulta` int(11) NOT NULL AUTO_INCREMENT,
  `nombrePaciente` text NOT NULL,
  `idMedico` int(11) NOT NULL,
  `tipoReferencia` text NOT NULL,
  `fechaConsulta` int(11) NOT NULL,
  `creado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idConsulta`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_consulta: ~0 rows (aproximadamente)
INSERT INTO `tbl_consulta` (`idConsulta`, `nombrePaciente`, `idMedico`, `tipoReferencia`, `fechaConsulta`, `creado`) VALUES
	(1, 'Josselyn Sorto', 2, 'Privada', 2147483647, '2025-05-02 20:38:35');

-- Volcando estructura para tabla db_clinica_radiologica.tbl_consumidor_final
CREATE TABLE IF NOT EXISTS `tbl_consumidor_final` (
  `idConsumidorFinal` int(11) NOT NULL AUTO_INCREMENT,
  `idVenta` int(11) NOT NULL,
  `numeroConsumidorFinal` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `corteZ` int(11) NOT NULL DEFAULT 0,
  `corteGranZ` int(11) NOT NULL DEFAULT 0,
  `creadoConsumidorFinal` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idConsumidorFinal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_consumidor_final: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_clinica_radiologica.tbl_credito_fiscal
CREATE TABLE IF NOT EXISTS `tbl_credito_fiscal` (
  `idCreditoFiscal` int(11) NOT NULL AUTO_INCREMENT,
  `idVenta` int(11) NOT NULL,
  `numeroCreditoFiscal` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `corteZ` int(11) NOT NULL DEFAULT 0,
  `corteGranZ` int(11) NOT NULL DEFAULT 0,
  `creadoCreditoFiscal` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idCreditoFiscal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_credito_fiscal: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_clinica_radiologica.tbl_detalle_consulta
CREATE TABLE IF NOT EXISTS `tbl_detalle_consulta` (
  `idDetalle` int(11) NOT NULL AUTO_INCREMENT,
  `idConsulta` int(11) NOT NULL,
  `idExamen` int(11) NOT NULL,
  `cantidadExamen` int(11) NOT NULL,
  `precioExamen` decimal(9,2) NOT NULL,
  `estadoDetalle` int(11) NOT NULL DEFAULT 1,
  `creado` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idDetalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_detalle_consulta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_clinica_radiologica.tbl_dte_ccf
CREATE TABLE IF NOT EXISTS `tbl_dte_ccf` (
  `idDTEFC` int(11) NOT NULL AUTO_INCREMENT,
  `numeroDTE` int(11) NOT NULL,
  `anioDTE` int(11) NOT NULL,
  `estadoDTE` int(11) NOT NULL DEFAULT 1,
  `detalleDTE` text NOT NULL,
  `codigoGeneracion` text NOT NULL,
  `respuestaHacienda` text NOT NULL,
  `datosLocales` text NOT NULL,
  `idHoja` int(11) NOT NULL DEFAULT 0,
  `jsonDTE` text NOT NULL,
  `padreDTE` int(11) NOT NULL DEFAULT 0,
  `notaCredito` int(11) NOT NULL DEFAULT 0,
  `notaDebito` int(11) NOT NULL DEFAULT 0,
  `enContingencia` int(11) NOT NULL DEFAULT 0,
  `firma` text NOT NULL,
  `creado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idDTEFC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_dte_ccf: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_clinica_radiologica.tbl_dte_fc
CREATE TABLE IF NOT EXISTS `tbl_dte_fc` (
  `idDTEFC` int(11) NOT NULL AUTO_INCREMENT,
  `numeroDTE` int(11) NOT NULL,
  `anioDTE` int(11) NOT NULL,
  `estadoDTE` int(11) NOT NULL DEFAULT 1,
  `detalleDTE` text NOT NULL,
  `codigoGeneracion` text NOT NULL,
  `respuestaHacienda` text NOT NULL,
  `datosLocales` text NOT NULL,
  `idHoja` int(11) NOT NULL DEFAULT 0,
  `jsonDTE` text NOT NULL,
  `padreDTE` int(11) NOT NULL DEFAULT 0,
  `enContingencia` int(11) NOT NULL DEFAULT 0,
  `firma` text NOT NULL,
  `creado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idDTEFC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_dte_fc: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_clinica_radiologica.tbl_dte_nc
CREATE TABLE IF NOT EXISTS `tbl_dte_nc` (
  `idDTEFC` int(11) NOT NULL AUTO_INCREMENT,
  `numeroDTE` int(11) NOT NULL,
  `anioDTE` int(11) NOT NULL,
  `estadoDTE` int(11) NOT NULL DEFAULT 1,
  `detalleDTE` text NOT NULL,
  `codigoGeneracion` text NOT NULL,
  `respuestaHacienda` text NOT NULL,
  `datosLocales` text NOT NULL,
  `idHoja` int(11) NOT NULL DEFAULT 0,
  `jsonDTE` text NOT NULL,
  `padreDTE` int(11) NOT NULL DEFAULT 0,
  `creado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idDTEFC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_dte_nc: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_clinica_radiologica.tbl_dte_nd
CREATE TABLE IF NOT EXISTS `tbl_dte_nd` (
  `idDTEFC` int(11) NOT NULL AUTO_INCREMENT,
  `numeroDTE` int(11) NOT NULL,
  `anioDTE` int(11) NOT NULL,
  `estadoDTE` int(11) NOT NULL DEFAULT 1,
  `detalleDTE` text NOT NULL,
  `codigoGeneracion` text NOT NULL,
  `respuestaHacienda` text NOT NULL,
  `datosLocales` text NOT NULL,
  `idHoja` int(11) NOT NULL DEFAULT 0,
  `jsonDTE` text NOT NULL,
  `padreDTE` int(11) NOT NULL DEFAULT 0,
  `creado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idDTEFC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_dte_nd: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_clinica_radiologica.tbl_dte_se
CREATE TABLE IF NOT EXISTS `tbl_dte_se` (
  `idDTEFC` int(11) NOT NULL AUTO_INCREMENT,
  `numeroDTE` int(11) NOT NULL,
  `anioDTE` int(11) NOT NULL,
  `estadoDTE` int(11) NOT NULL DEFAULT 1,
  `detalleDTE` text NOT NULL,
  `codigoGeneracion` text NOT NULL,
  `respuestaHacienda` text NOT NULL,
  `datosLocales` text NOT NULL,
  `idHoja` int(11) NOT NULL DEFAULT 0,
  `jsonDTE` text NOT NULL,
  `padreDTE` text NOT NULL DEFAULT '0',
  `enContingencia` int(11) NOT NULL DEFAULT 0,
  `firma` text NOT NULL,
  `creado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idDTEFC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_dte_se: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_clinica_radiologica.tbl_empleados
CREATE TABLE IF NOT EXISTS `tbl_empleados` (
  `idEmpleado` int(11) NOT NULL AUTO_INCREMENT,
  `nombreEmpleado` varchar(50) NOT NULL,
  `apellidoEmpleado` varchar(50) NOT NULL,
  `edadEmpleado` int(11) NOT NULL,
  `telefonoEmpleado` varchar(10) NOT NULL,
  `cargoEmpleado` int(11) NOT NULL,
  `sexoEmpleado` varchar(10) NOT NULL,
  `duiEmpleado` varchar(10) NOT NULL,
  `nitEmpleado` varchar(25) NOT NULL,
  `estadoEmpleado` varchar(15) NOT NULL,
  `nacimientoEmpleado` date NOT NULL,
  `departamentoEmpleado` int(11) NOT NULL,
  `municipioEmpleado` int(11) NOT NULL,
  `direccionEmpleado` text NOT NULL,
  `activo` int(11) NOT NULL DEFAULT 1,
  `ingresoEmpleado` date NOT NULL,
  PRIMARY KEY (`idEmpleado`),
  KEY `departamentoEmpleado` (`departamentoEmpleado`),
  KEY `municipioEmpleado` (`municipioEmpleado`),
  KEY `cargoEmpleado` (`cargoEmpleado`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_empleados: ~32 rows (aproximadamente)
INSERT INTO `tbl_empleados` (`idEmpleado`, `nombreEmpleado`, `apellidoEmpleado`, `edadEmpleado`, `telefonoEmpleado`, `cargoEmpleado`, `sexoEmpleado`, `duiEmpleado`, `nitEmpleado`, `estadoEmpleado`, `nacimientoEmpleado`, `departamentoEmpleado`, `municipioEmpleado`, `direccionEmpleado`, `activo`, `ingresoEmpleado`) VALUES
	(1, 'Edwin Alexander', 'Cortez Orantes', 29, '6310-0397 ', 1, 'Masculino', '00000000-0', '0000-000000-000-0', 'Casado/a', '1992-01-03', 11, 42, 'Usulután', 1, '2021-01-04'),
	(3, 'Carla Isolina', 'Ortez González', 27, '7537-3424 ', 9, 'Femenino', '00000000-0', '0000-000000-000-0', 'Soltero/a', '1994-08-06', 11, 64, 'Usulután', 1, '2020-07-01'),
	(4, 'Carla Marisa ', 'Maradiaga de Paredes', 21, '7193-3931', 9, 'Femenino', '00000000-0', '0000-000000-000-0', 'Casado/a', '1992-09-22', 11, 64, 'Usulután', 1, '2020-07-01'),
	(5, 'Catalina de Jesús', 'Gómez Vázquez', 42, '7492-2204 ', 9, 'Femenino', '00566108-9', '0000-000000-000-0', 'Soltero/a', '1978-11-22', 11, 54, 'Canton el Palmital, Ozatlan, Usulután', 1, '2016-08-08'),
	(6, 'Elena Abigail', 'Cruz Rodríguez', 29, '7927-8472', 10, 'Femenino', '00000000-0', '0000-000000-000-0', 'Casado/a', '1991-11-07', 11, 64, 'Usulután', 1, '2016-08-22'),
	(7, 'Jacquelinne Vanessa', 'Trejo de Zepeda', 35, '7870-0107', 11, 'Femenino', '00000000-0', '0000-000000-000-0', 'Casado/a', '1983-05-24', 11, 64, 'Usulután', 1, '2020-07-01'),
	(8, 'Alba Rosa ', 'Ramirez Vasquez', 35, '7043-7030', 15, 'Femenino', '03864088-4', '0000-000000-000-0', 'Soltero/a', '1985-10-14', 11, 64, 'Colonia Las Colinas, Casa ·6', 1, '2014-06-01'),
	(9, 'Wendy del Carmen', 'Serpas Funes eliminar', 27, '7244-5673', 11, 'Femenino', '05071660-1', '0000-000000-000-0', 'Soltero/a', '1994-03-20', 11, 54, 'Colonia El Milagro, Lote 8, Poligono 10, La Poza', 0, '2021-06-15'),
	(10, 'Claudia Patricia ', 'Garcia', 28, '7323-4541', 11, 'Femenino', '04481254-6', '0000-000000-000-0', 'Casado/a', '1991-07-09', 11, 64, 'Colonia Saravia', 0, '2020-09-21'),
	(11, 'Carlos Miguel', 'Martinez Mejia', 34, '7747-1360', 6, 'Masculino', '03731973-9', '1123-110587-101-9', 'Soltero/a', '1987-05-11', 11, 64, 'Colonia Espiritu Santo Calle Principal Casa # 40', 0, '2020-09-03'),
	(12, 'Laboratorio', 'Orellana', 25, '0000-0000', 12, 'Masculino', '00000000-0', '0000-000000-000-0', 'Soltero/a', '1996-07-01', 11, 64, 'Usulutan', 1, '2021-01-01'),
	(13, 'Usuario', 'Invitado', 28, '0000-0000', 8, 'Masculino', '00000000-0', '0000-000000-000-0', 'Soltero/a', '1992-08-26', 13, 81, 'Barrio El Calvario, Usulutan', 1, '2020-11-23'),
	(14, 'Kriscia Iveth', 'Segovia Flores', 19, '7793-5538', 10, 'Femenino', '00000000-0', '0000-000000-000-0', 'Soltero/a', '2002-01-25', 11, 60, 'Canton Joya Ancha Arriba', 1, '2021-09-22'),
	(15, 'Luis Fernando', 'Varaona Rodriguez eliminar', 24, '7922-1812', 14, 'Masculino', '05520616-4', '0000-000000-000-0', 'Soltero/a', '1997-03-23', 4, 129, '-', 0, '2021-03-26'),
	(16, 'Carla Ivette', 'López Muñoz', 21, '7007-5534', 15, 'Femenino', '06159979-5', '1123-210101-104-6', 'Soltero/a', '2001-01-21', 11, 64, 'Cantón Ojo de Agua, Caserio San Jaime, Usulután, Usulután', 1, '2022-01-11'),
	(17, 'Mirna Veronica', 'Ramirez Vasquez', 37, '7955-3701', 16, 'Femenino', '02459891-0', '0000-000000-000-0', 'Soltero/a', '1984-04-10', 11, 64, 'Colonia Las Colinas', 1, '2013-06-01'),
	(18, 'Rayos', 'X eliminar', 50, '0000-0000', 13, 'Femenino', '00000000-0', '0000-000000-000-0', 'Soltero/a', '1972-02-19', 11, 64, '6a Calle Ote #8, Usulután, El Salvador', 1, '2000-01-01'),
	(19, 'Allison Esmeralda', 'Quintanilla Martinez', 28, '7858-1702', 15, 'Femenino', '04921897-5', '1108-011293-102-9', 'Soltero/a', '1993-12-01', 11, 64, 'Residencial Las Veraneras, Poligono W, Casa 4', 0, '2022-06-24'),
	(20, 'Silvia Leonor', 'Campos de Granados', 28, '7960-8566', 15, 'Femenino', '04950644-1', '1123-071293-102-4', 'Soltero/a', '1993-12-07', 11, 64, 'Entrada a Residencial San José', 0, '2022-06-29'),
	(21, 'Veronica Guadalupe', ' Mira', 25, '7018-9778', 8, 'Femenino', '01678161-4', '0000-000000-000-0', 'Casado/a', '1973-08-01', 11, 64, '5a. Calle Ote. Col. Stand. Rosa #37 Usulutan ', 1, '2022-07-01'),
	(22, 'Jose Angel ', 'Chavez Peralta', 26, '7031-9189', 1, 'Masculino', '05174051-4', '0000-000000-000-0', 'Soltero/a', '1997-02-01', 11, 61, 'Lot. Pueblo Nuevo KM112 Salida a San Miguel Usulutan', 1, '2023-02-01'),
	(23, 'Hemodialisis', ' ', 0, '0000-0000', 16, 'Masculino', '00000000-0', '0000-000000-000-0', 'Soltero/a', '2000-01-01', 11, 64, 'Usulutan', 1, '0001-01-01'),
	(24, 'Fatima Azucena', 'Mariona Turcios', 32, '7810-2106', 6, 'Femenino', '04472404-4', '0614-130791-110-2', 'Soltero/a', '1991-07-13', 11, 64, 'Col Santa Clara Av Paz y Cl Guardado #10', 1, '2023-11-23'),
	(25, 'Mónica Melissa', 'Romero Campos', 27, '6316-3513', 11, 'Femenino', '05357414-7', '1123-240296-101-5', 'Soltero/a', '1996-02-24', 11, 64, 'COL. PUNTA DIAMANTE PSJ #2', 1, '2023-11-16'),
	(26, 'Paola Carla', 'Serrano Díaz', 28, '7948-1970', 11, 'Femenino', '05242282-6', '1123-140995-102-7', 'Soltero/a', '1995-09-14', 11, 64, 'COL. EL NARANJO 3 PSJ. ACC F CASA 7 ', 1, '2024-01-25'),
	(27, 'Gabriela María', 'Rivera Rivera', 27, '7848-7740', 15, 'Femenino', '05412653-1', '0000-000000-000-0', 'Soltero/a', '1996-09-11', 11, 64, 'COLONIA DEUSEM PASAJE 3 CASA #15', 1, '2024-02-05'),
	(28, 'Camila Alexandra', 'Rodriguez Bernabel', 25, '7995-5055', 12, 'Femenino', '05995992-1', '0000-000000-000-0', 'Casado/a', '1999-11-04', 11, 47, 'Ereguayquin, Usulutan', 1, '2023-01-15'),
	(29, 'Samuel Alexander', 'Aparicio Guevara', 32, '6134-5803', 12, 'Masculino', '04689728-3', '1207-090992-103-5', 'Soltero/a', '1992-09-09', 11, 45, 'Col. El Rancho, Canton El Paraisal, Calle Principal 4to pasaje oriente', 1, '2020-02-01'),
	(30, 'Ivan David', 'Joya Maravilla', 31, '7235-2004', 11, 'Masculino', '04756213-2', '1123-230193-103-4', 'Soltero/a', '1993-01-23', 11, 64, 'Final 2° Av. Norte. Paj. "A". Col. Saravia. Casa #2', 1, '2022-05-23'),
	(31, 'Roberto de Jesus', 'Duran Benitez', 34, '7205-5130', 12, 'Masculino', '04231015-8', '1121-210290-101-0', 'Casado/a', '1990-02-20', 13, 81, 'Residencial San Francisco, pje 21, calle al rio, casa #55', 1, '2020-02-01'),
	(32, 'María Guadalupe', 'Márquez Sura', 27, '7898-2643', 12, 'Masculino', '05600256-3', '0000-000000-000-0', 'Soltero/a', '1997-08-29', 11, 64, 'Barrio la parroquia, Concepcion Batres ', 1, '2024-01-16'),
	(33, 'Karen Azucena', 'Quintanilla Zelaya', 22, '7657-8425', 12, 'Femenino', '06301070-5', '0000-000000-000-0', 'Soltero/a', '2002-11-18', 13, 82, 'Canton Los Zelaya, San Rafael Oriente San Miguel', 1, '2024-04-03');

-- Volcando estructura para tabla db_clinica_radiologica.tbl_empresa
CREATE TABLE IF NOT EXISTS `tbl_empresa` (
  `idEmpresa` int(11) NOT NULL AUTO_INCREMENT,
  `nitEmpresa` text DEFAULT NULL,
  `nrcEmpresa` text DEFAULT NULL,
  `nombreEmpresa` text DEFAULT NULL,
  `codActividadEmpresa` text DEFAULT NULL,
  `descActividadEmpresa` text DEFAULT NULL,
  `comercialEmpresa` text DEFAULT NULL,
  `establecimientoEmpresa` text DEFAULT NULL,
  `direccionEmpresa` text DEFAULT NULL,
  `telefonoEmpresa` text DEFAULT NULL,
  `correoEmpresa` text DEFAULT NULL,
  `codMHEmpresa` text DEFAULT NULL,
  `codEstableEmpresa` text DEFAULT NULL,
  `puntoVentaMHEmpresa` text DEFAULT NULL,
  `codPuntoVentaEmpresa` text DEFAULT NULL,
  `ambiente` text DEFAULT '00',
  `departamento` text DEFAULT '00',
  `municipio` text DEFAULT '00',
  PRIMARY KEY (`idEmpresa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_empresa: ~0 rows (aproximadamente)
INSERT INTO `tbl_empresa` (`idEmpresa`, `nitEmpresa`, `nrcEmpresa`, `nombreEmpresa`, `codActividadEmpresa`, `descActividadEmpresa`, `comercialEmpresa`, `establecimientoEmpresa`, `direccionEmpresa`, `telefonoEmpresa`, `correoEmpresa`, `codMHEmpresa`, `codEstableEmpresa`, `puntoVentaMHEmpresa`, `codPuntoVentaEmpresa`, `ambiente`, `departamento`, `municipio`) VALUES
	(1, '06142405161029', '2510220', 'UNION MEDICA S.A. DE C.V.', '86100', 'Actividades de hospitales', 'Farmacia U. Medica', '02', '6A. CLL. OTE. #6 BO. LA PARROQUIA #8', '26069973', 'info@hospitalorellana.com.sv', '0000', '0000', '0000', '000000000000000', '00', '11', '25');

-- Volcando estructura para tabla db_clinica_radiologica.tbl_examenes
CREATE TABLE IF NOT EXISTS `tbl_examenes` (
  `idExamen` int(11) NOT NULL AUTO_INCREMENT,
  `codigoExamen` varchar(20) DEFAULT NULL,
  `nombreExamen` varchar(255) DEFAULT NULL,
  `precioPublico` decimal(10,2) DEFAULT NULL,
  `precioPrivado` decimal(10,2) DEFAULT NULL,
  `tipoExamen` int(11) DEFAULT NULL,
  `estadoExamen` int(11) DEFAULT 1,
  `creado` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idExamen`),
  CONSTRAINT `tbl_examenes_ibfk_1` FOREIGN KEY (`tipoExamen`) REFERENCES `tbl_tipo_examenes` (`idTipo`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_examenes: ~77 rows (aproximadamente)
INSERT INTO `tbl_examenes` (`idExamen`, `codigoExamen`, `nombreExamen`, `precioPublico`, `precioPrivado`, `tipoExamen`, `estadoExamen`, `creado`) VALUES
	(1, 'EX1000', 'Craneo Ap y Lat', 30.00, 35.00, 1, 1, '2025-05-02 20:22:47'),
	(2, 'EX1001', 'Orbitas', 30.00, 35.00, 1, 1, '2025-05-02 20:22:48'),
	(3, 'EX1002', 'Watters', 15.00, 15.00, 1, 1, '2025-05-02 20:22:49'),
	(4, 'EX1003', 'Cavum', 15.00, 15.00, 1, 1, '2025-05-02 20:22:49'),
	(5, 'EX1004', 'Senos Paranasales', 30.00, 35.00, 1, 1, '2025-05-02 20:22:51'),
	(6, 'EX1005', 'Silla Turca', 23.00, 25.00, 1, 1, '2025-05-02 20:22:50'),
	(7, 'EX1006', 'Huesos Nasales', 25.00, 25.00, 1, 1, '2025-05-02 20:22:51'),
	(8, 'EX1007', 'Agujeros Opticos', 30.00, 35.00, 1, 1, '2025-05-02 20:22:52'),
	(9, 'EX1008', 'Art Temp, Mand, Bilate', 55.00, 65.00, 1, 1, '2025-05-02 20:22:52'),
	(10, 'EX1009', 'Mandibulas', 30.00, 35.00, 1, 1, '2025-05-02 20:22:54'),
	(11, 'EX1010', 'Mastoides', 45.00, 55.00, 1, 1, '2025-05-02 20:22:54'),
	(12, 'EX1011', 'Arco Cigomatico', 25.00, 30.00, 1, 1, '2025-05-02 20:22:55'),
	(13, 'EX1012', 'Town', 15.00, 15.00, 1, 1, '2025-05-02 20:22:56'),
	(14, 'EX1013', 'Clavicula', 20.00, 25.00, 2, 1, '2025-05-02 20:22:56'),
	(15, 'EX1014', 'Columna Cervical', 25.00, 30.00, 2, 1, '2025-05-02 20:22:57'),
	(16, 'EX1015', 'Columna Dorsal', 25.00, 30.00, 2, 1, '2025-05-02 20:22:58'),
	(17, 'EX1016', 'Columna Lumbar', 25.00, 30.00, 2, 1, '2025-05-02 20:23:00'),
	(18, 'EX1017', 'Columna Cerv/Oblicuas', 45.00, 50.00, 2, 1, '2025-05-02 20:23:01'),
	(19, 'EX1018', 'Columna Lumb/Oblicuas', 45.00, 50.00, 2, 1, '2025-05-02 20:23:02'),
	(20, 'EX1019', 'Sacro Coxis', 25.00, 30.00, 2, 1, '2025-05-02 20:23:02'),
	(21, 'EX1020', 'Pelvis AP', 25.00, 30.00, 2, 1, '2025-05-02 20:23:03'),
	(22, 'EX1021', 'Columna Lumb AP/Flex', 55.00, 60.00, 2, 1, '2025-05-02 20:23:03'),
	(23, 'EX1022', 'Hombro Der o Izq', 20.00, 25.00, 3, 1, '2025-05-02 20:23:04'),
	(24, 'EX1023', 'Escapulas Der o Izq', 20.00, 25.00, 3, 1, '2025-05-02 20:23:05'),
	(25, 'EX1024', 'Humero Der o Izq', 20.00, 25.00, 3, 1, '2025-05-02 20:23:05'),
	(26, 'EX1025', 'Codo Der o Izq', 20.00, 25.00, 3, 1, '2025-05-02 20:23:05'),
	(27, 'EX1026', 'Antebrazo Der o Izq', 20.00, 25.00, 3, 1, '2025-05-02 20:23:06'),
	(28, 'EX1027', 'Muñeca Der o Izq', 20.00, 25.00, 3, 1, '2025-05-02 20:23:07'),
	(29, 'EX1028', 'Ambas Rodillas', 50.00, 55.00, 3, 1, '2025-05-02 20:23:07'),
	(30, 'EX1029', 'Femur Der o Izq', 20.00, 25.00, 3, 1, '2025-05-02 20:23:08'),
	(31, 'EX1030', 'Rodilla Der o Izq AP y LAT', 24.00, 27.00, 3, 1, '2025-05-02 20:23:09'),
	(32, 'EX1031', 'Pierna Der o Izq', 23.00, 25.00, 3, 1, '2025-05-02 20:23:10'),
	(33, 'EX1032', 'Ambas Piernas', 45.00, 50.00, 3, 1, '2025-05-02 20:23:11'),
	(34, 'EX1033', 'Tobillo Der o Izq', 20.00, 25.00, 3, 1, '2025-05-02 20:23:11'),
	(35, 'EX1034', 'Ambos Tobillos', 40.00, 50.00, 3, 1, '2025-05-02 20:23:12'),
	(36, 'EX1035', 'Pie Der o Izq', 20.00, 25.00, 3, 1, '2025-05-02 20:23:13'),
	(37, 'EX1036', 'Ambos Pies', 40.00, 50.00, 3, 1, '2025-05-02 20:23:14'),
	(38, 'EX1037', 'Pie Lateral', 20.00, 25.00, 3, 1, '2025-05-02 20:23:15'),
	(39, 'EX1038', 'Calcaneos', 20.00, 25.00, 3, 1, '2025-05-02 20:23:15'),
	(40, 'EX1039', 'Femur AP', 20.00, 25.00, 3, 1, '2025-05-02 20:23:16'),
	(41, 'EX1040', 'Mano Der o Izq', 20.00, 25.00, 3, 1, '2025-05-02 20:23:17'),
	(42, 'EX1041', 'Torax PA*', 15.00, 15.00, 4, 1, '2025-05-02 20:23:17'),
	(43, 'EX1042', 'Torax PA y Lateral', 25.00, 30.00, 4, 1, '2025-05-02 20:23:18'),
	(44, 'EX1043', 'Torax Costilla', 25.00, 30.00, 4, 1, '2025-05-02 20:23:19'),
	(45, 'EX1044', 'Esternon', 25.00, 30.00, 4, 1, '2025-05-02 20:23:20'),
	(46, 'EX1045', 'Abdomen AP Simple', 25.00, 30.00, 5, 1, '2025-05-02 20:23:20'),
	(47, 'EX1046', 'Abdomen Agudo', 55.00, 60.00, 5, 1, '2025-05-02 20:23:22'),
	(48, 'EX1047', 'Colangiograma en tubo T', 100.00, 125.00, 5, 1, '2025-05-02 20:23:24'),
	(49, 'EX1048', 'Transito Intestinal', 100.00, 125.00, 5, 1, '2025-05-02 20:23:25'),
	(50, 'EX1049', 'Enema Baritado', 100.00, 125.00, 5, 1, '2025-05-02 20:23:27'),
	(51, 'EX1050', 'Esofagograma', 100.00, 125.00, 5, 1, '2025-05-02 20:23:30'),
	(52, 'EX1051', 'Fistulograma', 100.00, 125.00, 5, 1, '2025-05-02 20:23:31'),
	(53, 'EX1052', 'Tubo Digestivo Sup', 100.00, 125.00, 5, 1, '2025-05-02 20:23:32'),
	(54, 'EX1053', 'Pielograma EV/ P', 100.00, 150.00, 6, 1, '2025-05-02 20:23:33'),
	(55, 'EX1054', 'Cistograma/ Cistografia', 75.00, 150.00, 6, 1, '2025-05-02 20:23:33'),
	(56, 'EX1055', 'Uretrograma', 75.00, 100.00, 6, 1, '2025-05-02 20:23:34'),
	(57, 'EX1056', 'Caderas', 25.00, 30.00, 7, 1, '2025-05-02 20:23:34'),
	(58, 'EX1057', 'Edad Osea', 25.00, 30.00, 7, 1, '2025-05-02 20:23:35'),
	(59, 'EX1058', 'Serie Osea/Surbey Oseo', 180.00, 200.00, 7, 1, '2025-05-02 20:23:36'),
	(60, 'EX1059', 'Histerosalpinpograma', 180.00, 200.00, 7, 1, '2025-05-02 20:23:36'),
	(61, 'EX1060', 'Mamo Bilateral', 25.00, 30.00, 8, 1, '2025-05-02 20:23:37'),
	(62, 'EX1061', 'USG Abd/Ren/Vesical', 18.00, 23.00, 9, 1, '2025-05-02 20:23:38'),
	(63, 'EX1062', 'USG Tejido Blando', 22.00, 25.00, 9, 1, '2025-05-02 20:23:38'),
	(64, 'EX1063', 'USG Tiroides', 22.00, 25.00, 9, 1, '2025-05-02 20:23:39'),
	(65, 'EX1064', 'USG Musculo Esqueletico', 30.00, 35.00, 9, 1, '2025-05-02 20:23:40'),
	(66, 'EX1065', 'USG Prostata', 22.00, 25.00, 9, 1, '2025-05-02 20:23:40'),
	(67, 'EX1066', 'Doppler Ambas Piernas', 130.00, 140.00, 9, 1, '2025-05-02 20:23:41'),
	(68, 'EX1067', 'Doppler Ambas Piernas', 65.00, 70.00, 9, 1, '2025-05-02 20:23:41'),
	(69, 'EX1068', 'UROTAC', 100.00, 200.00, 10, 1, '2025-05-02 20:23:42'),
	(70, 'EX1069', 'PIELOTAC', 100.00, 200.00, 10, 1, '2025-05-02 20:23:43'),
	(71, 'EX1070', 'ANGIOTAC', 200.00, 250.00, 10, 1, '2025-05-02 20:23:43'),
	(72, 'EX1071', 'TAC CRANEO', 100.00, 200.00, 10, 1, '2025-05-02 20:23:44'),
	(73, 'EX1072', 'TAC ABDOMINAL', 100.00, 200.00, 10, 1, '2025-05-02 20:23:44'),
	(74, 'EX1073', 'ABDOMINO/PELVICO', 250.00, 350.00, 10, 1, '2025-05-02 20:23:46'),
	(75, 'EX1074', 'TAC PELVICO', 100.00, 200.00, 10, 1, '2025-05-02 20:23:47'),
	(76, 'EX1075', 'TAC 3D/MI/Columnas', 180.00, 250.00, 10, 1, '2025-05-02 20:23:48'),
	(77, 'EX1076', 'TAC TORAX', 100.00, 200.00, 10, 1, '2025-05-02 20:23:48');

-- Volcando estructura para tabla db_clinica_radiologica.tbl_medicos
CREATE TABLE IF NOT EXISTS `tbl_medicos` (
  `idMedico` int(11) NOT NULL AUTO_INCREMENT,
  `nombreMedico` varchar(50) NOT NULL,
  `especialidadMedico` varchar(150) NOT NULL,
  `telefonoMedico` varchar(10) NOT NULL,
  `direccionMedico` text NOT NULL,
  PRIMARY KEY (`idMedico`)
) ENGINE=InnoDB AUTO_INCREMENT=485 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_medicos: ~482 rows (aproximadamente)
INSERT INTO `tbl_medicos` (`idMedico`, `nombreMedico`, `especialidadMedico`, `telefonoMedico`, `direccionMedico`) VALUES
	(1, 'Dr. Carlos Roberto Vidaurre Flores', 'Pediatra', '2662-2909', '6° Calle Oriente N° 13, Frente A Clinica Orellana, Usulután'),
	(2, 'Dr. Luis Gerardo Bernabel Cañas', 'Cirujano Urologo', '2624-7205', 'Calle Federico Penado, 7° Avenida Norte, Barrio La Merced, Usulután'),
	(3, 'Dr. Carlos Zepeda Fuentes', 'Ginecologo', '2262-0330', '6° Calle Poniente Y Avenida Napoleon Flores Huezo, Usulután'),
	(4, 'Dr. Jorge Nelson Santos Pineda', 'Ortopeda', '2624-1724', 'Calle Dr. Federico Penado N° 38,  Usulután'),
	(5, 'Dr. Luis Alfredo Quintanilla Morales', 'Cirujano', '0000-0000', 'Usulután'),
	(6, 'Dr.  Enrique Walter Mitjavila', 'Pediatra', '0000-0000', 'Usulután'),
	(7, 'Dra. Veronica Esmeralda Amaya Jurado', 'Medico Internista', '7140-4168', '6° Avenida Sur, Usulután'),
	(8, 'Dr. Marlon Ivan Rivera Navas', 'Neumologo', '0000-0000', 'Usulután'),
	(9, 'Dr. Erick Prado', 'Nefrologo', '0000-0000', 'Usulután'),
	(10, 'Dr. Jorge Alexander Prado Romero', 'Nefrologo', '0000-0000', 'Usulután'),
	(11, 'Dr. Noe Hamilton Zelaya Belloso', 'Cirujano', '2624-2526', '6° Avenida Sur, Barrio El Calvario N° 9, Usulután'),
	(12, 'Dr. Ricardo Antonio Reyes', 'Medico Cirujano', '2662-1540', '1° Avenida Norte, Barrio La Merced, N6, Usulután'),
	(13, 'Dr. Nelson Edgar Orellana', '-', '0000-0000', 'Usulután'),
	(14, 'Dr. Richardson Parada', 'Pediatra', '0000-0000', 'Usulután'),
	(15, 'Dr. Hector Antonio Perez', 'Neurologo', '0000-0000', 'Usulután'),
	(16, 'Dr. Marcelo Orlando Jandres Jandres', 'Ginecologo', '0', 'Pasaje C. Colonia Masferrer, # 18 Usulután'),
	(17, 'Dra. Grisela Azucena Martinez Rivera', 'Medico Cirujano', '2624-0326', '6° Calle Oriente, # 15, Frente A Clinica Orellana, Usulután'),
	(18, 'Dra. Anabell De Vidaurre', 'Medicina Géneral', '0000-0000', 'Usulután'),
	(19, 'Dra. Veronica Rodriguez De Garcia', '-', '0000-0000', 'Usulután'),
	(20, 'Dr.Fidel Alfredo Sandoval Paniagua', 'Nefrologo', '0', 'Calle Penado # 44, Usulután'),
	(21, 'Dr. Denis Asael Cocar Santos', '-', '0000-0000', 'Usulután'),
	(22, 'Dr. Jose Ricardo Caminos Ferrufino', 'Cardiólogo', '7437-8873', 'Usulutan'),
	(23, 'Dra. Mirta Cecilia Flores de Caminos', 'Cardiólogo', '7447-6992', 'Usulutan'),
	(24, 'Dr. Denis Calero', '-', '0000-0000', 'Usulután'),
	(25, 'Dr. Juan Francisco Lainez Flores', 'Cirujano General', '2624-8845', 'Calle Dr. Federico Penado, Barrio Candelaria # 4, Usulután'),
	(26, 'Dra. Lina Gertrudis Garcia De Quele', 'Medico Internista', '0', 'Pasaje E, Centro Urbano Alberto Masferrer, Casa # 35, Usulután'),
	(27, 'Dr. Ramon Quintanilla', 'Ginecologo', '0000-0000', 'Usulután'),
	(28, 'Dra. Maritza Arely Ayala Vargas', 'Medico General', '0', '2° Calle Ori°te Y 4° Avenida Norte, El Transito, Usulután'),
	(29, 'Dra. Martha Idalia Magarin', 'Nefrologa', '0000-0000', 'Usulután'),
	(30, 'Dra. Diniri De Leon', '-', '0000-0000', 'Usulután'),
	(31, 'Dra. Alba Luz Guzman De Navas', 'Ginecologa', '2624-7657', '4° Calle Oriente, # 14 Centro Comercial San Antonio, Usulután'),
	(32, 'Dra. Sandra Orellana del Cid', 'Ginecologa', '0000-0000', 'Usulután'),
	(33, 'Dr. Manuel Antonio Alvarado Villeda', 'Internista', '2662-1837', '2° Calle Oriente, N° 1 Contiguo A Bancomi, Usulután'),
	(34, 'Dr. Manuel Gamero', '-', '0000-0000', 'Usulután'),
	(35, 'Dr. Roberto Castillo', 'Medico General', '0000-0000', 'Usulután'),
	(36, 'Dr. Javier  Hernandez Rubio', '-', '0000-0000', 'Usulután'),
	(37, 'Dr. Joel Alejo Romero Lopez', '-', '0000-0000', 'Usulután'),
	(38, 'Dra. Claudia Rivas', 'Medico General', '0000-0000', 'Usulután'),
	(39, 'Dr. Jose Adalberto Sanchez Diaz', 'Ginecologo', '0000-0000', 'Usulután'),
	(40, 'Dr. Jose Adalberto Orellana Lizama', 'Cirujano', '0', '7° Avenida Norte N° 7, Barrio La Merced, Usulután'),
	(41, 'Dr. Luis Ernesto Campos Vasquez', 'Ginecologo', '0', '6° Calle Poniente N° 706, Barrio San Felipe, San Miguel'),
	(42, 'Dr. Carlos Mario Garcia', 'Cirujano', '0000-0000', '2 Avenida Norte, Barrio La Parroquia'),
	(43, 'Dr. Pedro Antonio Castillo Perdomo', 'Cirujano', '2624-5216', '4° Calle Oriente N°13, Barrio La Parroquia, Usulután'),
	(44, 'Dr. Nestor Antonio Reyes', 'Medico General', '0000-0000', 'Usulután'),
	(45, 'Dr. Luis Alonso Guzman', 'Ortopeda', '0000-0000', 'Usulután'),
	(46, 'Dr. Lopez Bermudez', 'Oftalmologo', '0000-0000', 'Usulután'),
	(47, 'Dr. Juan Carlos Reyes Vargas', 'Pediatra', '0000-0000', 'Usulután'),
	(48, 'Dr. Jose Ovidio Rodriguez Morataya', 'Ortopedia', '0000-0000', 'Usulután'),
	(49, 'Dra. Raquel Esmeralda Cruz', 'Gastroenterologa', '0000-0000', 'Usulután'),
	(50, 'Dr. Francisco Eduardo Villega Torres', 'Medico General', '0000-0000', 'Usulután'),
	(51, 'Dr. Miguel Angel Amaya', 'Internista', '0000-0000', 'Usulután'),
	(52, 'Dra. Maria Del Carmen Carranza', '-', '0000-0000', 'Usulután'),
	(53, 'Dr. Rene Alexander Alas Coreas', '-', '0000-0000', 'Usulután'),
	(54, 'Dra. Roxana Vanesa Andrade De Sandoval', 'Neumologa', '0000-0000', 'Usulután'),
	(55, 'Dr. Ramon Rigoberto Cruz Cruz', 'Medicina General', '0000-0000', 'Usulután'),
	(56, 'Dr. Jose Orlando Vallecillos Colato', 'Ginecologo', '0000-0000', 'Usulután'),
	(57, 'Dr. Juan Jose Portillo Larin', 'Medico General', '0000-0000', 'Usulután'),
	(58, 'Dr. Marvin Loza', 'Medicina', '0000-0000', 'Usulután'),
	(59, 'Dr. Oscar Martinez', 'Medicina', '0000-0000', 'Usulután'),
	(60, 'Dr. Odir Martinez', 'Medico General', '0000-0000', 'Usulután'),
	(61, 'Dr. Edwin Vitelio Fuentes', 'Medicina General', '7190-3454', 'Santiago de Maria, Usulutan'),
	(62, 'Dr. Ricardo Torres', '-', '0000-0000', 'Usulután'),
	(63, 'Dr. Neftali Mijango', '-', '0000-0000', 'Usulután'),
	(64, 'Dr. Carlos Elias Portillo Lazo', '-', '0000-0000', 'Usulután'),
	(65, 'Dr. Wilber Argueta', '-', '0000-0000', 'Usulután'),
	(66, 'Dra. Astrid Perdomo', '-', '0000-0000', 'Usulután'),
	(67, 'Dra. Dina Zavala', '-', '0000-0000', 'Usulután'),
	(68, 'Dra. Lea Contreras', '-', '0000-0000', 'Usulután'),
	(69, 'Dra. Isabel Trujillo', '-', '0000-0000', 'Usulután'),
	(70, 'Dra. Roxana Jimenez', '-', '0000-0000', 'Dra. Sandra Orellana'),
	(71, 'Dra. Silvia Rodriguez de Rivera', 'Pediatra', '0000-0000', 'Usulután'),
	(72, 'Dr. Joaquin Guerrero', '-', '0000-0000', 'Usulután'),
	(73, 'DR. ELISEO ENRIQUE MENDEZ APARICIO', 'MEDICO GENERAL', '2605-6298', 'EL TRANSITO SAN MIGUEL '),
	(74, 'Dr. Gilberto Castillo', '-', '0000-0000', 'Usulután'),
	(75, 'Dr. Jorge Alberto Fernandez', '-', '0000-0000', 'Usulután'),
	(76, 'Dr. Jorge Ernesto Molina Martinez', '-', '0000-0000', 'Usulután'),
	(77, 'Dr. Miguel Manzano', '-', '0000-0000', 'Usulután'),
	(78, 'Dr. Benjamin Mauricio Bermudez Berrios', '-', '0000-0000', 'Usulután'),
	(79, 'Dr. Ricardo Antonio Gonzales Yanes', '-', '0000-0000', 'Usulután'),
	(80, 'Dr. Edwin Amaya', '-', '0000-0000', 'Usulután'),
	(81, 'Dr. Ruben Ernesto Jovel', '-', '0000-0000', 'Usulután'),
	(82, 'Dr. Adilson Zelaya', '-', '0000-0000', 'Usulután'),
	(83, 'Dr. Dimas Zelaya', '-', '0000-0000', 'Usulután'),
	(84, 'Dr. Cesar Antonio Galdamez', '-', '0000-0000', 'Usulután'),
	(85, 'Dr. Josue Iraheta', '-', '0000-0000', 'Usulután'),
	(86, 'Dr. Manuel Rivera', 'Ginecologo', '0000-0000', 'Usulután'),
	(87, 'Dr. Josue Abraham Gonzalez Luna', '-', '0000-0000', 'Usulután'),
	(88, 'Dr. Mario Zelaya', '-', '0000-0000', 'Usulután'),
	(89, 'Dr. Danilo Aparicio', '-', '0000-0000', 'Usulután'),
	(90, 'Dra. Ena Cecilia Zepeda', '-', '0000-0000', 'Usulután'),
	(91, 'Dr. Robert Antonio Rivera B.', '-', '0000-0000', 'Usulután'),
	(92, 'Dr. Cristian Cruz', '-', '0000-0000', 'Usulután'),
	(93, 'Dr. Victor Manuel Cardona', '-', '0000-0000', 'Usulután'),
	(94, 'Dra. Silvia Carolina Navas Medrano', 'Ginecologa', '0000-0000', 'Usulután'),
	(95, 'Dr. Daniel Stanley Sanchez Flores', 'Medicina Géneral', '0000-0000', 'Usulután'),
	(96, 'Dr. Cecilio Martinez', 'Medicina Géneral', '0000-0000', 'Usulután'),
	(97, 'Dr. Raul Antonio Rodriguez', 'Medicina Géneral', '0000-0000', '3° Avenida Norte Final 3° Calle Oriente Frente a Juzgado, Santa Maria, Usulután'),
	(98, 'Dr. Lazaro Avilio Martinez Ramirez', 'Ginecologo', '0000-0000', 'Usulután'),
	(99, 'Dra. Dalila Azucena Turcios de Castillo', 'Medicina Géneral', '0000-0000', 'Usulután'),
	(100, 'Dr. Oscar Armando Martinez Valles', 'Medicina Géneral', '0000-0000', 'Usulután'),
	(101, 'Dr. Elias de Jesus Contreras', 'Ortopeda', '0000-0000', 'Usulután'),
	(102, 'Dr. Kerin Ramos', 'Ginecologo', '0000-0000', 'Usulután'),
	(103, 'Dr. Julio Adalberio Reyes Alvarez', 'Medicina Géneral', '0000-0000', 'Usulután'),
	(104, 'Dra. Ana Luisa Sosa Alvarenga', 'Medicina Géneral', '0000-0000', 'Usulután'),
	(105, 'Dr. Carlos Ernesto Trejo Alemán', 'Médico Pediatra', '7160-9151', 'Usulután.'),
	(106, 'Dra. Lucia Duanes', '-', '0000-0000', 'Usulután'),
	(107, 'Dr. Carlos Eduardo Muñoz Marquez', '-', '0000-0000', 'Usulután'),
	(108, 'Dra. Roxana Ramos', '-', '0000-0000', 'Usulután'),
	(109, 'Dra. Marisela de Jesus Carranza Parada', '-', '0000-0000', 'Usulután'),
	(110, 'Dr. Manuel Alberto Lopez', '-', '0000-0000', 'Usulután'),
	(111, 'Dr. Julio Olaf Carrillo Sanchez', '-', '0000-0000', 'Usulután'),
	(112, 'Dr. Luis Mora', 'Cirujano', '0000-0000', 'Usulután'),
	(113, 'Dra. Nancy Alonso', 'Medicina Géneral', '0000-0000', 'Usulután'),
	(114, 'Dra. Gabriela Handal', '-', '0000-0000', 'Usulután'),
	(115, 'Dra. Ana Deras', 'Medicina Géneral', '0000-0000', 'Usulután'),
	(116, 'Dr. Cesar Gomez Villanueva', '-', '0000-0000', 'Usulután'),
	(117, 'Dr. Cesar Federico Gomez', '-', '0000-0000', 'Usulután'),
	(118, 'Dra. Silvia Sosa', '-', '0000-0000', 'Usulután'),
	(119, 'Dr. Elmer Martinez', '-', '0000-0000', 'Usulután'),
	(120, 'KARLA MARIA GUZMAN DE LARA ', '-', '0000-0000', 'Usulután'),
	(121, 'Dra. Auristela Rodriguez Hernandez', '-', '0000-0000', 'Usulután'),
	(122, 'Dr. Erick Dagoberto Cruz Gonzales', '-', '0000-0000', 'Usulután'),
	(123, 'Lic. Cesar Rivera Blanco', 'Anestesista', '0000-0000', 'Usulutan'),
	(124, 'Dr. Villacorta', 'Medicina Genral', '0000-0000', 'La Union'),
	(125, 'Lic. Fulvio Arias', 'Anestesista', '0000-0000', 'Usulutan'),
	(126, 'Lic. Alexis Baires', 'Anestesista', '0000-0000', 'Usulutan'),
	(127, 'Lic. Eldon Sanchez', 'Anestesista', '0000-0000', 'Usulutan'),
	(128, 'Lic. Ricardo Urias', 'Anestesista', '0000-0000', 'Usulutan'),
	(129, 'Lic. Arnoldo Sorto', 'Anestesista', '0000-0000', 'El Transito, San Miguel'),
	(130, 'Lic. Emilio Hernandez', 'Anestesista', '0000-0000', 'Usulutan'),
	(131, 'Lic. Nancy Cruz', 'Anestesista', '0000-0000', 'Usulutan'),
	(132, 'Lic. Oscar Antonio Roguel Aguirrez', 'Anestesista', '0000-0000', 'Usulutan'),
	(133, 'Lic. Yadir Chicas', 'Anestesista', '0000-0000', 'Usulutan'),
	(134, 'Lic. Eduardo Guevara', 'Anestesista', '0000-0000', 'Usulutan'),
	(135, 'Pendiente', '-', '0000-0000', '-'),
	(136, 'Farmacia San Rey', 'proveedor', '0000-0000', '-'),
	(137, 'Dr. Cruz Garciaguirre', 'Medico General', '0000-0000', 'Usulutan'),
	(138, 'Srita. Saravia', 'Enfermeria ', '0000-0000', '-'),
	(139, 'AURORA NOHEMI VELASQUEZ', 'GINECOLOGO ONCOLOGO', '0000-0000', 'SAN SALVADOR'),
	(140, 'MIGUEL HERNANDEZ', 'Medicina Genral', '0000-0000', 'SAN SALVADOR '),
	(141, 'Licda. Maira Quintanilla(Honorarios)', 'enfermera', '0000-0000', 'Usulutan'),
	(142, 'Srita. Yesenia Arevalo', 'enfermera', '0000-0000', 'Usulutan'),
	(143, 'Clinica Dr Jaime Argueta', 'Medico Radiologo', '____-____', '-'),
	(145, 'Licda. Alicia de Zepeda( honorarios )', 'Anestesista', '0000-0000', '-'),
	(146, 'Licda. Alicia de Zepeda( honorarios )', 'Anestesista', '0000-0000', '-'),
	(147, 'JOSE LUIS NAVAS', 'GENERAL', '0000-0000', 'USULUTAN'),
	(148, 'LIC. ROBERTO PAZ', 'ANESTESISTA', '0000-0000', 'USULUTAN'),
	(149, 'Ana Astrid Perdomo de Jandres', 'Medico General', '0000-0000', '-'),
	(150, 'Dra. Yessica Lisett Moreira Recinos', 'MEDICINA', '0000-0000', 'USULUTAN'),
	(151, 'LICDA. XIOMARA LISSETTE TREJO MEDINA', 'NUTRICIONISRA', '2606-8814', 'LA MERCED USULUTAN'),
	(152, 'DR. MAURICIO ESAU ROMERO POLIO', 'MEDICO GENERAL', '0000-0000', 'SANTA ELENA'),
	(153, 'BOTIQUIN DR MARLON IVAN RIVERA NAVAS', 'NEUMOLOGO', '0000-0000', 'USULUTAN'),
	(154, 'DR. SALVADOR MELENDEZ MENA', 'MEDICO GENERAL', '2624-0534', '2A CALLE PONIENTE Y 3A AV NORTE N 12, 2 CUADRAS ABAJO DEL DUICENTRO USULUTAN'),
	(155, 'DRA. ANA GABRIELA DERAS GARCIA', 'MEDICO GENERAL', '7792-1896', 'GUALACHE CALLE PRINCIPAL'),
	(156, 'RICARDO ANTONIO GONZALEZ HERNANDEZ', 'INTERNISTA', '7483-3237', 'USULUTAN'),
	(157, 'Dr. Sergio Antonio Gomez Hernandez', 'Pediatra', '0000-0000', 'Usulutan'),
	(158, 'Lic. Douglas Trejo', 'Enfermeria', '0000-0000', 'Usulutan'),
	(159, 'Licda. Hermelinda Rivera', 'Enfermeria', '0000-0000', 'Usulutan'),
	(160, 'Licda. Vanessa Avalos', 'Enfermeria', '0000-0000', 'Usulutan'),
	(161, 'Srta. Gladis Rodriguez', 'Enfermeria', '0000-0000', 'Usulutan'),
	(162, 'Srta. Lilian Cisneros', 'Enfermeria', '0000-0000', 'Usulutan'),
	(163, 'Srta. Estefania Gomez', 'Enfermeria', '0000-0000', 'Usulutan'),
	(164, 'Srta. Connie Barrera', 'Enfermeria', '0000-0000', 'Usulutan'),
	(165, 'Srta. Marlene Lopez', 'Enfermeria', '0000-0000', 'Usulutan'),
	(166, 'Srta. Lidia Martinez', 'Enfermeria', '0000-0000', 'Usulutan'),
	(167, 'Srta. Tania Diaz', 'Enfermeria', '0000-0000', 'Usulutan'),
	(168, 'Srta. Patricia Delgado', 'Enfermeria', '0000-0000', 'Usulutan'),
	(169, 'Srta. Marina Parada', 'Enfermeria', '0000-0000', 'Usulutan'),
	(170, 'Srta. Claudia Martinez', 'Enfermeria', '7029-3028', 'Usulutan'),
	(171, 'Srta. Sandra Maravilla', 'Enfermeria', '0000-0000', 'Usulutan'),
	(172, 'Srta. Margarita Bonilla', 'Enfermeria', '0000-0000', 'Usulutan'),
	(173, 'Srta. Jessica Ortez', 'Enfermeria', '0000-0000', 'Usulutan'),
	(174, 'Srta. Beatriz Romero', 'Enfermeria', '0000-0000', 'Usulutan'),
	(175, 'Srta. Mairene Lemus', 'Enfermeria', '0000-0000', 'Usulutan'),
	(176, 'Srta. Victoria Acevedo', 'Enfermeria', '0000-0000', 'Usulutan'),
	(177, 'Licda. Yesenia Campos', 'Enfermeria', '0000-0000', 'Usulutan'),
	(178, 'Lic. Jeovany Gomez', 'Enfermeria', '0000-0000', 'Usulutan'),
	(179, 'Srta. Yesenia Arevalo', 'Enfermeria', '0000-0000', 'Usulutan'),
	(180, 'Srta. Aracely del Cid', 'Enfermeria', '0000-0000', 'Usulutan'),
	(181, 'Srta. Esnaida Rios', 'Enfermeria', '0000-0000', 'Usulutan'),
	(182, 'Esdras Noe Granados', 'Fisioterapista', '0000-0000', '-'),
	(183, 'DRA. PATRICIA CABALLERO', 'MEDICO GENERAL', '7484-0220', 'OZATLAN'),
	(184, 'Licda. Claudia Gonzalez(Fisioterapista)', 'Fisioterapista', '0000-0000', '-'),
	(185, 'SERGIO ANTONIO GOMEZ HERNANDEZ', ' Medico pediatra', '0000-0000', '-'),
	(186, 'RUDY VELASQUEZ', 'CIRUJANO', '0000-0000', 'USULUTÁN'),
	(187, 'LICDA. MIRNA ESTEFANY AMAYA AGUIÑADA', 'FISIOTERAPISTA', '0000-0000', 'USULUTAN'),
	(188, 'NASSER ODIR MARTINEZ MELENDEZ ', 'GERIATRIA ', '7150-8346', 'BARRIO LA MERCED USULUTAN '),
	(189, 'DR. RONALD RODRIGUEZ HURTADO', 'CIRUJANO', '0000-0000', 'SAN SALVADOR'),
	(190, 'GEOVANI ANTONIO ALFARO RIVERA', 'MEDICINA GENERAL', '0000-0000', 'USULUTAN '),
	(191, 'SALVADOR MAGAÑA MERCADO', 'NEFROLOGO', '2661-8604', 'SAN MIGUEL'),
	(192, 'ERICK NOE BAIRES ALFARO', 'MEDICO GENERAL', '0000-0000', 'USULUTAN'),
	(193, 'SRTA. SARAVIA', 'ENFERMERA', '0000-0000', 'USULUTAN'),
	(194, 'DR. PEDRO DAVID QUELE', 'CIRUJANO', '0000-0000', 'USULUTAN'),
	(195, 'DR. MANUEL DE JESUS MARTINEZ CHAVEZ', 'DOCTORA EN MEDICINA', '0000-0000', 'USULUTAN'),
	(196, 'TERAPIA BAC LICDA VAQUERANO', 'TERAPIA', '0000-0000', 'USULUTAN'),
	(197, 'LIC. RUDY RAMIREZ', 'ANESTESISTA', '0000-0000', 'USULUTAN'),
	(198, 'DROGUERIA SANTA LUCIA', 'CIRUJANO', '0000-0000', 'USULUTAN'),
	(199, 'DR. OMAR RUIZ', 'DERMATOLOGO', '0000-0000', 'USULUTAN'),
	(200, 'Dr. Carlos  Antonio Orellana ', 'MEDICO GENERAL', '0000-0000', 'USULUTAN'),
	(201, 'DRA. KARLA MIREYA DIAZ MIRANDA', 'PERINATOLA', '7118-6627', 'USULUTAN'),
	(202, 'LIC. SALVADOR QUINTANILLA', 'ANESTESISTA', '7293-5633', 'USULUTAN'),
	(203, 'HOLTER MIRTA DE CAMINOS', 'CARDIOLOGA', '0000-0000', 'USULUTAN'),
	(204, 'INDOMET', 'VVVV', '0000-0000', 'SAN SALVADOR'),
	(205, 'Lic. Oscar Ferrufino', 'Anestesista', '0000-0000', 'Usulutan'),
	(206, 'DRA. MIRNA CAMPOS', 'GENERAL', '0000-0000', '0000'),
	(207, 'Manuel Adalberto Rivera Lopez ', 'Ginecólogo Obstetra', '0000-0000', '-'),
	(208, 'Karla Mireya Diaz Miranda', 'Perinatologa', '0000-0000', '-'),
	(209, 'Diana Raquel Hernandez de Castro', 'Anestesista', '0000-0000', '0'),
	(210, 'Margarita de los Angeles Alvarado Romero', 'Anestesista', '0000-0000', '-'),
	(211, 'Luis Salvador Quintanilla Quintanilla ', 'Anestesista', '0000-0000', '-'),
	(212, 'Lic. Miguel Eduardo Cruz del Cid', 'Anestesista', '0000-0000', '-'),
	(213, 'DRA. AIDA CONCEPCION HERNANDEZ', 'PEDIATRA DERMATOLOGA', '7180-5139', 'USULUTAN'),
	(214, 'LABORATORIO DE ANALISIS LIZAMA', '-', '0000-0000', 'SERVICIOS'),
	(215, 'DR. FRANCISCO ULISES BRAN PORTILLO', 'MEDICINA GENERAL', '7948-4646', 'USULUTAN'),
	(216, 'RAUL GARCIA CAÑAS', 'CARDIOLOGO', '0000-0000', 'HSGS'),
	(217, 'MIREYA DIAZ', '-', '0000-0000', 'USULUTAN'),
	(218, ' JOSE VIRGILIO GARAY', 'MEDICO GENERAL', '0000-0000', 'USULUTAN'),
	(219, 'LICDA. FATIMA MARQUEZ', 'FISIOTERAPIA', '0000-0000', 'DDD'),
	(220, 'SR GRANADOS', 'enfermero', '0000-0000', 'usulutan'),
	(221, 'DR. NESTOR RICARDO REYES CAMPOS ', 'MEDICINA ', '7923-5769', 'CONCEPCION BATRES USULUTAN'),
	(222, 'DR. DAVID MEDINA', 'GINECOLOGO OBSTETRA', '7671-5145', 'USULUTAN'),
	(223, 'LICDA. GONZALEZ', 'FISIOTERAPIA', '0000-0000', 'USULUTAN'),
	(224, 'LIC. RODRIGUEZ (FISIOTERAPIA)', 'FISIOTERAPISTA', '0000-0000', 'USULUTAN'),
	(225, 'FERNANDO PARADA FUNES', 'GINECOLOGO', '0000-0000', 'EL TRANSITO SAN MIGUEL '),
	(226, 'DRA. VANESSA RIVERA', 'MEDICINA GENERAL', '7807-6084', 'USULUTAN'),
	(227, 'DR. JOSE HECTOR VASQUEZ MORENO', 'GENERAL', '0000-0000', 'USULUTAN'),
	(228, 'FRANCISCO OSEAS HERRERA QUINTANILLA', 'GENERAL', '0000-0000', 'USULUTAN'),
	(229, 'KATIA MERCEDES BENAVIDES CHAVEZ', 'GENERAL', '0000-0000', 'SANTA ELENA'),
	(230, 'SILVIA ELENA RODRIGUEZ SEGOVIA', 'PEDIATRA', '0000-0000', 'USULUTAN'),
	(231, 'DR. LOPEZ VEGA', 'MEDICO GENERAL', '0000-0000', 'USULUTAN'),
	(232, 'INMOVILIZADOR DR SANTOS', 'MEDICO', '0000-0000', 'USULUTAN'),
	(233, 'Dra. Mayra Patricia Sanchez Palacios', 'Medico General', '2662-3612', '-'),
	(234, 'Dr. Juan Carlos Sanchez Rosales', 'cirujano', '7118-9157', 'AVENIA FERROCARRIL BARRIO LA CRUZ #25'),
	(235, 'DRA. ROCIO ESMERALDA ZACAPA PINEDA', 'GINECOLOGIA', '6070-8839', 'BERLIN, USULUTAN '),
	(236, 'DR. RODOLFO ANIBAL MORALES VASQUEZ', 'ORTOPEDA', '0000-0000', 'SAN SALVADOR'),
	(237, 'Dr. Hector Jose Cruz Rodriguez', 'Medico General', '7841-8033', 'USULUTAN'),
	(238, 'Dra. Leydi Yoana Hernandez de Contreras', 'GENERAL', '0000-0000', 'usulutan'),
	(239, 'Dra. Patricia Jeannette Estrada Sandoval', 'Medico General', '0000-0000', '-'),
	(240, 'Licda. Ester Amaya ', 'FISIOTERAPISTA', '0000-0000', 'Usulutan'),
	(241, 'LIC. ALEXANDER  MALDONADO', 'ANESTECISTA', '0000-0000', 'VV'),
	(242, 'DR. GOMEZ (COLOPROCTOLOGO)', 'COLOPROCTOLOGO', '0000-0000', '1111'),
	(243, 'CARLOS FELIPE RAMIREZ GONZALEZ', 'OTORRINO', '0000-0000', 'SSSS'),
	(244, 'DR. OSCAR ENRIQUE VARGAS MONTOYA ', 'CIRUJANO PEDIATRA', '7165-5457', 'BARRIO EL CENTRO BERLIN  USULUTAN'),
	(245, 'DR. QUEZADA (CEPRE)', 'GENERAL', '0000-0000', 'SAN SALVADOR'),
	(246, 'DR. KELVIN RIVERA', 'MEDICO GENERAL', '7822-9013', 'EL TRANSITO SAN MIGUEL'),
	(247, 'DR. JOSE NAPOLEON VENTURA', 'ENDOSCOPIATA', '0000-0000', '1111'),
	(248, 'Dr. Victor Manuel Cardona Bonilla', 'MEDICO GENERAL', '6002-1015', 'USULUTAN'),
	(249, 'OBED ALCIDES DIAZ GRANADOS', 'MEDICO GENERAL ', '0000-0000', '-'),
	(250, 'Lic. Edwin B Molina Castro', '-', '0000-0000', 'san miguel'),
	(251, 'Anexos, Equipo de Flujo(Dr. Sandoval)', 'MEDICO', '0000-0000', 'usulutan'),
	(252, 'DRA. CLAUDIA ISABEL IGLESIA ', 'GINECOLOGO', '0000-0000', 'MMMM'),
	(253, 'LIC. MARINA CORNEJO', 'FISIOTERAPEUTA ', '0000-0000', 'USULUTAN '),
	(254, 'Dr. Ricardo Torres Parada ', 'MEDICO', '0000-0000', 'La Union'),
	(255, 'ISMAEL ZUNIGA', 'UROLOGO', '0000-0000', 'USULUTAN'),
	(256, 'DRA. JOHANA ALEXANDRA ORELLANA ARGUETA', 'DRA', '0000-0000', 'SAN MIGUEL'),
	(257, 'LABORATORIO IRIAS LOZANO', 'PATOLOGIA', '0000-0000', '0000'),
	(258, 'DR. BOLIVAR AGUIRRE', 'CIRUJANO PLASTICO', '0000-0000', 'USULUTAN'),
	(259, 'SRTA CLAROS', 'ENF', '0000-0000', 'SSS'),
	(260, 'MELVIN ULISES SANCHEZ ', 'LIC. FISIOTERAPIA', '0000-0000', 'USULUTAN'),
	(261, 'DR. CARLOS JOSE MONTOYA OSEGUEDA', 'MEDICO ORTOPEDA', '7813-7114', 'JIQUILISCO, USULUTAN'),
	(262, 'UNIDAD DE HEMODIALISIS', 'HEMODIALISIS', '0000-0000', '0000'),
	(263, 'DR. JOSE MAURICIO SANDOVAL PANIAGUA', 'MEDICO', '0000-0000', '-'),
	(264, 'DR. NELSON ORLANDO MOLINA AREVALO', 'MEDICO GENERAL', '7439-6340', 'CANTON EL PARAISAL CONCEPCION BATRES USULUTAN.'),
	(265, 'INGRID LISSETTE LOPEZ RODRIGUEZ', 'PEDIATRA', '0000-0000', 'USULUTAN'),
	(266, 'DR. ALBERTO ', 'MEDICO GENERAL', '0000-0000', 'USULUTAN '),
	(267, 'DR. JOSE ADILSON ZELAYA SARAVIA', 'MEDICINA FAMILIAR', '7640-6834', 'SAN JORGE SAN MIGUEL'),
	(268, 'FARMACIA SAN NICOLAS ', '-', '0000-0000', 'USULUTAN'),
	(269, 'SR. MANCIA', 'ENFERMERO', '0000-0000', 'FFF'),
	(270, 'DRA. VALLADARES', 'MEDICINA GENERAL ', '0000-0000', 'USULUTAN '),
	(271, 'LIC. EDGAR ANTONIO GOMEZ FRANCO', 'ANESTESISTA', '0000-0000', 'USULUTAN'),
	(272, 'SRTA. MACHUCA ', 'ENFERMERA', '0000-0000', 'USULUTAN '),
	(273, 'SALVAMEDICA SA DE CV', '00', '0000-0000', '00'),
	(274, 'DR. VICTOR GERARDO ROMERO SALGADO', 'CIRUJANO VASCULAR ', '7459-6330', 'SAN MIGUEL.'),
	(275, 'MARCELA BERRIOS DE GARCIA', 'MEDICO', '0000-0000', 'USULUTAN'),
	(276, 'DR. EMILIO SALVADOR RODRIGUEZ SARAVIA', 'MEDICO GENERAL', '7118-2031', 'EL TRANSITO SAN MIGUEL'),
	(277, 'DR. MIGUEL ANGEL PAZ DIAZ', 'MEDICO GENERAL', '0000-0000', 'EL TRANSITO SAN MIGUEL'),
	(278, 'EVER ROLANDO VASQUEZ VILLALOBOS', '-', '0000-0000', '-'),
	(279, 'DR. SALGUERO', 'MEDICO GENERAL', '0000-0000', 'USULUTAN '),
	(280, 'CARLA PENADO ', 'NUTRICIONISTA ', '0000-0000', 'USULUTAN USULUTAN '),
	(281, 'DR. MARVIN RENE ALBERTO CASTELLON ', 'INTERNISTA ', '0000-0000', 'BARRIO CONCEPCION SANTIAGO DE MARIA USULUTAN'),
	(282, 'DRA. ROXANA AMERICA MELENDEZ ESPINOZA', 'MEDICO PEDIATRA-NEONATOLOGA', '7885-4740', 'USULUTAN'),
	(283, 'Dr. Ever Campos', 'Medico Cirujano', '7132-8713', 'SAN MIGUEL'),
	(284, 'DR. EVER ORLANDO CAMPOS MARAVILLA', 'CIRUJANO GENERAL', '0000-0000', 'USULUTAN'),
	(285, 'TERAPIA BAC LIC. RODRIGO BARRAS', 'ANESTESISTA', '0000-0000', 'USULUTAN '),
	(286, 'Dr. Francisco Lopez Elias', 'Medico Radiologo', '0000-0000', 'usulutan'),
	(287, 'DR. CESAR SALVADOR CONTRERAS MARTINEZ', 'MEDICO GENERAL', '6107-7982', 'RES. SAN ANDRES POL. A CASA 16 SAN MIGUEL.'),
	(288, 'DRA. GLENDA RUBIO ', 'PSIQUIATRIA ', '7701-6056', 'RES. EL SITIO SAN MIGUEL'),
	(289, 'PABLO BATRES', 'MEDICO GENERAL', '0000-0000', 'USULUTAN'),
	(290, 'DR. MEJIA (SAN MARCOS)', 'MEDICO GENERAL', '0000-0000', 'SAN MARCOS '),
	(291, 'DRA. GLADYS MERCEDES DE LA PAZ SAENZ GARAY', 'COLOPROCTOLOGA', '0000-0000', 'USULUTAN'),
	(292, 'DR. GINO DIAZ (ORTOPEDA)', 'MEDICO', '0000-0000', '-'),
	(293, 'DR. ERICK SANTOS OVIEDO', 'CIRUJANO GENERAL', '7797-6114', 'SAN MIGUEL'),
	(294, 'DR. JOSUE ALEXANDER LARIN GARCIA', 'MEDICO GENERAL', '7188-1085', 'USULUTAN'),
	(295, 'DRA. GRISELDA MARISOL CASTELLANOS', 'MEDICO GENERAL', '6111-5404', 'RESIDENCIAL HACIENDA DE LA RIVIERA POLIGONO 8'),
	(296, 'DRA. FABIOLA JOSE ALVARADO ', 'GINECOLOGIA', '7000-8981', 'BARRIO LA MERCED USULUTAN '),
	(297, 'SR. VENTURA', '-', '0000-0000', 'USULUTAN'),
	(298, 'SRTA. HONEYDA MARTINEZ ', 'ENFERMERA ', '0000-0000', 'USULUTAN'),
	(299, 'DRA. MARISSA DIAZ', 'GINECOLOGA', '0000-0000', 'SAN MIGUEL'),
	(300, 'RENE ALEXANDER ALAS COREAS', 'MEDICO GENERAL ', '0000-0000', 'USULUTAN'),
	(301, 'DRA. EDITH ARGENTINA NAVARRETE AMAYA', 'MEDICINA GENERAL ', '0000-0000', 'USULUTAN '),
	(302, 'DRA. ANA ROSA DE RAMOS ', 'MEDICINA GENERAL ', '0000-0000', 'SANTAIGO DE MARIA'),
	(303, 'DRA.MAYRA EVELIN RODRIGUEZ CRUZ', 'GINECOLOGA', '7450-0761', 'USULUTAN'),
	(305, 'DR.RUIZ REYEZ', '.', '0000-0000', '.'),
	(306, 'DRA. SARDIS MEJIA DE MITJAVILA', 'GINECOLOGIA', '0000-0000', 'FF'),
	(307, 'DRA LEIDY  YOANA HERNANDEZ DE CONTRERAS', 'MEDICINA GENERAL', '0000-0000', 'USULUTAN'),
	(308, 'Dr. Ever Policarpo Villatoro Rosa', 'MEDICO', '0000-0000', '-'),
	(309, 'DR. SERGIO WILBER ARGUETA OVIEDO', 'MEDICINA GENERAL', '0000-0000', 'USULUTAN'),
	(310, 'DR. ALEXCIS GILBERTO AVILES ', 'MEDICO GENERAL', '0000-0000', 'SAN MIGUEL '),
	(311, 'DR. PABLO ORLANDO AMAYA CARRANZA', 'MEDICINA GENERAL', '6420-7028', 'BO. CANDELARIA CONCEPCION BATRES, USULUTAN'),
	(312, 'DR. LAZARO VENTURA', 'CIRUJANO GENERAL', '7150-6837', 'JIQUILISCO USULUTAN'),
	(313, 'DR. CARLOS ZELAYA RAMIREZ', 'OTORRINO', '7611-4539', 'USULUTAN'),
	(314, 'DR. William Alexander Romero Monge', 'medico ', '0000-0000', '-'),
	(315, 'DR. GUILLERMO ALCIDES REYES GOMEZ', 'COLOPROCTOLOGO', '0000-0000', 'SAN SALVADOR'),
	(316, 'DR. OSMIN VLADIMIR UMAÑA (NEUROMED)', 'NEUROLOGO', '7118-8054', 'B° SAN FELIPE CTON. FLAMENCO, JOCORO, MORAZAN'),
	(317, 'DR. DOUGLAS GARCIA', 'GENERAL', '0000-0000', '000'),
	(318, 'SRITA. ROSALES', '-', '0000-0000', '-'),
	(319, 'DR. CARLOS MARIO GARCIA (TROCARES)', 'CIRUJANO ', '0000-0000', 'USULUTAN '),
	(320, 'SR. AMAYA', 'ENFERMERIA', '0000-0000', '-'),
	(321, 'SRTA. ANABELA TREJO ', 'ENFERMERA', '0000-0000', 'SANTA ELENA USULUTAN '),
	(322, 'SRITA. KLIDIS QUINTANILLA', 'ENFERMERIA', '0000-0000', '-'),
	(323, 'LICDA. CLAUDIA BEATRIZ HERNANDEZ TORRES', 'NUTRICIONISTA ', '0000-0000', 'USULUTAN'),
	(324, 'GONZALO ERNESTO HERNANDEZ ZAPATA', 'INTERNISTA ', '6306-3803', 'USULUTAN'),
	(325, 'DR. GONZALO ERNESTO HERNANDEZ ZAPATA', 'MEDICINA INTERNA', '6306-3803', '000'),
	(326, 'HECTOR TRINIDAD CARCAMO LAZO ', 'medico', '0000-0000', '-'),
	(327, 'Dra. Karen Elizabeth Menjivar De Hernandez ', 'General', '7490-1590', 'LA PAZ, ZACATECOLUCA.'),
	(328, 'DRA. MARIA DEL CARMEN CARRANZA DE LAINEZ', 'GINECOLOGIA', '7820-2662', 'USULUTAN'),
	(329, 'FARMACIA LOS PINOS', '-', '0000-0000', 'USULUTAN'),
	(330, 'DR RAMON DE JESUS QUINTANILLA', 'GENERAL', '0000-0000', 'USULUTAN'),
	(331, 'DR. JAVIER ALCIDES PERAZA JIMENEZ ', 'MEDICO', '0000-0000', '-'),
	(332, 'Srita. Menjivar', 'ENFERMERIA', '0000-0000', 'Usulutan'),
	(333, 'SRITA. BEATRIZ GONZALEZ', 'ENFERMERIA', '0000-0000', 'USULUTAN'),
	(334, 'SRITA. KARLA GAITAN', 'ENFERMERIA', '0000-0000', 'USULUTAN'),
	(335, 'SRITA. CRISTINA FUNES', 'ENFERMERIA', '', 'USULUTAN'),
	(336, 'SRTA. HILDA ROMERO', 'TECNICA EN ENFERMERIA ', '0000-0000', 'USULUTAN'),
	(337, 'SRITA. SAIRA RODAS', 'ENFERMERIA', '0000-0000', 'USULUTAN'),
	(338, 'LIC. SAUDY CAMPOS', 'ENFERMERIA', '0000-0000', 'USULUTAN'),
	(339, 'DR. TITO HERNAN GAMEZ DURAN', 'CIRUJANO GENERAL', '7815-7333', 'USULUTAN'),
	(340, 'Dr. Rodolfo Alfredo Mendez Flores', 'MEDICO', '0000-0000', '-'),
	(341, 'DR. JOSE MILTON GUARDADO ', 'FISIATRA ', '0000-0000', 'SAN MIGUEL '),
	(342, 'DR. MILTON VLADIMIR  GARCIA PINEDA ', 'CIRUJANO GENERAL', '0000-0000', 'USULUTAN'),
	(343, 'DRA CRUZ LOPEZ', 'GASTROENTEROLOGA ', '0000-0000', 'USULUTAN'),
	(344, 'LICDA. HEYMI AGUIRRE', 'FISIOTERAPISTA', '7044-1365', ' 11'),
	(345, 'DRA. MARIA LUISA CASTILLO LÓPEZ', 'ONCOLOGA', '7646-8031', 'SAN SALVADOR '),
	(346, 'LIC. MILAGRO CASTRO(fisioterapia) ', 'FISIOTERAPIA', '0000-0000', 'USULUTAN'),
	(347, 'DR. KERIN OMAR RAMOS MARTINEZ', 'GINECOLOGIA', '2678-3031', 'SANTIAGO DE MARÍA USULUTAN'),
	(348, 'DR. LUIS ALFREDO PINTO RODRIGUEZ', 'ORTOPEDIA', '0000-0000', 'USULUTAN'),
	(349, 'SRTA. MIRIAN PINEDA', 'ENFERMERA', '0000-0000', 'USULUTAN'),
	(350, 'DRA. KAREN LINDA ROSA MOREIRA', 'GENERAL', '0000-0000', '.'),
	(351, 'DR. OSCAR ALEXANDER MONDRAGON', 'CIRUJANO ', '6136-7836', 'USULUTAN'),
	(352, 'Lic. Jose Manuel Flores Chicas', 'ANESTESISTA', '0000-0000', 'USULUTAN'),
	(353, 'DR. EDUARDO MONTALVO', 'GENERAL', '0000-0000', 'LA UNION'),
	(354, 'DR. EDGARDO ENRIQUE AQUINO SANCHEZ', 'ORTOPEDA', '7883-7400', 'SAN SALVADOR'),
	(355, 'DR. MAURICIO ANTONIO CORTEZ HERNANDEZ', 'MEDICINA GENERAL ', '0000-0000', 'USULUTAN'),
	(356, 'Srta. Estefani Melara', 'Enfermera', '0000-0000', 'jiquilisco usulutan'),
	(357, 'CLAUDIA ISABEL IGLESIAS DE IBAÑEZ', 'GINECOLOGIA', '7459-5985', 'USULUTAN'),
	(358, 'LIC. KATIA INTERIANO ', 'ENFERMERA', '0000-0000', 'USULUTAN'),
	(359, 'SRTA. DORIS ISAMAR CORTEZ', 'ENFERMERA', '0000-0000', 'USULUTAN'),
	(360, 'LICDA. SARAI NORIO(FISIOTERAPIA)', 'FISIATRA', '0000-0000', 'USULUTAN'),
	(361, 'DR. JUAN PABLO SORTO MANZANO', 'GENERAL', '0000-0000', 'USULUTAN'),
	(362, 'DR. RICARDO JOSE UMANZOR', 'MEDICO GENERAL', '0000-0000', 'USULUTAN'),
	(363, 'DR. JOSE GUILLERMO CENTENO NOLASCO', 'MEDICO CIRUJANO', '7730-6777', 'EL TRANSITO SAN MIGUEL '),
	(364, 'DR. ROBERTO JOSE MORAN VIDES', 'MEDICINA GENERAL', '0000-0000', 'USULUTAN'),
	(365, 'DR. MARVIN ROBERTO MENDOZA CAMPOS', 'MEDICINA GENERAL', '2201-3489', 'SAN MIGUEL'),
	(366, 'SRTA. ASTRID MARAVILLA', 'ENFERMERIA', '0000-0000', 'USULUTAN'),
	(367, 'Dr. Jose Orlando Vallecillos Romero', 'GINECOLOGIA', '0000-0000', 'USULUTAN'),
	(368, 'DR. ATILIO VARGAS TREJO', 'MEDICINA GENERAL', '0000-0000', 'USULUTAN'),
	(369, 'DR. JOSE RAMON CENTENO CASTRO', 'MEDICINA GENERAL', '7140-7638', 'USULUTAN, USULUTAN'),
	(370, 'Dr. Ramael Cordova Alvarado', 'Medicina General', '7737-2171', 'El Transito, San Miguel.'),
	(371, 'Joseline Patricia Villalta de Martinez', 'Medico General ', '0000-0000', '-'),
	(372, 'Cesar David Castillo ', 'medico ', '0000-0000', '-'),
	(373, 'Gustavo Enrique Luna Cortez', 'medico', '0000-0000', 'Usulutan '),
	(374, 'DR. CARLOS MARIO AREVALO TORRES', 'PEDIATRA', '0000-0000', 'USULUTAN'),
	(375, 'DRA. KAREN ULLOA', 'MEDICO GENERAL', '0000-0000', 'USULUTAN '),
	(376, 'DR. JEANCARLOS RIVERA', 'MEDICINA GENERAL', '0000-0000', '.'),
	(377, 'REYNALDO ALEXANDER ALVARENGA GOMEZ', 'Gastroenterologo', '0000-0000', 'san miguel'),
	(378, 'DR. NELSON CALDERON ANDRADE', 'MEDICINA GENERAL', '7742-3829', 'LA UNIÓN'),
	(379, 'RUTH GERARDINA FLORES PAREDES', 'GINECOLOGA', '0000-0000', 'USULUTAN '),
	(380, 'DR. RAFAEL JEOVANNY GUEVARA VANEGAS', 'MEDICINA GENERAL', '7140-4018', 'USULUTAN'),
	(381, 'DRA. CLAUDIA JOSEFINA CRUZ AGUILERA', 'GINECOLOGA', '0000-0000', 'USULUTAN '),
	(382, 'DRA. INGRID LISETH LOPEZ DE CASTRO', 'MEDICO PEDIATRA', '7165-0670', 'HACIENDAS DE LA REVIERA, USULUTÁN'),
	(383, 'DR. MANUEL ALBERTO LOPEZ MORALES', 'OFTALMOLOGO ', '0000-0000', '-'),
	(384, 'DR. JESUS ALBERTO AVILES SOTO', 'MEDICO GENERAL', '6026-3486', 'BARRIO EL CALVARIO SAN RAFAEL ORIENTE '),
	(385, 'SRTA. CLAUDIA CRISTABEL CENTENO VENTURA', 'TECNICO EN ENFERMERIA', '0000-0000', 'USULUTAN'),
	(386, 'SRTA. TATIANA ELIZABETH MARTINEZ ISMAEL ', 'ENFERMERA', '0000-0000', 'USULUTAN'),
	(387, 'DR. CRISTIAN EDGARDO MELARA MELENDEZ', 'MEDICO GENERAL', '0000-0000', 'USULUTAN '),
	(388, 'JUAN CARLOS OSORIO PEREZ', 'ORTOPEDIA', '0000-0000', '-'),
	(389, 'LIGIA HERNANDEZ ', 'MEDICO GENERAL', '0000-0000', 'USULUTAN'),
	(390, 'DR. ELEAZAR ANTONIO NUÑEZ', 'MEDICINA GENERAL', '7724-0302', 'SAN MIGUEL'),
	(391, 'DRA. EDITH MARGARITA MORALES ARGUETA', 'MEDICO CIRUJANO', '0000-0000', 'USULUTAN'),
	(392, 'SRTA. DIANA CISNEROS', 'ENFERMERA', '0000-0000', '-'),
	(393, 'DR. FRANCISCO MELVIN GONZALEZ YANEZ', 'MEDICO GINECOLOGO', '7871-7180', 'BARRIO EL CALVARIO USULUTAN'),
	(394, 'DR. JUAN CARLOS HERNANDEZ ', 'MEDICO GENERAL', '0000-0000', 'USULUTAN '),
	(395, 'DR. JOSE LOPEZ (DERMATOLOGO)', 'DERMATOLOGO ', '0000-0000', '.'),
	(396, 'DR. JUAREZ ', 'MEDICO GENERAL', '7210-0665', 'USULUTAN'),
	(397, 'DR. MIGUEL NICOLAS MERINO HERNANDEZ', 'PEDIATRA', '7495-5183', 'PRIMERA AVENIDA  Y PRIMER CALLE PONIENTE #6  JIQUILISCO USULUTAN '),
	(398, 'DRA. AIDA ISABEL PARADA CAMPOS ', 'GASTROENTEROLOGA ', '2660-5502', 'BARRIO LA MERCED A 40 MTS DE PARQUEO TORRE  LA PAZ SAN MIGUEL '),
	(399, 'DR. VLADIMIR AREVALO DIAZ ', 'MEDICO GENERAL', '0000-0000', 'MERCEDES UMAÑA'),
	(400, 'DRA. ALBA LUZ LEMUS', 'GINECOLOGA ', '0000-0000', 'USULUTAN'),
	(401, 'SRTA. FATIMA BRISEYDA HERNANDEZ GARCIA', 'TECNICO EN ENFERMERIA', '0000-0000', 'USULUTAN'),
	(402, 'DR. VICTOR HUGO  LOPEZ', 'MEDICO NEUMOLOGO', '0000-0000', 'USULUTAN'),
	(403, 'LIC. MIRANDA', 'ENFERMERO', '0000-0000', 'USULUTAN'),
	(404, 'DR. LUIS JOSE LOPEZ HERNANDEZ', 'MEDICO DERMATOLOGO', '0000-0000', 'USULUTAN'),
	(405, 'DRA. GRANADOS ', '---------', '0000-0000', '---'),
	(406, 'JORGE ALBERTO MEDRANO SORTO', 'MEDICO GENERAL', '0000-0000', 'USULUTAN'),
	(407, 'DR. EDWARD RAFAEL RIVERA NAVAS', 'UROLOGIA', '7150-9374', 'USULUTAN'),
	(408, 'SRTA. SINDI CAROLINA CORTEZ FLORES', 'ENFERMERA', '0000-0000', 'USULUTAN'),
	(409, 'SRTA. IRIS MONJARAS', 'ENFERMERA', '0000-0000', '-'),
	(410, 'DR. MERLIN VILLATORO', 'MEDICINA ONCOLOGICA', '0000-0000', 'USULUTAN'),
	(411, 'LIC. LILIANA ROMERO ', 'FISIOTERAPIA RESPIRATORIA', '0000-0000', 'USULUTAN'),
	(412, 'LIC. LILIANA ROMERO', 'FISIOTERAPIA RESPIRATORIA', '0000-0000', 'USULUTAN '),
	(413, 'DR. CARLUIS FLORES ', 'MEDICO GENERAL', '7459-2532', 'USULUTAN'),
	(414, 'DR. RONALD ARNOLDO PORTILLO CLAROS', 'MEDICO GENERAL', '7450-8990', 'QUINTA CALLE ORIENTE #12 BARRIO EL CALVARIO USULUTAN '),
	(415, 'DR. MARVIN EVELIO CLAROS HERNANDEZ ', 'HEMATOLOGO', '0000-0000', 'SAN SALVADOR'),
	(416, 'DR. CARLOS ELIAS ORELLANA DIAZ ', 'MEDICO GENERAL', '0000-0000', 'SAN GERARDO '),
	(417, 'DRA. SANDRA BEATRIZ SANCHEZ GUZMAN ', 'MEDICO GINECOLOGA', '0000-0000', 'USULUTAN'),
	(418, 'DR. KELVIN VLADIMIR PORTILLO', 'MEDICO GENERAL', '0000-0000', 'USULUTAN'),
	(419, 'LICDA. SALMERON (FISIOTERAPIA)', 'FISIOTERAPEUTA', '0000-0000', 'USULUTAN'),
	(420, 'DR. MARIO PAZ GUEVARA', 'MEDICINA GENERAL', '0000-0000', 'SAN MIGUEL'),
	(421, 'DR. JOSE WALTER PAIZ JURADO', 'MEDICO GENERAL', '7150-6684', 'USULUTAN'),
	(422, 'DR. MARCO MONTENEGRO', 'MEDICO GENERAL', '0000-0000', 'USULUTAN'),
	(423, 'DR. REYES MONICO', 'MEDICO GENERAL', '0000-0000', '-'),
	(424, 'LICDA. LIZET RAMIREZ MOLINA', 'PSICOLOGA', '0000-0000', '-'),
	(425, 'DRA. EMPERATRIZ EUGENIA LOPEZ MORALES', '-', '7745-9299', '-'),
	(426, 'DR. JULIO REYES', '-', '0000-0000', '-'),
	(427, 'DR. CESAR DOUGLAS GARCIA RODRIGUEZ', '-', '7210-3477', 'CHINAMECA, SAN MIGUEL'),
	(428, 'SRTA. VALLADARES', '-', '0000-0000', '-'),
	(429, 'DR. SERGIO ARMANDO RODRIGUEZ VILLALTA', 'PEDIATRA', '7160-6967', 'JIQUILISCO USULUTAN'),
	(430, 'DR. ROSEMBER BENITEZ ', 'MEDICO GENERAL', '0000-0000', 'INTIPUCÁ'),
	(431, 'DRA. KATIA CAMPOS', 'MEDICO GENERAL', '0000-0000', '-'),
	(432, 'ROSALVO FRANCISCO VELASQUEZ BRUNO', 'MEDICINA GENERAL', '0000-0000', 'N/A'),
	(433, 'DR. WILSON MEJIA', 'DR. WILSON MEJIA', '0000-0000', 'SAN MIGUEL'),
	(434, 'DRA. KATY MARIELA CHAVEZ BATRES', 'PEDIATRA', '0000-0000', '-'),
	(435, 'LIC. MIGUEL HIDALGO', 'LIC EN ENFERMERIA', '0000-0000', '-'),
	(436, 'ALBA NYDIA BRAN ', 'MEDICO GENERAL', '0000-0000', '6 CL OTE #8'),
	(437, 'LICDA. JOSSELYN ESTEFANI PARADA SORTO ', 'PSICÓLOGA ', '0000-0000', 'USULUTAN '),
	(438, 'DR. JOSE FLAVIO JIMENEZ SANTOS', 'MEDICINA FAMILIAR ', '7287-5949', '-'),
	(439, 'DR. EDWIN ANTONIO MONTOYA CORDOVA (UROLOGO)', 'UROLOGO', '0000-0000', '.'),
	(440, 'DR. HENRY DANILO (MAXILOFACIAL). ', 'MAXILOFACIAL', '0000-0000', 'USULUTAN '),
	(441, 'DRA. NURIA IVETH FLORES', 'MEDICO GENERAL', '7949-1621', 'EL TRANSITO, SAN MIGUEL '),
	(442, 'DR. JOSE FERNANDO VILLEGAS NAVARRETE ', 'MEDICO GENERAL', '0000-0000', '-'),
	(443, 'LIC. JOSE MANUEL MARTINEZ', 'LIC LABORATORIO CLINICO', '0000-0000', 'USULUTAN'),
	(444, 'LICDA. KATERIN ALEYDA ALVARADO ', 'ANESTESISTA ', '0000-0000', 'SANTA ELENA, USULUTAN '),
	(445, 'DR. CASTILLO MARTINEZ ', 'MEDICO GENERAL', '0000-0000', 'SAN MIGUEL'),
	(446, 'DRA. VILLATORO', 'NUTRIOLOGA', '0000-0000', 'USULUTAN'),
	(447, 'DR. JORGE FERNANDEZ SERRANO', 'MEDICO GENERAL', '0000-0000', '-'),
	(448, 'LIC. RENE FRANCISCO PERALTA CABRERA', 'ENFERMERO', '0000-0000', '-'),
	(449, 'DRA. DORIAM AMAYA', 'GINECOLOGIA ', '0000-0000', '-'),
	(450, 'DRA. GLADIS DE ALVARADO', 'MEDICINA GENERAL', '0000-0000', 'USULUTAN'),
	(451, 'DRA. BERRIOS', '.', '0000-0000', '-'),
	(452, 'DR. MARVIN EVELIO CLAROS HERNANDEZ ', 'HEMOTALOGIA ', '7450-8016', 'HOSPITAL SAN FRANCISCO '),
	(453, 'DR. SANDOVAL (COLOCACIÓN DE CATÉTER)', 'NEFROLOGO/ INTERNISTA ', '0000-0000', '-'),
	(454, 'DR. ROQUE LUIS LOPEZ ', '-', '0000-0000', '-'),
	(455, 'DRA. VERONICA VASQUEZ IGLESIAS', 'MEDICO GENERAL', '7185-9865', '-'),
	(456, 'DRA. SUSAN SALMERON DE QUINTANILLA', 'MEDICO GENERAL', '7819-7764', '-'),
	(457, 'CENTRO MEDICO LA FAMILIA', 'MEDICINA GENERAL', '0000-0000', 'USULUTAN'),
	(458, 'EDWIN CORTEZ ARTIGA', 'MEDICO INTERNISTA', '6984-9401', 'JIQUILISCO USULUTAN'),
	(459, 'DR JOVEL ALVARADO', 'MEDICINA GENERAL', '0000-0000', 'USULUTAN'),
	(460, 'Dr. VILLATORO GONZALEZ', 'MEDICO ONCOLOGO', '0000-0000', 'SAN MIGUEL'),
	(461, 'DR. EVER ALEXANDER HENRIQUEZ', 'MEDICINA GENERAL', '0000-0000', 'SAN MIGUEL'),
	(462, 'DR. JOHNY HENRY ESCOBAR', 'MEDICO GENERAL', '7930-8309', '-'),
	(463, 'DRA. KARLA ROCABRUNA', 'MEDICO GENERAL', '0000-0000', '-'),
	(464, 'DR. JORGE ADALBERTO CASTRO LOPEZ', 'MEDICO GENERAL', '0000-0000', '-'),
	(465, 'DR. NAPOLEON AVENDAÑO CHACON', 'CIRUJANO DE COLUMNA ', '0000-0000', 'USULUTAN'),
	(466, 'SISTEMA BIOMEDICOS', '-', '0000-0000', 'SS'),
	(467, 'DR. ENRIQUE OVIDIO VILLATORO PAZ', 'MEDICO GENERAL', '0000-0000', 'SANTA ROSA'),
	(468, 'LICDA. BLANCA STHEPHANIA APARICIO MONTANO', 'FISIOTERAPIA ', '7763-2399', 'USULUTAN'),
	(469, 'Dr. GERMAN RENE CAMPOS LOPEZ', 'MEDICINA GENERAL', '0000-0000', 'USULUTAN'),
	(470, 'RONAL CASTILLO', 'MEDICINA GENERAL', '0000-0000', 'USULUTAN'),
	(471, 'DR. MENDOZA (RADIOLOGO )', 'RADIOLOGO', '0000-0000', 'SAN MIGUEL'),
	(472, 'DRA. PATRICIA MATILDE AREVALO BARRERA ', 'MEDICO GENERAL', '6304-2577', 'BARRIO ANALCO SANTA ELENA '),
	(473, 'DR. JULIO ERNESTO MANCIA', 'MEDICINA GENERAL', '0000-0000', '-'),
	(474, 'DR. ISAAC RODRIGUEZ', 'MEDICO GENERAL', '0000-0000', '-'),
	(475, 'DR. SANTOS ALEXIS BENITEZ HERNANDEZ', 'MEDICO', '0000-0000', 'SAN MIGUEL'),
	(476, 'DRA. YANIRA ELIZABETH CORTEZ DE VALLECILLOS', '-', '0000-0000', '-'),
	(477, 'ARBELIS KRISSEL ARGUETA DE PARADA ', 'INTERNISTA ', '0000-0000', 'USULUTAN'),
	(478, 'DRA. YANIRA  CORTEZ', ' ENDOCRINOLOGA', '7667-1115', 'USULUTAN'),
	(479, 'DRA. PAULA JANETTE ALICIA HERNANDEZ LARIOS', 'MEDICO GENERAL', '0000-0000', 'ESTANZUELAS'),
	(480, 'EDWARD ESAU SEGOVIA ALVARADO', 'PEDIATRA', '7644-8501', 'COL SANTA CRISTINA CALLE EL ROSAL BLOCK D  NUMERO 3'),
	(481, 'Brenda Johana Bonilla', 'Lic.', '0000-0000', 'Usulutan'),
	(482, 'DR. VICTOR MANUEL MARAVILLA MACHADO', 'MEDICO', '0000-0000', 'USULUTAN '),
	(483, 'DRA. ADRIANA CRISTINA AGUILAR SANTOS ', 'GINECÓLOGA ', '0000-0000', 'SAN MIGUEL '),
	(484, 'DR. JAVIER MOLINA VELASQUEZ ', 'OTORRINO ', '0000-0000', 'SAN MIGUEL');

-- Volcando estructura para tabla db_clinica_radiologica.tbl_menu
CREATE TABLE IF NOT EXISTS `tbl_menu` (
  `idMenu` int(11) NOT NULL AUTO_INCREMENT,
  `nombreMenu` varchar(25) NOT NULL,
  `htmlMenu` text NOT NULL,
  `fechaMenu` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idMenu`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_menu: ~3 rows (aproximadamente)
INSERT INTO `tbl_menu` (`idMenu`, `nombreMenu`, `htmlMenu`, `fechaMenu`) VALUES
	(1, 'Pacientes', '<li class="menu-item">\r\n                    <a href="#" class="has-chevron" data-toggle="collapse" data-target="#patient" aria-expanded="false"\r\n                        aria-controls="patient">\r\n                        <span><i class="fas fa-user"></i>Pacientes</span>\r\n                    </a>\r\n                    <ul id="patient" class="collapse" aria-labelledby="patient" data-parent="#side-nav-accordion">\r\n                        <li> <a href="<?php echo base_url(); ?>Consulta/agregar_paciente">Agregar paciente</a> </li>\r\n                        <li> <a href="<?php echo base_url(); ?>Consulta/lista_pacientes">Lista pacientes</a> </li>\r\n                    </ul>\r\n                </li>', '2021-04-29 20:00:15'),
	(2, 'Configuraciòn', '<li class="menu-item">\r\n                    <a href="#" class="has-chevron" data-toggle="collapse" data-target="#configuracion"\r\n                        aria-expanded="false" aria-controls="configuracion">\r\n                        <span><i class="fa fa-cog"></i>Configuración</span>\r\n                    </a>\r\n                    <ul id="configuracion" class="collapse" aria-labelledby="configuracion"\r\n                        data-parent="#side-nav-accordion">\r\n                        <li><a href="<?php echo base_url(); ?>Accesos/">Accesos</a></li>\r\n                        <li><a href="<?php echo base_url(); ?>Usuarios/gestion_usuarios">Usuarios</a></li>\r\n                        <li><a href="<?php echo base_url(); ?>Permisos/">Permisos</a></li>\r\n                    </ul>\r\n                </li>', '2021-04-30 18:20:44'),
	(3, 'Medicos', '<li class="menu-item">\r\n    <a href="#" class="has-chevron" data-toggle="collapse" data-target="#doctor" aria-expanded="false"\r\n        aria-controls="doctor">\r\n        <span><i class="fas fa-stethoscope"></i>Médico</span>\r\n    </a>\r\n    <ul id="doctor" class="collapse" aria-labelledby="doctor" data-parent="#side-nav-accordion">\r\n        <li> <a href="<?php echo base_url(); ?>Medico/">Lista médicos</a> </li>\r\n    </ul>\r\n</li>', '2025-05-02 20:25:40');

-- Volcando estructura para tabla db_clinica_radiologica.tbl_permisos
CREATE TABLE IF NOT EXISTS `tbl_permisos` (
  `idPermiso` int(11) NOT NULL AUTO_INCREMENT,
  `idMenu` int(11) NOT NULL,
  `idAcceso` int(11) NOT NULL,
  `estadoPermiso` int(11) NOT NULL,
  `fechaPermiso` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idPermiso`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_permisos: ~2 rows (aproximadamente)
INSERT INTO `tbl_permisos` (`idPermiso`, `idMenu`, `idAcceso`, `estadoPermiso`, `fechaPermiso`) VALUES
	(1, 1, 1, 1, '2021-04-29 20:41:05'),
	(2, 2, 1, 1, '2021-04-30 19:48:47'),
	(131, 3, 1, 1, '2025-05-02 20:26:29');

-- Volcando estructura para tabla db_clinica_radiologica.tbl_tipo_examenes
CREATE TABLE IF NOT EXISTS `tbl_tipo_examenes` (
  `idTipo` int(11) NOT NULL AUTO_INCREMENT,
  `codigoTipo` varchar(20) DEFAULT NULL,
  `nombreTipo` varchar(100) DEFAULT NULL,
  `creado` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idTipo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_tipo_examenes: ~10 rows (aproximadamente)
INSERT INTO `tbl_tipo_examenes` (`idTipo`, `codigoTipo`, `nombreTipo`, `creado`) VALUES
	(1, '1000', 'SISTEMA OSEO CRANEO', '2025-05-02 19:57:02'),
	(2, '1001', 'TRONCO', '2025-05-02 19:57:02'),
	(3, '1002', 'EXTREMIDADES', '2025-05-02 19:57:02'),
	(4, '1003', 'SISTEMA RESPIRATORIO', '2025-05-02 19:57:02'),
	(5, '1004', 'SISTEMA GASTROINTESTINAL', '2025-05-02 19:57:02'),
	(6, '1005', 'SISTEMA URINARIO', '2025-05-02 19:57:02'),
	(7, '1006', 'ESTUDIOS ESPECIALES', '2025-05-02 19:57:02'),
	(8, '1007', 'MAMOGRAFIAS', '2025-05-02 19:57:02'),
	(9, '1008', 'ULTRASONOGRAFIAS', '2025-05-02 19:57:02'),
	(10, '1009', 'TOMOGRAFIAS', '2025-05-02 19:57:02');

-- Volcando estructura para tabla db_clinica_radiologica.tbl_usuarios
CREATE TABLE IF NOT EXISTS `tbl_usuarios` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombreUsuario` varchar(50) NOT NULL,
  `psUsuario` text NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `idAcceso` int(11) NOT NULL,
  `codigoVerificacion` varchar(50) NOT NULL,
  `nivelUsuario` int(11) NOT NULL DEFAULT 0,
  `estadoUsuario` int(11) NOT NULL DEFAULT 1,
  `pivoteUsuario` int(11) NOT NULL DEFAULT 0,
  `celebrar` int(11) NOT NULL DEFAULT 1,
  `imagen` text NOT NULL,
  `fechaUsuario` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idUsuario`),
  KEY `idEmpleado` (`idEmpleado`),
  KEY `idAcceso` (`idAcceso`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla db_clinica_radiologica.tbl_usuarios: ~0 rows (aproximadamente)
INSERT INTO `tbl_usuarios` (`idUsuario`, `nombreUsuario`, `psUsuario`, `idEmpleado`, `idAcceso`, `codigoVerificacion`, `nivelUsuario`, `estadoUsuario`, `pivoteUsuario`, `celebrar`, `imagen`, `fechaUsuario`) VALUES
	(1, 'Informatica', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, '2f2bb449c2cb83320769d123f0904b5a', 1, 1, 0, 0, 'edwin', '2021-04-29 18:05:52');

-- Volcando estructura para disparador db_clinica_radiologica.tbl_dte_ccf_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `tbl_dte_ccf_after_insert` AFTER INSERT ON `tbl_dte_ccf` FOR EACH ROW BEGIN
	UPDATE tbl_ventas AS v SET v.dteVenta = NEW.numeroDTE,
											  v.notaFactura = 'Crédito fiscal'
	WHERE v.idVenta = NEW.idHoja;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador db_clinica_radiologica.tbl_dte_fc_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `tbl_dte_fc_after_insert` AFTER INSERT ON `tbl_dte_fc` FOR EACH ROW BEGIN
	UPDATE tbl_ventas AS v SET v.dteVenta = NEW.numeroDTE,
											  v.notaFactura = 'Consumidor final'
	WHERE v.idVenta = NEW.idHoja;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador db_clinica_radiologica.tbl_dte_se_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `tbl_dte_se_after_insert` AFTER INSERT ON `tbl_dte_se` FOR EACH ROW BEGIN
	UPDATE tbl_ventas AS v SET v.dteVenta = NEW.numeroDTE,
											  v.notaFactura = 'Sujeto excluido'
	WHERE v.dteVenta = NEW.idHoja;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
