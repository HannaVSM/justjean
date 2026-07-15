-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2026 a las 22:49:16
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `creaciones_justean_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_orden_compra`
--

CREATE TABLE `detalle_orden_compra` (
  `idDetalleOrden` int(11) NOT NULL,
  `idOrdenCompra` int(11) NOT NULL,
  `idInsumo` int(11) NOT NULL,
  `cantidadSolicitada` decimal(10,2) NOT NULL,
  `costoUnitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `idDetallePedido` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idInsumo` int(11) NOT NULL,
  `cantidadAsignada` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos`
--

CREATE TABLE `insumos` (
  `idInsumo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `unidadMedida` varchar(30) DEFAULT NULL,
  `stockActual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stockMinimo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `costo` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `insumos`
--

INSERT INTO `insumos` (`idInsumo`, `nombre`, `descripcion`, `tipo`, `unidadMedida`, `stockActual`, `stockMinimo`, `costo`) VALUES
(1, 'Tela Denim Azul', 'Tela denim peso 14 oz, ancho 1.5 m', 'Tela', 'Metro', 150.00, 50.00, 12500.00),
(2, 'Tela Denim Negro', 'Tela denim peso 14 oz, ancho 1.5 m', 'Tela', 'Metro', 80.00, 40.00, 13000.00),
(3, 'Hilo Azul 100% Poliéster', 'Hilo cono 5000 yardas color azul jean', 'Hilo', 'Cono', 45.00, 10.00, 8500.00),
(4, 'Hilo Blanco Resistente', 'Hilo cono 5000 yardas color blanco', 'Hilo', 'Cono', 30.00, 10.00, 8000.00),
(5, 'Cremallera 15 cm', 'Cremallera metálica color plateado', 'Accesorio', 'Unidad', 500.00, 100.00, 1200.00),
(6, 'Botón Jean Dorado', 'Botón metálico tono dorado 17 mm', 'Accesorio', 'Unidad', 810.00, 200.00, 350.00),
(7, 'Remache Cobre', 'Remache decorativo tono cobre', 'Accesorio', 'Unidad', 600.00, 150.00, 200.00),
(8, 'Etiqueta de Marca', 'Etiqueta tejida JustJean talla única', 'Etiqueta', 'Unidad', 1000.00, 300.00, 150.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `idMovimiento` int(11) NOT NULL,
  `idInsumo` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `tipoMovimiento` enum('entrada','salida') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `idPedido` int(11) DEFAULT NULL COMMENT 'Relacionado si el movimiento es por un pedido',
  `idOrdenCompra` int(11) DEFAULT NULL COMMENT 'Relacionado si el movimiento es por una orden de compra'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `movimientos_inventario`
--

INSERT INTO `movimientos_inventario` (`idMovimiento`, `idInsumo`, `idUsuario`, `tipoMovimiento`, `cantidad`, `fecha`, `idPedido`, `idOrdenCompra`) VALUES
(1, 6, 4, 'entrada', 10.00, '2026-05-22 15:39:28', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_compra`
--

CREATE TABLE `ordenes_compra` (
  `idOrdenCompra` int(11) NOT NULL,
  `idProveedor` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('Pendiente','Recibida','Cancelada') NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL,
  `cliente` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('Registrado','En Produccion','Finalizado','Cancelado') NOT NULL DEFAULT 'Registrado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `idProveedor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`idProveedor`, `nombre`, `contacto`, `email`) VALUES
(1, 'Textiles del Norte S.A.S', '310 555 0001', 'ventas@textilesnorte.com'),
(2, 'Distribuidora Hilos y Más', '315 555 0002', 'pedidos@hilosymas.com'),
(3, 'Insumos Moda Express', '320 555 0003', 'comercial@modaexpress.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Hash bcrypt generado con password_hash()',
  `rol` enum('Administrador','Operario') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `login`, `password`, `rol`) VALUES
(4, 'Administrador', 'admin', '$2y$10$s.g0OYbx7ad4NzbhgdaqvuvqtpuSiQ4PR/JNbAxCFJpFxZINUHqHm', 'Administrador'),
(5, 'Operario', 'operario', '$2y$10$s.g0OYbx7ad4NzbhgdaqvuvqtpuSiQ4PR/JNbAxCFJpFxZINUHqHm', 'Operario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalle_orden_compra`
--
ALTER TABLE `detalle_orden_compra`
  ADD PRIMARY KEY (`idDetalleOrden`),
  ADD KEY `fk_doc_orden` (`idOrdenCompra`),
  ADD KEY `fk_doc_insumo` (`idInsumo`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`idDetallePedido`),
  ADD KEY `fk_dp_pedido` (`idPedido`),
  ADD KEY `fk_dp_insumo` (`idInsumo`);

--
-- Indices de la tabla `insumos`
--
ALTER TABLE `insumos`
  ADD PRIMARY KEY (`idInsumo`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`idMovimiento`),
  ADD KEY `fk_mi_insumo` (`idInsumo`),
  ADD KEY `fk_mi_usuario` (`idUsuario`),
  ADD KEY `fk_mi_pedido` (`idPedido`),
  ADD KEY `fk_mi_orden_compra` (`idOrdenCompra`);

--
-- Indices de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD PRIMARY KEY (`idOrdenCompra`),
  ADD KEY `fk_oc_proveedor` (`idProveedor`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`idProveedor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `uq_login` (`login`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_orden_compra`
--
ALTER TABLE `detalle_orden_compra`
  MODIFY `idDetalleOrden` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `idDetallePedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `insumos`
--
ALTER TABLE `insumos`
  MODIFY `idInsumo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `idMovimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  MODIFY `idOrdenCompra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_orden_compra`
--
ALTER TABLE `detalle_orden_compra`
  ADD CONSTRAINT `fk_doc_insumo` FOREIGN KEY (`idInsumo`) REFERENCES `insumos` (`idInsumo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_doc_orden` FOREIGN KEY (`idOrdenCompra`) REFERENCES `ordenes_compra` (`idOrdenCompra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `fk_dp_insumo` FOREIGN KEY (`idInsumo`) REFERENCES `insumos` (`idInsumo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dp_pedido` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`idPedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `fk_mi_insumo` FOREIGN KEY (`idInsumo`) REFERENCES `insumos` (`idInsumo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mi_orden_compra` FOREIGN KEY (`idOrdenCompra`) REFERENCES `ordenes_compra` (`idOrdenCompra`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mi_pedido` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`idPedido`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mi_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD CONSTRAINT `fk_oc_proveedor` FOREIGN KEY (`idProveedor`) REFERENCES `proveedores` (`idProveedor`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
