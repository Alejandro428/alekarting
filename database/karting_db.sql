-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2025 a las 22:56:09
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
-- Base de datos: `karting_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `id_pistas` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `franja_horaria_id` int(11) DEFAULT NULL,
  `num_participantes` int(11) NOT NULL,
  `cantidad` float(10,2) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `pagado` tinyint(1) NOT NULL DEFAULT 0,
  `payment_intent_id` varchar(255) DEFAULT NULL COMMENT 'ID único de Stripe'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`id`, `id_usuario`, `empleado_id`, `id_pistas`, `fecha`, `franja_horaria_id`, `num_participantes`, `cantidad`, `metodo_pago`, `fecha_pago`, `pagado`, `payment_intent_id`) VALUES
(109, 7, 2, 1, '2025-03-20', 4, 1, 15.00, 'card', '2025-03-18', 0, NULL),
(110, 7, 2, 1, '2025-03-20', 1, 1, 15.00, 'card', '2025-03-18', 0, NULL),
(111, 7, 2, 1, '2025-03-19', 2, 3, 45.00, 'card', '2025-03-18', 0, NULL),
(112, 7, 2, 1, '2025-03-22', 1, 4, 60.00, 'card', '2025-03-18', 0, NULL),
(113, 20, 2, 3, '2025-03-23', 3, 6, 126.00, 'card', '2025-03-15', 0, NULL),
(118, 7, 3, 1, '2025-03-28', 7, 2, 30.00, 'card', '2025-03-24', 0, NULL),
(119, 7, 3, 1, '2025-03-26', 5, 1, 15.00, 'card', '2025-03-24', 0, NULL),
(120, 7, 3, 1, '2025-03-28', 11, 1, 15.00, 'card', '2025-03-24', 0, NULL),
(122, 7, 3, 1, '2025-03-25', 7, 1, 15.00, 'card', '2025-03-24', 0, NULL),
(123, 7, 3, 1, '2025-03-25', 8, 1, 15.00, 'paypal', '2025-03-24', 0, NULL),
(124, 7, 3, 1, '2025-03-25', 2, 1, 15.00, 'paypal', '2025-03-24', 0, NULL),
(125, 7, 3, 1, '2025-03-27', 6, 3, 45.00, 'paypal', '2025-03-25', 0, NULL),
(126, 7, 3, 3, '2025-03-28', 4, 2, 42.00, 'paypal', '2025-03-25', 0, NULL),
(127, 7, 3, 1, '2025-03-27', 2, 2, 30.00, 'paypal', '2025-03-25', 0, NULL),
(129, 7, 3, 2, '2025-03-27', 7, 2, 36.00, 'paypal', '2025-03-25', 0, NULL),
(130, 7, 3, 2, '2025-03-29', 6, 2, 36.00, 'paypal', '2025-03-25', 0, NULL),
(131, 7, 3, 1, '2025-03-25', 6, 3, 45.00, 'paypal', '2025-03-25', 0, NULL),
(133, 7, 2, 1, '2025-03-27', 11, 7, 105.00, 'card', '2025-03-29', 0, NULL),
(134, 7, 2, 1, '2025-03-27', 4, 2, 30.00, 'paypal', '2025-03-25', 0, NULL),
(135, 7, 2, 1, '2025-03-27', 8, 3, 45.00, 'paypal', '2025-03-25', 0, NULL),
(144, 13, 2, 1, '2025-03-28', 1, 2, 30.00, 'paypal', '2025-03-28', 0, NULL),
(162, 7, 2, 1, '2025-04-03', 4, 3, 45.00, 'paypal', '2025-03-30', 0, NULL),
(178, 21, 9, 1, '2025-04-06', 14, 1, 15.00, 'paypal', '2025-04-06', 0, NULL),
(179, 7, 2, 1, '2025-04-15', 4, 3, 45.00, 'paypal', '2025-04-09', 0, NULL),
(180, 7, 2, 1, '2025-04-15', 3, 1, 15.00, 'card', '2025-04-12', 0, NULL),
(181, 7, 2, 2, '2025-04-13', 3, 3, 54.00, 'card', '2025-04-12', 0, NULL),
(182, 7, 2, 3, '2025-04-13', 8, 4, 84.00, 'card', '2025-04-12', 0, NULL),
(183, 7, 3, 1, '2025-04-17', 1, 1, 15.00, 'card', '2025-04-12', 0, NULL),
(184, 7, 3, 1, '2025-04-13', 4, 1, 15.00, 'card', '2025-04-12', 0, NULL),
(188, 7, 2, 1, '2025-04-14', 4, 1, 15.00, 'card', '2025-04-13', 1, 'pi_3RDURNFythxFnUWF0asC8PwT'),
(200, 7, 9, 1, '2025-04-15', 1, 3, 45.00, 'card', '2025-04-14', 1, 'pi_3RDq9EFythxFnUWF0iw9U6w7'),
(201, 7, 2, 1, '2025-04-17', 2, 1, 15.00, 'card', '2025-04-14', 1, 'pi_3RDqHpFythxFnUWF0iypojWw'),
(202, 7, 3, 3, '2025-04-15', 2, 3, 63.00, 'card', '2025-04-14', 1, 'pi_3RDqPTFythxFnUWF0YidhSyy'),
(204, 7, 2, 2, '2025-05-17', 6, 3, 54.00, 'card', '2025-04-14', 1, 'pi_3RDqbuFythxFnUWF0wQZrvlR'),
(205, 7, 3, 3, '2025-05-17', 7, 3, 63.00, 'card', '2025-04-14', 1, 'pi_3RDqe8FythxFnUWF17WFrT7n'),
(206, 7, 9, 1, '2025-05-17', 8, 1, 15.00, 'card', '2025-04-14', 1, 'pi_3RDqiJFythxFnUWF06ucs4k5'),
(207, 7, 2, 1, '2025-05-17', 9, 3, 45.00, 'card', '2025-04-14', 1, 'pi_3RDqudFythxFnUWF1GI57fXa'),
(208, 7, 3, 1, '2025-05-17', 10, 3, 45.00, 'card', '2025-04-14', 1, 'pi_3RDqw1FythxFnUWF0FhUMLLd'),
(209, 7, 9, 1, '2025-05-17', 11, 1, 15.00, 'card', '2025-04-14', 1, 'pi_3RDrYKFythxFnUWF01kmItsi'),
(210, 7, 2, 1, '2025-04-15', 9, 1, 15.00, 'card', '2025-04-14', 1, 'pi_3RDrfhFythxFnUWF1EqjUgP4'),
(212, 7, 9, 1, '2025-04-15', 11, 1, 15.00, 'card', '2025-04-14', 1, 'pi_3RDrm8FythxFnUWF0f1imJCC'),
(254, 7, 3, 1, '2025-04-18', 14, 1, 15.00, 'paypal', '2025-04-18', 1, 'pi_3RFIKNFythxFnUWF1wFnH1eC'),
(255, 7, 3, 1, '2025-04-20', 1, 1, 15.00, 'card', '2025-04-18', 1, 'pi_3RFIMwFythxFnUWF14lCUUCi'),
(256, 7, 9, 1, '2025-04-20', 2, 1, 15.00, 'card', '2025-04-18', 1, 'pi_3RFIOiFythxFnUWF1AUYGMAJ'),
(257, 7, 3, 1, '2025-04-19', 5, 3, 45.00, 'card', '2025-04-19', 1, 'pi_3RFY7gFythxFnUWF0siZsQoO'),
(258, 7, 2, 1, '2025-04-21', 1, 1, 15.00, 'card', '2025-04-19', 1, 'pi_3RFYpFFythxFnUWF0vHU4O9h'),
(259, 7, 3, 3, '2025-04-22', 13, 3, 63.00, 'paypal', '2025-04-19', 1, 'pi_3RFZgIFythxFnUWF0euuNmKJ'),
(260, 7, 9, 3, '2025-04-24', 2, 3, 63.00, 'card', '2025-04-23', 1, 'pi_3RH4oAFythxFnUWF1K4WxdsV'),
(261, 7, 2, 3, '2025-04-25', 14, 4, 84.00, 'card', '2025-04-23', 1, 'pi_3RH4p8FythxFnUWF0V9VPlD2'),
(262, 7, 9, 2, '2025-04-25', 7, 3, 54.00, 'card', '2025-04-23', 1, 'pi_3RH4pPFythxFnUWF0kr2jzBk'),
(263, 7, 2, 1, '2025-04-28', 10, 3, 45.00, 'card', '2025-04-23', 1, 'pi_3RH4pqFythxFnUWF0gmGkQa1'),
(264, 7, 2, 3, '2025-04-29', 10, 3, 63.00, 'paypal', '2025-04-23', 1, 'pi_3RH4qIFythxFnUWF0SclxwSi'),
(265, 7, 9, 1, '2025-04-29', 13, 3, 45.00, 'card', '2025-04-23', 1, 'pi_3RH4qbFythxFnUWF00fuhbGC'),
(266, 13, 2, 2, '2025-04-25', 8, 5, 90.00, 'card', '2025-04-26', 0, NULL),
(267, 7, 3, 1, '2025-04-26', 1, 3, 45.00, 'card', '2025-04-23', 1, 'pi_3RH6D8FythxFnUWF1bRPL8Fo'),
(268, 7, 9, 1, '2025-04-25', 11, 3, 45.00, 'paypal', '2025-04-23', 1, 'pi_3RH6auFythxFnUWF1xUwmuA8'),
(269, 7, 2, 3, '2025-04-26', 2, 3, 63.00, 'card', '2025-04-23', 1, 'pi_3RH6cFFythxFnUWF16pGOwBl'),
(270, 7, 9, 1, '2025-04-26', 10, 2, 30.00, 'card', '2025-04-23', 1, 'pi_3RH6d3FythxFnUWF1K0JgwZp'),
(271, 7, 2, 1, '2025-04-26', 13, 3, 45.00, 'card', '2025-04-23', 1, 'pi_3RH6eTFythxFnUWF1g1awZNz'),
(272, 13, 3, 2, '2025-04-25', 2, 4, 72.00, 'card', '2025-04-22', 0, NULL),
(273, 7, 3, 2, '2025-04-26', 3, 3, 54.00, 'card', '2025-04-24', 0, NULL),
(274, 18, 3, 3, '2025-04-26', 6, 5, 105.00, 'card', '2025-04-24', 0, NULL),
(276, 7, 3, 3, '2025-04-30', 9, 3, 63.00, 'card', '2025-04-27', 0, NULL),
(277, 46, 15, 3, '2025-04-29', 11, 15, 315.00, 'paypal', '2025-04-27', 0, NULL),
(278, 56, 15, 2, '2025-04-27', 1, 3, 54.00, 'card', '2025-04-26', 1, 'pi_3RIBUtFythxFnUWF1zDVll51'),
(279, 7, 2, 3, '2025-05-08', 8, 3, 63.00, 'paypal', '2025-04-26', 1, 'pi_3RICqmFythxFnUWF1PJC8n5T'),
(280, 7, 17, 3, '2025-04-30', 11, 4, 84.00, 'card', '2025-04-30', 1, 'pi_3RJcbFFythxFnUWF0yk4PlBv'),
(281, 52, 2, 2, '2025-05-14', 5, 5, 90.00, 'card', '2025-05-06', 1, NULL),
(282, 13, 3, 1, '2025-05-17', 3, 3, 45.00, 'paypal', '2025-05-13', 0, NULL),
(283, 17, 17, 2, '2025-05-04', 6, 14, 252.00, 'card', '2025-05-01', 0, NULL),
(284, 52, 15, 3, '2025-05-14', 6, 2, 42.00, 'paypal', '2025-05-12', 1, NULL),
(285, 13, 17, 3, '2025-05-14', 11, 8, 168.00, 'card', '2025-05-12', 1, NULL),
(286, 7, 3, 1, '2025-05-08', 13, 3, 45.00, 'card', '2025-05-02', 1, 'pi_3RKNf7FythxFnUWF05m3ICOv'),
(287, 7, 3, 1, '2025-05-03', 1, 1, 15.00, 'paypal', '2025-05-02', 1, 'pi_3RKP1UFythxFnUWF0LDnilJG'),
(288, 7, 9, 1, '2025-05-03', 2, 1, 15.00, 'card', '2025-05-02', 0, 'pi_3RKPAaFythxFnUWF1DcxVJrD'),
(289, 7, 9, 1, '2025-05-05', 1, 4, 60.00, 'card', '2025-05-03', 0, 'pi_3RKlH8FythxFnUWF0AnTJ7rL'),
(290, 7, 15, 1, '2025-05-05', 4, 3, 45.00, 'card', '2025-05-03', 0, 'pi_3RKlJzFythxFnUWF08nffDZk'),
(291, 7, 17, 1, '2025-05-05', 2, 3, 45.00, 'card', '2025-05-03', 0, 'pi_3RKlKSFythxFnUWF1oHdLFwK'),
(292, 7, 15, 1, '2025-05-04', 1, 3, 45.00, 'card', '2025-05-03', 0, 'pi_3RKm5AFythxFnUWF19kYuCuJ'),
(293, 7, 2, 1, '2025-05-05', 7, 1, 15.00, 'card', '2025-05-04', 0, 'pi_3RL2JqFythxFnUWF1CtgGeVO'),
(294, 7, 3, 1, '2025-05-05', 3, 1, 15.00, 'card', '2025-05-04', 0, 'pi_3RL3jpFythxFnUWF0wcI08nG'),
(295, 7, 9, 1, '2025-05-05', 10, 1, 15.00, 'paypal', '2025-05-04', 0, 'pi_3RL5BoFythxFnUWF02ISazrk'),
(296, 13, 15, 1, '2025-05-05', 5, 1, 15.00, 'paypal', '2025-05-04', 0, 'pi_3RL5xcFythxFnUWF0lhofzS4'),
(297, 13, 17, 1, '2025-05-05', 13, 1, 15.00, 'paypal', '2025-05-04', 0, 'pi_3RL6CpFythxFnUWF0gllVwJF'),
(298, 13, 15, 1, '2025-05-08', 12, 1, 15.00, 'card', '2025-05-06', 1, 'pi_3RL6DbFythxFnUWF1H9I53yW'),
(299, 13, 3, 1, '2025-05-05', 8, 1, 15.00, 'card', '2025-05-04', 0, 'pi_3RL6HLFythxFnUWF04mU5GsZ'),
(300, 13, 9, 1, '2025-05-05', 6, 1, 15.00, 'card', '2025-05-04', 0, 'pi_3RL6JyFythxFnUWF1q3COutJ'),
(301, 7, 15, 1, '2025-05-05', 9, 1, 15.00, 'card', '2025-05-04', 0, 'pi_3RL8BXFythxFnUWF1LxRaXsP'),
(302, 7, 9, 1, '2025-05-12', 1, 1, 15.00, 'paypal', '2025-05-06', 1, 'pi_3RL8BzFythxFnUWF1hEtnMaY'),
(303, 7, 17, 1, '2025-05-14', 1, 3, 45.00, 'paypal', '2025-05-06', 1, 'pi_3RL8L9FythxFnUWF1DgDSiKT'),
(304, 19, 17, 2, '2025-05-06', 1, 4, 72.00, 'card', '2025-05-12', 0, NULL),
(305, 7, 2, 1, '2025-05-06', 3, 3, 45.00, 'card', '2025-05-06', 0, 'pi_3RLgJwFythxFnUWF01rGov8b'),
(306, 7, 17, 1, '2025-05-11', 1, 20, 300.00, 'card', '2025-05-06', 1, NULL),
(308, 20, 2, 2, '2025-06-18', 5, 6, 108.00, 'card', '2025-05-06', 1, NULL),
(309, 13, 17, 2, '2025-06-23', 3, 5, 90.00, 'card', '2025-05-06', 1, NULL),
(310, 13, 2, 2, '2025-06-20', 8, 5, 90.00, 'card', '2025-05-06', 1, NULL),
(311, 13, 3, 3, '2025-06-13', 11, 20, 420.00, 'card', '2025-05-19', 1, NULL),
(312, 13, 3, 3, '2025-06-20', 1, 5, 105.00, 'card', '2025-05-06', 1, NULL),
(313, 13, 2, 2, '2025-06-19', 6, 8, 144.00, 'card', '2025-05-06', 1, NULL),
(314, 7, 2, 3, '2025-06-11', 1, 3, 63.00, 'card', '2025-05-07', 1, 'pi_3RLvaRFythxFnUWF0BEp0aum'),
(315, 7, 2, 3, '2025-05-16', 1, 3, 63.00, 'card', '2025-05-07', 1, 'pi_3RMG1HFythxFnUWF1N4sXIvB'),
(316, 7, 3, 2, '2025-05-16', 11, 4, 72.00, 'card', '2025-05-07', 1, 'pi_3RMG1qFythxFnUWF0WbA3kn3'),
(318, 18, 17, 3, '2025-06-17', 11, 3, 63.00, 'card', '2025-05-08', 1, NULL),
(319, 7, 9, 3, '2025-05-17', 1, 3, 63.00, 'paypal', '2025-05-08', 1, 'pi_3RMZS9FythxFnUWF0IrKmum6'),
(320, 7, 15, 1, '2025-05-14', 2, 3, 45.00, 'card', '2025-05-08', 1, 'pi_3RMZWLFythxFnUWF1LLa2ZcW'),
(324, 7, 3, 3, '2025-05-27', 14, 4, 84.00, 'card', '2025-05-08', 1, 'pi_3RMZk1FythxFnUWF1lbG4Sot'),
(325, 7, 2, 2, '2025-05-17', 14, 3, 54.00, 'card', '2025-05-08', 1, 'pi_3RMbeuFythxFnUWF1KX1fNzC'),
(327, 18, 9, 1, '2025-05-27', 5, 3, 45.00, 'card', '2025-05-08', 1, 'pi_3RMcGoFythxFnUWF04oQxNN2'),
(328, 7, 15, 1, '2025-05-31', 14, 3, 45.00, 'card', '2025-05-08', 1, 'pi_3RMcKMFythxFnUWF1KqRaJqi'),
(329, 7, 17, 1, '2025-05-31', 1, 3, 45.00, 'card', '2025-05-08', 1, 'pi_3RMclDFythxFnUWF1ANTXAeH'),
(330, 7, 3, 3, '2025-05-25', 13, 3, 63.00, 'card', '2025-05-08', 1, 'pi_3RMcw5FythxFnUWF0BCEeDhz'),
(331, 7, 2, 3, '2025-05-27', 10, 3, 63.00, 'paypal', '2025-05-09', 1, 'pi_3RMlxkFythxFnUWF0AGzNJJJ'),
(337, 7, 2, 2, '2025-05-11', 2, 2, 36.00, 'card', '2025-05-10', 1, 'pi_3RNHukFythxFnUWF0FkMNicT'),
(338, 7, 3, 3, '2025-05-31', 5, 3, 63.00, 'card', '2025-05-10', 1, 'pi_3RNHvrFythxFnUWF0QgcAlYJ'),
(339, 7, 3, 3, '2025-05-10', 14, 3, 63.00, 'card', '2025-05-10', 1, 'pi_3RNI3vFythxFnUWF1jzdbvl1'),
(340, 7, 3, 2, '2025-05-16', 5, 3, 54.00, 'card', '2025-05-10', 1, 'pi_3RNKWHFythxFnUWF0GFFZ5WV'),
(341, 7, 9, 3, '2025-05-11', 7, 3, 63.00, 'paypal', '2025-05-10', 1, 'pi_3RNKdxFythxFnUWF00nWN4Mp'),
(343, 7, 2, 3, '2025-05-26', 13, 3, 63.00, 'card', '2025-05-11', 1, 'pi_3RNbblFythxFnUWF1enILaiJ'),
(344, 7, 9, 3, '2025-05-15', 4, 3, 63.00, 'paypal', '2025-05-12', 1, 'pi_3RO5nZFythxFnUWF0IDEByZI'),
(345, 7, 9, 1, '2025-05-27', 2, 3, 45.00, 'card', '2025-05-13', 1, 'pi_3ROLlxFythxFnUWF0MkCOxPg'),
(346, 7, 15, 3, '2025-05-17', 4, 4, 84.00, 'paypal', '2025-05-13', 1, 'pi_3ROLnWFythxFnUWF1Xr49xCQ'),
(347, 18, 17, 3, '2025-05-16', 3, 3, 63.00, 'card', '2025-05-14', 1, 'pi_3ROcl5FythxFnUWF1StXdS6x'),
(348, 7, 2, 3, '2025-05-17', 2, 3, 63.00, 'paypal', '2025-05-14', 1, 'pi_3ROjSzFythxFnUWF1lzrhUpR'),
(349, 7, 3, 3, '2025-05-17', 5, 3, 63.00, 'paypal', '2025-05-14', 1, 'pi_3ROjUcFythxFnUWF192eSmMh'),
(351, 7, 9, 1, '2025-05-15', 8, 1, 15.00, 'paypal', '2025-05-14', 1, 'pi_3ROjq6FythxFnUWF1so5RMHD'),
(353, 7, 17, 1, '2025-05-15', 1, 2, 30.00, 'paypal', '2025-05-14', 1, 'pi_3ROjr0FythxFnUWF1S7Acpf2'),
(354, 7, 15, 1, '2025-05-22', 14, 3, 45.00, 'paypal', '2025-05-14', 1, 'pi_3ROjrLFythxFnUWF1An8SFjy'),
(355, 7, 2, 3, '2025-05-22', 1, 3, 63.00, 'paypal', '2025-05-14', 1, 'pi_3ROo98FythxFnUWF0nGJmCQh'),
(356, 7, 9, 3, '2025-05-23', 14, 4, 84.00, 'card', '2025-05-16', 1, 'pi_3RPUwsFythxFnUWF12oTBw4q'),
(357, 7, 17, 3, '2025-05-23', 7, 3, 63.00, 'paypal', '2025-05-17', 1, 'pi_3RPlv0FythxFnUWF077hDxQC'),
(358, 7, 2, 1, '2025-05-17', 12, 1, 15.00, 'paypal', '2025-05-17', 1, 'pi_3RPnZuFythxFnUWF1Ih8gZ3L'),
(359, 7, 15, 3, '2025-05-17', 13, 2, 42.00, 'paypal', '2025-05-17', 1, 'pi_3RPnptFythxFnUWF1h1fsRdn'),
(360, 7, 2, 3, '2025-05-21', 6, 3, 63.00, 'paypal', '2025-05-18', 1, 'pi_3RQ3PwFythxFnUWF1A8PnNzK'),
(361, 7, 3, 3, '2025-05-22', 5, 1, 21.00, 'card', '2025-05-18', 1, 'pi_3RQ4X0FythxFnUWF0qWfejqw'),
(362, 7, 9, 3, '2025-05-23', 11, 4, 84.00, 'card', '2025-05-18', 1, 'pi_3RQGXQFythxFnUWF1c8f3GT0'),
(363, 13, 17, 2, '2025-05-19', 8, 10, 180.00, 'card', '2025-05-16', 0, NULL),
(364, 56, 15, 3, '2025-06-19', 8, 3, 63.00, 'card', '2025-05-19', 1, NULL),
(365, 20, 3, 3, '2025-06-10', 9, 5, 105.00, 'card', '2025-05-19', 1, NULL),
(367, 55, 2, 3, '2025-06-10', 7, 2, 42.00, 'card', '2025-05-19', 1, NULL),
(369, 52, 9, 2, '2025-06-12', 8, 3, 54.00, 'card', '2025-05-31', 0, NULL),
(370, 54, 2, 3, '2025-06-10', 11, 4, 84.00, 'card', '2025-05-19', 1, NULL),
(371, 54, 2, 3, '2025-06-19', 13, 5, 105.00, 'card', '2025-05-19', 1, NULL),
(373, 55, 2, 3, '2025-06-06', 9, 3, 63.00, 'card', '2025-05-19', 1, NULL),
(375, 7, 15, 1, '2025-05-21', 8, 1, 15.00, 'card', '2025-05-20', 1, 'pi_3RQz5bFythxFnUWF0er6fT6A'),
(376, 7, 2, 2, '2025-05-21', 5, 2, 36.00, 'card', '2025-05-20', 1, 'pi_3RQzRMFythxFnUWF0VrfWOTV'),
(377, 7, 3, 3, '2025-05-21', 11, 2, 42.00, 'card', '2025-05-20', 1, 'pi_3RQzYcFythxFnUWF1ofdpvja'),
(379, 7, 9, 3, '2025-05-23', 10, 3, 63.00, 'card', '2025-05-21', 1, 'pi_3RRBGJFythxFnUWF1npFfbNz'),
(380, 7, 2, 3, '2025-06-11', 8, 3, 63.00, 'card', '2025-05-21', 1, NULL),
(381, 7, 15, 2, '2025-05-31', 4, 3, 54.00, 'card', '2025-05-27', 1, 'pi_3RTW7zFythxFnUWF0dKqSkqv');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre_categoria`) VALUES
(1, 'F1'),
(2, 'Karting'),
(3, 'Motorsports');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `emp_noticia` tinyint(1) DEFAULT 0,
  `emp_evento` tinyint(1) DEFAULT 0,
  `emp_carreras` tinyint(1) DEFAULT 0,
  `esAdmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `usuario_id`, `emp_noticia`, `emp_evento`, `emp_carreras`, `esAdmin`) VALUES
(2, 14, 1, 1, 1, 0),
(3, 15, 1, 0, 1, 0),
(9, 39, 1, 1, 1, 0),
(10, 40, 1, 0, 0, 0),
(11, 21, 1, 1, 1, 1),
(12, 41, 1, 1, 1, 1),
(14, 44, 1, 1, 1, 1),
(15, 45, 0, 1, 1, 0),
(16, 48, 1, 1, 1, 1),
(17, 49, 0, 1, 1, 0),
(18, 50, 0, 1, 0, 0),
(19, 51, 1, 1, 0, 0),
(20, 57, 1, 1, 0, 0),
(21, 58, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `tipo_evento_id` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `precio` double NOT NULL,
  `fecha` date NOT NULL,
  `franja_horaria_id` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `empleados_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `nombre`, `descripcion`, `tipo_evento_id`, `imagen`, `precio`, `fecha`, `franja_horaria_id`, `capacidad`, `empleados_id`) VALUES
