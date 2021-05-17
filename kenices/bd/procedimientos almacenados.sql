-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2021 a las 22:46:14
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventariokenices`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_det_tem_compra` (IN `codigo` INT, IN `cantidad` INT, IN `preciocompra` DECIMAL(10,2), IN `token_user` VARCHAR(50))  BEGIN

 
    
INSERT INTO det_tem_compra(token_user,id_producto,cantidad,preciocompra) VALUES(token_user,codigo,cantidad,preciocompra);

SELECT tmp.id_det_compra,tmp.id_producto,p.nombre,tmp.cantidad,tmp.preciocompra FROM det_tem_compra tmp
INNER JOIN productos p
ON tmp.id_producto=p.id_producto
WHERE tmp.token_user=token_user ;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_det_tem_venta` (IN `codigo` INT, IN `cantidad` INT, IN `token_user` VARCHAR(50))  BEGIN
DECLARE precio_actual decimal(10,2);
DECLARE cantidades int;
DECLARE registros bigint;
SELECT precio INTO precio_actual FROM productos WHERE id_producto=codigo;
 
    
INSERT INTO det_tem_venta(token_user,id_producto,cantidad,precioventa) VALUES(token_user,codigo,cantidad,precio_actual);

SELECT tmp.id_det_venta,tmp.id_producto,p.nombre,tmp.cantidad,tmp.precioventa FROM det_tem_venta tmp
INNER JOIN productos p
ON tmp.id_producto=p.id_producto
WHERE tmp.token_user=token_user ;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_det_tem_compra` (IN `id_deta_compra` INT, IN `token` VARCHAR(50))  BEGIN
DELETE FROM det_tem_compra WHERE id_det_compra=id_deta_compra;
SELECT tmp.id_det_compra,tmp.id_producto,p.nombre,tmp.cantidad,tmp.preciocompra FROM det_tem_compra tmp
INNER JOIN productos p
ON tmp.id_producto=p.id_producto
WHERE tmp.token_user=token_user;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_det_tem_venta` (IN `id_deta_venta` INT, IN `token` VARCHAR(50))  BEGIN
DELETE FROM det_tem_venta WHERE id_det_venta=id_deta_venta;
SELECT tmp.id_det_venta,tmp.id_producto,p.nombre,tmp.cantidad,tmp.precioventa FROM det_tem_venta tmp
INNER JOIN productos p
ON tmp.id_producto=p.id_producto
WHERE tmp.token_user=token_user;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_compra` (IN `id_usuario` INT, IN `token` VARCHAR(50))  BEGIN
	DECLARE compra int;
    DECLARE registros int;
    DECLARE ingreso int;
    DECLARE egreso int;
    DECLARE stocka int;
    DECLARE stockac int;
    DECLARE Movimiento varchar(50);
    DECLARE tmp_id_producto int;
    DECLARE tmp_cant_producto int;
    DECLARE a int;
    DECLARE total DECIMAL (10,2);
     DECLARE id_productos int;
   
    SET a=1;
    SET egreso=0;
    SET Movimiento='Compra';
   SET id_productos =(SELECT id_producto FROM det_tem_compra);
  
    CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
    		id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        	id_producto BIGINT,
        	cant_prod int
    		);
    SET registros=(SELECT COUNT(*) FROM det_tem_compra WHERE token_user =token  );
   

    IF registros>0 THEN
      
    	INSERT INTO tbl_tmp_tokenuser(id_producto,cant_prod) SELECT id_producto, cantidad FROM det_tem_compra WHERE token_user =token ;
        
        INSERT INTO compra(id_usuario) VALUES(id_usuario);
        SET compra =LAST_INSERT_ID();
        INSERT INTO det_compra(id_compra,id_producto, cantidad,totalcompra) SELECT (compra) as id_compra, id_producto,SUM(cantidad) as cantidad,SUM(preciocompra*cantidad) as preciocompra FROM det_tem_compra WHERE token_user=token GROUP BY id_producto;

        WHILE a <= registros DO
        SELECT id_producto,cant_prod INTO tmp_id_producto, tmp_cant_producto FROM tbl_tmp_tokenuser WHERE  id=a;
        SELECT stock INTO stocka FROM productos WHERE id_producto=tmp_id_producto;
        SET ingreso=tmp_cant_producto;
        SET stockac=stocka+ingreso-egreso;
       UPDATE productos SET stock=stockac WHERE id_producto=tmp_id_producto;
        SET a=a+1;
       
         INSERT INTO det_kardex (id_producto,id_compra,id_usuario,id_det_compra,Movimiento,stock_inicial,ingreso,egreso,stock_actual) VALUES (id_productos,compra,id_usuario,id_det_compra,Movimiento,stocka,ingreso,egreso,stockac);
        
      
        END WHILE;
 		
       
        
        
        SET total=(SELECT SUM(cantidad*preciocompra) FROM det_tem_compra WHERE token_user=token);
        UPDATE compra set totalcompra=total WHERE id_compra=compra ;
         
        DELETE FROM det_tem_compra WHERE token_user =token;
        TRUNCATE TABLE tbl_tmp_tokenuser;
        SELECT * FROM compra WHERE id_compra=compra;
    ELSE
    	SELECT 0;
            
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (IN `id_usuario` INT, IN `id_cliente` INT, IN `token` VARCHAR(50))  BEGIN
	DECLARE venta int;
    DECLARE registros int;
    DECLARE ingreso int;
    DECLARE egreso int;
    DECLARE stocka int;
    DECLARE stockac int;
    DECLARE Movimiento varchar(50);
    DECLARE tmp_id_producto int;
    DECLARE tmp_cant_producto int;
    DECLARE a int;
    DECLARE total DECIMAL (10,2);
     DECLARE id_productos int;
    DECLARE id_det_ventas int;
    SET a=1;
    SET ingreso=0;
    SET Movimiento='Venta';
  SET id_productos =(SELECT id_producto FROM det_tem_venta);
  
    CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
    		id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        	id_producto BIGINT,
        	cant_prod int);
    SET registros=(SELECT COUNT(*) FROM det_tem_venta WHERE token_user =token  );
   

    IF registros>0 THEN
      
    	INSERT INTO tbl_tmp_tokenuser(id_producto,cant_prod) SELECT id_producto, cantidad FROM det_tem_venta WHERE token_user =token ;
        
        INSERT INTO venta(id_usuario,id_cliente) VALUES(id_usuario,id_cliente);
        SET venta =LAST_INSERT_ID();
        INSERT INTO det_venta(id_venta,id_producto, cantidad,totalventa) SELECT (venta) as id_venta, id_producto,SUM(cantidad) as cantidad,SUM(precioventa*cantidad) as precioventa FROM det_tem_venta WHERE token_user=token GROUP BY id_producto;

        WHILE a <= registros DO
        SELECT id_producto,cant_prod INTO tmp_id_producto, tmp_cant_producto FROM tbl_tmp_tokenuser WHERE  id=a;
        SELECT stock INTO stocka FROM productos WHERE id_producto=tmp_id_producto;
        SET egreso=tmp_cant_producto;
        SET stockac=stocka+ingreso-egreso;
       UPDATE productos SET stock=stockac WHERE id_producto=tmp_id_producto;
        SET a=a+1;
       
         INSERT INTO det_kardex (id_producto,id_venta,id_usuario,id_det_venta,Movimiento,stock_inicial,ingreso,egreso,stock_actual) VALUES (id_productos,venta,id_usuario,id_det_ventas,Movimiento,stocka,ingreso,egreso,stockac);
        
      
        END WHILE;
 		
       
        
        
        SET total=(SELECT SUM(cantidad*precioventa) FROM det_tem_venta WHERE token_user=token);
        UPDATE venta set totalventa=total WHERE id_venta=venta ;
         
        DELETE FROM det_tem_venta WHERE token_user =token;
        TRUNCATE TABLE tbl_tmp_tokenuser;
        SELECT * FROM venta WHERE id_venta=venta;
    ELSE
    	SELECT 0;
            
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(15) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`, `estado`) VALUES
(1, 'Chocolates', 'ds', b'1'),
(2, 'Almendras', '', b'1'),
(3, 'mazorca', '', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(15) NOT NULL,
  `id_usuario` int(15) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellido` varchar(200) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `telefono` varchar(200) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `id_usuario`, `cedula`, `nombre`, `apellido`, `direccion`, `email`, `telefono`, `fecha`, `estado`) VALUES
