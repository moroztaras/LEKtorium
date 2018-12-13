-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Гру 13 2018 р., 16:03
-- Версія сервера: 5.7.24-0ubuntu0.16.04.1
-- Версія PHP: 7.2.11-2+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `lektorium_blog`
--

-- --------------------------------------------------------

--
-- Структура таблиці `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `article`
--

INSERT INTO `article` (`id`, `title`, `text`, `created_at`, `user_id`) VALUES
(1, 'Doctrine Fixtures Bundle', 'Fixtures are used to load a "fake" set of data into a database that can then be used for testing or to help give you some interesting data while you\'re developing your application.', '2018-11-30 19:50:20', 1),
(25, 'Workflow', 'A workflow is a model of a process in your application. It may be the process of how a blog post goes from draft, review and publish. Another example is when a user submits a series of different forms to complete a task. Such processes are best kept away from your models and should be defined in configuration.\r\n\r\nA definition of a workflow consist of places and actions to get from one place to another. The actions are called transitions. A workflow does also need to know each object\'s position in the workflow. That marking store writes to a property of the object to remember the current place.', '2018-12-13 15:39:03', 1),
(26, 'Validation', 'Validation is a very common task in web applications. Data entered in forms needs to be validated. Data also needs to be validated before it is written into a database or passed to a web service.\r\n\r\nSymfony provides a Validator component that makes this task easy and transparent. This component is based on the JSR303 Bean Validation specification.', '2018-12-13 15:42:33', 1),
(27, 'Translations', 'The term "internationalization" (often abbreviated i18n) refers to the process of abstracting strings and other locale-specific pieces out of your application into a layer where they can be translated and converted based on the user\'s locale (i.e. language and country). For text, this means wrapping each with a function capable of translating the text (or "message") into the language of the user:', '2018-12-13 15:43:49', 1),
(28, 'Testing', 'Whenever you write a new line of code, you also potentially add new bugs. To build better and more reliable applications, you should test your code using both functional and unit tests.', '2018-12-13 15:45:04', 1),
(29, 'Service Container', 'Your application is full of useful objects: a "Mailer" object might help you send emails while another object might help you save things to the database. Almost everything that your app "does" is actually done by one of these objects. And each time you install a new bundle, you get access to even more!\r\n\r\nIn Symfony, these useful objects are called services and each service lives inside a very special object called the service container. The container allows you to centralize the way objects are constructed. It makes your life easier, promotes a strong architecture and is super fast!', '2018-12-13 15:46:23', 1),
(30, 'How to Use the Serializer', 'Symfony provides a serializer to serialize/deserialize to and from objects and different formats (e.g. JSON or XML). Before using it, read the Serializer component docs to get familiar with its philosophy and the normalizers and encoders terminology.', '2018-12-13 15:47:56', 1),
(31, 'How to Use the Messenger', 'Symfony\'s Messenger provides a message bus and some routing capabilities to send messages within your application and through transports such as message queues. Before using it, read the Messenger component docs to get familiar with its concepts.', '2018-12-13 15:49:17', 1),
(32, 'Logging', 'Symfony comes with a minimalist PSR-3 logger: Logger. In conformance with the twelve-factor app methodology, it sends messages starting from the WARNING level to stderr.\r\n\r\nThe minimal log level can be changed by setting the SHELL_VERBOSITY environment variable.\r\n\r\nThe minimum log level, the default output and the log format can also be changed by passing the appropriate arguments to the constructor of Logger. To do so, override the "logger" service definition.', '2018-12-13 15:51:13', 1),
(33, 'HTTP Cache', 'The nature of rich web applications means that they\'re dynamic. No matter how efficient your application, each request will always contain more overhead than serving a static file. Usually, that\'s fine. But when you need your requests to be lightning fast, you need HTTP caching.', '2018-12-13 15:52:46', 1),
(34, 'Managing CSS and JavaScript', 'Symfony ships with a pure-JavaScript library - called Webpack Encore - that makes working with CSS and JavaScript a joy. You can use it, use something else, or just create static CSS and JS files in your public/ directory and include them in your templates.\r\n\r\nWebpack Encore¶\r\nWebpack Encore is a simpler way to integrate Webpack into your application. It wraps Webpack, giving you a clean & powerful API for bundling JavaScript modules, pre-processing CSS & JS and compiling and minifying assets. Encore gives you professional asset system that\'s a delight to use.\r\n\r\nEncore is inspired by Webpacker and Mix, but stays in the spirit of Webpack: using its features, concepts and naming conventions for a familiar feel. It aims to solve the most common Webpack use cases.', '2018-12-13 15:55:06', 1),
(35, 'Forms', 'Dealing with HTML forms is one of the most common - and challenging - tasks for a web developer. Symfony integrates a Form component that makes dealing with forms easy. In this article, you\'ll build a complex form from the ground up, learning the most important features of the form library along the way.', '2018-12-13 15:56:57', 1),
(36, 'Using Symfony Flex to Manage Symfony Applications', 'Symfony Flex is the new way to install and manage Symfony applications. Flex is not a new Symfony version, but a tool that replaces and improves the Symfony Installer and the Symfony Standard Edition.\r\n\r\nSymfony Flex automates the most common tasks of Symfony applications, like installing and removing bundles and other Composer dependencies. Symfony Flex works for Symfony 3.3 and higher. Starting from Symfony 4.0, Flex should be used by default, but it is still optional.', '2018-12-13 15:59:56', 1);

-- --------------------------------------------------------

--
-- Структура таблиці `article_like`
--

CREATE TABLE `article_like` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `article_like`
--

INSERT INTO `article_like` (`id`, `article_id`, `user_id`) VALUES
(112, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблиці `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `comment`
--

