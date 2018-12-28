-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Май 16 2018 г., 10:39
-- Версия сервера: 10.1.16-MariaDB
-- Версия PHP: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `diplom`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ads`
--

CREATE TABLE `ads` (
  `id` int(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `about` date NOT NULL,
  `id_user` int(5) NOT NULL,
  `type` int(2) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `attachments`
--

CREATE TABLE `attachments` (
  `id` int(5) NOT NULL,
  `source` varchar(255) NOT NULL,
  `id_task_main` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `id` int(5) NOT NULL,
  `type` int(2) NOT NULL,
  `id_user` int(5) NOT NULL,
  `id_item` int(5) NOT NULL,
  `date_add` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `position_ads`
--

CREATE TABLE `position_ads` (
  `id` int(5) NOT NULL,
  `id_position` int(5) NOT NULL,
  `id_ad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `position_list`
--

CREATE TABLE `position_list` (
  `id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_parent` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `position_list`
--

INSERT INTO `position_list` (`id`, `name`, `id_parent`) VALUES
(1, 'WEB Development', 0),
(2, 'Game Development', 0),
(3, 'Programmer', 1),
(4, 'Design', 1),
(5, 'Programmer', 2),
(6, 'Design', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `position_projects`
--

CREATE TABLE `position_projects` (
  `id` int(5) NOT NULL,
  `id_project` int(5) NOT NULL,
  `id_position` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `position_projects`
--

INSERT INTO `position_projects` (`id`, `id_project`, `id_position`) VALUES
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `position_users`
--

CREATE TABLE `position_users` (
  `id` int(5) NOT NULL,
  `id_user` int(5) NOT NULL,
  `id_position` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `position_users`
--

INSERT INTO `position_users` (`id`, `id_user`, `id_position`) VALUES
(1, 22, 3),
(3, 22, 6),
(4, 31, 4),
(5, 31, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE `projects` (
  `id` int(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `about` text NOT NULL,
  `summa` varchar(50) NOT NULL,
  `privacy` int(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `real_date_start` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `projects`
--

INSERT INTO `projects` (`id`, `title`, `about`, `summa`, `privacy`, `active`, `date_start`, `date_end`, `real_date_start`) VALUES
(3, 'Name project', 'About this project', '1200', 1, 1, '2017-10-13', '2018-03-08', '2017-10-13'),
(4, 'Name this project', 'About this project', '1200', 1, 1, '2017-10-13', '2018-01-25', '2017-10-13'),
(5, 'New project', 'About this project', '1200', 1, 2, '2017-10-13', '2017-11-07', '2017-10-13'),
(6, 'Сайт знакомств', 'Необходим сайт знакомств с простой регистрацией и авторизацией. Более подробно просьба писать на почту указаную в профиле.', '4000', 1, 0, '2018-01-12', '2018-03-31', '2018-01-11');

-- --------------------------------------------------------

--
-- Структура таблицы `projects_users`
--

CREATE TABLE `projects_users` (
  `id` int(5) NOT NULL,
  `id_project` int(5) NOT NULL,
  `id_user` int(5) NOT NULL,
  `id_position` int(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `permission` int(1) NOT NULL,
  `date_event` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `projects_users`
--

INSERT INTO `projects_users` (`id`, `id_project`, `id_user`, `id_position`, `status`, `permission`, `date_event`) VALUES
(3, 3, 22, 0, 1, 1, '2017-10-13'),
(4, 4, 22, 0, 1, 1, '2017-10-13'),
(5, 5, 22, 0, 1, 1, '2017-10-13'),
(6, 5, 31, 3, 1, 0, '2017-10-13'),
(7, 4, 31, 4, 1, 0, '2018-01-11'),
(8, 6, 34, 0, 1, 1, '2018-01-11');

-- --------------------------------------------------------

--
-- Структура таблицы `proposed_positions`
--

CREATE TABLE `proposed_positions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_user` int(5) NOT NULL,
  `id_parent` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ratings`
--

CREATE TABLE `ratings` (
  `id` int(5) NOT NULL,
  `id_user` int(5) NOT NULL,
  `id_task_main` int(5) NOT NULL,
  `valuation` int(2) NOT NULL,
  `date` date NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `resetpass`
--

CREATE TABLE `resetpass` (
  `id` int(5) NOT NULL,
  `code` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE `tasks` (
  `id` int(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `id_task_main` int(5) NOT NULL,
  `completed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `id_task_main`, `completed`) VALUES
(1, 'Second', 1, 1),
(2, 'First', 1, 1),
(3, 'Дизайн шапки', 2, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tasks_main`
--

CREATE TABLE `tasks_main` (
  `id` int(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `about` text NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `privacy` int(1) NOT NULL,
  `id_project` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tasks_main`
--

INSERT INTO `tasks_main` (`id`, `title`, `about`, `date_start`, `date_end`, `comment`, `status`, `privacy`, `id_project`) VALUES
(1, 'New task', 'About this task', '2017-10-13', '2017-11-07', '', 2, 1, 5),
(2, 'Тест ограничения', 'Ничего особенного', '2018-01-11', '2018-01-18', '', 1, 0, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `task_main_users`
--

CREATE TABLE `task_main_users` (
  `id` int(5) NOT NULL,
  `id_user` int(5) NOT NULL,
  `id_task_main` int(5) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_event` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `task_main_users`
--

INSERT INTO `task_main_users` (`id`, `id_user`, `id_task_main`, `status`, `date_event`) VALUES
(1, 31, 1, 1, '2017-10-13'),
(2, 31, 2, 1, '2018-01-11');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `date_activation` datetime NOT NULL,
  `id_invite_user` int(5) NOT NULL,
  `online` int(15) NOT NULL,
  `refurl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `active`, `date_activation`, `id_invite_user`, `online`, `refurl`) VALUES
(22, 'login', '827ccb0eea8a706c4c34a16891f84e7b', 'sined199@mail.ru', 1, '2017-10-02 05:38:50', 0, 1526459943, '81368ed67c6d25112d84dc3e3d0590a5'),
(31, '_sined', '25f9e794323b453885f5181f1b624d0b', 'sined199@gmail.com', 1, '2017-10-13 11:23:43', 0, 1515657997, 'e06d4a764e660c71e029fb88ab807017'),
(32, 'newlogin', 'a5081b83b86347e2c60e64106cef854c', 'test@mail.ru', 1, '2018-01-11 08:02:49', 22, 1515655732, ''),
(34, 'makks', '1b2b06b92ba87f8475485de910ac1984', 'makks@gmail.com', 1, '2018-01-11 09:11:38', 0, 1515773093, '8e6bb2298b0cd84a6bf8e6b4c9a78869');

-- --------------------------------------------------------

--
-- Структура таблицы `users_contacts`
--

CREATE TABLE `users_contacts` (
  `id` int(5) NOT NULL,
  `id_user` int(5) NOT NULL,
  `id_user_added` int(5) NOT NULL,
  `date_add` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users_contacts`
--

INSERT INTO `users_contacts` (`id`, `id_user`, `id_user_added`, `date_add`) VALUES
(13, 31, 22, '2017-10-13'),
(14, 22, 31, '2017-10-13'),
(15, 32, 22, '2018-01-11'),
(16, 22, 32, '2018-01-11');

-- --------------------------------------------------------

--
-- Структура таблицы `users_info`
--

CREATE TABLE `users_info` (
  `id` int(5) NOT NULL,
  `id_user` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `bday` date NOT NULL,
  `about` text NOT NULL,
  `number_mobile` varchar(255) NOT NULL,
  `email_for_contact` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users_info`
--

INSERT INTO `users_info` (`id`, `id_user`, `name`, `surname`, `country`, `city`, `bday`, `about`, `number_mobile`, `email_for_contact`) VALUES
(14, 22, 'Денис', 'Серебринский', 'Украина', 'Одесса', '1991-10-21', '', '+380968569315', 'dev@gmail.com'),
(23, 31, 'Олег', 'Кружкин', 'Украина', 'Киев', '0000-00-00', '', '+38096259648', 'call@mail.ru'),
(24, 32, '', '', '', '', '0000-00-00', '', '', ''),
(25, 34, 'Максим', 'Сайтов', 'Украина', 'Одесса', '0000-00-00', '', '', 'foremail@mail.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `users_invited`
--

CREATE TABLE `users_invited` (
  `id` int(5) NOT NULL,
  `key_invite` varchar(255) NOT NULL,
  `id_user` int(5) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users_keys`
--

CREATE TABLE `users_keys` (
  `id` int(5) NOT NULL,
  `key_reg` varchar(255) NOT NULL,
  `id_user` int(5) NOT NULL,
  `date_add` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users_keys`
--

INSERT INTO `users_keys` (`id`, `key_reg`, `id_user`, `date_add`) VALUES
(2, '093242', 33, '2018-01-11 09:09:32');

-- --------------------------------------------------------

--
-- Структура таблицы `users_settings`
--

CREATE TABLE `users_settings` (
  `id` int(5) NOT NULL,
  `id_user` int(5) NOT NULL,
  `search_user` tinyint(1) NOT NULL,
  `view_statistics` tinyint(1) NOT NULL,
  `send_invite` tinyint(1) NOT NULL,
  `hidden_profile` tinyint(1) NOT NULL,
  `mail_invite` tinyint(1) NOT NULL,
  `mail_new_ads` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users_settings`
--

INSERT INTO `users_settings` (`id`, `id_user`, `search_user`, `view_statistics`, `send_invite`, `hidden_profile`, `mail_invite`, `mail_new_ads`) VALUES
(16, 22, 1, 1, 1, 0, 0, 0),
(25, 31, 1, 1, 1, 0, 0, 0),
(26, 32, 1, 1, 1, 0, 0, 0),
(27, 34, 1, 1, 1, 1, 1, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `position_ads`
--
ALTER TABLE `position_ads`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `position_list`
--
ALTER TABLE `position_list`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `position_projects`
--
ALTER TABLE `position_projects`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `position_users`
--
ALTER TABLE `position_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `projects_users`
--
ALTER TABLE `projects_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `proposed_positions`
--
ALTER TABLE `proposed_positions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `resetpass`
--
ALTER TABLE `resetpass`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks_main`
--
ALTER TABLE `tasks_main`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `task_main_users`
--
ALTER TABLE `task_main_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`,`login`);

--
-- Индексы таблицы `users_contacts`
--
ALTER TABLE `users_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_invited`
--
ALTER TABLE `users_invited`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_keys`
--
ALTER TABLE `users_keys`
  ADD PRIMARY KEY (`id`,`id_user`);

--
-- Индексы таблицы `users_settings`
--
ALTER TABLE `users_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `position_ads`
--
ALTER TABLE `position_ads`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `position_list`
--
ALTER TABLE `position_list`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `position_projects`
--
ALTER TABLE `position_projects`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `position_users`
--
ALTER TABLE `position_users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `projects_users`
--
ALTER TABLE `projects_users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `proposed_positions`
--
ALTER TABLE `proposed_positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `resetpass`
--
ALTER TABLE `resetpass`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `tasks_main`
--
ALTER TABLE `tasks_main`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `task_main_users`
--
ALTER TABLE `task_main_users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT для таблицы `users_contacts`
--
ALTER TABLE `users_contacts`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT для таблицы `users_info`
--
ALTER TABLE `users_info`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT для таблицы `users_invited`
--
ALTER TABLE `users_invited`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `users_keys`
--
ALTER TABLE `users_keys`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `users_settings`
--
ALTER TABLE `users_settings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