(1, 1, '432', 'Liliana Maria', 'Garzón', 'Cll39a#73c-43sur', 'ya@gmail.com', '88245223423', '2021-04-23', b'1'),
(2, 1, '999', 'Maria Paula', 'Blanco', 'Cll39a#73c-43', 'yuliandrea_p@hotmail.com', '8824522342', '2021-04-23', b'1'),
(3, 5, '444', 'Yeimy', 'Sanchez', 'Cll39a#73c-43sur', 'yuliuzuheart@outlook.com', '23423423423', '2021-05-01', b'1'),
(7, 5, '66546', 'Lian', 'Blanco', 'Cll39a#73c-43sur', 'pru@gmail.com', '23423423423', '2021-05-01', b'1'),
(9, 1, '3242', 'mari', 'Linares', 'cll35a', 'lizeth@gmail.com', '2423423', '2021-05-08', b'1'),
(10, 1, '32', 'mari', 'Linares', 'cll35a', 'lizeth@gmail.com', '2423423', '2021-05-08', b'1'),
(11, 1, '4536', 'sdf', 'fsd', 'ds', 'ya@gmail.com', 'fsd', '2021-05-08', b'1'),
(12, 1, '654', 'fada', 'gtre', 'cfsw', 'li@gmail.com', '2342', '2021-05-08', b'1'),
(13, 1, '435', 'mari', 'Linares', 'cll35a', 'li@gmail.com', '2423423', '2021-05-09', b'1'),
(14, 1, '5423', 'fdvs', 'fsd', 'Cll39a#73c-43', 'leslypsanchezg@gmail.com', '8824522342', '2021-05-11', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id_compra` bigint(15) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(15) DEFAULT NULL,
  `totalcompra` decimal(10,2) NOT NULL,
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id_compra`, `fecha`, `id_usuario`, `totalcompra`, `estado`) VALUES
(1, '2021-05-15 20:25:35', 1, '1.00', b'1'),
(2, '2021-05-15 21:17:51', 1, '4.00', b'1'),
(3, '2021-05-15 21:20:19', 1, '3.00', b'1'),
(4, '2021-05-16 00:53:21', 1, '30.00', b'1'),
(5, '2021-05-16 02:16:56', 1, '10.00', b'1'),
(6, '2021-05-16 02:25:39', 1, '10.00', b'1'),
(7, '2021-05-16 02:35:06', 1, '10.00', b'1'),
(8, '2021-05-16 02:46:25', 1, '10.00', b'1'),
(9, '2021-05-16 02:56:05', 1, '50.00', b'1'),
(10, '2021-05-16 10:41:21', 1, '60.00', b'1'),
(11, '2021-05-16 11:25:45', 1, '80.00', b'1'),
(12, '2021-05-16 12:52:13', 1, '20.00', b'1'),
(13, '2021-05-16 12:53:29', 1, '10.00', b'1'),
(14, '2021-05-16 12:53:47', 1, '1.00', b'1'),
(15, '2021-05-16 12:56:48', 1, '1.00', b'1'),
(16, '2021-05-16 12:57:44', 1, '1.00', b'1'),
(17, '2021-05-16 12:58:35', 1, '1.00', b'1'),
(18, '2021-05-16 12:59:11', 1, '1.00', b'1'),
(19, '2021-05-16 13:00:12', 1, '1.00', b'1'),
(20, '2021-05-16 13:02:12', 1, '1.00', b'1'),
(21, '2021-05-16 13:02:43', 1, '1.00', b'1'),
(22, '2021-05-16 13:03:13', 1, '1.00', b'1'),
(23, '2021-05-16 13:04:11', 1, '1.00', b'1'),
(24, '2021-05-16 13:07:45', 1, '1.00', b'1'),
(25, '2021-05-16 13:08:11', 1, '1.00', b'1'),
(26, '2021-05-16 13:09:25', 1, '1.00', b'1'),
(27, '2021-05-16 13:10:12', 1, '1.00', b'1'),
(28, '2021-05-16 13:12:12', 1, '1.00', b'1'),
(29, '2021-05-16 13:13:47', 1, '1.00', b'1'),
(30, '2021-05-16 13:14:38', 1, '1.00', b'1'),
(31, '2021-05-16 13:16:35', 1, '1.00', b'1'),
(32, '2021-05-16 13:17:59', 1, '1.00', b'1'),
(33, '2021-05-16 13:22:34', 1, '1.00', b'1'),
(34, '2021-05-16 13:26:34', 1, '1.00', b'1'),
(35, '2021-05-16 13:27:58', 1, '1.00', b'1'),
(36, '2021-05-16 13:28:16', 1, '1.00', b'1'),
(37, '2021-05-17 11:13:50', 14, '1.00', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `det_compra`
--

CREATE TABLE `det_compra` (
  `id_det_compra` bigint(15) NOT NULL,
  `id_compra` bigint(15) DEFAULT NULL,
  `id_producto` int(15) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `totalcompra` decimal(10,2) NOT NULL,
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `det_compra`
--

INSERT INTO `det_compra` (`id_det_compra`, `id_compra`, `id_producto`, `cantidad`, `totalcompra`, `estado`) VALUES
(1, 1, 14, 1, '1.00', b'1'),
(2, 2, 15, 4, '4.00', b'1'),
(3, 3, 14, 3, '3.00', b'1'),
(4, 4, 14, 30, '30.00', b'1'),
(5, 5, 15, 10, '10.00', b'1'),
(6, 6, 15, 10, '10.00', b'1'),
(7, 7, 15, 10, '10.00', b'1'),
(8, 8, 15, 10, '10.00', b'1'),
(9, 9, 19, 50, '50.00', b'1'),
(10, 10, 11, 60, '60.00', b'1'),
(11, 11, 12, 80, '80.00', b'1'),
(12, 12, 11, 20, '20.00', b'1'),
(13, 13, 13, 10, '10.00', b'1'),
(14, 14, 13, 1, '1.00', b'1'),
(15, 15, 13, 1, '1.00', b'1'),
(16, 16, 13, 1, '1.00', b'1'),
(17, 17, 13, 1, '1.00', b'1'),
(18, 18, 13, 1, '1.00', b'1'),
(19, 19, 13, 1, '1.00', b'1'),
(20, 20, 13, 1, '1.00', b'1'),
(21, 21, 13, 1, '1.00', b'1'),
(22, 22, 13, 1, '1.00', b'1'),
(23, 23, 13, 1, '1.00', b'1'),
(24, 24, 13, 1, '1.00', b'1'),
(25, 25, 13, 1, '1.00', b'1'),
(26, 26, 13, 1, '1.00', b'1'),
(27, 27, 13, 1, '1.00', b'1'),
(28, 28, 13, 1, '1.00', b'1'),
(29, 29, 13, 1, '1.00', b'1'),
(30, 30, 13, 1, '1.00', b'1'),
(31, 31, 14, 1, '1.00', b'1'),
(32, 32, 14, 1, '1.00', b'1'),
(33, 33, 14, 1, '1.00', b'1'),
(34, 34, 14, 1, '1.00', b'1'),
(35, 35, 14, 1, '1.00', b'1'),
(36, 36, 14, 1, '1.00', b'1'),
(37, 37, 15, 1, '1.00', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `det_kardex`
--

CREATE TABLE `det_kardex` (
  `id_kardex` bigint(15) NOT NULL,
  `id_producto` int(15) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `id_venta` bigint(15) DEFAULT NULL,
  `id_compra` bigint(15) DEFAULT NULL,
  `id_usuario` int(15) DEFAULT NULL,
  `id_det_compra` bigint(15) DEFAULT NULL,
  `id_det_venta` bigint(15) DEFAULT NULL,
  `Movimiento` varchar(50) DEFAULT NULL,
  `stock_inicial` int(11) DEFAULT NULL,
  `ingreso` int(11) NOT NULL DEFAULT 0,
  `egreso` int(11) NOT NULL DEFAULT 0,
  `stock_actual` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `det_kardex`
--

INSERT INTO `det_kardex` (`id_kardex`, `id_producto`, `fecha`, `id_venta`, `id_compra`, `id_usuario`, `id_det_compra`, `id_det_venta`, `Movimiento`, `stock_inicial`, `ingreso`, `egreso`, `stock_actual`) VALUES
(700, 14, '2021-05-14 23:14:49', 116, NULL, 1, NULL,106, 'Venta', 34, 0, 1, 33),
(701, 15, '2021-05-14 23:15:08', 117, NULL, 1, NULL, 106, 'Venta', 11, 0, 1, 10),
;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `det_tem_compra`
--

CREATE TABLE `det_tem_compra` (
  `id_det_compra` bigint(15) NOT NULL,
  `token_user` varchar(50) DEFAULT NULL,
  `id_producto` int(15) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `preciocompra` decimal(10,2) NOT NULL,
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `det_tem_venta`
--

CREATE TABLE `det_tem_venta` (
  `id_det_venta` bigint(15) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precioventa` decimal(10,2) NOT NULL,
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `det_venta`
--

CREATE TABLE `det_venta` (
  `id_det_venta` bigint(15) NOT NULL,
  `id_venta` bigint(15) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `totalventa` decimal(10,2) NOT NULL,
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `det_venta`
--

INSERT INTO `det_venta` (`id_det_venta`, `id_venta`, `id_producto`, `cantidad`, `totalventa`, `estado`) VALUES
(231, 106, 14, 2, '84646.00', b'1'),
(232, 106, 15, 3, '126969.00', b'1'),
(234, 107, 14, 1, '42323.00', b'1'),
(235, 107, 15, 2, '84646.00', b'1'),
(236, 108, NULL, 5, '211615.00', b'1'),
(237, 109, NULL, 4, '169292.00', b'1'),
(238, 110, NULL, 5, '211615.00', b'1'),
(239, 111, NULL, 4, '169292.00', b'1'),
(240, 112, NULL, 4, '169292.00', b'1'),
(241, 113, NULL, 1, '42323.00', b'1'),
(242, 114, 14, 1, '42323.00', b'1'),
(243, 115, 14, 2, '84646.00', b'1'),
(244, 115, 15, 3, '126969.00', b'1'),
(246, 116, 14, 1, '42323.00', b'1'),
(247, 117, 15, 1, '42323.00', b'1'),
(248, 118, 14, 2, '84646.00', b'1'),
(249, 118, 15, 2, '84646.00', b'1'),
(251, 119, 14, 1, '42323.00', b'1'),
(252, 119, 15, 1, '42323.00', b'1'),
(254, 120, 14, 1, '42323.00', b'1'),
(255, 120, 15, 1, '42323.00', b'1'),
(257, 121, 14, 1, '42323.00', b'1'),
(258, 121, 15, 1, '42323.00', b'1'),
(260, 122, 14, 1, '42323.00', b'1'),
(261, 122, 15, 1, '42323.00', b'1'),
(263, 123, 14, 1, '42323.00', b'1'),
(264, 123, 15, 1, '42323.00', b'1'),
(266, 124, 14, 1, '42323.00', b'1'),
(267, 124, 15, 1, '42323.00', b'1'),
(269, 125, 14, 1, '42323.00', b'1'),
(270, 125, 15, 1, '42323.00', b'1'),
(272, 126, 14, 1, '42323.00', b'1'),
(273, 126, 15, 1, '42323.00', b'1'),
(274, 127, NULL, 5, '211615.00', b'1'),
(275, 127, 14, 1, '42323.00', b'1'),
(276, 127, 15, 1, '42323.00', b'1'),
(277, 128, 14, 1, '42323.00', b'1'),
(278, 129, 14, 1, '42323.00', b'1'),
(279, 129, 15, 1, '42323.00', b'1'),
(281, 130, 14, 1, '42323.00', b'1'),
(282, 131, 14, 1, '42323.00', b'1'),
(283, 132, 14, 1, '42323.00', b'1'),
(284, 133, 14, 1, '42323.00', b'1'),
(285, 134, 14, 1, '42323.00', b'1'),
(286, 135, 14, 1, '42323.00', b'1'),
(287, 136, 14, 1, '42323.00', b'1'),
(288, 137, 14, 1, '42323.00', b'1'),
(289, 138, 14, 1, '42323.00', b'1'),
(290, 139, 14, 1, '42323.00', b'1'),
(291, 140, 14, 1, '42323.00', b'1'),
(292, 141, 14, 1, '42323.00', b'1'),
(293, 142, 14, 1, '42323.00', b'1'),
(294, 143, 14, 1, '42323.00', b'1'),
(295, 144, 14, 1, '42323.00', b'1'),
(296, 145, 14, 1, '42323.00', b'1'),
(297, 146, 14, 1, '42323.00', b'1'),
(298, 147, 14, 1, '42323.00', b'1'),
(299, 148, 14, 1, '42323.00', b'1'),
(300, 149, 14, 1, '42323.00', b'1'),
(301, 150, 14, 1, '42323.00', b'1'),
(302, 151, 14, 1, '42323.00', b'1'),
(303, 152, 14, 1, '42323.00', b'1'),
(304, 153, 14, 1, '42323.00', b'1'),
(305, 154, 14, 1, '42323.00', b'1'),
(306, 155, 14, 1, '42323.00', b'1'),
(307, 156, 14, 1, '42323.00', b'1'),
(308, 157, 14, 1, '42323.00', b'1'),
(309, 158, 14, 1, '42323.00', b'1'),
(310, 159, 14, 1, '42323.00', b'1'),
(311, 160, 14, 1, '42323.00', b'1'),
(312, 161, 14, 1, '42323.00', b'1'),
(313, 162, 14, 1, '42323.00', b'1'),
(314, 163, 14, 1, '42323.00', b'1'),
(315, 164, 14, 1, '42323.00', b'1'),
(316, 165, 14, 1, '42323.00', b'1'),
(317, 166, 14, 1, '42323.00', b'1'),
(318, 167, 14, 1, '42323.00', b'1'),
(319, 168, 14, 1, '42323.00', b'1'),
(320, 169, 14, 1, '42323.00', b'1'),
(321, 170, 14, 1, '42323.00', b'1'),
(322, 171, 14, 1, '42323.00', b'1'),
(323, 172, 14, 1, '42323.00', b'1'),
(324, 173, 14, 2, '84646.00', b'1'),
(325, 174, 14, 1, '42323.00', b'1'),
(326, 175, 14, 1, '42323.00', b'1'),
(327, 176, 14, 1, '42323.00', b'1'),
(328, 177, 14, 1, '42323.00', b'1'),
(329, 178, 14, 1, '42323.00', b'1'),
(330, 179, 14, 1, '42323.00', b'1'),
(331, 180, 14, 1, '42323.00', b'1'),
(332, 181, 14, 1, '42323.00', b'1'),
(333, 182, 14, 1, '42323.00', b'1'),
(334, 183, 15, 1, '42323.00', b'1'),
(335, 184, 15, 1, '42323.00', b'1'),
(336, 185, 15, 1, '42323.00', b'1'),
(337, 186, 15, 1, '42323.00', b'1'),
(338, 187, 15, 1, '42323.00', b'1'),
(339, 188, 15, 1, '42323.00', b'1'),
(340, 189, 15, 1, '42323.00', b'1'),
(341, 190, 15, 1, '42323.00', b'1'),
(342, 191, 15, 1, '42323.00', b'1'),
(343, 192, 15, 1, '42323.00', b'1'),
(344, 193, 15, 1, '42323.00', b'1'),
(345, 194, 15, 1, '42323.00', b'1'),
(346, 195, 15, 1, '42323.00', b'1'),
(347, 196, 15, 1, '42323.00', b'1'),
(348, 197, 15, 1, '42323.00', b'1'),
(349, 198, 15, 1, '42323.00', b'1'),
(350, 199, 15, 1, '42323.00', b'1'),
(351, 200, 15, 1, '42323.00', b'1'),
(352, 201, 15, 1, '42323.00', b'1'),
(353, 202, 15, 1, '42323.00', b'1'),
(354, 203, 15, 1, '42323.00', b'1'),
(355, 204, 15, 1, '42323.00', b'1'),
(356, 205, 15, 1, '42323.00', b'1'),
(357, 206, 15, 1, '42323.00', b'1'),
(358, 207, 15, 1, '42323.00', b'1'),
(359, 208, 15, 1, '42323.00', b'1'),
(360, 209, 15, 1, '42323.00', b'1'),
(361, 210, 15, 1, '42323.00', b'1'),
(362, 211, 15, 1, '42323.00', b'1'),
(363, 212, 15, 1, '42323.00', b'1'),
(364, 213, 15, 1, '42323.00', b'1'),
(365, 214, 15, 1, '42323.00', b'1'),
(366, 215, 15, 1, '42323.00', b'1'),
(367, 216, 15, 1, '42323.00', b'1'),
(368, 217, 15, 1, '42323.00', b'1'),
(369, 218, 15, 1, '42323.00', b'1'),
(370, 219, 10, 1, '2323.00', b'1'),
(371, 220, 10, 1, '2323.00', b'1'),
(372, 221, 10, 1, '2323.00', b'1'),
(373, 222, 10, 1, '2323.00', b'1'),
(374, 223, 10, 1, '2323.00', b'1'),
(375, 224, 10, 1, '2323.00', b'1'),
(376, 225, 10, 1, '2323.00', b'1'),
(377, 226, 10, 1, '2323.00', b'1'),
(378, 227, 10, 1, '2323.00', b'1'),
(379, 228, 10, 1, '2323.00', b'1'),
(380, 229, 19, 1, '3242.00', b'1'),
(381, 230, 19, 1, '3242.00', b'1'),
(382, 231, 19, 1, '3242.00', b'1'),
(383, 232, 19, 1, '3242.00', b'1'),
(384, 233, 19, 1, '3242.00', b'1'),
(385, 234, 19, 1, '3242.00', b'1'),
(386, 235, 19, 1, '3242.00', b'1'),
(387, 236, 19, 1, '3242.00', b'1'),
(388, 237, 19, 1, '3242.00', b'1'),
(389, 238, 19, 1, '3242.00', b'1'),
(390, 239, 19, 1, '3242.00', b'1'),
(391, 240, 19, 1, '3242.00', b'1'),
(392, 241, 19, 1, '3242.00', b'1'),
(393, 242, 19, 1, '3242.00', b'1'),
(394, 243, 19, 1, '3242.00', b'1'),
(395, 244, 19, 1, '3242.00', b'1'),
(396, 245, 19, 1, '3242.00', b'1'),
(397, 246, 19, 1, '3242.00', b'1'),
(398, 247, 19, 1, '3242.00', b'1'),
(399, 248, 19, 1, '3242.00', b'1'),
(400, 249, 19, 1, '3242.00', b'1'),
(401, 250, 19, 1, '3242.00', b'1'),
(402, 251, 19, 1, '3242.00', b'1'),
(403, 252, 19, 1, '3242.00', b'1'),
(404, 253, 19, 1, '3242.00', b'1'),
(405, 254, 19, 1, '3242.00', b'1'),
(406, 255, 19, 1, '3242.00', b'1'),
(407, 256, 19, 1, '3242.00', b'1'),
(408, 257, 19, 1, '3242.00', b'1'),
(409, 258, 19, 1, '3242.00', b'1'),
(410, 259, 19, 1, '3242.00', b'1'),
(411, 260, 19, 1, '3242.00', b'1'),
(412, 261, 19, 1, '3242.00', b'1'),
(413, 262, 19, 1, '3242.00', b'1'),
(414, 263, 19, 1, '3242.00', b'1'),
(415, 264, 19, 1, '3242.00', b'1'),
(416, 265, 19, 1, '3242.00', b'1'),
(417, 266, 19, 1, '3242.00', b'1'),
(418, 267, 19, 1, '3242.00', b'1'),
(419, 268, 19, 1, '3242.00', b'1'),
(420, 269, 19, 1, '3242.00', b'1'),
(421, 270, 19, 1, '3242.00', b'1'),
(422, 271, 19, 1, '3242.00', b'1'),
(423, 272, 19, 1, '3242.00', b'1'),
(424, 273, 19, 1, '3242.00', b'1'),
(425, 274, 19, 1, '3242.00', b'1'),
(426, 275, 19, 1, '3242.00', b'1'),
(427, 276, 19, 1, '3242.00', b'1'),
(428, 277, 19, 1, '3242.00', b'1'),
(429, 278, 19, 1, '3242.00', b'1'),
(430, 279, 11, 1, '324.00', b'1'),
(431, 280, 11, 1, '324.00', b'1'),
(432, 281, 11, 1, '324.00', b'1'),
(433, 282, 11, 1, '324.00', b'1'),
(434, 283, 11, 1, '324.00', b'1'),
(435, 284, 11, 1, '324.00', b'1'),
(436, 285, 11, 1, '324.00', b'1'),
(437, 286, 11, 1, '324.00', b'1'),
(438, 287, 11, 1, '324.00', b'1'),
(439, 288, 11, 1, '324.00', b'1'),
(440, 289, 11, 1, '324.00', b'1'),
(441, 290, 11, 1, '324.00', b'1'),
(442, 291, 11, 1, '324.00', b'1'),
(443, 292, 11, 1, '324.00', b'1'),
(444, 293, 11, 1, '324.00', b'1'),
(445, 294, 11, 1, '324.00', b'1'),
(446, 295, 11, 1, '324.00', b'1'),
(447, 296, 11, 1, '324.00', b'1'),
(448, 297, 11, 1, '324.00', b'1'),
(449, 298, 11, 1, '324.00', b'1'),
(450, 299, 11, 1, '324.00', b'1'),
(451, 300, 11, 1, '324.00', b'1'),
(452, 301, 11, 1, '324.00', b'1'),
(453, 302, 11, 1, '324.00', b'1'),
(454, 303, 11, 1, '324.00', b'1'),
(455, 304, 11, 1, '324.00', b'1'),
(456, 305, 11, 1, '324.00', b'1'),
(457, 306, 11, 1, '324.00', b'1'),
(458, 307, 11, 1, '324.00', b'1'),
(459, 308, 11, 1, '324.00', b'1'),
(460, 309, 11, 1, '324.00', b'1'),
(461, 310, 11, 1, '324.00', b'1'),
(462, 311, 11, 1, '324.00', b'1'),
(463, 312, 11, 1, '324.00', b'1'),
(464, 313, 11, 1, '324.00', b'1'),
(465, 314, 11, 1, '324.00', b'1'),
(466, 315, 11, 1, '324.00', b'1'),
(467, 316, 11, 1, '324.00', b'1'),
(468, 317, 11, 1, '324.00', b'1'),
(469, 318, 11, 1, '324.00', b'1'),
(470, 319, 11, 1, '324.00', b'1'),
(471, 320, 11, 1, '324.00', b'1'),
(472, 321, 11, 1, '324.00', b'1'),
(473, 322, 11, 1, '324.00', b'1'),
(474, 323, 11, 1, '324.00', b'1'),
(475, 324, 11, 1, '324.00', b'1'),
(476, 325, 11, 1, '324.00', b'1'),
(477, 326, 11, 1, '324.00', b'1'),
(478, 327, 11, 1, '324.00', b'1'),
(479, 328, 11, 1, '324.00', b'1'),
(480, 329, 11, 1, '324.00', b'1'),
(481, 330, 11, 1, '324.00', b'1'),
(482, 331, 11, 1, '324.00', b'1'),
(483, 332, 11, 1, '324.00', b'1'),
(484, 333, 11, 1, '324.00', b'1'),
(485, 334, 11, 1, '324.00', b'1'),
(486, 335, 11, 1, '324.00', b'1'),
(487, 336, 11, 1, '324.00', b'1'),
(488, 337, 11, 1, '324.00', b'1'),
(489, 338, 11, 1, '324.00', b'1'),
(490, 339, 12, 1, '231231.00', b'1'),
(491, 340, 12, 1, '231231.00', b'1'),
(492, 341, 12, 1, '231231.00', b'1'),
(493, 342, 12, 1, '231231.00', b'1'),
(494, 343, 12, 1, '231231.00', b'1'),
(495, 344, 12, 1, '231231.00', b'1'),
(496, 345, 12, 1, '231231.00', b'1'),
(497, 346, 12, 1, '231231.00', b'1'),
(498, 347, 12, 1, '231231.00', b'1'),
(499, 348, 12, 1, '231231.00', b'1'),
(500, 349, 12, 1, '231231.00', b'1'),
(501, 350, 12, 1, '231231.00', b'1'),
(502, 351, 12, 1, '231231.00', b'1'),
(503, 352, 12, 1, '231231.00', b'1'),
(504, 353, 12, 1, '231231.00', b'1'),
(505, 354, 12, 1, '231231.00', b'1'),
(506, 355, 12, 1, '231231.00', b'1'),
(507, 356, 12, 1, '231231.00', b'1'),
(508, 357, 12, 1, '231231.00', b'1'),
(509, 358, 12, 1, '231231.00', b'1'),
(510, 359, 12, 1, '231231.00', b'1'),
(511, 360, 12, 1, '231231.00', b'1'),
(512, 361, 12, 1, '231231.00', b'1'),
(513, 362, 12, 1, '231231.00', b'1'),
(514, 363, 12, 1, '231231.00', b'1'),
(515, 364, 12, 1, '231231.00', b'1'),
(516, 365, 12, 1, '231231.00', b'1'),
(517, 366, 12, 1, '231231.00', b'1'),
(518, 367, 12, 1, '231231.00', b'1'),
(519, 368, 12, 1, '231231.00', b'1'),
(520, 369, 12, 1, '231231.00', b'1'),
(521, 370, 12, 1, '231231.00', b'1'),
(522, 371, 12, 1, '231231.00', b'1'),
(523, 372, 12, 1, '231231.00', b'1'),
(524, 373, 12, 1, '231231.00', b'1'),
(525, 374, 12, 1, '231231.00', b'1'),
(526, 375, 12, 1, '231231.00', b'1'),
(527, 376, 12, 1, '231231.00', b'1'),
(528, 377, 12, 1, '231231.00', b'1'),
(529, 378, 12, 1, '231231.00', b'1'),
(530, 379, 12, 1, '231231.00', b'1'),
(531, 380, 12, 1, '231231.00', b'1'),
(532, 381, 12, 1, '231231.00', b'1'),
(533, 382, 12, 1, '231231.00', b'1'),
(534, 383, 12, 1, '231231.00', b'1'),
(535, 384, 12, 1, '231231.00', b'1'),
(536, 385, 12, 1, '231231.00', b'1'),
(537, 386, 12, 1, '231231.00', b'1'),
(538, 387, 12, 1, '231231.00', b'1'),
(539, 388, 12, 1, '231231.00', b'1'),
(540, 389, 12, 1, '231231.00', b'1'),
(541, 390, 12, 1, '231231.00', b'1'),
(542, 391, 12, 1, '231231.00', b'1'),
(543, 392, 12, 1, '231231.00', b'1'),
(544, 393, 12, 1, '231231.00', b'1'),
(545, 394, 12, 1, '231231.00', b'1'),
(546, 395, 12, 1, '231231.00', b'1'),
(547, 396, 15, 1, '42323.00', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id_empresa` bigint(20) NOT NULL,
  `nit` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `direccion` text NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `iva` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id_empresa`, `nit`, `nombre`, `telefono`, `email`, `direccion`, `razon_social`, `iva`) VALUES
(1, '123124233242', 'kenices', 312321412123, 'ke@gmail.com', 'la@gmail.com', 'kenices', '19.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE `login` (
  `id_usuario` int(15) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `apellido` varchar(250) NOT NULL,
  `email` varchar(70) NOT NULL,
  `password` varchar(100) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `telefono` bigint(15) NOT NULL,
  `fecha` date NOT NULL,
  `id_rol` int(15) DEFAULT NULL,
  `estado` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `login`
--

INSERT INTO `login` (`id_usuario`, `cedula`, `nombre`, `apellido`, `email`, `password`, `direccion`, `telefono`, `fecha`, `id_rol`, `estado`) VALUES
(1, '1030679419', 'Yuliana Andrea', 'Garzón', 'yagarzon99@ucatolica.edu.co', '0391393dc1fffa3708b60efc4b3cea92', 'Cll39a#73c-43', 3017234742, '2021-05-03', 1, b'1'),
(11, '9999', 'Yuliana Andrea', 'Sanchez', 'p@gmail.com', '0f4628cb1e2db198e0d363efce328bd7', 'Cll39a#73c-43s', 3017234742, '2021-05-02', 2, b'1'),
(12, 'fds', '465464', 'ddad', 'm@gmail.com', '0391393dc1fffa3708b60efc4b3cea92', 'Cll39a#73c-43', 0, '2021-05-10', 1, b'1'),
(14, '999', 'Liliana Maria', 'Perez', 'yuliuzuheart@outlook.com', '0391393dc1fffa3708b60efc4b3cea92', 'Cll39a#73c-43', 8824522342, '2021-05-04', 2, b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(15) NOT NULL,
  `id_categoria` int(15) DEFAULT NULL,
  `id_proveedor` int(15) DEFAULT NULL,
  `id_usuario` int(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `tamaño` varchar(50) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `foto` text DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_categoria`, `id_proveedor`, `id_usuario`, `nombre`, `marca`, `tamaño`, `stock`, `precio`, `fecha`, `foto`, `descripcion`, `estado`) VALUES
(10, 3, 6, 1, 'ffsfs', 'dfsdf', 'fdsf', 0, '2323.00', '2021-04-24 17:15:27', 'img_cebe72ad0ff9a845239073dd627301dd.jpg', '', b'1'),
(11, 2, 6, 1, 'Almentra', 'italy', 'pequeño', 20, '324.00', '2021-04-24 17:17:09', 'img_06169941a666de9b122a850d2424616d.jpg', 'x12', b'1'),
(12, 1, 1, 1, 'fds', 'fsdf', 'fsfs', 23, '231231.00', '2021-04-24 17:21:03', 'img_bcb09b2a5fc48afbfcba1329359efe8b.jpg', 'fdsf', b'1'),
(13, 1, 1, 1, 'das', 'sad', '4322', 27, '42323.00', '2021-04-24 17:29:51', 'img_producto.png', '', b'1'),
(14, 1, 1, 1, 'das', 'sad', '4322', 7, '42323.00', '2021-04-24 17:39:18', 'img_producto.png', '', b'1'),
(15, 1, 1, 1, 'das', 'sad', '4322', 10, '42323.00', '2021-04-24 17:39:43', 'img_producto.png', '', b'1'),
(16, 1, 1, 1, 'das', 'sad', '4322', 100, '42323.00', '2021-04-24 17:59:43', 'img_producto.png', '', b'1'),
(17, 1, 1, 1, 'das', 'sad', '4322', 0, '42323.00', '2021-04-24 18:08:29', 'img_producto.png', '', b'1'),
(18, 1, 1, 1, 'dfs', 'fsfs', 'ds', 0, '4234.00', '2021-04-24 18:08:48', 'img_producto.png', '', b'1'),
(19, 1, 1, 1, 'fsd', 'fds', 'fdsf', 0, '3242.00', '2021-04-24 18:15:32', 'img_4e9b02a9cb70aa757507f143134097ac.jpg', '', b'1'),
(20, 1, 1, 1, 'fsd', 'fds', 'fdsf', 0, '3242.00', '2021-04-24 18:22:26', 'img_346aff39a6d8ccf6b97d563356c766eb.jpg', '', b'1'),
(21, 1, 1, 1, 'fsdds', 'fdsf', 'fsd', 0, '312321.00', '2021-04-24 18:23:12', 'img_cfc7705b0ed4b9dfcee1f4436606d423.jpg', '', b'1'),
(22, 1, 1, 1, 'fsdds', 'fdsf', 'fsd', 0, '312321.00', '2021-04-24 18:28:15', 'img_105fb5cfdfafcc58bfab45957a306282.jpg', '', b'1'),
(23, 1, 1, 1, 'fsdds', 'fdsf', 'fsd', 0, '312321.00', '2021-04-24 18:37:11', 'img_68c8527ab3998bfd532cdf231dc4e932.jpg', '', b'1'),
(24, 1, 1, 1, 'fsdds', 'fdsf', 'fsd', 0, '312321.00', '2021-04-24 18:37:21', 'img_25d497283ec844a3ef2f6a7b4fff2a37.jpg', '', b'1'),
(25, 1, 1, 1, 'fsdds', 'fdsf', 'fsd', 0, '312321.00', '2021-04-24 18:38:07', 'img_ed929c8238f01d3097ba3774ba373a0d.jpg', '', b'1'),
(26, 1, 1, 1, 'fsdds', 'fdsf', 'fsd', 0, '312321.00', '2021-04-24 19:41:48', 'img_e00b6eabd2a9a6cc2b95bada65eeed89.jpg', '', b'1'),
(27, 1, 1, 1, 'maiz', 'fdsf', 'fsd', 0, '231.00', '2021-05-11 15:39:15', 'img_producto.png', '', b'1'),
(28, 1, 1, 1, 'maiz', 'fdsf', 'fsd', 0, '231.00', '2021-05-11 15:41:25', 'img_producto.png', '', b'1'),
(29, 1, 1, 1, 'fds', 'fds', 'fds', 0, '3242.00', '2021-05-11 15:41:37', 'img_producto.png', '', b'1'),
(30, 1, 1, 1, 'Lesly Paola', 'fdsfd', 'SDFDF', 0, '231.00', '2021-05-11 15:49:26', 'img_producto.png', '', b'1'),
(31, 1, 1, 1, 'Lesly Paola', 'fdsfd', 'SDFDF', 0, '231.00', '2021-05-11 15:50:46', 'img_producto.png', '', b'1'),
(32, 1, 1, 1, 'sfdsd', 'fds', 'fsd', 0, '4234.00', '2021-05-11 15:54:10', 'img_producto.png', '', b'1'),
(33, 1, 1, 1, 'fsd', 'fsd', 'dasdas', 0, '3242.00', '2021-05-11 15:54:56', 'img_producto.png', '', b'1'),
(34, 1, 1, 1, 'fsd', 'fds', 'fds', 0, '4234.00', '2021-05-11 16:04:51', 'img_producto.png', '', b'1'),
(35, 1, 1, 1, 'fdsf', 'fdsf', 'fds', 0, '4234.00', '2021-05-11 23:09:27', 'img_producto.png', '', b'1'),
(36, 1, 1, 1, 'fdsf', 'fdsf', 'fds', 0, '4234.00', '2021-05-11 23:18:22', 'img_producto.png', '', b'1'),
(37, 1, 1, 1, 'fdsf', 'fdsf', 'fds', 0, '4234.00', '2021-05-11 23:18:46', 'img_producto.png', '', b'1'),
(38, 1, 1, 1, 'fdsf', 'fdsf', 'fds', 0, '4234.00', '2021-05-11 23:25:17', 'img_producto.png', '', b'1'),
(39, 1, 1, 1, 'fdsf', 'fdsf', 'fds', 0, '4234.00', '2021-05-11 23:43:06', 'img_producto.png', '', b'1'),
(40, 1, 1, 1, 'fdsf', 'fdsf', 'fds', 0, '4234.00', '2021-05-11 23:46:18', 'img_producto.png', '', b'1'),
(41, 1, 1, 1, 'fds', 'fdsfd', 'fsdfs', 0, '234432.00', '2021-05-12 00:26:24', 'img_producto.png', '', b'1'),
(42, 1, 1, 1, 'fds', 'fdsfd', 'fsdfs', 0, '234432.00', '2021-05-12 00:27:50', 'img_producto.png', '', b'1'),
(43, 1, 1, 1, 'fds', 'fds', 'fds', 0, '423.00', '2021-05-12 00:28:21', 'img_producto.png', '', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(15) NOT NULL,
  `id_usuario` int(15) NOT NULL,
  `proveedor` varchar(250) NOT NULL,
  `contacto` varchar(250) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `email` varchar(70) DEFAULT NULL,
  `telefono` bigint(15) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `detalle` varchar(500) DEFAULT NULL,
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `id_usuario`, `proveedor`, `contacto`, `direccion`, `email`, `telefono`, `fecha`, `detalle`, `estado`) VALUES
(1, 1, 'Ferrero', 'Maria lopez', 'Cll39a#73c-43', 'fe@gmail.com', 3017234742, '2021-05-02', '', b'1'),
(2, 1, 'Snickers', 'Laura', 'Cll39a#73c-43', 'alu@gmail.com', 3017234742, '2021-05-02', '', b'1'),
(3, 1, 'fd', 'Maria lopez', 'Cll39a#73c-43', 'yuliandrea_p@hotmail.com', 3017234742, '2021-05-02', '----', b'1'),
(4, 1, 'fsd', 'Maria lopez', 'Cll39a#73c-43', 'leslypsanchezg@gmail.com', 8824522342, '2021-05-02', 'dsds', b'1'),
(5, 1, 'fs', 'Maria lopez', 'Cll39a#73c-43', 'alu@gmail.com', 3017234742, '2021-05-02', 'dsfd', b'1'),
(6, 1, 'italy', 'Marcela', 'Cll39a#73c-43', 'leslypsanchezg@gmail.com', 3017234742, '2021-05-06', '', b'1'),
(7, 1, 'fdcasfred', 'Marcela', 'Cll39a#73c-43', 'yagarzon99@ucatolica.edu.co', 3017234742, '2021-05-11', '', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(15) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id_venta` bigint(15) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(15) DEFAULT NULL,
  `id_cliente` int(15) DEFAULT NULL,
  `totalventa` decimal(10,2) NOT NULL,
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id_venta`, `fecha`, `id_usuario`, `id_cliente`, `totalventa`, `estado`) VALUES
(106, '2021-05-13 22:21:35', 1, 1, '211615.00', b'1'),
(107, '2021-05-13 23:05:55', 1, 1, '126969.00', b'1'),
(108, '2021-05-14 19:10:20', 1, 1, '211615.00', b'1'),
(109, '2021-05-14 19:12:57', 1, 1, '169292.00', b'1'),
(110, '2021-05-14 22:37:18', 1, 1, '211615.00', b'1'),
(111, '2021-05-14 22:38:22', 1, 1, '169292.00', b'1'),
(112, '2021-05-14 22:44:58', 1, 1, '169292.00', b'1'),
(113, '2021-05-14 22:57:30', 1, 1, '42323.00', b'1'),
(114, '2021-05-14 23:01:46', 1, 1, '42323.00', b'1'),
(115, '2021-05-14 23:13:23', 1, 1, '211615.00', b'1'),
(116, '2021-05-14 23:14:49', 1, 1, '42323.00', b'1'),
(117, '2021-05-14 23:15:08', 1, 1, '42323.00', b'1'),
(118, '2021-05-14 23:25:50', 1, 1, '169292.00', b'1'),
(119, '2021-05-14 23:30:24', 1, 1, '0.00', b'1'),
(120, '2021-05-14 23:30:31', 1, 1, '0.00', b'1'),
(121, '2021-05-14 23:30:32', 1, 1, '0.00', b'1'),
(122, '2021-05-14 23:30:32', 1, 1, '0.00', b'1'),
(123, '2021-05-14 23:30:32', 1, 1, '0.00', b'1'),
(124, '2021-05-14 23:30:32', 1, 1, '0.00', b'1'),
(125, '2021-05-14 23:30:33', 1, 1, '0.00', b'1'),
(126, '2021-05-14 23:30:33', 1, 1, '0.00', b'1'),
(127, '2021-05-15 10:57:39', 1, 1, '296261.00', b'1'),
(128, '2021-05-15 10:58:57', 1, 1, '42323.00', b'1'),
(129, '2021-05-15 11:00:19', 1, 1, '84646.00', b'1'),
(130, '2021-05-15 12:05:35', 1, 1, '42323.00', b'1'),
(131, '2021-05-15 12:07:15', 1, 1, '0.00', b'1'),
(132, '2021-05-15 12:19:02', 1, 1, '0.00', b'1'),
(133, '2021-05-15 12:33:24', 1, 1, '42323.00', b'1'),
(134, '2021-05-15 12:34:34', 1, 1, '42323.00', b'1'),
(135, '2021-05-15 12:35:30', 1, 1, '42323.00', b'1'),
(136, '2021-05-15 23:50:24', 1, 1, '42323.00', b'1'),
(137, '2021-05-15 23:58:05', 1, 1, '42323.00', b'1'),
(138, '2021-05-15 23:59:26', 1, 1, '42323.00', b'1'),
(139, '2021-05-15 23:59:48', 1, 1, '42323.00', b'1'),
(140, '2021-05-16 00:02:54', 1, 1, '42323.00', b'1'),
(141, '2021-05-16 00:03:46', 1, 1, '42323.00', b'1'),
(142, '2021-05-16 00:07:32', 1, 1, '42323.00', b'1'),
(143, '2021-05-16 00:09:44', 1, 1, '42323.00', b'1'),
(144, '2021-05-16 00:11:09', 1, 1, '42323.00', b'1'),
(145, '2021-05-16 00:12:32', 1, 1, '42323.00', b'1'),
(146, '2021-05-16 00:24:50', 1, 1, '42323.00', b'1'),
(147, '2021-05-16 00:45:11', 1, 1, '42323.00', b'1'),
(148, '2021-05-16 00:46:59', 1, 1, '42323.00', b'1'),
(149, '2021-05-16 00:47:20', 1, 1, '42323.00', b'1'),
(150, '2021-05-16 00:49:07', 1, 1, '42323.00', b'1'),
(151, '2021-05-16 00:49:43', 1, 1, '42323.00', b'1'),
(152, '2021-05-16 00:51:06', 1, 1, '42323.00', b'1'),
(153, '2021-05-16 00:53:32', 1, 1, '42323.00', b'1'),
(154, '2021-05-16 00:57:33', 1, 1, '42323.00', b'1'),
(155, '2021-05-16 01:01:11', 1, 1, '42323.00', b'1'),
(156, '2021-05-16 01:02:02', 1, 1, '42323.00', b'1'),
(157, '2021-05-16 01:04:27', 1, 1, '42323.00', b'1'),
(158, '2021-05-16 01:08:02', 1, 1, '42323.00', b'1'),
(159, '2021-05-16 01:09:30', 1, 1, '42323.00', b'1'),
(160, '2021-05-16 01:13:02', 1, 1, '42323.00', b'1'),
(161, '2021-05-16 01:13:16', 1, 1, '42323.00', b'1'),
(162, '2021-05-16 01:14:23', 1, 1, '42323.00', b'1'),
(163, '2021-05-16 01:14:37', 1, 1, '42323.00', b'1'),
(164, '2021-05-16 01:17:23', 1, 1, '42323.00', b'1'),
(165, '2021-05-16 01:18:26', 1, 1, '42323.00', b'1'),
(166, '2021-05-16 01:19:22', 1, 1, '42323.00', b'1'),
(167, '2021-05-16 01:22:30', 1, 1, '42323.00', b'1'),
(168, '2021-05-16 01:23:13', 1, 1, '42323.00', b'1'),
(169, '2021-05-16 01:28:25', 1, 1, '42323.00', b'1'),
(170, '2021-05-16 01:28:45', 1, 1, '42323.00', b'1'),
(171, '2021-05-16 01:33:45', 1, 1, '42323.00', b'1'),
(172, '2021-05-16 01:36:50', 1, 1, '42323.00', b'1'),
(173, '2021-05-16 01:37:02', 1, 1, '84646.00', b'1'),
(174, '2021-05-16 01:58:40', 1, 1, '42323.00', b'1'),
(175, '2021-05-16 02:01:37', 1, 1, '42323.00', b'1'),
(176, '2021-05-16 02:03:12', 1, 1, '42323.00', b'1'),
(177, '2021-05-16 02:04:30', 1, 1, '42323.00', b'1'),
(178, '2021-05-16 02:05:50', 1, 1, '42323.00', b'1'),
(179, '2021-05-16 02:06:33', 1, 1, '42323.00', b'1'),
(180, '2021-05-16 02:09:49', 1, 1, '42323.00', b'1'),
(181, '2021-05-16 02:11:10', 1, 1, '42323.00', b'1'),
(182, '2021-05-16 02:11:29', 1, 1, '42323.00', b'1'),
(183, '2021-05-16 02:12:20', 1, 1, '42323.00', b'1'),
(184, '2021-05-16 02:13:57', 1, 1, '42323.00', b'1'),
(185, '2021-05-16 02:14:41', 1, 1, '42323.00', b'1'),
(186, '2021-05-16 02:14:55', 1, 1, '42323.00', b'1'),
(187, '2021-05-16 02:15:24', 1, 1, '42323.00', b'1'),
(188, '2021-05-16 02:16:38', 1, 1, '42323.00', b'1'),
(189, '2021-05-16 02:17:12', 1, 1, '42323.00', b'1'),
(190, '2021-05-16 02:18:31', 1, 1, '42323.00', b'1'),
(191, '2021-05-16 02:19:18', 1, 1, '42323.00', b'1'),
(192, '2021-05-16 02:19:54', 1, 1, '42323.00', b'1'),
(193, '2021-05-16 02:20:28', 1, 1, '42323.00', b'1'),
(194, '2021-05-16 02:20:53', 1, 1, '42323.00', b'1'),
(195, '2021-05-16 02:21:13', 1, 1, '42323.00', b'1'),
(196, '2021-05-16 02:22:43', 1, 1, '42323.00', b'1'),
(197, '2021-05-16 02:23:10', 1, 1, '42323.00', b'1'),
(198, '2021-05-16 02:24:43', 1, 1, '42323.00', b'1'),
(199, '2021-05-16 02:25:46', 1, 1, '42323.00', b'1'),
(200, '2021-05-16 02:26:24', 1, 1, '42323.00', b'1'),
(201, '2021-05-16 02:27:10', 1, 1, '42323.00', b'1'),
(202, '2021-05-16 02:27:51', 1, 1, '42323.00', b'1'),
(203, '2021-05-16 02:28:15', 1, 1, '42323.00', b'1'),
(204, '2021-05-16 02:32:01', 1, 1, '42323.00', b'1'),
(205, '2021-05-16 02:33:04', 1, 1, '42323.00', b'1'),
(206, '2021-05-16 02:33:24', 1, 1, '42323.00', b'1'),
(207, '2021-05-16 02:33:42', 1, 1, '42323.00', b'1'),
(208, '2021-05-16 02:34:04', 1, 1, '42323.00', b'1'),
(209, '2021-05-16 02:35:13', 1, 1, '42323.00', b'1'),
(210, '2021-05-16 02:36:27', 1, 1, '42323.00', b'1'),
(211, '2021-05-16 02:36:50', 1, 1, '42323.00', b'1'),
(212, '2021-05-16 02:38:32', 1, 1, '42323.00', b'1'),
(213, '2021-05-16 02:39:52', 1, 1, '42323.00', b'1'),
(214, '2021-05-16 02:41:30', 1, 1, '42323.00', b'1'),
(215, '2021-05-16 02:41:56', 1, 1, '42323.00', b'1'),
(216, '2021-05-16 02:42:17', 1, 1, '42323.00', b'1'),
(217, '2021-05-16 02:42:53', 1, 1, '42323.00', b'1'),
(218, '2021-05-16 02:45:30', 1, 1, '42323.00', b'1'),
(219, '2021-05-16 02:46:37', 1, 1, '2323.00', b'1'),
(220, '2021-05-16 02:47:30', 1, 1, '2323.00', b'1'),
(221, '2021-05-16 02:51:02', 1, 1, '2323.00', b'1'),
(222, '2021-05-16 02:51:33', 1, 1, '2323.00', b'1'),
(223, '2021-05-16 02:51:55', 1, 1, '2323.00', b'1'),
(224, '2021-05-16 02:52:25', 1, 1, '2323.00', b'1'),
(225, '2021-05-16 02:52:59', 1, 1, '2323.00', b'1'),
(226, '2021-05-16 02:53:14', 1, 1, '2323.00', b'1'),
(227, '2021-05-16 02:53:31', 1, 1, '2323.00', b'1'),
(228, '2021-05-16 02:54:15', 1, 1, '2323.00', b'1'),
(229, '2021-05-16 02:56:13', 1, 1, '3242.00', b'1'),
(230, '2021-05-16 02:56:39', 1, 1, '3242.00', b'1'),
(231, '2021-05-16 02:56:56', 1, 1, '3242.00', b'1'),
(232, '2021-05-16 02:57:23', 1, 1, '3242.00', b'1'),
(233, '2021-05-16 02:58:32', 1, 1, '3242.00', b'1'),
(234, '2021-05-16 02:59:55', 1, 1, '3242.00', b'1'),
(235, '2021-05-16 03:00:24', 1, 1, '3242.00', b'1'),
(236, '2021-05-16 03:01:10', 1, 1, '3242.00', b'1'),
(237, '2021-05-16 03:01:54', 1, 1, '3242.00', b'1'),
(238, '2021-05-16 03:02:14', 1, 1, '3242.00', b'1'),
(239, '2021-05-16 03:02:35', 1, 1, '3242.00', b'1'),
(240, '2021-05-16 03:03:05', 1, 1, '3242.00', b'1'),
(241, '2021-05-16 03:03:29', 1, 1, '3242.00', b'1'),
(242, '2021-05-16 03:03:50', 1, 1, '3242.00', b'1'),
(243, '2021-05-16 03:06:03', 1, 1, '3242.00', b'1'),
(244, '2021-05-16 03:06:20', 1, 1, '3242.00', b'1'),
(245, '2021-05-16 03:06:38', 1, 1, '3242.00', b'1'),
(246, '2021-05-16 03:07:04', 1, 1, '3242.00', b'1'),
(247, '2021-05-16 03:07:37', 1, 1, '3242.00', b'1'),
(248, '2021-05-16 03:07:51', 1, 1, '3242.00', b'1'),
(249, '2021-05-16 03:08:09', 1, 1, '3242.00', b'1'),
(250, '2021-05-16 03:12:34', 1, 1, '3242.00', b'1'),
(251, '2021-05-16 03:24:54', 1, 1, '3242.00', b'1'),
(252, '2021-05-16 03:26:38', 1, 1, '3242.00', b'1'),
(253, '2021-05-16 09:21:21', 1, 1, '3242.00', b'1'),
(254, '2021-05-16 09:23:14', 1, 1, '3242.00', b'1'),
(255, '2021-05-16 09:25:48', 1, 1, '3242.00', b'1'),
(256, '2021-05-16 09:26:16', 1, 1, '3242.00', b'1'),
(257, '2021-05-16 09:27:02', 1, 1, '3242.00', b'1'),
(258, '2021-05-16 09:32:07', 1, 1, '3242.00', b'1'),
(259, '2021-05-16 10:06:59', 1, 1, '3242.00', b'1'),
(260, '2021-05-16 10:10:34', 1, 1, '3242.00', b'1'),
(261, '2021-05-16 10:11:10', 1, 1, '3242.00', b'1'),
(262, '2021-05-16 10:11:54', 1, 1, '3242.00', b'1'),
(263, '2021-05-16 10:12:11', 1, 1, '3242.00', b'1'),
(264, '2021-05-16 10:13:52', 1, 1, '3242.00', b'1'),
(265, '2021-05-16 10:14:10', 1, 1, '3242.00', b'1'),
(266, '2021-05-16 10:15:36', 1, 1, '3242.00', b'1'),
(267, '2021-05-16 10:29:29', 1, 1, '3242.00', b'1'),
(268, '2021-05-16 10:29:46', 1, 1, '3242.00', b'1'),
(269, '2021-05-16 10:30:32', 1, 1, '3242.00', b'1'),
(270, '2021-05-16 10:31:44', 1, 1, '3242.00', b'1'),
(271, '2021-05-16 10:32:22', 1, 1, '3242.00', b'1'),
(272, '2021-05-16 10:33:12', 1, 1, '3242.00', b'1'),
(273, '2021-05-16 10:34:38', 1, 1, '3242.00', b'1'),
(274, '2021-05-16 10:36:08', 1, 1, '3242.00', b'1'),
(275, '2021-05-16 10:38:14', 1, 1, '3242.00', b'1'),
(276, '2021-05-16 10:39:10', 1, 1, '3242.00', b'1'),
(277, '2021-05-16 10:39:47', 1, 1, '3242.00', b'1'),
(278, '2021-05-16 10:40:30', 1, 1, '3242.00', b'1'),
(279, '2021-05-16 10:41:41', 1, 1, '324.00', b'1'),
(280, '2021-05-16 10:44:11', 1, 1, '324.00', b'1'),
(281, '2021-05-16 10:45:18', 1, 1, '324.00', b'1'),
(282, '2021-05-16 10:46:55', 1, 1, '324.00', b'1'),
(283, '2021-05-16 10:47:30', 1, 1, '324.00', b'1'),
(284, '2021-05-16 10:48:09', 1, 1, '324.00', b'1'),
(285, '2021-05-16 10:48:30', 1, 1, '324.00', b'1'),
(286, '2021-05-16 10:48:56', 1, 1, '324.00', b'1'),
(287, '2021-05-16 10:49:24', 1, 1, '324.00', b'1'),
(288, '2021-05-16 10:49:58', 1, 1, '324.00', b'1'),
(289, '2021-05-16 10:50:30', 1, 1, '324.00', b'1'),
(290, '2021-05-16 10:51:10', 1, 1, '324.00', b'1'),
(291, '2021-05-16 10:51:53', 1, 1, '324.00', b'1'),
(292, '2021-05-16 10:52:22', 1, 1, '324.00', b'1'),
(293, '2021-05-16 10:52:41', 1, 1, '324.00', b'1'),
(294, '2021-05-16 10:53:29', 1, 1, '324.00', b'1'),
(295, '2021-05-16 10:54:05', 1, 1, '324.00', b'1'),
(296, '2021-05-16 10:54:43', 1, 1, '324.00', b'1'),
(297, '2021-05-16 10:55:13', 1, 1, '324.00', b'1'),
(298, '2021-05-16 10:55:30', 1, 1, '324.00', b'1'),
(299, '2021-05-16 10:56:13', 1, 1, '324.00', b'1'),
(300, '2021-05-16 10:56:41', 1, 1, '324.00', b'1'),
(301, '2021-05-16 10:57:09', 1, 1, '324.00', b'1'),
(302, '2021-05-16 10:57:42', 1, 1, '324.00', b'1'),
(303, '2021-05-16 10:58:13', 1, 1, '324.00', b'1'),
(304, '2021-05-16 10:58:41', 1, 1, '324.00', b'1'),
(305, '2021-05-16 10:59:15', 1, 1, '324.00', b'1'),
(306, '2021-05-16 10:59:41', 1, 1, '324.00', b'1'),
(307, '2021-05-16 11:00:03', 1, 1, '324.00', b'1'),
(308, '2021-05-16 11:00:18', 1, 1, '324.00', b'1'),
(309, '2021-05-16 11:00:37', 1, 1, '324.00', b'1'),
(310, '2021-05-16 11:00:50', 1, 1, '324.00', b'1'),
(311, '2021-05-16 11:01:12', 1, 1, '324.00', b'1'),
(312, '2021-05-16 11:01:28', 1, 1, '324.00', b'1'),
(313, '2021-05-16 11:01:49', 1, 1, '324.00', b'1'),
(314, '2021-05-16 11:02:19', 1, 1, '324.00', b'1'),
(315, '2021-05-16 11:02:35', 1, 1, '324.00', b'1'),
(316, '2021-05-16 11:03:31', 1, 1, '324.00', b'1'),
(317, '2021-05-16 11:05:02', 1, 1, '324.00', b'1'),
(318, '2021-05-16 11:05:20', 1, 1, '324.00', b'1'),
(319, '2021-05-16 11:05:34', 1, 1, '324.00', b'1'),
(320, '2021-05-16 11:06:09', 1, 1, '324.00', b'1'),
(321, '2021-05-16 11:06:49', 1, 1, '324.00', b'1'),
(322, '2021-05-16 11:07:18', 1, 1, '324.00', b'1'),
(323, '2021-05-16 11:07:48', 1, 1, '324.00', b'1'),
(324, '2021-05-16 11:08:24', 1, 1, '324.00', b'1'),
(325, '2021-05-16 11:08:39', 1, 1, '324.00', b'1'),
(326, '2021-05-16 11:09:18', 1, 1, '324.00', b'1'),
(327, '2021-05-16 11:11:41', 1, 1, '324.00', b'1'),
(328, '2021-05-16 11:12:24', 1, 1, '324.00', b'1'),
(329, '2021-05-16 11:12:39', 1, 1, '324.00', b'1'),
(330, '2021-05-16 11:13:17', 1, 1, '324.00', b'1'),
(331, '2021-05-16 11:14:59', 1, 1, '324.00', b'1'),
(332, '2021-05-16 11:15:27', 1, 1, '324.00', b'1'),
(333, '2021-05-16 11:22:18', 1, 1, '324.00', b'1'),
(334, '2021-05-16 11:22:35', 1, 1, '324.00', b'1'),
(335, '2021-05-16 11:23:13', 1, 1, '324.00', b'1'),
(336, '2021-05-16 11:23:33', 1, 1, '324.00', b'1'),
(337, '2021-05-16 11:23:45', 1, 1, '324.00', b'1'),
(338, '2021-05-16 11:24:58', 1, 1, '324.00', b'1'),
(339, '2021-05-16 11:25:52', 1, 1, '231231.00', b'1'),
(340, '2021-05-16 11:27:08', 1, 1, '231231.00', b'1'),
(341, '2021-05-16 11:28:55', 1, 1, '231231.00', b'1'),
(342, '2021-05-16 11:31:08', 1, 1, '231231.00', b'1'),
(343, '2021-05-16 11:32:08', 1, 1, '231231.00', b'1'),
(344, '2021-05-16 11:33:53', 1, 1, '231231.00', b'1'),
(345, '2021-05-16 11:34:45', 1, 1, '231231.00', b'1'),
(346, '2021-05-16 11:38:31', 1, 1, '231231.00', b'1'),
(347, '2021-05-16 11:39:19', 1, 1, '231231.00', b'1'),
(348, '2021-05-16 11:43:59', 1, 1, '231231.00', b'1'),
(349, '2021-05-16 11:45:19', 1, 1, '231231.00', b'1'),
(350, '2021-05-16 11:48:54', 1, 1, '231231.00', b'1'),
(351, '2021-05-16 11:50:31', 1, 1, '231231.00', b'1'),
(352, '2021-05-16 11:51:53', 1, 1, '231231.00', b'1'),
(353, '2021-05-16 11:53:17', 1, 1, '231231.00', b'1'),
(354, '2021-05-16 11:54:31', 1, 1, '231231.00', b'1'),
(355, '2021-05-16 11:55:33', 1, 1, '231231.00', b'1'),
(356, '2021-05-16 11:56:25', 1, 1, '231231.00', b'1'),
(357, '2021-05-16 11:57:39', 1, 1, '231231.00', b'1'),
(358, '2021-05-16 11:58:18', 1, 1, '231231.00', b'1'),
(359, '2021-05-16 12:00:59', 1, 1, '231231.00', b'1'),
(360, '2021-05-16 12:01:35', 1, 1, '231231.00', b'1'),
(361, '2021-05-16 12:02:23', 1, 1, '231231.00', b'1'),
(362, '2021-05-16 12:02:40', 1, 1, '231231.00', b'1'),
(363, '2021-05-16 12:03:04', 1, 1, '231231.00', b'1'),
(364, '2021-05-16 12:03:21', 1, 1, '231231.00', b'1'),
(365, '2021-05-16 12:03:59', 1, 1, '231231.00', b'1'),
(366, '2021-05-16 12:04:47', 1, 1, '231231.00', b'1'),
(367, '2021-05-16 12:05:32', 1, 1, '231231.00', b'1'),
(368, '2021-05-16 12:05:55', 1, 1, '231231.00', b'1'),
(369, '2021-05-16 12:06:26', 1, 1, '231231.00', b'1'),
(370, '2021-05-16 12:07:28', 1, 1, '231231.00', b'1'),
(371, '2021-05-16 12:07:55', 1, 1, '231231.00', b'1'),
(372, '2021-05-16 12:08:15', 1, 1, '231231.00', b'1'),
(373, '2021-05-16 12:08:34', 1, 1, '231231.00', b'1'),
(374, '2021-05-16 12:09:06', 1, 1, '231231.00', b'1'),
(375, '2021-05-16 12:09:38', 1, 1, '231231.00', b'1'),
(376, '2021-05-16 12:10:01', 1, 1, '231231.00', b'1'),
(377, '2021-05-16 12:10:20', 1, 1, '231231.00', b'1'),
(378, '2021-05-16 12:10:30', 1, 1, '231231.00', b'1'),
(379, '2021-05-16 12:10:44', 1, 1, '231231.00', b'1'),
(380, '2021-05-16 12:11:08', 1, 1, '231231.00', b'1'),
(381, '2021-05-16 12:12:37', 1, 1, '231231.00', b'1'),
(382, '2021-05-16 12:13:11', 1, 1, '231231.00', b'1'),
(383, '2021-05-16 12:14:00', 1, 1, '231231.00', b'1'),
(384, '2021-05-16 12:14:35', 1, 1, '231231.00', b'1'),
(385, '2021-05-16 12:17:39', 1, 1, '231231.00', b'1'),
(386, '2021-05-16 12:17:57', 1, 1, '231231.00', b'1'),
(387, '2021-05-16 12:19:19', 1, 1, '231231.00', b'1'),
(388, '2021-05-16 12:19:31', 1, 1, '231231.00', b'1'),
(389, '2021-05-16 12:20:04', 1, 1, '231231.00', b'1'),
(390, '2021-05-16 12:20:48', 1, 1, '231231.00', b'1'),
(391, '2021-05-16 12:21:44', 1, 1, '231231.00', b'1'),
(392, '2021-05-16 12:22:00', 1, 1, '231231.00', b'1'),
(393, '2021-05-16 12:22:52', 1, 1, '231231.00', b'1'),
(394, '2021-05-16 12:24:31', 1, 1, '231231.00', b'1'),
(395, '2021-05-16 12:24:50', 1, 1, '231231.00', b'1'),
(396, '2021-05-17 11:19:20', 14, 2, '42323.00', b'1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `cedula` (`cedula`,`email`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `det_compra`
--
ALTER TABLE `det_compra`
  ADD PRIMARY KEY (`id_det_compra`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_compra` (`id_compra`);

--
-- Indices de la tabla `det_kardex`
--
ALTER TABLE `det_kardex`
  ADD PRIMARY KEY (`id_kardex`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_det_compra` (`id_det_compra`),
  ADD KEY `id_det_venta` (`id_det_venta`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `det_tem_compra`
--
ALTER TABLE `det_tem_compra`
  ADD PRIMARY KEY (`id_det_compra`),
  ADD KEY `id_compra` (`token_user`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `det_tem_venta`
--
ALTER TABLE `det_tem_venta`
  ADD PRIMARY KEY (`id_det_venta`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_venta` (`token_user`);

--
-- Indices de la tabla `det_venta`
--
ALTER TABLE `det_venta`
  ADD PRIMARY KEY (`id_det_venta`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id_empresa`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `cedula` (`cedula`,`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `det_compra`
--
ALTER TABLE `det_compra`
  MODIFY `id_det_compra` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `det_kardex`
--
ALTER TABLE `det_kardex`
  MODIFY `id_kardex` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1019;

--
-- AUTO_INCREMENT de la tabla `det_tem_compra`
--
ALTER TABLE `det_tem_compra`
  MODIFY `id_det_compra` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `det_tem_venta`
--
ALTER TABLE `det_tem_venta`
  MODIFY `id_det_venta` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=603;

--
-- AUTO_INCREMENT de la tabla `det_venta`
--
ALTER TABLE `det_venta`
  MODIFY `id_det_venta` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=548;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id_empresa` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `id_usuario` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id_venta` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=397;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id_usuario`);

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id_usuario`);

--
-- Filtros para la tabla `det_compra`
--
ALTER TABLE `det_compra`
  ADD CONSTRAINT `det_compra_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `det_compra_ibfk_2` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`);

--
-- Filtros para la tabla `det_kardex`
--
ALTER TABLE `det_kardex`
  ADD CONSTRAINT `det_kardex_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id_usuario`),
  ADD CONSTRAINT `det_kardex_ibfk_3` FOREIGN KEY (`id_det_compra`) REFERENCES `det_compra` (`id_det_compra`),
  ADD CONSTRAINT `det_kardex_ibfk_4` FOREIGN KEY (`id_det_venta`) REFERENCES `det_venta` (`id_det_venta`),
  ADD CONSTRAINT `det_kardex_ibfk_5` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`),
  ADD CONSTRAINT `det_kardex_ibfk_6` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`),
  ADD CONSTRAINT `det_kardex_ibfk_7` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `det_tem_compra`
--
ALTER TABLE `det_tem_compra`
  ADD CONSTRAINT `det_tem_compra_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `det_tem_venta`
--
ALTER TABLE `det_tem_venta`
  ADD CONSTRAINT `det_tem_venta_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `det_venta`
--
ALTER TABLE `det_venta`
  ADD CONSTRAINT `det_venta_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `det_venta_ibfk_2` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`);

--
-- Filtros para la tabla `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id_usuario`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id_usuario`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id_usuario`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