(2, 'Networking Empresarial', 'Evento de networking para profesionales.', 1, 'networking.jpg', 20, '2025-03-10', 1, 100, 3),
(91, 'Puertas Abiertas: Visita Guiada', 'Evento para conocer nuestras instalaciones y servicios.', 3, 'puertas_abiertas.jpg', 0, '2025-04-20', 3, 150, 3),
(107, 'Exposición de Innovación en Karting 2025', 'Evento dedicado a los apasionados del karting', 2, '1745012873_56b906d8e4d2345b8792.jpg', 14.67, '2025-04-30', 8, 25, 2),
(108, 'Café y Conexiones', 'Un espacio relajado para conocer gente interesante', 1, '1745054145_e11d7da3f2368ae1c59e.jpg', 10.51, '2025-05-02', 3, 25, 2),
(109, 'Creatividad Sin Límites', 'Un espacio donde las ideas fluyen sin restricciones.', 7, '1745054781_fe06ccf2aa366c7e77e2.webp', 7.65, '2025-05-06', 2, 40, 2),
(110, 'Innovación Educativa Herramientas y Estrategias para Aula del Futuro', 'Explora nuevas formas de enseñar y aprender en este evento dedicado a la transformación educativa. A través de charlas, talleres interactivos y paneles con expertos, descubrirás metodologías activas, tecnologías emergentes y recursos prácticos para aplicar en el aula. Ideal para docentes, coordinadores pedagógicos y profesionales interesados en la mejora continua de la educación.', 5, '1745425128_1fc93d0349615052f74e.jpg', 14.33, '2025-05-16', 6, 20, 2),
(111, 'Conecta+', 'Oportunidades &amp; contactos', 1, '1745598407_23e27ccc999b8a811928.jpg', 12.27, '2025-06-08', 7, 40, 17),
(112, 'Innovación Educativa: Herramientas y Tendencias para el Futuro', 'Únete a este evento educativo diseñado para docentes, estudiantes, investigadores y profesionales del sector académico. Exploraremos las últimas herramientas tecnológicas, metodologías activas y tendencias que están transformando la educación.', 5, '1746207533_29437c899d5ac8a1bf31.webp', 24.99, '2025-06-13', 10, 36, 2),
(113, 'Exposición \"Arte y Movimiento: Perspectivas Contemporáneas\"', 'Sumérgete en una experiencia visual única que reúne a artistas contemporáneos locales e internacionales en una exposición que explora el movimiento, la identidad y la transformación a través de la pintura.', 2, '1746479267_e8e60f32fefa20696e22.webp', 25.99, '2025-05-27', 8, 50, 2),
(114, 'Futuro Conectado Innovación y Tecnología 2025', 'Únete a líderes, emprendedores y visionarios en el evento más relevante del año sobre innovación tecnológica. Durante dos días intensivos, exploraremos avances disruptivos en inteligencia artificial, IoT, blockchain, sostenibilidad digital y más.', 6, '1746542589_a571d05f3494582a2dd6.webp', 29.99, '2025-06-10', 5, 50, 18),
(115, 'Explosión Creativa: Imaginación sin Límites 2025', 'Sumérgete en un espacio donde el arte, el diseño, la música, la escritura y la innovación convergen. Explosión Creativa es un evento diseñado para despertar tu potencial creativo a través de talleres inmersivos, charlas inspiradoras y colaboraciones en vivo con artistas y creativos de todo el mundo.', 7, '1746546643_f4f004c794ef16ef7812.webp', 24.99, '2025-06-17', 6, 50, 2),
(116, 'Networking Power: Conectando Talentos 2025', '\"Networking Power: Conectando Talentos 2025\" es el evento insignia para profesionales que buscan potenciar su red de contactos y descubrir oportunidades colaborativas.', 1, '1746547012_d66fb9fa00f6e9a0610c.jpg', 14.99, '2025-06-20', 9, 50, 15),
(135, 'El Mundo del Karting: Historia, Innovación y Pasión sobre Ruedas', '<p>La exposición <em data-start=\"323\" data-end=\"347\">El Mundo del Karting</em>&nbsp;ofrece una visión completa de la historia y la evolución de este deporte desde sus inicios hasta su importancia en el automovilismo profesional. A través de una serie de karts históricos, fotos, videos y simuladores interactivos, los visitantes podrán conocer las primeras competiciones de karting, los avances tecnológicos en los vehículos y cómo este deporte ha sido la cantera de algunos de los más grandes pilotos del mundo.</p>', 2, '1748463146_93609b2605f9441a7624.jpg', 29.99, '2025-06-28', 8, 50, 19),
(136, 'Networking en Boxes', '<p data-start=\"304\" data-end=\"801\"><strong data-start=\"304\" data-end=\"399\">Un encuentro profesional distinto, donde las conexiones fluyen tan rápido como un pit stop.</strong><br data-start=\"399\" data-end=\"402\">\r\n<em data-start=\"402\" data-end=\"423\">Networking en Boxes</em> es un evento pensado para reunir emprendedores, creativos, líderes y profesionales en un ambiente relajado pero con espíritu competitivo... fuera de la pista.<br data-start=\"582\" data-end=\"585\"><br></p>', 1, '1748463788_9a9eaa4936fc0cbd8016.jpg', 19.99, '2025-06-26', 10, 64, 18),
(137, 'Tech Pit: Ideas en Pole Position', '<p><strong data-start=\"343\" data-end=\"403\">Un espacio donde la innovación arranca a toda velocidad.</strong><br data-start=\"403\" data-end=\"406\">\r\n<em data-start=\"406\" data-end=\"416\">Tech Pit</em> es un evento de tecnología e innovación que reúne a mentes inquietas, emprendedores tech, desarrolladores, diseñadores y curiosos digitales en un entorno poco convencional: un espacio ambientado en el mundo del karting.</p>', 6, '1748464012_fbeb467d73355bc1bdd5.png', 14.99, '2025-06-20', 7, 100, 17),
(138, 'Escudería del Saber - Aprender en Modo Carrera', '<p data-start=\"405\" data-end=\"518\"><strong data-start=\"405\" data-end=\"518\">Una experiencia educativa diferente, donde el aula se transforma en paddock y el conocimiento toma velocidad.</strong></p><p>\r\n</p><p data-start=\"520\" data-end=\"725\"><em data-start=\"520\" data-end=\"541\">Escudería del Saber</em> es un evento pensado para estudiantes de primaria y secundaria que combina creatividad, trabajo en equipo y aprendizaje interdisciplinario en un entorno inspirado en el automovilismo.</p>', 5, '1748464695_f230279a3468e4a50cf3.png', 9.99, '2025-06-19', 5, 75, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `franjas_horarias`
--

CREATE TABLE `franjas_horarias` (
  `id` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `franjas_horarias`
--

INSERT INTO `franjas_horarias` (`id`, `hora_inicio`, `hora_fin`, `descripcion`) VALUES
(1, '08:00:00', '09:00:00', 'Mañana Temprano'),
(2, '09:00:00', '10:00:00', 'Mañana'),
(3, '10:00:00', '11:00:00', 'Media Mañana'),
(4, '11:00:00', '12:00:00', 'Antes del Mediodía'),
(5, '12:00:00', '13:00:00', 'Mediodía'),
(6, '13:00:00', '14:00:00', 'Almuerzo'),
(7, '14:00:00', '15:00:00', 'Siesta'),
(8, '15:00:00', '16:00:00', 'Tarde Temprano'),
(9, '16:00:00', '17:00:00', 'Media Tarde'),
(10, '17:00:00', '18:00:00', 'Tarde'),
(11, '18:00:00', '19:00:00', 'Tarde-Noche'),
(12, '19:00:00', '20:00:00', 'Noche Temprana'),
(13, '20:00:00', '21:00:00', 'Noche'),
(14, '21:00:00', '22:00:00', 'Noche Avanzada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `subtitulo` varchar(100) NOT NULL,
  `contenido` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `fecha_publicacion` date NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `visitas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `subtitulo`, `contenido`, `imagen`, `video`, `fecha_publicacion`, `id_categoria`, `empleado_id`, `visitas`) VALUES
(2, 'Fernando Alonso firma su regreso al podio en el Gran Premio de Monaco 2023', 'El piloto español obtiene un tercer puesto en una carrera cargada de emoción y estrategia', '<p data-start=\"439\" data-end=\"704\">En una de las competiciones más emocionantes del calendario, Fernando Alonso logró regresar al podio en el Gran Premio de Mónaco 2023, logrando un tercer puesto que fue un reflejo de su habilidad estratégica, experiencia y el rendimiento de su equipo, Aston Martin.</p>\r\n<p data-start=\"706\" data-end=\"1081\">La carrera, que se desarrolló en el tradicional circuito urbano de Montecarlo, se caracterizó por la imprevisibilidad de las condiciones meteorológicas, lo que dificultó aún más las maniobras y la estrategia de los pilotos. Desde la salida, Alonso estuvo al acecho de sus rivales, gestionando perfectamente sus neumáticos y tomando decisiones clave en cuanto a los pit stops.</p>\r\n<p data-start=\"1083\" data-end=\"1502\">El piloto asturiano, quien ya había demostrado ser uno de los más constantes y competitivos en las primeras etapas de la temporada 2023, mostró toda su clase al superar a pilotos como Charles Leclerc y Sergio Pérez. Durante el tramo final de la carrera, cuando los riesgos se multiplicaban por la lluvia intermitente, Alonso logró mantener la calma y aprovechó al máximo las oportunidades que le ofrecieron sus rivales.</p>\r\n<p data-start=\"1504\" data-end=\"1787\">El podio fue dominado por Max Verstappen, quien se llevó la victoria con su Red Bull, y por Lewis Hamilton, quien ocupó la segunda plaza. Sin embargo, la presencia de Alonso en el tercer puesto fue un verdadero hito para Aston Martin, que sigue creciendo con el paso de cada carrera.</p>\r\n<p data-start=\"1789\" data-end=\"2130\">En declaraciones posteriores a la carrera, Alonso se mostró muy satisfecho con el resultado: \"Este tercer puesto es el resultado de un gran trabajo en equipo. Mónaco siempre es un desafío único y esta vez, las condiciones eran extremas. Estoy feliz por el equipo, por cómo hemos gestionado las complicaciones y por mi desempeño en la pista.\"</p>\r\n<p data-start=\"2132\" data-end=\"2492\">Este resultado reafirma el compromiso de Alonso con la lucha por estar en lo más alto, a pesar de los años de carrera que lleva en la Fórmula 1. La temporada 2023, que parecía ser una de transición para el piloto español, se ha convertido en una de las más prometedoras, con el objetivo claro de luchar por podios y victorias a medida que el campeonato avanza.</p>\r\n<p data-start=\"2494\" data-end=\"2780\">Los aficionados del automovilismo no han dejado de aplaudir el esfuerzo y la determinación de Fernando Alonso, quien sigue demostrando que su pasión por la Fórmula 1 sigue intacta y su nivel de competitividad sigue siendo de primer orden, incluso en su cuarta década dentro del deporte.</p><span style=\"font-size: 24px;\">\r\n</span>', 'ejemplo1Noticia.jpg', '1746638863_5d4f9849b8d147f47cb2.mp4', '2023-03-26', 1, 2, 89),
(3, 'Arranca el campeonato de España de Karting', 'El campeonato comienza con gran expectación en los circuitos', '<p data-start=\"229\" data-end=\"803\">El Campeonato de España de Karting 2025 ha dado el pistoletazo de salida con una gran expectación en los circuitos del país, donde miles de aficionados y pilotos se han reunido para presenciar y participar en una de las competiciones más emocionantes y prometedoras del automovilismo juvenil. Con la participación de jóvenes talentos de todo el territorio nacional, esta temporada se perfila como una de las más emocionantes y competitivas de los últimos años, con carreras que se auguran llenas de adrenalina, maniobras espectaculares y una lucha sin cuartel por el título.</p>\r\n<p data-start=\"805\" data-end=\"1315\">Los primeros compases del campeonato han demostrado que el nivel de los pilotos españoles ha alcanzado nuevas cotas, y que la cantera nacional de karting sigue produciendo jóvenes promesas con gran potencial para triunfar en categorías superiores del automovilismo. Con un enfoque especial en el desarrollo de nuevos talentos, el campeonato ha comenzado con gran fervor y la presencia de algunos de los mejores pilotos de la historia de este deporte, quienes han puesto el listón muy alto para los más jóvenes.</p>\r\n<p data-start=\"1317\" data-end=\"1954\">En el circuito de Zuera, donde se disputó la primera prueba del campeonato, la emoción no se hizo esperar. Los pilotos, desde los más pequeños hasta los más experimentados, demostraron un nivel impresionante desde las pruebas de clasificación. El clima de camaradería y respeto entre los competidores se reflejó en una jornada llena de emoción y sacrificio, con victorias que no fueron fáciles de alcanzar. Cada curva y cada recta de la pista representaron un nuevo desafío para los jóvenes pilotos, quienes deben aprender a manejar no solo el kart, sino también la presión de estar ante su público y en un escenario de alta competición.</p>\r\n<p data-start=\"1956\" data-end=\"2423\">Uno de los puntos destacados de la jornada inaugural fue la actuación de algunos de los pilotos más prometedores de la nueva generación, que están demostrando su gran talento y ambición por escalar posiciones en el mundo del karting. Entre ellos, varios nombres de la cantera nacional han brillado con luz propia, destacándose no solo por su velocidad, sino también por su capacidad para gestionar las estrategias de carrera y mantener la calma en momentos decisivos.</p>\r\n<p data-start=\"2425\" data-end=\"3008\">El Campeonato de España de Karting es conocido por ser una plataforma fundamental para el desarrollo de pilotos que, en el futuro, podrían dar el salto a categorías superiores como la Fórmula 4, la Eurocopa de Fórmula Renault, o incluso la Fórmula 1. El campeonato de este año no es una excepción, y ya hay varios equipos y representantes de categorías superiores que observan de cerca el rendimiento de los jóvenes talentos. Las escuderías saben que es en el karting donde se forjan las bases de los futuros campeones, y por ello el seguimiento a esta competición es cada vez mayor.</p>\r\n<p data-start=\"3010\" data-end=\"3545\">La emoción también se trasladó a las gradas, donde los aficionados, familiares y seguidores del karting mostraron su incondicional apoyo a los pilotos. A pesar de ser una competición juvenil, el nivel de entusiasmo y la calidad de los espectáculos vividos en el circuito dejaron claro que este campeonato es una de las citas más esperadas del calendario deportivo nacional. Los aficionados vibraron con cada adelantamiento, cada derrapaje controlado y con los inicios de lo que promete ser una temporada llena de emoción y competencia.</p>\r\n<p data-start=\"3547\" data-end=\"3952\">A medida que avanza el campeonato, las expectativas crecen aún más. Las próximas pruebas, que se celebrarán en circuitos emblemáticos como el de Campillos, Alcarrás y La Rasa, también prometen ofrecer una gran dosis de emoción. Cada circuito presenta un reto diferente, y los pilotos tendrán que adaptarse rápidamente a las características únicas de cada trazado para maximizar sus posibilidades de éxito.</p>\r\n<p data-start=\"3954\" data-end=\"4482\">Los organizadores del evento han subrayado la importancia de la formación integral de los jóvenes pilotos, destacando que el karting no solo es una escuela de habilidades técnicas y físicas, sino también una plataforma donde los jóvenes aprenden valores como el trabajo en equipo, el sacrificio, la disciplina y la perseverancia. Además, el karting es una disciplina que fomenta la igualdad, ya que todos los pilotos compiten en condiciones similares, lo que permite que el talento sea el verdadero protagonista en cada carrera.</p>\r\n<p data-start=\"4484\" data-end=\"4930\">Por otro lado, los entrenadores y técnicos que acompañan a los pilotos en este campeonato también desempeñan un papel crucial. La preparación y el apoyo técnico detrás de cada joven piloto son esenciales para maximizar su rendimiento en la pista. Durante este arranque de temporada, muchos equipos han ajustado sus estrategias, optimizando los karts para adaptarse a las condiciones cambiantes y a los nuevos desafíos que presentan los circuitos.</p>\r\n<p data-start=\"4932\" data-end=\"5447\">A medida que el campeonato avance, las apuestas por los favoritos para alzarse con el título de campeón de España de Karting 2025 se irán aclarando. Los aficionados ya tienen sus ojos puestos en los pilotos más consistentes, aquellos que han mostrado una gran regularidad en los entrenamientos y que se perfilan como los grandes contendientes al título. Sin embargo, como siempre en el karting, no hay nada seguro, y es precisamente esta incertidumbre la que mantiene la emoción y la adrenalina a niveles altísimos.</p>\r\n<p data-start=\"5449\" data-end=\"6051\">En resumen, el Campeonato de España de Karting 2025 ha comenzado con una gran dosis de emoción y competitividad. Con jóvenes pilotos dispuestos a dar lo mejor de sí mismos, una infraestructura impecable y el apoyo incondicional de los aficionados, esta temporada promete ser una de las más inolvidables de la historia de la competición. Queda mucho por recorrer, pero lo que es seguro es que las emociones, los desafíos y las sorpresas seguirán a lo largo de todo el año, dejando una huella imborrable en los corazones de los aficionados y en la trayectoria de los jóvenes talentos del karting español.</p>', 'ejemplo2Noticia.png', '1748462801_9c17369a457e99f7dbcd.mp4', '2025-03-02', 2, 2, 5),
(4, 'Posiciones del Dakar 2025', 'La competición presenta sorpresas en cada etapa', '<p data-start=\"280\" data-end=\"768\">La edición 2025 del Rally Dakar está siendo una de las más emocionantes y sorprendentes de los últimos años, con giros inesperados y actuaciones de pilotos que han dejado a todos con la boca abierta. En cada una de las etapas de esta competición, que ha comenzado a recorrer las duras y variadas rutas del desierto, las sorpresas no han dejado de llegar, y las posiciones en la clasificación cambian constantemente, lo que hace que los aficionados sigan con gran expectación cada jornada.</p>\r\n<p data-start=\"770\" data-end=\"1293\">A lo largo de las primeras etapas, se ha visto a pilotos de diferentes categorías luchando con todo para mantenerse al frente de la clasificación general. Desde los coches, motos, quads hasta los camiones, todos los equipos han tenido que poner a prueba no solo su destreza al volante, sino también su capacidad para tomar decisiones estratégicas bajo presión, enfrentándose a terrenos impredecibles, temperaturas extremas y condiciones climáticas que han ido desde la tormenta de arena hasta la intensa calor del desierto.</p>\r\n<p data-start=\"1295\" data-end=\"1889\">Una de las grandes sorpresas de este Dakar 2025 ha sido la actuación de varios pilotos menos conocidos, quienes han dado la campanada en las primeras etapas, desbancando a algunos de los favoritos a llevarse la victoria. Estos pilotos han logrado posicionarse entre los primeros, demostrando que el Dakar es una competición donde todo puede pasar y donde la habilidad, la preparación y la resistencia son tan importantes como la experiencia. En una edición donde se ha visto una gran paridad entre los competidores, la tensión aumenta con cada kilómetro recorrido, y los errores no se perdonan.</p>\r\n<p data-start=\"1891\" data-end=\"2523\">En la categoría de coches, el líder de la clasificación general ha cambiado en varias ocasiones, con los equipos oficiales de marcas como Toyota, Audi y Peugeot, buscando imponerse en un terreno que, hasta el momento, ha sido impredecible. Los nuevos coches híbridos de Audi, que debutan en este Dakar, han sorprendido por su rendimiento en las primeras etapas, pero también han tenido que lidiar con diversos problemas técnicos que han puesto a prueba la fiabilidad de su tecnología. Los pilotos de Toyota, por otro lado, han demostrado una gran consistencia y rapidez, aunque las sorpresas en el rally aún están lejos de terminar.</p>\r\n<p data-start=\"2525\" data-end=\"3130\">Uno de los momentos más emocionantes de la edición 2025 se produjo cuando el líder de la clasificación general en la categoría de motos, un piloto joven de origen sudamericano, sufrió una caída en una de las etapas más complicadas del rally, abriendo la puerta a otros competidores que no esperaban tener tanta suerte. Este cambio en la clasificación generó un vuelco en las apuestas, y los expertos ya no pueden predecir quién será el ganador definitivo. Las motos han sido una de las categorías más competitivas, con pilotos veteranos y novatos luchando palmo a palmo en un terreno sumamente desafiante.</p>\r\n<p data-start=\"3132\" data-end=\"3594\">En cuanto a los quads, la lucha también ha estado reñida. Los competidores se han enfrentado a desiertos de dunas enormes, caminos rocosos y tramos de alta velocidad. Los pilotos de quads han mostrado una gran capacidad para adaptarse a las condiciones extremas del Dakar, y aunque algunos favoritos han perdido terreno debido a errores o problemas mecánicos, otros han sabido aprovechar las dificultades de sus rivales para subir posiciones en la clasificación.</p>\r\n<p data-start=\"3596\" data-end=\"3988\">Por su parte, los camiones, que siempre son una de las categorías más impresionantes por la enorme maquinaria que manejan, han continuado sorprendiendo por su resistencia. Los equipos rusos y europeos, que en años anteriores dominaron la categoría, han tenido que enfrentarse a una feroz competencia de equipos sudamericanos y asiáticos, quienes han mejorado su rendimiento de manera notable.</p>\r\n<p data-start=\"3990\" data-end=\"4632\">Una de las claves de este Dakar 2025 está siendo la estrategia. Los equipos deben no solo mantener un ritmo constante, sino también gestionar perfectamente los recursos del vehículo, como el combustible, los neumáticos y la fiabilidad mecánica. El calor extremo del desierto y las duras condiciones de navegación, que exigen a los pilotos la máxima concentración, son factores que han jugado un papel crucial en la clasificación. Los problemas de fiabilidad, aunque son inevitables en una competición tan exigente, han sido una sorpresa continua, con algunos de los vehículos más preparados sufriendo averías que han costado valiosos minutos.</p>\r\n<p data-start=\"4634\" data-end=\"5087\">Los organizadores del Dakar 2025 han destacado que, a pesar de las dificultades, la edición de este año ha sido muy positiva en cuanto a la seguridad, ya que se ha dado especial atención a los protocolos médicos y de asistencia, dado que el rally atraviesa algunas de las zonas más remotas del mundo. Los equipos de apoyo han estado atentos en todo momento, ofreciendo la ayuda necesaria a los pilotos que han sufrido accidentes o problemas en la pista.</p>\r\n<p data-start=\"5089\" data-end=\"5501\">El Dakar es una prueba que pone a prueba los límites de los pilotos y los vehículos, y en esta edición no ha sido la excepción. Las sorpresas se siguen acumulando en cada etapa, y cada jornada es una nueva oportunidad para que los competidores den lo mejor de sí mismos. Sin duda, las posiciones seguirán cambiando conforme el rally avanza, y todo apunta a que la lucha por la victoria será más reñida que nunca.</p>\r\n<p data-start=\"5503\" data-end=\"5992\">A medida que el Dakar 2025 se acerca a su recta final, los equipos y los pilotos se preparan para afrontar las etapas más duras, donde cada segundo cuenta y donde los errores pueden ser fatales. Sin embargo, lo que está claro es que este rally, con sus giros inesperados y con su nivel de competitividad, se está consolidando como una de las ediciones más emocionantes y destacadas de la historia del Dakar. La carrera continúa, y la batalla por las posiciones sigue más abierta que nunca.</p>', 'ejemplo3Noticia.jpg', '1748462217_c901851758a44dd51688.mp4', '2025-03-03', 3, 2, 16),
(5, 'Carlos Sainz en su primer día en Williams', 'El piloto visita por primera vez la fábrica y sorprende a todos', '<p data-start=\"294\" data-end=\"725\" class=\"\"><span style=\"font-size: 24px;\">Carlos Sainz ha visitado por primera vez la fábrica del equipo Williams en Grove, en un gesto que ha despertado gran expectación dentro del paddock. El piloto madrileño, que aún no ha confirmado su destino para la temporada 2026 tras su salida de Ferrari, fue recibido por ingenieros y directivos del equipo británico, con quienes compartió impresiones y mostró un genuino interés por el proyecto técnico que encabeza James Vowles.</span></p><p data-start=\"294\" data-end=\"725\" class=\"\"><span style=\"font-size: 24px;\">Durante el recorrido, Sainz se mostró sorprendido por las mejoras recientes en las instalaciones y el enfoque innovador que está adoptando Williams para recuperar competitividad en la Fórmula 1. Fuentes cercanas al equipo aseguran que el piloto español pasó varias horas revisando datos, interactuando con el personal técnico y discutiendo el desarrollo del monoplaza actual, además de los planes a largo plazo.</span></p><p data-start=\"294\" data-end=\"725\" class=\"\"><span style=\"font-size: 24px;\">“Carlos se mostró muy implicado, preguntó mucho y prestó especial atención a cómo están enfocando la evolución aerodinámica y los cambios para 2026”, comentó un miembro del equipo que estuvo presente en la visita.</span></p><p data-start=\"294\" data-end=\"725\" class=\"\"><span style=\"font-size: 24px;\">La presencia de Sainz en la fábrica ha alimentado los rumores de un posible fichaje por Williams, escudería que busca dar un salto de calidad incorporando a un piloto experimentado que pueda liderar el proyecto en la nueva era técnica de la F1. Aunque ni el piloto ni el equipo han hecho declaraciones oficiales al respecto, su visita no ha pasado desapercibida en el mundo del automovilismo.</span></p>', 'ejemplo4Noticia.webp', '1745833167_d9e3b2aaaec7875219bf.mp4', '2025-03-04', 1, 2, 26),
(48, 'Verstappen conquista Mónaco', 'El neerlandés suma otra victoria sólida mientras Alonso brilla con una remontada magistral', 'Max Verstappen se alzó con una nueva victoria en el mítico Gran Premio de Mónaco, pero fueron Fernando Alonso y Esteban Ocon quienes se llevaron gran parte de los aplausos tras una carrera llena de estrategia, nervios y talento puro.\r\n\r\nVerstappen, implacable desde la pole, lideró de principio a fin con el control quirúrgico que lo caracteriza. Sin embargo, la emoción estuvo en la lucha por el segundo y tercer puesto. Fernando Alonso, partiendo desde la quinta posición, ejecutó una estrategia perfecta con su Aston Martin y protagonizó una espectacular remontada para terminar segundo, logrando su mejor resultado de la temporada.\r\n\r\n\"Estoy muy contento. Mónaco siempre exige lo mejor, y hoy hemos estado ahí\", dijo Alonso tras bajarse del coche, visiblemente emocionado.\r\n\r\nLa gran sorpresa del día fue Esteban Ocon. El piloto francés llevó su Alpine al límite y consiguió un meritorio tercer puesto tras aprovechar al máximo los errores ajenos y mostrar una solidez sorprendente. Es el primer podio para Alpine en la temporada, y uno que se celebró como una victoria en el garaje del equipo.\r\n\r\nCon este resultado, Verstappen se mantiene como líder destacado del campeonato, pero la emoción vuelve al paddock con equipos como Aston Martin y Alpine demostrando que aún tienen mucho que decir en 2025.\r\n\r\nPróxima parada: Canadá, donde el campeonato promete seguir al rojo vivo.', '1745834111_de43c55249c083572f0b.jpg', '1745834159_fd9e49cbb647e9e86f4f.mp4', '2025-04-24', 1, 15, 14),
(49, 'Hamilton en crisis: su inicio en Ferrari desata dudas', 'El siete veces campeón mundial no logra adaptarse al equipo italiano', '<p data-start=\"192\" data-end=\"753\">Lewis Hamilton, una de las figuras más emblemáticas de la Fórmula 1, atraviesa actualmente uno de los momentos más complejos de su carrera deportiva. Después de dominar la categoría durante más de una década con Mercedes, el cambio a Ferrari en la temporada 2025 parecía ser la oportunidad perfecta para escribir un nuevo capítulo en su ilustre historia. Sin embargo, lo que comenzó como un potencial regreso a la gloria se ha transformado en un desafío abrumador, un camino cuesta arriba que aún no parece tener la recompensa que el británico había anticipado.</p><p data-start=\"755\" data-end=\"1294\">Al inicio de la temporada, las expectativas eran altas. Ferrari, con su herencia de victorias y su pasión por la competición, parecía ser el equipo adecuado para Hamilton, quien a sus 40 años estaba buscando nuevos horizontes. Las promesas de un coche rápido, competitivo y capaz de desafiar a los líderes del campeonato como Red Bull y Mercedes hicieron que muchos, incluidas las altas esferas de la Fórmula 1, apostaran por un regreso triunfal del campeón británico. Sin embargo, la realidad ha sido más dura de lo que muchos imaginaban.</p><p data-start=\"1296\" data-end=\"1997\">La única victoria que ha logrado Hamilton hasta el momento en 2025 fue en la carrera al esprint del Gran Premio de China, un resultado que, aunque satisfactorio, no ha sido suficiente para silenciar las críticas que han comenzado a resonar con fuerza. En las demás citas, el rendimiento de Hamilton ha sido desalentador, con un desempeño que se ha distanciado notablemente del nivel al que estamos acostumbrados a verlo. En Arabia Saudí, por ejemplo, terminó en una decepcionante séptima posición, una plaza que dista mucho de la que se esperaría de un piloto de su calibre, y mucho más de la que consiguió su compañero de equipo, Charles Leclerc, quien logró subirse al podio con un gran rendimiento.</p><p data-start=\"1999\" data-end=\"2787\">Este contraste entre Hamilton y Leclerc ha alimentado las especulaciones sobre la adaptación del británico a Ferrari. Si bien la presión siempre ha sido parte de la vida de un piloto de Fórmula 1, en este caso parece que las expectativas no solo provienen del equipo, sino de la crítica feroz de los aficionados y expertos. Entre esos comentarios, destacan los de Ralf Schumacher, quien no ha dudado en sugerir que Hamilton debería considerar la opción del retiro si no consigue mejorar su rendimiento en el corto plazo. Para algunos, el dominio absoluto de Hamilton durante la era híbrida de Mercedes parece ser un recuerdo lejano, y la pregunta sobre si el británico tiene la capacidad de adaptarse a un equipo que no está en su mejor momento, comienza a generar más dudas que certezas.</p><p data-start=\"2789\" data-end=\"3420\">Sin embargo, aquellos que siguen la carrera de Hamilton saben que su historia está hecha de momentos de adversidad superados con resiliencia y determinación. Desde sus primeras temporadas en McLaren, donde compitió de igual a igual con un campeón como Fernando Alonso, hasta sus éxitos con Mercedes, Hamilton ha demostrado que no solo tiene el talento necesario para ganar, sino también la mentalidad de un verdadero campeón. En más de una ocasión, ha mostrado que sabe levantarse cuando las circunstancias lo ponen a prueba, algo que lo ha hecho trascender no solo como piloto, sino como una de las leyendas vivas de la Fórmula 1.</p><p data-start=\"3422\" data-end=\"4117\">Ahora, en 2025, Lewis Hamilton se enfrenta al reto más grande de su carrera: adaptarse a un Ferrari que todavía lucha por encontrar la consistencia y el rendimiento necesarios para competir de manera seria por el campeonato. El equipo de Maranello ha estado buscando durante años una combinación perfecta de coche, estrategia y piloto que lo devuelva a lo más alto. En este contexto, la presencia de Hamilton aporta una gran dosis de experiencia y liderazgo, pero también plantea una pregunta fundamental: ¿puede el británico, con 40 años y una carrera plagada de éxitos, adaptarse a un nuevo estilo de trabajo y, sobre todo, a un coche que aún no está a la altura de los mejores del campeonato?</p><p data-start=\"4119\" data-end=\"4919\">Ferrari es un equipo que históricamente ha sido sinónimo de pasión y ambición, pero también ha estado marcado por la falta de estabilidad en los últimos años. Si bien los coches rojos han mostrado destellos de velocidad, la fiabilidad y la estrategia han sido sus talones de Aquiles. En este escenario, Hamilton tiene la oportunidad de demostrar que su grandeza no se limita a un solo equipo, sino que se puede aplicar a cualquier desafío que enfrente. Su habilidad para leer las carreras, gestionar sus neumáticos y maximizar cada oportunidad le dan la capacidad de ser competitivo en cualquier circunstancia. Sin embargo, los años de experiencia también le han enseñado que la Fórmula 1 es un deporte de constantes cambios, y a veces, esos cambios pueden ser impredecibles y difíciles de gestionar.</p><p data-start=\"4921\" data-end=\"5441\">Por otro lado, el hecho de que su compañero de equipo, Charles Leclerc, esté superando a Hamilton en varias ocasiones ha aumentado la presión sobre el británico. Leclerc, un piloto joven y talentoso, tiene la motivación de establecerse como la referencia dentro del equipo, y esa competencia interna puede estar marcando la diferencia. Sin embargo, no debemos olvidar que Hamilton ha luchado contra compañeros de equipo mucho más jóvenes y ambiciosos a lo largo de su carrera, y ha salido victorioso en muchas ocasiones.</p><p data-start=\"5443\" data-end=\"5957\">El ambiente en Ferrari se ha vuelto tenso en cuanto a las expectativas que recaen sobre Hamilton. A pesar de las dificultades, el piloto británico sigue siendo una figura clave para el equipo, y su influencia en el desarrollo del coche será crucial a medida que avancen las próximas carreras. A medida que la temporada se alarga, la pregunta no es solo si Hamilton puede adaptarse, sino si tiene la paciencia y la perseverancia necesarias para seguir luchando por su lugar entre los mejores, ahora vestido de rojo.</p>', '1745794090_bd3e16899047965f96cb.jpg', '1745833101_aed2dd180ed5764fc7bb.mp4', '2025-04-27', 1, 2, 36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pistas`
--

CREATE TABLE `pistas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pistas`
--

INSERT INTO `pistas` (`id`, `nombre`, `precio`) VALUES
(1, 'Pista infantil', 15.00),
(2, 'Pista adolescentes', 18.00),
(3, 'Pista adultos', 21.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas_eventos`
--

CREATE TABLE `reservas_eventos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pagado` tinyint(1) NOT NULL DEFAULT 0,
  `payment_intent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas_eventos`
--

INSERT INTO `reservas_eventos` (`id`, `usuario_id`, `evento_id`, `cantidad`, `metodo_pago`, `fecha_pago`, `total`, `pagado`, `payment_intent_id`) VALUES
(37, 13, 2, 2, 'tarjeta', '2025-01-01', 40.00, 0, NULL),
(45, 7, 2, 1, 'tarjeta', '2025-03-06', 15.00, 0, NULL),
(102, 17, 107, 1, 'card', '2025-04-23', 14.67, 0, NULL),
(105, 7, 107, 3, 'card', '2025-04-19', 44.01, 1, 'pi_3RFZXhFythxFnUWF0meLkvxI'),
(106, 7, 109, 4, 'paypal', '2025-04-19', 30.60, 1, 'pi_3RFZbWFythxFnUWF0cRKVh8w'),
(107, 7, 108, 12, 'paypal', '2025-04-23', 126.12, 1, 'pi_3RH4jyFythxFnUWF1dm72QjE'),
(108, 13, 107, 9, 'paypal', '2025-04-23', 132.03, 1, 'pi_3RH4lgFythxFnUWF1tuJNGZo'),
(112, 19, 110, 1, 'card', '2025-05-05', 14.33, 1, NULL),
(113, 18, 108, 4, 'card', '2025-04-23', 42.04, 0, NULL),
(114, 17, 109, 5, 'card', '2025-05-05', 38.25, 1, NULL),
(115, 20, 110, 1, 'card', '2025-05-05', 14.33, 1, NULL),
(116, 17, 110, 5, 'card', '2025-05-06', 71.65, 1, NULL),
(117, 13, 108, 3, 'card', '2025-04-23', 31.53, 0, NULL),
(118, 18, 107, 2, 'card', '2025-04-23', 29.34, 0, NULL),
(119, 20, 107, 1, 'card', '2025-04-23', 14.67, 0, NULL),
(121, 19, 107, 3, 'card', '2025-04-23', 44.01, 0, NULL),
(130, 7, 110, 4, 'card', '2025-05-06', 57.32, 1, NULL),
(132, 56, 111, 8, 'paypal', '2025-04-26', 98.16, 1, 'pi_3RIBVOFythxFnUWF0llKGTDH'),
(133, 56, 110, 6, 'paypal', '2025-04-26', 85.98, 1, 'pi_3RIBWUFythxFnUWF0l8hnl5d'),
(135, 13, 110, 3, 'card', '2025-04-26', 42.99, 1, 'pi_3RID2hFythxFnUWF11O4f3ar'),
(136, 13, 111, 5, 'paypal', '2025-04-26', 61.35, 1, 'pi_3RID4nFythxFnUWF1QxRJ9KZ'),
(137, 13, 109, 5, 'paypal', '2025-04-26', 38.25, 1, 'pi_3RID76FythxFnUWF0IITTiWH'),
(140, 46, 109, 5, 'card', '2025-05-03', 38.25, 1, 'pi_3RKkUQFythxFnUWF19PPjnnp'),
(141, 46, 111, 6, 'paypal', '2025-05-03', 73.62, 1, 'pi_3RKkVZFythxFnUWF1ao1YLjy'),
(142, 46, 112, 6, 'paypal', '2025-05-03', 149.94, 1, 'pi_3RKlE7FythxFnUWF0u3sbddx'),
(144, 13, 112, 4, 'paypal', '2025-05-04', 99.96, 1, 'pi_3RL5pIFythxFnUWF0KMTUbjz'),
(146, 17, 113, 5, 'card', '2025-05-05', 129.95, 1, NULL),
(147, 20, 113, 2, 'paypal', '2025-05-05', 51.98, 1, NULL),
(148, 19, 112, 6, 'card', '2025-05-06', 149.94, 1, NULL),
(149, 19, 113, 3, 'card', '2025-05-06', 77.97, 1, NULL),
(150, 20, 114, 6, 'paypal', '2025-05-06', 179.94, 1, NULL),
(151, 20, 112, 4, 'card', '2025-05-06', 99.96, 1, NULL),
(152, 17, 116, 4, 'paypal', '2025-05-06', 59.96, 1, NULL),
(153, 46, 116, 6, 'card', '2025-05-06', 89.94, 1, NULL),
(154, 13, 115, 4, 'card', '2025-05-06', 99.96, 1, NULL),
(156, 18, 111, 5, 'card', '2025-05-08', 61.35, 1, NULL),
(159, 18, 116, 5, 'card', '2025-05-08', 74.95, 1, 'pi_3RMcErFythxFnUWF0DAjBx7c'),
(160, 18, 112, 4, 'paypal', '2025-05-08', 99.96, 1, 'pi_3RMcIrFythxFnUWF1M8bvScL'),
(164, 17, 112, 3, 'card', '2025-05-08', 74.97, 1, NULL),
(168, 46, 113, 8, 'paypal', '2025-05-09', 207.92, 1, NULL),
(170, 18, 115, 4, 'card', '2025-05-09', 99.96, 1, 'pi_3RMm0uFythxFnUWF19CsAF9z'),
(171, 55, 115, 5, 'card', '2025-05-18', 124.95, 1, NULL),
(203, 7, 114, 2, 'paypal', '2025-05-14', 59.98, 1, 'pi_3ROm0lFythxFnUWF07huBCdJ'),
(206, 7, 113, 1, 'paypal', '2025-05-14', 25.99, 1, 'pi_3ROmARFythxFnUWF1j5HNaF1'),
(207, 7, 111, 4, 'card', '2025-05-14', 49.08, 1, 'pi_3ROo7VFythxFnUWF0Vz0TSsz'),
(216, 55, 111, 3, 'card', '2025-05-18', 36.81, 1, NULL),
(218, 54, 113, 3, 'card', '2025-05-18', 77.97, 1, NULL),
(219, 56, 115, 4, 'card', '2025-05-18', 99.96, 1, NULL),
(220, 56, 116, 2, 'card', '2025-05-18', 29.98, 1, NULL),
(221, 17, 115, 3, 'paypal', '2025-05-18', 74.97, 1, 'pi_3RQGZfFythxFnUWF1NZLcPHg'),
(222, 52, 111, 3, 'card', '2025-05-18', 36.81, 1, NULL),
(224, 56, 113, 2, 'card', '2025-05-18', 51.98, 1, NULL),
(226, 18, 113, 2, 'card', '2025-05-19', 51.98, 1, NULL),
(227, 47, 115, 3, 'card', '2025-05-19', 74.97, 1, NULL),
(229, 20, 115, 3, 'card', '2025-05-19', 74.97, 1, NULL),
(230, 55, 116, 2, 'card', '2025-05-20', 29.98, 1, NULL),
(243, 17, 114, 4, 'card', '2025-05-19', 119.96, 1, NULL),
(249, 54, 111, 2, 'card', '2025-05-20', 24.54, 0, NULL),
(250, 60, 111, 2, 'card', '2025-05-20', 24.54, 0, NULL),
(252, 47, 111, 2, 'card', '2025-05-20', 24.54, 0, NULL),
(253, 55, 112, 7, 'card', '2025-05-20', 174.93, 0, NULL),
(256, 7, 112, 2, 'paypal', '2025-05-27', 49.98, 1, 'pi_3RTWSnFythxFnUWF0OCqO4uH'),
(257, 7, 115, 1, 'paypal', '2025-05-27', 24.99, 1, 'pi_3RTWcSFythxFnUWF1x6Uox4x');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_evento`
--

CREATE TABLE `tipo_evento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_evento`
--

INSERT INTO `tipo_evento` (`id`, `nombre`) VALUES
(1, 'Networking'),
(2, 'Exposición'),
(3, 'Puertas Abiertas'),
(5, 'Eventos Educativos'),
(6, 'Eventos de Innovación/Tecnología'),
(7, 'Eventos de Creatividad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `id` int(11) NOT NULL,
  `nombre_tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`id`, `nombre_tipo`) VALUES
(1, 'cliente'),
(2, 'empleado'),
(3, 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `expiracion_token` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `nombre`, `apellidos`, `email`, `contraseña`, `telefono`, `id_tipo`, `estado`, `token_recuperacion`, `expiracion_token`) VALUES
(7, 'alejandro42', 'Alejandro', 'Test', 'alejandro42@example.com', '351a10de42226e43ee37f10ac11a97a4', '600000007', 1, 1, NULL, NULL),
(13, 'Dani12', 'Daniel', 'Del pozo Medie', 'dani123@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '763453982', 1, 1, NULL, NULL),
(14, 'laugomez87', 'Laura', 'Gómez Mendoza', 'laugomez85@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '635908584', 2, 1, NULL, NULL),
(15, 'alejandro68', 'Alejandro', 'Rodríguez Munuera', 'alejandrorod@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '635978541', 2, 1, NULL, NULL),
(17, 'juan12', 'Juan', 'García Navarrete', 'juan@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '635482127', 1, 1, NULL, NULL),
(18, 'ruben12', 'Rubén', 'Sobrino Cabrera', 'rubyyii65@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '634861642', 1, 1, '6e8901beb4c0596b2bed11d2da248361605dd20d2bdf0fdf248f69d009fc07c5', '2025-05-15 10:02:22'),
(19, 'soler12', 'Jesús', 'Soler', 'soler@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '646844321', 1, 1, NULL, NULL),
(20, 'Pablo99', 'Pablo', 'Navarro', 'pablonavarro@gmail.es', '91ee17da5873ca7479ed1d0a27c8b14a', '961234567', 1, 1, NULL, NULL),
(21, 'admin1', 'Alejandro', 'Jiménez Cabrera', 'alej@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '635905582', 3, 1, NULL, NULL),
(39, 'roncero38', 'Toni', 'Roncero Palencia', 'roncero@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '615455447', 2, 1, NULL, NULL),
(40, 'Alberto77', 'Alberto', 'Moreno García', 'albertogarcia@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '633333222', 2, 0, NULL, NULL),
(41, 'nuevoadmin', 'Mario', 'Martinez', 'mario12@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '635908583', 3, 1, NULL, NULL),
(44, 'admin5', 'Jorge', 'Cuenca Ramos', 'jorgecuenca@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '654887787', 3, 1, NULL, NULL),
(45, 'javi428', 'Javier', 'Hernández González', 'javihergo@gmail.com', '28d726debe5e7edcd92d07c7c11b5094', '644125545', 2, 1, NULL, NULL),
(46, 'jorge64', 'Jorge', 'Salgado Herrera', 'jorgesalgado@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '635908589', 1, 1, NULL, NULL),
(47, 'Sergio89', 'Sergio', 'Martínez López', 'sergiomart@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '654785474', 1, 1, NULL, NULL),
(48, 'admin8', 'Manuel', 'Ruiz González', 'manuelruiz@gmail.com', '28d726debe5e7edcd92d07c7c11b5094', '633697874', 3, 1, NULL, NULL),
(49, 'Francisco35', 'Francisco', 'Romero Díaz', 'franciscoromero@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '698789547', 2, 1, NULL, NULL),
(50, 'David31', 'David', 'Perez Ramos', 'davidperez@gmail.com', '28d726debe5e7edcd92d07c7c11b5094', '654847474', 2, 1, NULL, NULL),
(51, 'Alvaro99', 'Alvaro', 'Garcia Gimenez', 'alvarogarcia@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '655477747', 2, 1, NULL, NULL),
(52, 'Mario56', 'Mario', 'Lopez Diaz', 'mariodiaz@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '658984444', 1, 1, NULL, NULL),
(54, 'Nerea53', 'Nerea', 'Martí Gonzalez', 'nereamarti@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '635886366', 1, 1, NULL, NULL),
(55, 'Miguel89', 'Miguel', 'Morales Castro', 'miguelmorales@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '655658998', 1, 1, NULL, NULL),
(56, 'Andrea12', 'Andrea', 'Velazquez Belmonte', 'andreavelazquez@gmail.com', '9f263a0b45e295f98fc3413bdbd44269', '645477744', 1, 1, NULL, NULL),
(57, 'Sofia53', 'Sofia', 'Martinez Gomez', 'sofiamartinez@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '654562114', 2, 1, NULL, NULL),
(58, 'Emilia64', 'Emilia', 'Castro Torres', 'emiliacastro@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '652114557', 2, 0, NULL, NULL),
(60, 'tomas12', 'Tomás', 'Fernández Gómez', 'tomasfernandez@gmail.com', '351a10de42226e43ee37f10ac11a97a4', '633225411', 1, 1, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `empleado_id` (`empleado_id`),
  ADD KEY `circuito_id` (`id_pistas`),
  ADD KEY `franja_horaria_id` (`franja_horaria_id`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_evento_id` (`tipo_evento_id`),
  ADD KEY `empleados_id` (`empleados_id`),
  ADD KEY `fk_eventos_franjas` (`franja_horaria_id`);

--
-- Indices de la tabla `franjas_horarias`
--
ALTER TABLE `franjas_horarias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `empleado_id` (`empleado_id`);

--
-- Indices de la tabla `pistas`
--
ALTER TABLE `pistas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas_eventos`
--
ALTER TABLE `reservas_eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `evento_id` (`evento_id`);

--
-- Indices de la tabla `tipo_evento`
--
ALTER TABLE `tipo_evento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=382;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT de la tabla `franjas_horarias`
--
ALTER TABLE `franjas_horarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de la tabla `pistas`
--
ALTER TABLE `pistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `reservas_eventos`
--
ALTER TABLE `reservas_eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- AUTO_INCREMENT de la tabla `tipo_evento`
--
ALTER TABLE `tipo_evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD CONSTRAINT `carreras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carreras_ibfk_2` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carreras_ibfk_3` FOREIGN KEY (`id_pistas`) REFERENCES `pistas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carreras_ibfk_4` FOREIGN KEY (`franja_horaria_id`) REFERENCES `franjas_horarias` (`id`);

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`tipo_evento_id`) REFERENCES `tipo_evento` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `eventos_ibfk_2` FOREIGN KEY (`empleados_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_eventos_franjas` FOREIGN KEY (`franja_horaria_id`) REFERENCES `franjas_horarias` (`id`);

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `noticias_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `noticias_ibfk_2` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservas_eventos`
--
ALTER TABLE `reservas_eventos`
  ADD CONSTRAINT `reservas_eventos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_eventos_ibfk_2` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
