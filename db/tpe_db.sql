-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-11-2022 a las 05:16:07
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tpe_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrator`
--

CREATE TABLE `administrator` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL,
  `userName` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `administrator`
--

INSERT INTO `administrator` (`id`, `email`, `password`, `userName`) VALUES
(1, 'elmeste.88@gmail.com', '$2a$12$5C/Y0ZwqCEAAUD6Z./EUGeaSEyT9mmCqeY.Xy6uVrNtSIuhEvq2ZW', 'Ezequiel Mestelan');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `garage`
--

CREATE TABLE `garage` (
  `id` int(11) NOT NULL,
  `area` varchar(40) NOT NULL,
  `manager` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `garage`
--

INSERT INTO `garage` (`id`, `area`, `manager`) VALUES
(1, 'Torneria', 'Daniel Mestelan'),
(2, 'Cajas de cambios', 'Ezequiel Mestelan'),
(3, 'Trabado', 'Ezequiel Mestelan'),
(4, 'Lavado', 'David Alberdi');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `works`
--

CREATE TABLE `works` (
  `id` int(11) NOT NULL,
  `work_name` varchar(40) NOT NULL,
  `work_description` text NOT NULL,
  `client_name` varchar(40) NOT NULL,
  `work_id` int(50) NOT NULL,
  `work_status` varchar(40) NOT NULL,
  `fk_id` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `works`
--

INSERT INTO `works` (`id`, `work_name`, `work_description`, `client_name`, `work_id`, `work_status`, `fk_id`) VALUES
(2, 'Caja de cambios Vw Gol Country 1.9sd', 'Zumbido en tercera marcha', 'Ezequiel Mestelan', 134037899, 'Recibido', 2),
(3, 'Caja zf Mar y Sierras B', 'Servicio de caja de cambios zf', 'Marcos Costas', 128564785, 'Recibido', 2),
(7, 'Caja de cambios Chevrolet Cruze', 'Service cambio de aceite', 'Alexa Knussel', 136580868, 'Recibido', 2),
(10, 'Balanceo de cardan', 'Control cardan Chev Silverado 98, vibracion.', 'Claudio Guzman', 3253463, 'Recibido', 1),
(14, 'Rectificar eje de pedalera', 'Desarmar eje de pedalera, rectificar eje y embujar cuerpo.', 'Miguel Arce', 112458, 'Recibido', 1),
(32, 'Caja de cambios de Toyota Hilux', '5ta marcha no tracciona', 'La chicha', 242215, 'Finalizado', 2),
(33, 'Reparacion de caja de velocidades G32', 'Zumbido, perdida de aceite, juego excesivo en directa', 'Franco De Paoli', 14552, 'Recibido', 2),
(34, 'Lavar carcaza caja Toyota Hilux', 'Lavado premium', 'La chicha', 22456, 'Recibido', 4),
(35, 'Balanceo de cardan MyS B', 'Control por vibracion, cambio de crucetas', 'Matias Erpelding', 12334451, 'Recibido', 1),
(36, 'Lavar carcaza caja Fiat Uno', 'Lavado premium', 'Franco Arias', 4564654, 'Recibido', 4),
(37, 'Lavar carcaza caja Honda Fit', 'Lavado simple', 'Gustavo Pollero', 654654, 'Recibido', 4),
(38, 'Caja de cambios de Tc4000 Argentino', 'Servicio post carrera', 'Claudio Bazterrica', 13562, 'En proceso', 2),
(41, 'Caja de cambios de Mar y Sierras B', 'Servicio post carrera', 'Damian Daza', 1356212, 'En proceso', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `garage`
--
ALTER TABLE `garage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `area` (`area`);

--
-- Indices de la tabla `works`
--
ALTER TABLE `works`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `work_id` (`work_id`),
  ADD KEY `fk_sector` (`fk_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrator`
--
ALTER TABLE `administrator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `garage`
--
ALTER TABLE `garage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `works`
--
ALTER TABLE `works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `works`
--
ALTER TABLE `works`
  ADD CONSTRAINT `fk_id` FOREIGN KEY (`fk_id`) REFERENCES `garage` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
