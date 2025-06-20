-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2025 a las 00:22:26
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `repository`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `idUser` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(1) NOT NULL DEFAULT 1,
  `level` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`idUser`, `firstname`, `lastname`, `email`, `password`, `fecha`, `status`, `level`) VALUES
(1, 'Oscar Rafael', 'Contreras Flota', 'oscarcontrerasf91@gmail.com', '$2a$07$asxx54ahjppf45sd87a5auRHz5VyvxsgbNKYyggXQtOKy0VNwnILK', '2024-07-10 00:30:38', 1, 0),
(2, 'Aaron', 'Prado', 'informatica@unimontrer.edu.mx', '$2a$07$asxx54ahjppf45sd87a5auRHz5VyvxsgbNKYyggXQtOKy0VNwnILK', '2025-02-18 04:21:58', 1, 0),
(3, 'Karla', 'Lopez Valdespino', 'klopez@unimontrer.edu.mx', '$2a$07$asxx54ahjppf45sd87a5auRHz5VyvxsgbNKYyggXQtOKy0VNwnILK', '2024-07-25 04:36:58', 1, 1),
(4, 'CATALINA', 'HERNANDEZ URBINA', 'chernandez@unimontrer.edu.mx', '$2a$07$asxx54ahjppf45sd87a5auRHz5VyvxsgbNKYyggXQtOKy0VNwnILK', '2024-11-02 04:06:12', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
