-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para programacionaplicada
DROP DATABASE IF EXISTS `programacionaplicada`;
CREATE DATABASE IF NOT EXISTS `programacionaplicada` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `programacionaplicada`;

-- Volcando estructura para procedimiento programacionaplicada.ActualizarAbogado
DROP PROCEDURE IF EXISTS `ActualizarAbogado`;
DELIMITER //
CREATE PROCEDURE `ActualizarAbogado`(IN `p_id_abogado` INT, IN `p_nombre` VARCHAR(100), IN `p_dni` INT(11), IN `p_email` VARCHAR(100), IN `p_rol` TINYINT(1), IN `p_clave` VARCHAR(255), IN `p_confirmaClave` VARCHAR(255), IN `p_IDToken` VARCHAR(255), IN `p_fecha` DATETIME)
BEGIN
    UPDATE tbl_abogados
    SET nombre = p_nombre,
        dni = p_dni,
        email = p_email,
        rol = p_rol,
        clave = p_clave,
        confirmaClave = p_confirmaClave
    WHERE id_abogado = p_id_abogado;
END//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.actualizar_periodo
DROP PROCEDURE IF EXISTS `actualizar_periodo`;
DELIMITER //
CREATE PROCEDURE `actualizar_periodo`(IN `p_fecha_vigencia` DATE)
BEGIN
    -- Actualizar los periodos cuyo estado sea 'activo' y cuya fecha de vigencia haya expirado
    UPDATE tbl_periodos
    SET estado = 'inactivo'
    WHERE estado = 'activo' 
      AND YEAR(fecha_vigencia) <= YEAR(p_fecha_vigencia);
END//
DELIMITER ;

-- Volcando estructura para evento programacionaplicada.actualizar_periodos_anuales
DROP EVENT IF EXISTS `actualizar_periodos_anuales`;
DELIMITER //
CREATE EVENT `actualizar_periodos_anuales` ON SCHEDULE EVERY 1 MONTH STARTS '2024-11-27 15:04:31' ON COMPLETION PRESERVE DISABLE DO BEGIN
    -- Verificar si algún registro necesita ser actualizado
    UPDATE tbl_periodos SET estado = 'inactivo', fecha_vigencia = CONCAT(YEAR(CURRENT_DATE()), '-12-31') WHERE estado = 'activo' and YEAR(tbl_periodos.fecha_vigencia) <= YEAR(CURRENT_DATE()) ;
END//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.BuscarGabinete
DROP PROCEDURE IF EXISTS `BuscarGabinete`;
DELIMITER //
CREATE PROCEDURE `BuscarGabinete`(IN `p_id_gabinete` INT)
SELECT * FROM `tbl_gabinetes` WHERE id_gabinete = p_id_gabinete//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.ConsultarAbogadoDNI
DROP PROCEDURE IF EXISTS `ConsultarAbogadoDNI`;
DELIMITER //
CREATE PROCEDURE `ConsultarAbogadoDNI`(
	IN `p_dni` INT(11)
)
BEGIN
    SELECT nombre FROM tbl_abogados WHERE dni = p_dni;
END//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.ConsultarAbogadoEmail
DROP PROCEDURE IF EXISTS `ConsultarAbogadoEmail`;
DELIMITER //
CREATE PROCEDURE `ConsultarAbogadoEmail`(IN `p_email` VARCHAR(200))
BEGIN
    SELECT * FROM tbl_abogados
        WHERE email = p_email;
END//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.ConsultarAbogados
DROP PROCEDURE IF EXISTS `ConsultarAbogados`;
DELIMITER //
CREATE PROCEDURE `ConsultarAbogados`()
BEGIN
    SELECT * FROM tbl_abogados;
END//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.ConsultarClave
DROP PROCEDURE IF EXISTS `ConsultarClave`;
DELIMITER //
CREATE PROCEDURE `ConsultarClave`(IN `p_idtoken` VARCHAR(250))
BEGIN
    -- Verifica si el usuario existe y obtiene la clave.
    SELECT clave
    FROM tbl_abogados
    WHERE IDToken = p_idtoken;
END//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.ConsultarGabinete
DROP PROCEDURE IF EXISTS `ConsultarGabinete`;
DELIMITER //
CREATE PROCEDURE `ConsultarGabinete`(IN `p_nombre` VARCHAR(200))
SELECT * FROM tbl_gabinetes WHERE nombre = p_nombre//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.ConsultarGabinetes
DROP PROCEDURE IF EXISTS `ConsultarGabinetes`;
DELIMITER //
CREATE PROCEDURE `ConsultarGabinetes`()
SELECT * FROM tbl_gabinetes//
DELIMITER ;

-- Volcando estructura para evento programacionaplicada.ejecutar_actualizacion_periodos
DROP EVENT IF EXISTS `ejecutar_actualizacion_periodos`;
DELIMITER //
CREATE EVENT `ejecutar_actualizacion_periodos` ON SCHEDULE EVERY 1 MINUTE STARTS '2024-11-27 13:55:00' ON COMPLETION PRESERVE ENABLE DO BEGIN
    -- Declarar la fecha objetivo
    DECLARE fecha_objetivo, fecha_final DATE;
    SET fecha_objetivo = CONCAT(YEAR(CURRENT_DATE()) + 1, '-12-31');
    SET fecha_final = tbl_periodos.fecha_vigencia;
	
    
    -- Verificar si la fecha actual coincide con la fecha objetivo
    IF YEAR(fecha_objetivo) > YEAR(fecha_final) THEN
        -- Llamar al procedimiento con la fecha actual como parámetro
        UPDATE tbl_periodos SET estado = 'inactivo' WHERE estado = 'activo';         
       -- Insertar un nuevo periodo como activo
        INSERT INTO tbl_periodos (id_periodo_pk, nombre_periodo, estado, fecha_vigencia)
        VALUES (NULL, 'Nuevo Periodo', 'activo', fecha_objetivo);
    END IF;
