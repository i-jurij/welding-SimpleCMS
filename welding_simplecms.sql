-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 03 2024 г., 18:54
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `welding_simplecms`
--

-- --------------------------------------------------------

--
-- Структура таблицы `abouts`
--

CREATE TABLE `abouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` varchar(1500) NOT NULL,
  `image` varchar(1500) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `abouts`
--

INSERT INTO `abouts` (`id`, `title`, `content`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Гарантия', 'Гарантия на все конструкции и изделия составляет один год.', 'images/pages/about/garantia.jpg', '2024-10-19 19:19:51', '2024-10-19 19:19:51'),
(2, 'Оплата', 'Оплата частями согласно договору: аванс на материалы и подготовительную часть работы, далее по выполнению этапов работ.', 'images/pages/about/oplata.jpg', '2024-10-19 19:19:51', '2024-10-19 19:19:51'),
(3, 'Договор', 'Гарантия выполнения обязательств для вас и для нас.', 'images/pages/about/dogovor.jpg', '2024-10-19 19:19:51', '2024-10-19 19:19:51'),
(4, 'Стандарты', 'Мы следуем СНиП и требованиям ГОСТ. Поэтому и даем гарантию.', 'images/pages/about/standarty.jpg', '2024-10-19 19:19:51', '2024-10-19 19:19:51'),
(5, 'Обучение', 'Персонал в большинстве имеет профильное образование или прошел обучение у нас.', 'images/pages/about/obucenie.jpg', '2024-10-19 19:19:51', '2024-10-19 19:19:51'),
(6, 'Опыт', 'Самостоятельно работают только опытные мастера. Они же являются наставниками для начинающих.', 'images/pages/about/opyt.jpg', '2024-10-19 19:19:51', '2024-10-19 19:19:51'),
(7, 'Время', 'Мы ценим и ваше и наше время, соблюдая баланс между качеством и количеством.', 'images/pages/about/vrema.jpg', '2024-10-19 19:19:51', '2024-10-19 19:19:51'),
(8, 'Производство', 'Соберем ваше изделие по возможности в цеху или у вас на месте. Качество будет идентичным в любом случае.', 'images/pages/about/proizvodstvo.jpg', '2024-10-19 19:19:51', '2024-10-19 19:19:51');

-- --------------------------------------------------------

--
-- Структура таблицы `callbacks`
--

CREATE TABLE `callbacks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `send` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `response` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `callbacks`
--

INSERT INTO `callbacks` (`id`, `client_id`, `order_id`, `send`, `created_at`, `updated_at`, `response`) VALUES
(6, 6, NULL, NULL, '2024-10-30 17:49:46', '2024-10-30 18:42:15', 1),
(7, 7, NULL, NULL, '2024-10-30 17:55:51', '2024-10-30 18:42:15', 1),
(8, 8, NULL, NULL, '2024-10-30 18:26:04', '2024-10-30 18:42:15', 1),
(9, 9, NULL, NULL, '2024-10-30 18:27:59', '2024-10-30 18:44:00', 1),
(10, 10, NULL, NULL, '2024-10-30 18:36:09', '2024-10-30 20:25:54', 1),
(11, 11, NULL, NULL, '2024-10-30 18:37:28', '2024-10-30 20:25:54', 1),
(12, 12, NULL, NULL, '2024-10-30 18:57:34', '2024-10-30 20:25:54', 1),
(13, 13, NULL, NULL, '2024-10-30 19:04:05', '2024-10-30 20:25:54', 1),
(14, 14, NULL, NULL, '2024-10-30 19:05:04', '2024-10-30 20:25:54', 1),
(15, 15, NULL, NULL, '2024-10-30 19:05:43', '2024-10-30 20:25:54', 1),
(16, 16, NULL, NULL, '2024-10-30 19:07:48', '2024-10-30 20:25:54', 1),
(17, 17, NULL, NULL, '2024-10-30 19:13:07', '2024-10-30 20:25:54', 1),
(18, 18, NULL, NULL, '2024-10-30 19:14:19', '2024-10-30 20:25:54', 1),
(19, 19, NULL, NULL, '2024-10-30 19:15:04', '2024-10-30 20:25:54', 1),
(20, 20, NULL, NULL, '2024-10-30 19:15:55', '2024-10-30 20:25:54', 1),
(21, 21, NULL, NULL, '2024-10-30 19:17:44', '2024-10-30 20:25:54', 1),
(22, 22, NULL, NULL, '2024-10-30 19:18:59', '2024-10-30 20:26:04', 1),
(23, 23, NULL, NULL, '2024-10-30 19:20:58', '2024-10-30 20:26:04', 1),
(24, 24, NULL, NULL, '2024-10-30 19:21:17', '2024-10-30 20:26:04', 1),
(25, 25, NULL, NULL, '2024-10-30 19:22:03', '2024-10-30 19:22:03', 0),
(26, 26, NULL, NULL, '2024-10-30 20:47:55', '2024-10-30 20:47:55', 0),
(27, 27, NULL, NULL, '2024-10-30 21:05:14', '2024-10-30 21:05:14', 0),
(28, 28, NULL, NULL, '2024-10-30 21:08:10', '2024-10-30 21:08:10', 0),
(29, 29, NULL, NULL, '2024-10-30 21:10:52', '2024-10-30 21:10:52', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(17) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id`, `name`, `phone`, `email`, `created_at`, `updated_at`, `password`) VALUES
(1, 'gfd', '+7 334 568 78 76', NULL, '2024-10-17 17:08:22', '2024-10-17 17:08:22', NULL),
(2, NULL, '+7 345 687 65 45', NULL, '2024-10-17 17:25:16', '2024-10-17 17:25:16', NULL),
(3, NULL, '+7 454 678 76 54', NULL, '2024-10-17 17:25:52', '2024-10-17 17:25:52', NULL),
(4, 'sdfv', '+7 234 566 76 54', NULL, '2024-10-17 17:41:18', '2024-10-17 17:41:18', NULL),
(5, NULL, '+7 978 444 88 44', NULL, '2024-10-19 14:51:30', '2024-10-19 14:51:30', NULL),
(6, NULL, '+7 435 453 45 65', NULL, '2024-10-30 17:49:46', '2024-10-30 17:49:46', NULL),
(7, NULL, '+7 435 677 54 34', NULL, '2024-10-30 17:55:51', '2024-10-30 17:55:51', NULL),
(8, NULL, '+7 343 566 76 54', NULL, '2024-10-30 18:26:04', '2024-10-30 18:26:04', NULL),
(9, NULL, '+7 234 567 75 43', NULL, '2024-10-30 18:27:59', '2024-10-30 18:27:59', NULL),
(10, NULL, '+7 233 445 66 54', NULL, '2024-10-30 18:36:09', '2024-10-30 18:36:09', NULL),
(11, NULL, '+7 334 567 67 65', NULL, '2024-10-30 18:37:28', '2024-10-30 18:37:28', NULL),
(12, NULL, '+7 234 658 77 65', NULL, '2024-10-30 18:57:34', '2024-10-30 18:57:34', NULL),
(13, NULL, '+7 324 566 54 32', NULL, '2024-10-30 19:04:05', '2024-10-30 19:04:05', NULL),
(14, NULL, '+7 324 567 87 76', NULL, '2024-10-30 19:05:04', '2024-10-30 19:05:04', NULL),
(15, NULL, '+7 345 676 54 34', NULL, '2024-10-30 19:05:43', '2024-10-30 19:05:43', NULL),
(16, NULL, '+7 234 566 54 33', NULL, '2024-10-30 19:07:48', '2024-10-30 19:07:48', NULL),
(17, NULL, '+7 234 566 54 32', NULL, '2024-10-30 19:13:07', '2024-10-30 19:13:07', NULL),
(18, NULL, '+7 345 665 43 45', NULL, '2024-10-30 19:14:19', '2024-10-30 19:14:19', NULL),
(19, NULL, '+7 566 545 67 87', NULL, '2024-10-30 19:15:04', '2024-10-30 19:15:04', NULL),
(20, NULL, '+7 234 566 54 34', NULL, '2024-10-30 19:15:55', '2024-10-30 19:15:55', NULL),
(21, NULL, '+7 345 675 43 24', NULL, '2024-10-30 19:17:44', '2024-10-30 19:17:44', NULL),
(22, NULL, '+7 435 666 54 56', NULL, '2024-10-30 19:18:59', '2024-10-30 19:18:59', NULL),
(23, NULL, '+7 345 678 76 54', NULL, '2024-10-30 19:20:58', '2024-10-30 19:20:58', NULL),
(24, NULL, '+7 234 567 87 65', NULL, '2024-10-30 19:21:17', '2024-10-30 19:21:17', NULL),
(25, NULL, '+7 345 678 77 65', NULL, '2024-10-30 19:22:03', '2024-10-30 19:22:03', NULL),
(26, NULL, '+7 345 677 65 43', NULL, '2024-10-30 20:47:55', '2024-10-30 20:47:55', NULL),
(27, NULL, '+7 233 456 65 43', NULL, '2024-10-30 21:05:14', '2024-10-30 21:05:14', NULL),
(28, NULL, '+7 434 556 67 65', NULL, '2024-10-30 21:08:10', '2024-10-30 21:08:10', NULL),
(29, NULL, '+7 435 665 43 24', NULL, '2024-10-30 21:10:52', '2024-10-30 21:10:52', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL,
  `data` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `contacts`
--

INSERT INTO `contacts` (`id`, `type`, `data`, `created_at`, `updated_at`) VALUES
(5, 'tlf', '+7 978 696 83 90', '2024-10-16 13:14:43', '2024-10-16 13:14:43'),
(6, 'tlf', '+7 000 111 22 33', '2024-10-16 14:03:01', '2024-10-16 14:03:01'),
(7, 'adres', 'Sity Street House Office', '2024-10-16 14:03:23', '2024-10-16 14:03:23'),
(8, 'telegram', 'tg', '2024-10-16 14:03:43', '2024-10-16 14:03:43'),
(9, 'vk', 'vk', '2024-10-16 14:04:05', '2024-10-16 14:04:05'),
(10, 'adres', 'City Street House Office', '2024-10-30 16:28:48', '2024-10-30 16:28:48');

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `masters`
--

CREATE TABLE `masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `master_photo` varchar(1500) DEFAULT NULL,
  `master_name` varchar(100) NOT NULL,
  `sec_name` varchar(100) DEFAULT NULL,
  `master_fam` varchar(100) NOT NULL,
  `master_phone_number` varchar(100) NOT NULL,
  `spec` varchar(100) NOT NULL,
  `data_priema` date DEFAULT NULL,
  `data_uvoln` date DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `master_service`
--

CREATE TABLE `master_service` (
  `master_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_04_21_073138_add_status_to_users_table', 1),
(6, '2023_05_02_150410_create_contacts_table', 1),
(7, '2023_05_08_164010_create_pages_table', 1),
(8, '2023_05_16_154449_add_service_page_column_in_pages_table', 1),
(9, '2023_05_19_161920_create_masters_table', 1),
(11, '2023_05_26_204743_create_clients_table', 1),
(12, '2023_05_26_212840_create_service_pages_table', 1),
(13, '2023_05_26_212903_create_service_categories_table', 1),
(14, '2023_05_26_212921_create_services_table', 1),
(15, '2023_05_26_213223_create_orders_table', 1),
(17, '2023_05_30_204100_change_column_email_in_clients_table', 1),
(18, '2023_06_01_163839_create_jobs_table', 1),
(19, '2023_06_04_155525_add_column_to_services_table', 1),
(20, '2023_06_04_171736_add_column_to_service_categories_table', 1),
(21, '2023_06_10_213040_create_master_service_table', 1),
(22, '2023_06_14_213121_del_exec_add_start_end_column_to_orders_table', 1),
(23, '2023_06_18_170142_drop_create_column_in_orders_table', 1),
(24, '2023_06_24_164046_create_orgworktimesets_table', 1),
(25, '2023_06_24_170012_create_orgweekends_table', 1),
(26, '2023_06_24_170247_create_holidays_table', 1),
(27, '2023_06_25_154900_create_restdaytimes_table', 1),
(28, '2023_07_05_214105_add_user_id_column_in_masters_table', 1),
(29, '2023_07_19_191421_add_passcol_in_clients_table', 1),
(30, '2023_05_30_165144_create_callbacks_table', 2),
(31, '2023_05_19_211947_create_abouts_table', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `master_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `start_dt` datetime NOT NULL,
  `end_dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orgweekends`
--

CREATE TABLE `orgweekends` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_of_day_of_week` varchar(255) NOT NULL,
  `time` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orgworktimesets`
--

CREATE TABLE `orgworktimesets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lehgth_cal` tinyint(3) UNSIGNED DEFAULT NULL,
  `endtime` varchar(255) DEFAULT NULL,
  `tz` varchar(255) DEFAULT NULL,
  `period` smallint(5) UNSIGNED DEFAULT NULL,
  `lunch_time` varchar(255) DEFAULT NULL,
  `lunch_duration` varchar(255) DEFAULT NULL,
  `work_start` varchar(255) DEFAULT NULL,
  `work_end` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alias` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(500) NOT NULL,
  `robots` varchar(100) NOT NULL DEFAULT 'INDEX, FOLLOW',
  `content` text NOT NULL,
  `single_page` char(10) NOT NULL DEFAULT 'yes',
  `img` varchar(255) NOT NULL,
  `publish` char(10) NOT NULL DEFAULT 'yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `service_page` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`id`, `alias`, `title`, `description`, `keywords`, `robots`, `content`, `single_page`, `img`, `publish`, `created_at`, `updated_at`, `service_page`) VALUES
(2, 'callback', 'Перезвоните мне', 'Форма заказа обратного звонка', 'перезвоните, мне, обратный, звонок', 'INDEX,FOLLOW', '', 'no', 'images/pages/callback.webp', 'yes', '2024-10-17 16:45:29', '2024-10-17 16:45:29', 'no'),
(6, 'gates', 'Ворота', 'Откатные, распашные, гаражные, автоматика для ворот', 'ворота, металлические, двери, калитки, сварка, автоматика', 'INDEX,FOLLOW', '', 'yes', 'images/pages/gates.webp', 'yes', '2024-10-20 18:56:44', '2024-10-20 18:56:44', 'yes'),
(12, 'gallery', 'Галерея фото', 'Фото выполненных работ', 'фотографии, выполненных, работ, ворота, заборы, калитки, навесы, перила, лестницы, двери, скамейки, металлоизделия', 'INDEX,FOLLOW', '', 'yes', 'images/pages/gallery.webp', 'yes', '2024-10-18 15:42:05', '2024-10-18 16:36:13', 'no'),
(13, 'about', 'О нас', 'И как мы работаем', 'о, нас, сварщик, сварка, металл, ворота, заборы, профнастил, калитки, навесы, перила, лестницы, металлоизделия', 'INDEX,FOLLOW', '', 'no', 'images/pages/about.webp', 'yes', '2024-10-19 18:52:29', '2024-10-19 18:52:29', 'no'),
(17, 'persinfo', 'Политика обработки персональных данных', 'Политика обработки персональных данных', 'Политика, обработки, персональных, данных', 'INDEX,FOLLOW', '<h3>Основания обработки персональной информации:</h3>\r\n<p>Передавая владельцу данного ПО персональные данные пользователь дает согласие на использование их с целью предоставления пользователю доступа к возможностям приложения и оказания услуг пользователю.</p>\r\n<h3>Какие персональные данные обрабатывает и хранит данное программное обеспечение?</h3>\r\n<p>Номер телефона, имя, информация в сообщении пользователя на странице \"Перезвоните мне\" после отправки формы.</p>\r\n<p> Данные используются с целью консультирования по предоставлению услуг, заключения договора на оказания услуг и выполнения услуги.</p>\r\n<p class=\"font-bold underline\">Имя, номер телефона и сообщение пользователя удаляются из базы данных после осуществления консультации по услуге и далее в электронном виде не хранятся.</p>\r\n<p class=\"text-red-400 underline\">Лицам, не достигшим 18 лет, запрещается предоставлять личную информацию.</p>\r\n<p class=\"text-red-400 underline\">С целью защиты Ваших персональных данных не предоставляйте никакую информацию, если таковая специально не запрашивается.</p>\r\n\r\n<h3>Передача персональных данных:</h3>\r\n<p>Владелец сайта вправе передавать персональную информацию третьим лицам в следующих случаях:  </p>\r\n<ul>\r\n<li>пользователь выразил свое согласие на такие действия, включая случаи применения пользователем настроек используемого программного обеспечения, не ограничивающих предоставление определенной информации;  \r\n</li>\r\n<li>\r\nпередача необходима для осуществления обязательств по договору с пользователем; \r\n</li>\r\n<li>\r\nв связи с передачей веб-приложения во владение, пользование или собственность такого третьего лица, включая уступку прав по заключенным с пользователем договорам в пользу такого третьего лица;\r\n</li>\r\n<li>\r\nпо запросу суда или иного уполномоченного государственного органа в рамках установленной законодательством процедуры;\r\n</li>\r\n<li>\r\nдля защиты прав и законных интересов владельца веб-приложения в связи с нарушением заключенных с пользователем договоров.\r\n</li>  \r\n</ul>\r\n<p>Вышеуказанные правила обработки персональных данных могут быть изменены или прекращены владельцем веб-приложения в одностороннем порядке без предварительного уведомления пользователя.</p>', 'yes', 'images/pages/persinfo.webp', 'yes', '2024-10-20 17:23:32', '2024-10-20 18:00:07', 'no'),
(19, 'price', 'Расценки', 'Стоимость услуг и товаров', 'цена, стоимость, прайс, сварка, конструкции, металл, ворота, каоитки, забор, перила, лестница, навес', 'INDEX,FOLLOW', '', 'no', 'images/pages/price.webp', 'yes', '2024-10-21 15:36:54', '2024-10-21 15:36:54', 'no'),
(20, 'canopy', 'Навесы', 'Навесы, беседки, козырьки', 'Навесы, беседки, козырьки', 'INDEX,FOLLOW', '', 'yes', 'images/pages/canopy.webp', 'yes', '2024-10-28 15:24:35', '2024-10-28 15:24:35', 'yes'),
(21, 'stairs', 'Лестницы', 'Металлические лестницы любой конструкции, вида и назначения', 'лестница, металлическая, ступени, ступеньки, винтовая, одномаршевая, пожарнаямногомаршевая', 'INDEX,FOLLOW', '', 'yes', 'images/pages/stairs.webp', 'yes', '2024-11-01 17:29:17', '2024-11-01 17:29:17', 'yes'),
(22, 'fence', 'Заборы', 'Металлические ограждения любой конструкции, вида и назначения', 'забор, профлист, 3д, сетка, рабица, штакетник, штахетник, жалюзи, сварной', 'INDEX,FOLLOW', '', 'yes', 'images/pages/fence.webp', 'yes', '2024-11-01 18:03:53', '2024-11-01 18:03:53', 'yes'),
(23, 'other', 'Прочие услуги', 'Сварочные и другие работы, изделия, конструкции', 'сварка, сварочные работы, металлоизделия, перила, балконы, электрод, бетон, веранда', 'INDEX,FOLLOW', '', 'yes', 'images/pages/other.jpg', 'yes', '2024-11-03 14:05:07', '2024-11-03 14:05:07', 'yes');

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `restdaytimes`
--

CREATE TABLE `restdaytimes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `master_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image` varchar(1500) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(500) NOT NULL,
  `price` decimal(9,2) NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `services`
--

INSERT INTO `services` (`id`, `created_at`, `updated_at`, `page_id`, `category_id`, `image`, `name`, `description`, `price`, `duration`) VALUES
(1, NULL, NULL, 6, NULL, 'services/6/Vorota_otkatnie.webp', 'Ворота откатные', 'Наиболее популярный вариант. Не отнимают место на въезд и выезд, требуют место для отката.', 160000.00, 4),
(2, NULL, NULL, 6, NULL, 'services/6/Vorota_raspashnie.webp', 'Ворота распашные', 'Классический вариант. Подходят практически для всех видов въездных групп. Требуют обслуживания петель. Требуют места для открытия.', 100000.00, 4),
(3, NULL, NULL, 6, NULL, 'services/6/Vorota_garazhnie.webp', 'Ворота гаражные', 'Прочные металлические ворота для гаража. Калитка и прочее оформление опционально. Требует крепкого проема для установки рамы.', 100000.00, 3),
(4, NULL, NULL, 6, NULL, 'services/6/Avtomaticheskie_sistemi_upravleniya_vorotami.webp', 'Автоматические системы управления воротами', 'После согласования параметров автоматики для ворот, стоимости и подписания договора приступаем к монтажу. Основные этапы монтажа: рама двигателя крепится к закладной или опорам ворот, установка и подключение фотоэлементов, фонарей, монтаж рычагов или рейки, осуществляется временное или постоянное подключение для настройки двигателя и элементов, настройка согласно инструкции производителя.', 40000.00, 1),
(5, NULL, NULL, 20, NULL, 'services/20/Navesi.webp', 'Навесы', 'Навесы для автомобиля, места отдыха и прочего назначения.', 7000.00, 14),
(6, NULL, NULL, 20, NULL, 'services/20/Besedki.webp', 'Беседки', 'Большие, маленькие, закрытые, открытые, с твердым или мягким, прозрачным или нет покрытием.', 7000.00, 14),
(7, NULL, NULL, 20, NULL, 'services/20/Koziryki.webp', 'Козырьки', 'Маркизы, козырьки и прочие легкие конструкции над дверями и окнами', 5000.00, 7),
(8, NULL, NULL, 20, NULL, 'services/20/Pergola.webp', 'Пергола', 'Галереи, перголы, берсо и прочие крытые аллеи', 3000.00, 7),
(9, NULL, NULL, 20, NULL, 'services/20/Skameyki.webp', 'Скамейки', 'Скамейки для парка, двора, места отдыха или приема пищи', 10000.00, 4),
(10, NULL, NULL, 20, NULL, 'services/20/Vodostochnie_sistemi.webp', 'Водосточные системы', 'Устанавливаем также водосточные системы', 500.00, 2),
(11, NULL, NULL, 21, NULL, 'services/21/Lestnica_s_pryamimi_marshami.jpg', 'Лестница с прямыми маршами', 'На косоурах, тетивах, больцах или консольные, уличные или внутридомовые, пожарные и другие', 10000.00, 7),
(12, NULL, NULL, 21, NULL, 'services/21/Lestnica_vintovaya.jpg', 'Лестница винтовая', 'Одномаршевая винтовая лестница с массивной стойкой или шахтой, двухмаршевая двойная винтовая лестница или другая по вашему описанию', 20000.00, 7),
(13, NULL, '2024-11-03 13:37:08', 22, NULL, 'services/22/Zabor_sekcionniy.jpg', 'Забор секционный', 'Забор из металлических сварных или сборных секций, закрепленных на металлических стойках или опорах, закрепленных в столбах из другого материала', 10000.00, 3),
(14, NULL, NULL, 22, NULL, 'services/22/Zabor_iz_proflista.jpg', 'Забор из профлиста', 'Простой, надежный современный забор из профлиста закрепленного на продольных лагах', 5000.00, 4),
(15, NULL, NULL, 22, NULL, 'services/22/Zabor_iz_shtaketnika.jpg', 'Забор из штакетника', 'Традиционный забор из металлического штакетника со специальным устойчивым покрытием, обеспечивающим долгий срок службы', 5000.00, 4),
(16, NULL, NULL, 22, NULL, 'services/22/Zabor_iz_3D_setki.jpg', 'Забор из 3D сетки', 'Простое и надежное решение для ограды участков или сооружений из металлической сетки со специальным покрытием, обеспечивающим долгий срок службы', 5000.00, 4),
(17, NULL, NULL, 22, NULL, 'services/22/Zabor_iz_kovanih_elementov.jpg', 'Забор из кованых элементов', 'Ограждение в классическом стиле с уникальным внешним видом из металла с покрытием по вашему выбору', 20000.00, 4),
(18, NULL, NULL, 23, NULL, 'services/23/Svarochnie_raboti.jpg', 'Сварочные работы', 'Выполним необходимые вам сварочные работы', 10000.00, 1),
(19, NULL, NULL, 23, NULL, 'services/23/Betonnie_i_obshtestroitelynie_raboti.webp', 'Бетонные и общестроительные работы', 'Цоколь, лента, фундамент под забор, бетонное перекрытие, площадка под стоянку автомобиля и другие работы', 10000.00, 1),
(20, NULL, NULL, 23, 1, 'services/23/1/terassa_veranda.webp', 'Терасса, веранда', 'Пристроим к дому террасу или веранду, с лестницей или без по вашему заказу', 3000.00, 7),
(21, NULL, NULL, 23, 1, 'services/23/1/perila.webp', 'Перила', 'Поставим перила на балкон, лестницу, веранду или там, где вам нужно', 10000.00, 3),
(22, NULL, NULL, 23, 1, 'services/23/1/skafy_i_drugie_sistem_hranenia.webp', 'Шкафы и другие системы хранения', 'Шкафы с металлическим сварным каркасом', 10000.00, 3),
(23, NULL, NULL, 23, 1, 'services/23/1/ramy_i_procie_konstrukcii.webp', 'Рамы и прочие конструкции', 'Любые сварные металлические рамы и прочие конструкции по вашему заказу', 10000.00, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(1500) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `service_categories`
--

INSERT INTO `service_categories` (`id`, `created_at`, `updated_at`, `page_id`, `image`, `name`, `description`) VALUES
(1, NULL, NULL, 23, 'categories/23/Drugie_konstrukcii_i_izdeliya.webp', 'Другие конструкции и изделия', 'Веранды, перила, шкафы, рамы, прочее');

-- --------------------------------------------------------

--
-- Структура таблицы `service_pages`
--

CREATE TABLE `service_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `status`) VALUES
(1, 'admin', 'admin@admin.com', NULL, '$2y$10$laVII2YTs5K27g6BOj9omuOxV4kqUkQ5oQK0YkrcS4WFp8aj.H5Ey', NULL, NULL, NULL, 'admin'),
(2, 'admin2', 'admin2@admin.com', NULL, '$2y$10$0OEZw7omIo/r6f.5vvhTuuGNQPSCM4JKNqIccRq6b3l33BGSq6PLu', NULL, '2024-10-30 14:47:52', '2024-10-30 14:47:52', 'admin');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `abouts`
--
ALTER TABLE `abouts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `callbacks`
--
ALTER TABLE `callbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `callbacks_client_id_foreign` (`client_id`),
  ADD KEY `callbacks_order_id_foreign` (`order_id`);

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_phone_unique` (`phone`),
  ADD UNIQUE KEY `clients_email_unique` (`email`);

--
-- Индексы таблицы `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contacts_data_unique` (`data`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `holidays_date_unique` (`date`);

--
-- Индексы таблицы `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Индексы таблицы `masters`
--
ALTER TABLE `masters`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `master_service`
--
ALTER TABLE `master_service`
  ADD PRIMARY KEY (`master_id`,`service_id`),
  ADD KEY `master_service_service_id_foreign` (`service_id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_client_id_foreign` (`client_id`),
  ADD KEY `orders_service_id_foreign` (`service_id`),
  ADD KEY `orders_master_id_foreign` (`master_id`);

--
-- Индексы таблицы `orgweekends`
--
ALTER TABLE `orgweekends`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orgworktimesets`
--
ALTER TABLE `orgworktimesets`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_alias_unique` (`alias`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `restdaytimes`
--
ALTER TABLE `restdaytimes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restdaytimes_master_id_foreign` (`master_id`);

--
-- Индексы таблицы `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_page_id_foreign` (`page_id`),
  ADD KEY `services_category_id_foreign` (`category_id`);

--
-- Индексы таблицы `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_categories_page_id_foreign` (`page_id`);

--
-- Индексы таблицы `service_pages`
--
ALTER TABLE `service_pages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `abouts`
--
ALTER TABLE `abouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `callbacks`
--
ALTER TABLE `callbacks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `masters`
--
ALTER TABLE `masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `orgweekends`
--
ALTER TABLE `orgweekends`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `orgworktimesets`
--
ALTER TABLE `orgworktimesets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `restdaytimes`
--
ALTER TABLE `restdaytimes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `service_pages`
--
ALTER TABLE `service_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `callbacks`
--
ALTER TABLE `callbacks`
  ADD CONSTRAINT `callbacks_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `callbacks_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `master_service`
--
ALTER TABLE `master_service`
  ADD CONSTRAINT `master_service_master_id_foreign` FOREIGN KEY (`master_id`) REFERENCES `masters` (`id`),
  ADD CONSTRAINT `master_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `orders_master_id_foreign` FOREIGN KEY (`master_id`) REFERENCES `masters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Ограничения внешнего ключа таблицы `restdaytimes`
--
ALTER TABLE `restdaytimes`
  ADD CONSTRAINT `restdaytimes_master_id_foreign` FOREIGN KEY (`master_id`) REFERENCES `masters` (`id`);

--
-- Ограничения внешнего ключа таблицы `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Ограничения внешнего ключа таблицы `service_categories`
--
ALTER TABLE `service_categories`
  ADD CONSTRAINT `service_categories_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
