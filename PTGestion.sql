-- phpMyAdmin SQL Dump
-- version 2.11.9.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 09-02-2009 a las 13:48:18
-- Versión del servidor: 5.0.67
-- Versión de PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE USER 'PTUser'@'localhost' IDENTIFIED BY '3jj9STdSdKE2GS7w';

GRANT ALL PRIVILEGES ON * . * TO 'PTUser'@'localhost' IDENTIFIED BY '3jj9STdSdKE2GS7w' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

--
-- Base de datos: `PTGestion`
--

DROP DATABASE IF EXISTS `PTGestion`;

CREATE DATABASE `PTGestion` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `PTGestion`;

-- --------------------------------------------------------


--
-- Estructura de tabla para la tabla 'articulo'
--

DROP TABLE IF EXISTS articulo;
CREATE TABLE IF NOT EXISTS articulo (
  Referencia varchar(8) collate utf8_unicode_ci NOT NULL,
  Nombre text collate utf8_unicode_ci NOT NULL,
  Precio float NOT NULL,
  Coste float NOT NULL,
  ReferenciaProveedor text collate utf8_unicode_ci,
  Observaciones text collate utf8_unicode_ci,
  Imagen mediumblob,
  IdProveedor int(11) default NULL,
  Unidades int(11) NOT NULL default '0',
  PRIMARY KEY  (Referencia),
  KEY IdProveedor (IdProveedor)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'capitulo'
--
DROP TABLE IF EXISTS capitulo;
CREATE TABLE IF NOT EXISTS `capitulo` (
  `IdCapitulo` int(11) NOT NULL auto_increment,
  `Nombre` text collate utf8_unicode_ci NOT NULL,
  `OrdenCapitulo` float NOT NULL,
  PRIMARY KEY  (`IdCapitulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'cliente'
--

DROP TABLE IF EXISTS cliente;
CREATE TABLE IF NOT EXISTS cliente (
  IdCliente int(11) NOT NULL auto_increment,
  NIF varchar(9) default NULL,
  Nombre text collate utf8_unicode_ci NOT NULL,
  Apellidos text collate utf8_unicode_ci NOT NULL,
  Titulo enum('Sr. D.','Sra. Dª.','Sres. de') collate utf8_unicode_ci NOT NULL,
  Observaciones text collate utf8_unicode_ci,
  Direccion text collate utf8_unicode_ci,
  CP int(5) default NULL,
  Localidad text collate utf8_unicode_ci,
  Provincia text collate utf8_unicode_ci,
  Pais text collate utf8_unicode_ci,
  FechaAlta date default NULL,
  Tlf1 int(11) default NULL,
  Tlf2 int(11) default NULL,
  Email text collate utf8_unicode_ci,
  PRIMARY KEY  (IdCliente)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'compra'
--

DROP TABLE IF EXISTS compra;
CREATE TABLE IF NOT EXISTS compra (
  IdCompra int(11) NOT NULL auto_increment,
  Fecha date NOT NULL,
  Observaciones text collate utf8_unicode_ci,
  PRIMARY KEY  (IdCompra)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'factura'
--

DROP TABLE IF EXISTS factura;
CREATE TABLE IF NOT EXISTS factura (
  IdFactura int(11) NOT NULL auto_increment,
  IdVenta int(11) NOT NULL,
  PRIMARY KEY  (IdFactura),
  UNIQUE KEY IdVenta (IdVenta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'facturaant'
--

DROP TABLE IF EXISTS facturaant;
CREATE TABLE IF NOT EXISTS facturaant (
  IdFactura int(11) NOT NULL auto_increment,
  IdVenta int(11) NOT NULL,
  PRIMARY KEY  (IdFactura),
  UNIQUE KEY IdVenta (IdVenta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'lineacompra'
--

DROP TABLE IF EXISTS lineacompra;
CREATE TABLE IF NOT EXISTS lineacompra (
  Referencia varchar(8) collate utf8_unicode_ci NOT NULL,
  IdCompra int(11) NOT NULL,
  Unidades int(11) NOT NULL,
  Coste float NOT NULL,
  PRIMARY KEY  (Referencia,IdCompra),
  KEY IdCompra (IdCompra),
  KEY Referencia (Referencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'linealistaboda'
--

DROP TABLE IF EXISTS linealistaboda;
CREATE TABLE IF NOT EXISTS linealistaboda (
  Referencia varchar(8) collate utf8_unicode_ci NOT NULL,
  IdListaBoda int(11) NOT NULL,
  Unidades int(11) NOT NULL,
  Precio float NOT NULL,
  Comentario text collate utf8_unicode_ci,
  IdLineaListaBoda int(11) NOT NULL auto_increment,
  PRIMARY KEY  (IdLineaListaBoda),
  KEY Referencia (Referencia),
  KEY IdListaBoda (IdListaBoda)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'lineapresupuesto'
--

DROP TABLE IF EXISTS lineapresupuesto;
CREATE TABLE IF NOT EXISTS lineapresupuesto (
  Referencia varchar(8) collate utf8_unicode_ci NOT NULL,
  IdPresupuesto int(11) NOT NULL,
  Unidades int(11) NOT NULL,
  IdCapitulo int(11) default NULL,
  IdSubcapitulo int(11) default NULL,
  Precio float NOT NULL,
  Descuento int(11) NOT NULL,
  IdLineaPresupuesto int(11) NOT NULL auto_increment,
  Comentario text collate utf8_unicode_ci,
  PRIMARY KEY  (IdLineaPresupuesto),
  KEY Referencia (Referencia),
  KEY IdPresupuesto (IdPresupuesto),
  KEY IdCapitulo (IdCapitulo),
  KEY IdSubcapitulo (IdSubcapitulo)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'lineaventa'
--

DROP TABLE IF EXISTS lineaventa;
CREATE TABLE IF NOT EXISTS lineaventa (
  Referencia varchar(8) collate utf8_unicode_ci NOT NULL,
  IdVenta int(11) NOT NULL,
  Unidades int(11) NOT NULL,
  Precio float NOT NULL,
  IdLineaVenta int(11) NOT NULL auto_increment,
  Comentario text collate utf8_unicode_ci NOT NULL,
  Descuento int(11) NOT NULL,
  PRIMARY KEY  (IdLineaVenta),
  KEY Referencia (Referencia),
  KEY IdVenta (IdVenta)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'listaboda'
--

DROP TABLE IF EXISTS listaboda;
CREATE TABLE IF NOT EXISTS listaboda (
  IdListaBoda int(11) NOT NULL auto_increment,
  Fecha date NOT NULL,
  Observaciones text collate utf8_unicode_ci,
  IdCliente int(11) NOT NULL,
  PRIMARY KEY  (IdListaBoda),
  KEY IdCliente (IdCliente)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'presupuesto'
--

DROP TABLE IF EXISTS `presupuesto`;
CREATE TABLE IF NOT EXISTS `presupuesto` (
  `IdPresupuesto` int(11) NOT NULL auto_increment,
  `Fecha` date NOT NULL,
  `Observaciones` text collate utf8_unicode_ci,
  `IdCliente` int(11) NOT NULL,
  `IdVenta` int(11) default NULL,
  PRIMARY KEY  (`IdPresupuesto`),
  KEY `IdCliente` (`IdCliente`),
  KEY `IdVenta` (`IdVenta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'proveedor'
--

DROP TABLE IF EXISTS proveedor;
CREATE TABLE IF NOT EXISTS proveedor (
  IdProveedor int(11) NOT NULL auto_increment,
  Nombre text collate utf8_unicode_ci NOT NULL,
  Direccion text collate utf8_unicode_ci,
  Localidad text collate utf8_unicode_ci,
  Provincia text collate utf8_unicode_ci,
  Pais text collate utf8_unicode_ci,
  CP int(5) default NULL,
  Tlf1 int(11) default NULL,
  Tlf2 int(11) default NULL,
  Fax int(11) default NULL,
  Email text collate utf8_unicode_ci,
  PRIMARY KEY  (IdProveedor)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'subcapitulo'
--
DROP TABLE IF EXISTS subcapitulo;
CREATE TABLE IF NOT EXISTS `subcapitulo` (
  `IdSubcapitulo` int(11) NOT NULL auto_increment,
  `Nombre` text collate utf8_unicode_ci NOT NULL,
  `OrdenSubcapitulo` float NOT NULL,
  PRIMARY KEY  (`IdSubcapitulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'ticket'
--

DROP TABLE IF EXISTS ticket;
CREATE TABLE IF NOT EXISTS ticket (
  IdTicket int(11) NOT NULL auto_increment,
  IdVenta int(11) NOT NULL,
  PRIMARY KEY  (IdTicket),
  UNIQUE KEY IdVenta (IdVenta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'venta'
--

DROP TABLE IF EXISTS venta;
CREATE TABLE IF NOT EXISTS venta (
  IdVenta int(11) NOT NULL auto_increment,
  FechaVenta date NOT NULL,
  FechaCobro date default NULL,
  FormaPago enum('Visa','Efectivo','Transferencia','Talón') collate utf8_unicode_ci default NULL,
  Observaciones text collate utf8_unicode_ci,
  IdCliente int(11) default NULL,
  Antiguedad enum('Si','No') collate utf8_unicode_ci NOT NULL,
  Descuento int(11) NOT NULL,
  PRIMARY KEY  (IdVenta),
  KEY IdCliente (IdCliente)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD CONSTRAINT articulo_ibfk_1 FOREIGN KEY (IdProveedor) REFERENCES proveedor (IdProveedor) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT factura_ibfk_1 FOREIGN KEY (IdVenta) REFERENCES venta (IdVenta) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturaant`
--
ALTER TABLE `facturaant`
  ADD CONSTRAINT facturaant_ibfk_1 FOREIGN KEY (IdVenta) REFERENCES venta (IdVenta) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `lineacompra`
--
ALTER TABLE `lineacompra`
  ADD CONSTRAINT lineacompra_ibfk_1 FOREIGN KEY (Referencia) REFERENCES articulo (Referencia) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT lineacompra_ibfk_2 FOREIGN KEY (IdCompra) REFERENCES compra (IdCompra) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `linealistaboda`
--
ALTER TABLE `linealistaboda`
  ADD CONSTRAINT linealistaboda_ibfk_1 FOREIGN KEY (Referencia) REFERENCES articulo (Referencia) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT linealistaboda_ibfk_2 FOREIGN KEY (IdListaBoda) REFERENCES listaboda (IdListaBoda) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `lineapresupuesto`
--
ALTER TABLE `lineapresupuesto`
  ADD CONSTRAINT lineapresupuesto_ibfk_1 FOREIGN KEY (Referencia) REFERENCES articulo (Referencia) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT lineapresupuesto_ibfk_2 FOREIGN KEY (IdPresupuesto) REFERENCES presupuesto (IdPresupuesto) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT lineapresupuesto_ibfk_3 FOREIGN KEY (IdCapitulo) REFERENCES capitulo (IdCapitulo) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT lineapresupuesto_ibfk_4 FOREIGN KEY (IdSubcapitulo) REFERENCES subcapitulo (IdSubcapitulo) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `lineaventa`
--
ALTER TABLE `lineaventa`
  ADD CONSTRAINT lineaventa_ibfk_1 FOREIGN KEY (Referencia) REFERENCES articulo (Referencia) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT lineaventa_ibfk_2 FOREIGN KEY (IdVenta) REFERENCES venta (IdVenta) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `listaboda`
--
ALTER TABLE `listaboda`
  ADD CONSTRAINT listaboda_ibfk_1 FOREIGN KEY (IdCliente) REFERENCES cliente (IdCliente) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD CONSTRAINT `presupuesto_ibfk_2` FOREIGN KEY (`IdVenta`) REFERENCES `venta` (`IdVenta`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `presupuesto_ibfk_1` FOREIGN KEY (`IdCliente`) REFERENCES `cliente` (`IdCliente`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT ticket_ibfk_1 FOREIGN KEY (IdVenta) REFERENCES venta (IdVenta) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT venta_ibfk_1 FOREIGN KEY (IdCliente) REFERENCES cliente (IdCliente) ON DELETE NO ACTION ON UPDATE CASCADE;