INSERT INTO `comment` (`id`, `article_id`, `comment`, `created_at`, `user_id`) VALUES
(1, 1, 'Comment 1 > Doctrine Fixtures Bundle', '2018-11-30 19:50:21', 1),
(2, 1, 'Comment 2 > Doctrine Fixtures Bundle', '2018-11-30 19:50:21', 1),
(3, 1, 'Comment 3 > Doctrine Fixtures Bundle', '2018-11-30 19:50:21', 1);

-- --------------------------------------------------------

--
-- Структура таблиці `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `migration_versions`
--

INSERT INTO `migration_versions` (`version`) VALUES
('20181031111726'),
('20181105101320'),
('20181105103157'),
('20181108085419'),
('20181110150008'),
('20181110183959'),
('20181123175229'),
('20181126111024'),
('20181130174849');

-- --------------------------------------------------------

--
-- Структура таблиці `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` json NOT NULL,
  `first_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `roles`, `first_name`, `last_name`, `created_at`) VALUES
(1, 'moroztaras@i.ua', '$argon2i$v=19$m=1024,t=2,p=2$MkdORkgxSVFxV2tuMUFMSA$PMTd3gddVH3eMt/UVWZJZJGLpK/76nnDx0ODEqOPshY', '["ROLE_ADMIN"]', 'Taras', 'Moroz', '2018-11-30 19:50:21'),
(2, 'user@mail.ua', '$argon2i$v=19$m=1024,t=2,p=2$OEZSVmVVL0FEY1djR1ZUeA$ejPRz34PF1OxFUJ/mFz1uhRdW2OdHcoFb4vrh0GldNI', '["ROLE_USER"]', 'UserFirstName', 'UserLastName', '2018-11-30 19:50:21');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_23A0E66A76ED395` (`user_id`);

--
-- Індекси таблиці `article_like`
--
ALTER TABLE `article_like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1C21C7B27294869C` (`article_id`),
  ADD KEY `IDX_1C21C7B2A76ED395` (`user_id`);

--
-- Індекси таблиці `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526C7294869C` (`article_id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`);

--
-- Індекси таблиці `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Індекси таблиці `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT для таблиці `article_like`
--
ALTER TABLE `article_like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;
--
-- AUTO_INCREMENT для таблиці `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT для таблиці `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_23A0E66A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `article_like`
--
ALTER TABLE `article_like`
  ADD CONSTRAINT `FK_1C21C7B27294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_1C21C7B2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C7294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