END//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.EliminarAbogado
DROP PROCEDURE IF EXISTS `EliminarAbogado`;
DELIMITER //
CREATE PROCEDURE `EliminarAbogado`(IN `p_id_abogado` INT)
BEGIN
    DELETE FROM tbl_abogados
    WHERE id_abogado = p_id_abogado;
END//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.EliminarGabinete
DROP PROCEDURE IF EXISTS `EliminarGabinete`;
DELIMITER //
CREATE PROCEDURE `EliminarGabinete`(IN `p_id_gabinete` INT)
DELETE FROM `tbl_gabinetes` WHERE id_gabinete = p_id_gabinete//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.Login
DROP PROCEDURE IF EXISTS `Login`;
DELIMITER //
CREATE PROCEDURE `Login`(IN `p_email` VARCHAR(200))
BEGIN
SELECT * FROM tbl_abogados WHERE email = p_email;
END//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.RegistrarAbogado
DROP PROCEDURE IF EXISTS `RegistrarAbogado`;
DELIMITER //
CREATE PROCEDURE `RegistrarAbogado`(IN `p_nombre` VARCHAR(100), IN `p_dni` INT(11), IN `p_email` VARCHAR(100), IN `p_rol` TINYINT(1), IN `p_clave` VARCHAR(255), IN `p_confirmaClave` VARCHAR(255), IN `p_IDToken` VARCHAR(255), IN `p_fecha` DATETIME)
BEGIN
    INSERT INTO tbl_abogados (nombre, dni, email, rol, clave, confirmaClave, IDToken, fecha)
    VALUES (p_nombre, p_dni, p_email, p_rol, p_clave, p_confirmaClave, p_IDToken, p_fecha);
END//
DELIMITER ;

-- Volcando estructura para procedimiento programacionaplicada.RegistrarGabinete
DROP PROCEDURE IF EXISTS `RegistrarGabinete`;
DELIMITER //
CREATE PROCEDURE `RegistrarGabinete`(IN `p_nombre` VARCHAR(200), IN `p_direccion` VARCHAR(100), IN `p_correo` VARCHAR(100), IN `p_telefono` VARCHAR(50))
INSERT INTO `tbl_gabinetes`(`id_gabinete`, `nombre`, `direccion`, `telefono`, `correo`) VALUES (NULL,p_nombre,p_direccion,p_correo,p_telefono)//
DELIMITER ;

-- Volcando estructura para tabla programacionaplicada.tbl_abogados
DROP TABLE IF EXISTS `tbl_abogados`;
CREATE TABLE IF NOT EXISTS `tbl_abogados` (
  `id_abogado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `dni` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `confirmaClave` varchar(255) NOT NULL,
  `IDToken` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_abogado`),
  UNIQUE KEY `dni` (`dni`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla programacionaplicada.tbl_abogados: ~2 rows (aproximadamente)
DELETE FROM `tbl_abogados`;
INSERT INTO `tbl_abogados` (`id_abogado`, `nombre`, `dni`, `email`, `rol`, `clave`, `confirmaClave`, `IDToken`, `fecha`) VALUES
	(15, 'Ericka ', '39', 'ericka@correo.com', '1', '12345', '12345', '1', '2026-06-04 14:49:34'),
	(16, 'Maria', '123', 'maria@correo.com', '3', '$2y$10$shiDYrxVZCZWgas/CcbRluQbIfrvlMFjsL3KIK.9szWbBdTEaHARy', '12345abc', '03adf365fcf0281a50e78c97e24119a2a70e317edbb79b25e4ad777dece065c399c6b90ade2b45314a65f512e277a4d53cc6502a5a5289502e55958053535db6', '2002-07-26 14:46:51');

-- Volcando estructura para tabla programacionaplicada.tbl_gabinetes
DROP TABLE IF EXISTS `tbl_gabinetes`;
CREATE TABLE IF NOT EXISTS `tbl_gabinetes` (
  `id_gabinete` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_gabinete`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla programacionaplicada.tbl_gabinetes: ~3 rows (aproximadamente)
DELETE FROM `tbl_gabinetes`;
INSERT INTO `tbl_gabinetes` (`id_gabinete`, `nombre`, `direccion`, `telefono`, `correo`) VALUES
	(1, 'Tus tramites', 'TGS', '123', 'tustramites@correo.com'),
	(2, 'Tus Tramites 1', 'SPS', '12345', 'tustramites1@correo.com'),
	(3, 'ggggg', 'tgs', 'fgdgfg@fdkfld.com', '1212');

-- Volcando estructura para tabla programacionaplicada.tbl_periodos
DROP TABLE IF EXISTS `tbl_periodos`;
CREATE TABLE IF NOT EXISTS `tbl_periodos` (
  `id_periodo_pk` int NOT NULL AUTO_INCREMENT,
  `nombre_periodo` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `fecha_vigencia` date NOT NULL,
  PRIMARY KEY (`id_periodo_pk`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla programacionaplicada.tbl_periodos: ~1 rows (aproximadamente)
DELETE FROM `tbl_periodos`;
INSERT INTO `tbl_periodos` (`id_periodo_pk`, `nombre_periodo`, `estado`, `fecha_vigencia`) VALUES
	(1, 'Periodo1', 'inactivo', '2024-12-31');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
