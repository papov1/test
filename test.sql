-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 31 2019 г., 20:23
-- Версия сервера: 5.7.20
-- Версия PHP: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test`
--
222uu
-- --------------------------------------------------------

--
-- Структура таблицы `tovar`
--

CREATE TABLE `tovar` (
  `id_tovar` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mini_description` text NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tovar`
--

INSERT INTO `tovar` (`id_tovar`, `name`, `mini_description`, `foto`) VALUES
(1, 'Palit GeForce GTX 1060 ', 'видеокарта NVIDIA GeForce GTX 1060\r\n6144 Мб видеопамяти GDDR5\r\nчастота ядра/памяти: 1506/8000 МГц\r\nразъемы DVI, HDMI, DisplayPort x3\r\nподдержка DirectX 12, OpenGL 4.5, Vulkan\r\nработа с 4 мониторами', '404.png'),
(3, 'Palit GeForce GTX 1060 ', 'видеокарта NVIDIA GeForce GTX 1060\r\n	6144 Мб видеопамяти GDDR5\r\n	частота ядра/памяти: 1506/8000 МГц\r\n	разъемы DVI, HDMI, DisplayPort x3\r\n	поддержка DirectX 12, OpenGL 4.5, Vulkan\r\nработа с 4 мониторами\r\n\r\n', 'd88bd1.jpg'),
(4, 'Palit GeForce GTX 1060 ', '\r\nчастота ядра/памяти: 1506/8000&nbsp;МГц\r\nразъемы DVI, HDMI, DisplayPort x3\r\nподдержка DirectX 12, OpenGL 4.5, Vulkan\r\nработа с 4&nbsp;мониторами\r\n\r\n', '1.JPG'),
(26, 'Palit GeForce GTX 1060 ', 'видеокарта NVIDIA GeForce GTX 1060\r\n6144 Мб видеопамяти GDDR5\r\nчастота ядра/памяти: 1506/8000 МГц\r\nразъемы DVI, HDMI, DisplayPort x3\r\nподдержка DirectX 12, OpenGL 4.5, Vulkan\r\nработа с 4 мониторами', '404.png'),
(27, 'Palit GeForce GTX 1060 ', 'видеокарта NVIDIA GeForce GTX 1060\r\n	6144 Мб видеопамяти GDDR5\r\n	частота ядра/памяти: 1506/8000 МГц\r\n	разъемы DVI, HDMI, DisplayPort x3\r\n	поддержка DirectX 12, OpenGL 4.5, Vulkan\r\nработа с 4 мониторами\r\n\r\n', 'd88bd1.jpg'),
(28, 'Palit GeForce GTX 1060 ', '\r\nчастота ядра/памяти: 1506/8000&nbsp;МГц\r\nразъемы DVI, HDMI, DisplayPort x3\r\nподдержка DirectX 12, OpenGL 4.5, Vulkan\r\nработа с 4&nbsp;мониторами\r\n\r\n', '1.JPG'),
(29, 'Palit GeForce GTX 1060 ', 'видеокарта NVIDIA GeForce GTX 1060\r\n	6144 Мб видеопамяти GDDR5\r\n	частота ядра/памяти: 1506/8000 МГц\r\n	разъемы DVI, HDMI, DisplayPort x3\r\n	поддержка DirectX 12, OpenGL 4.5, Vulkan\r\nработа с 4 мониторами\r\n\r\n', 'd88bd1.jpg'),
(30, 'Palit GeForce GTX 1060 ', '\r\nчастота ядра/памяти: 1506/8000&nbsp;МГц\r\nразъемы DVI, HDMI, DisplayPort x3\r\nподдержка DirectX 12, OpenGL 4.5, Vulkan\r\nработа с 4&nbsp;мониторами\r\n\r\n', '1.JPG');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `tovar`
--
ALTER TABLE `tovar`
  ADD PRIMARY KEY (`id_tovar`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `tovar`
--
ALTER TABLE `tovar`
  MODIFY `id_tovar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
