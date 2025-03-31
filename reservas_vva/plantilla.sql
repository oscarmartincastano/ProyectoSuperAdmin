-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-03-2025 a las 08:30:21
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
-- Estructura de tabla para la tabla `acceso`
--

CREATE TABLE `acceso` (
  `id` int(20) NOT NULL,
  `activo` varchar(10) DEFAULT NULL,
  `inicio` date NOT NULL,
  `fin` date DEFAULT NULL,
  `apertura` time DEFAULT NULL,
  `cierre` time DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `num_usos` int(11) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `acceso_puerta`
--
CREATE TABLE `accesos_puerta` (
  `id` int(11) NOT NULL,
  `estado` varchar(125) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'cerrar',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accion`
--
CREATE TABLE `accion` (
  `id` int(11) NOT NULL,
  `mensaje` varchar(255) NOT NULL,
  `cod_error` int(20) NOT NULL,
  `tipo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `app_logs`
--

CREATE TABLE `app_logs` (
  `id` int(11) NOT NULL,
  `code` text DEFAULT NULL,
  `flotador_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `entrada_id` varchar(255) DEFAULT NULL,
  `pedido_id` text DEFAULT NULL,
  `participante_id` int(11) DEFAULT NULL,
  `lector_id` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bonos`
--

CREATE TABLE `bonos` (
  `id` int(11) NOT NULL,
  `nombre` text DEFAULT NULL,
  `num_usos` int(11) DEFAULT NULL,
  `precio` double(10,2) NOT NULL DEFAULT 0.00,
  `id_deporte` int(11) DEFAULT NULL,
  `descripcion` varchar(350) DEFAULT NULL,
  `activado` tinyint(1) NOT NULL DEFAULT 0,
  `id_instalacion` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bono_participante`
--

CREATE TABLE `bono_participante` (
  `id` int(11) NOT NULL,
  `id_participante` int(11) DEFAULT NULL,
  `fecha` varchar(255) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bono_usuario`
--

CREATE TABLE `bono_usuario` (
  `id` int(11) NOT NULL,
  `num_usos` int(11) DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_bono` int(11) DEFAULT NULL,
  `id_pedido` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campos_personalizados`
--

CREATE TABLE `campos_personalizados` (
  `id` int(11) NOT NULL,
  `id_instalacion` int(11) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `label` text NOT NULL,
  `opciones` text DEFAULT NULL,
  `required` tinyint(1) NOT NULL,
  `all_pistas` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobros`
--

CREATE TABLE `cobros` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `forma` varchar(255) NOT NULL,
  `cantidad` double NOT NULL,
  `notas` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `id_instalacion` int(11) NOT NULL,
  `max_reservas_tipo_espacio` text DEFAULT NULL,
  `allow_cancel` int(11) DEFAULT 0,
  `block_today` int(11) DEFAULT 0,
  `observaciones` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_ip`
--

CREATE TABLE `configuracion_ip` (
  `id` int(11) NOT NULL,
  `campo` varchar(255) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deportes`
--

CREATE TABLE `deportes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desactivaciones_periodicas`
--

CREATE TABLE `desactivaciones_periodicas` (
  `id` int(11) NOT NULL,
  `id_pista` int(11) NOT NULL,
  `dias` text NOT NULL,
  `hora_inicio` text NOT NULL,
  `hora_fin` text NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desactivacion_reservas`
--

CREATE TABLE `desactivacion_reservas` (
  `id` int(11) NOT NULL,
  `id_pista` int(11) NOT NULL,
  `reserva_id` int(11) DEFAULT NULL,
  `timestamp` int(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuentos`
--

CREATE TABLE `descuentos` (
  `id` int(11) NOT NULL,
  `id_servicio_padre` int(11) DEFAULT NULL,
  `id_servicio_descuento` int(11) DEFAULT NULL,
  `nuevo_precio` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias_festivos`
--

CREATE TABLE `dias_festivos` (
  `id` int(11) NOT NULL,
  `dia_festivo` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `id_instalacion` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `localizacion` text DEFAULT NULL,
  `precio_participante` double DEFAULT NULL,
  `id_deporte` int(11) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `num_participantes` int(11) NOT NULL,
  `id_tipo_participante` int(11) NOT NULL,
  `insc_fecha_inicio` datetime NOT NULL,
  `insc_fecha_fin` datetime NOT NULL,
  `renovacion_mes` int(11) NOT NULL DEFAULT 0,
  `entradas_agotadas` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `excepciones_desactivaciones_periodicas`
--

CREATE TABLE `excepciones_desactivaciones_periodicas` (
  `id` int(11) NOT NULL,
  `id_pista` int(11) NOT NULL,
  `timestamp` int(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exportusers`
--

CREATE TABLE `exportusers` (
  `COL 1` varchar(2) DEFAULT NULL,
  `COL 2` varchar(14) DEFAULT NULL,
  `COL 3` varchar(31) DEFAULT NULL,
  `COL 4` varchar(48) DEFAULT NULL,
  `COL 5` varchar(13) DEFAULT NULL,
  `COL 6` varchar(17) DEFAULT NULL,
  `COL 7` varchar(60) DEFAULT NULL,
  `COL 8` varchar(4) DEFAULT NULL,
  `COL 9` varchar(24) DEFAULT NULL,
  `COL 10` varchar(14) DEFAULT NULL,
  `COL 11` varchar(16) DEFAULT NULL,
  `COL 12` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fallos_recurrente`
--

CREATE TABLE `fallos_recurrente` (
  `id` int(11) NOT NULL,
  `error` longtext DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(125) NOT NULL,
  `horario` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instalaciones`
--

CREATE TABLE `instalaciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `tlfno` varchar(255) NOT NULL,
  `html_normas` text DEFAULT NULL,
  `servicios` text DEFAULT NULL,
  `horario` text DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `tipo_reservas_id` int(11) NOT NULL,
  `finalidad_eventos` varchar(255) DEFAULT 'servicios',
  `prefijo_pedido` varchar(100) DEFAULT NULL,
  `politica` longtext DEFAULT NULL,
  `politica_html` varchar(255) DEFAULT NULL,
  `terminos` longtext DEFAULT NULL,
  `terminos_html` varchar(255) DEFAULT NULL,
  `condiciones` longtext DEFAULT NULL,
  `condiciones_html` varchar(255) DEFAULT NULL,
  `ver_normas` tinyint(1) NOT NULL DEFAULT 1,
  `ver_servicios` tinyint(1) NOT NULL DEFAULT 1,
  `ver_horario` tinyint(1) NOT NULL DEFAULT 1,
  `ver_politica` tinyint(1) NOT NULL DEFAULT 1,
  `ver_condiciones` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_recibos_diario`
--

CREATE TABLE `log_recibos_diario` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(255) DEFAULT NULL,
  `mensaje` longtext DEFAULT NULL,
  `pago_recurrente` varchar(255) DEFAULT NULL,
  `id_servicio_usuario` int(11) DEFAULT NULL,
  `fecha_expiracion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_difusion`
--

CREATE TABLE `mensajes_difusion` (
  `id` int(11) NOT NULL,
  `id_instalacion` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `tipo_mensaje` enum('publico','privado') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'publico',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion_log`
--

CREATE TABLE `notificacion_log` (
  `id` int(11) NOT NULL,
  `pedido_id` varchar(255) DEFAULT NULL,
  `servicio_usuario_id` int(11) DEFAULT NULL,
  `fecha_expiracion` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes`
--

CREATE TABLE `participantes` (
  `id` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_servicio` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `estado_pedido` varchar(255) DEFAULT 'active',
  `fecha_pedido` varchar(255) DEFAULT NULL,
  `id_pedido` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante_eventos_mes`
--

CREATE TABLE `participante_eventos_mes` (
  `id` int(11) NOT NULL,
  `id_participante` int(11) NOT NULL,
  `id_pedido` text NOT NULL,
  `num_mes` int(11) NOT NULL,
  `num_year` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` varchar(100) NOT NULL,
  `amount` float NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_reserva` int(11) DEFAULT NULL,
  `id_evento` int(11) DEFAULT NULL,
  `id_servicio` int(11) DEFAULT NULL,
  `id_bono` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT NULL,
  `estado` text DEFAULT NULL,
  `checked` varchar(255) DEFAULT NULL,
  `tipo_pago` enum('tarjeta','efectivo') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'tarjeta',
  `expiration` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pistas`
--

CREATE TABLE `pistas` (
  `id` int(11) NOT NULL,
  `id_instalacion` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `nombre_corto` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `id_deporte` int(11) DEFAULT NULL,
  `horario` text NOT NULL,
  `precio` double DEFAULT NULL,
  `reservas_por_tramo` int(11) DEFAULT 1,
  `allow_cancel` tinyint(4) DEFAULT NULL,
  `atenlacion_reserva` int(11) DEFAULT 0,
  `allow_more_res` tinyint(1) NOT NULL,
  `max_dias_antelacion` int(11) NOT NULL,
  `active` int(11) DEFAULT 1,
  `bloqueo` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pistas_campos`
--

CREATE TABLE `pistas_campos` (
  `id` int(11) NOT NULL,
  `id_pista` int(11) NOT NULL,
  `id_campo` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibo`
--

CREATE TABLE `recibo` (
  `id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `id_servicio` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `pedido_id` varchar(100) DEFAULT NULL,
  `id_servicio_usuario` int(11) DEFAULT NULL,
  `tipo` text DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `redsys_log`
--

CREATE TABLE `redsys_log` (
  `id` int(11) NOT NULL,
  `ds_date` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ds_hour` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ds_amount` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ds_response` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `pedido_id` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `version` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `params` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `signature` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `id` int(20) NOT NULL,
  `fecha_apertura` timestamp NULL DEFAULT current_timestamp(),
  `accion_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  `estado` varchar(125) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_pista` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `timestamp` int(255) NOT NULL,
  `horarios` text NOT NULL,
  `fecha` date NOT NULL,
  `hora` varchar(255) NOT NULL,
  `tarifa` int(11) NOT NULL,
  `minutos_totales` int(11) NOT NULL,
  `estado` varchar(255) DEFAULT 'active',
  `estado_asistencia` varchar(50) DEFAULT NULL,
  `tipo` varchar(200) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `observaciones_admin` text DEFAULT NULL,
  `reserva_periodica` int(11) DEFAULT NULL,
  `reserva_multiple` int(11) DEFAULT NULL,
  `id_pedido` varchar(20) DEFAULT NULL,
  `aprobado` tinyint(4) DEFAULT 0,
  `creado_por` enum('user','admin') DEFAULT 'user',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas_periodicas`
--

CREATE TABLE `reservas_periodicas` (
  `id` int(11) NOT NULL,
  `id_pista` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `dias` text NOT NULL,
  `hora_inicio` text NOT NULL,
  `hora_fin` text NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(350) DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `duracion` varchar(30) NOT NULL,
  `precio` double(10,2) NOT NULL DEFAULT 0.00,
  `reservas` varchar(11) NOT NULL DEFAULT '0',
  `tipo_espacio` varchar(30) NOT NULL,
  `pista_id` int(11) DEFAULT NULL,
  `id_tipo_participante` int(11) DEFAULT NULL,
  `instalacion_id` int(11) DEFAULT NULL,
  `formapago` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_adicionales`
--

CREATE TABLE `servicios_adicionales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_usuario`
--

CREATE TABLE `servicio_usuario` (
  `id` int(11) NOT NULL,
  `fecha_expiracion` date NOT NULL,
  `activo` enum('si','no') DEFAULT 'si',
  `id_servicio` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_participante`
--

CREATE TABLE `tipos_participante` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `id_instalacion` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_participante_campos`
--

CREATE TABLE `tipos_participante_campos` (
  `id` int(11) NOT NULL,
  `id_tipo_participante` int(11) NOT NULL,
  `id_campo` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_reservas`
--

CREATE TABLE `tipo_reservas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_reservas`
--

INSERT INTO `tipo_reservas` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Tipo 1: Reservas estándar', '2023-07-12 08:48:39', '2023-07-12 08:48:39'),
(2, 'Tipo 2: Reservas por dias', '2023-07-12 09:18:06', '2023-07-12 09:18:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_instalacion` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `apellidos` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `tlfno` varchar(255) DEFAULT NULL,
  `movil` text DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `codigo_postal` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `cuota` varchar(255) DEFAULT NULL,
  `date_birth` date DEFAULT NULL,
  `rol` varchar(255) DEFAULT NULL,
  `subrol` varchar(50) DEFAULT NULL,
  `max_reservas_tipo_espacio` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `varios` int(11) DEFAULT NULL,
  `token_redsys` text DEFAULT NULL,
  `aprobado` datetime DEFAULT NULL,
  `pago_recurrente` varchar(255) DEFAULT 'off',
  `codigo_tarjeta` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valor_campo_personalizado`
--

CREATE TABLE `valor_campo_personalizado` (
  `id` int(11) NOT NULL,
  `id_reserva` int(11) DEFAULT NULL,
  `id_participante` int(11) DEFAULT NULL,
  `id_campo` int(11) DEFAULT NULL,
  `valor` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acceso`
--
ALTER TABLE `acceso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `accesos_puerta`
--
ALTER TABLE `accesos_puerta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `accion`
--
ALTER TABLE `accion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `app_logs`
--
ALTER TABLE `app_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bonos`
--
ALTER TABLE `bonos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bono_participante`
--
ALTER TABLE `bono_participante`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bono_usuario`
--
ALTER TABLE `bono_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `campos_personalizados`
--
ALTER TABLE `campos_personalizados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cobros`
--
ALTER TABLE `cobros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion_ip`
--
ALTER TABLE `configuracion_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `deportes`
--
ALTER TABLE `deportes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `desactivaciones_periodicas`
--
ALTER TABLE `desactivaciones_periodicas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `desactivacion_reservas`
--
ALTER TABLE `desactivacion_reservas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `descuentos`
--
ALTER TABLE `descuentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `dias_festivos`
--
ALTER TABLE `dias_festivos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `excepciones_desactivaciones_periodicas`
--
ALTER TABLE `excepciones_desactivaciones_periodicas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `fallos_recurrente`
--
ALTER TABLE `fallos_recurrente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tipo_mapa` (`tipo_reservas_id`);

--
-- Indices de la tabla `log_recibos_diario`
--
ALTER TABLE `log_recibos_diario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mensajes_difusion`
--
ALTER TABLE `mensajes_difusion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificacion_log`
--
ALTER TABLE `notificacion_log`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `participante_eventos_mes`
--
ALTER TABLE `participante_eventos_mes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `pistas`
--
ALTER TABLE `pistas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pistas_campos`
--
ALTER TABLE `pistas_campos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recibo`
--
ALTER TABLE `recibo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `redsys_log`
--
ALTER TABLE `redsys_log`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas_periodicas`
--
ALTER TABLE `reservas_periodicas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios_adicionales`
--
ALTER TABLE `servicios_adicionales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicio_usuario`
--
ALTER TABLE `servicio_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_participante`
--
ALTER TABLE `tipos_participante`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_participante_campos`
--
ALTER TABLE `tipos_participante_campos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_reservas`
--
ALTER TABLE `tipo_reservas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `valor_campo_personalizado`
--
ALTER TABLE `valor_campo_personalizado`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acceso`
--
ALTER TABLE `acceso`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=435;

--
-- AUTO_INCREMENT de la tabla `accesos_puerta`
--
ALTER TABLE `accesos_puerta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `accion`
--
ALTER TABLE `accion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `app_logs`
--
ALTER TABLE `app_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bonos`
--
ALTER TABLE `bonos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `bono_participante`
--
ALTER TABLE `bono_participante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bono_usuario`
--
ALTER TABLE `bono_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `campos_personalizados`
--
ALTER TABLE `campos_personalizados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cobros`
--
ALTER TABLE `cobros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `configuracion_ip`
--
ALTER TABLE `configuracion_ip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `deportes`
--
ALTER TABLE `deportes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `desactivaciones_periodicas`
--
ALTER TABLE `desactivaciones_periodicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `desactivacion_reservas`
--
ALTER TABLE `desactivacion_reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT de la tabla `descuentos`
--
ALTER TABLE `descuentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `dias_festivos`
--
ALTER TABLE `dias_festivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `excepciones_desactivaciones_periodicas`
--
ALTER TABLE `excepciones_desactivaciones_periodicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fallos_recurrente`
--
ALTER TABLE `fallos_recurrente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `log_recibos_diario`
--
ALTER TABLE `log_recibos_diario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1734;

--
-- AUTO_INCREMENT de la tabla `mensajes_difusion`
--
ALTER TABLE `mensajes_difusion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacion_log`
--
ALTER TABLE `notificacion_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `participante_eventos_mes`
--
ALTER TABLE `participante_eventos_mes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pistas`
--
ALTER TABLE `pistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pistas_campos`
--
ALTER TABLE `pistas_campos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recibo`
--
ALTER TABLE `recibo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2638;

--
-- AUTO_INCREMENT de la tabla `redsys_log`
--
ALTER TABLE `redsys_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1058;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27332;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=816;

--
-- AUTO_INCREMENT de la tabla `reservas_periodicas`
--
ALTER TABLE `reservas_periodicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `servicios_adicionales`
--
ALTER TABLE `servicios_adicionales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicio_usuario`
--
ALTER TABLE `servicio_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=354;

--
-- AUTO_INCREMENT de la tabla `tipos_participante`
--
ALTER TABLE `tipos_participante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipos_participante_campos`
--
ALTER TABLE `tipos_participante_campos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `tipo_reservas`
--
ALTER TABLE `tipo_reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3120;

--
-- AUTO_INCREMENT de la tabla `valor_campo_personalizado`
--
ALTER TABLE `valor_campo_personalizado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  ADD CONSTRAINT `fk_tipo_mapa` FOREIGN KEY (`tipo_reservas_id`) REFERENCES `tipo_reservas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;