-- Полный дамп БД для разработки 

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;

--
-- Database: `defcon_game_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_online_sessions_tbl`
--

DROP TABLE IF EXISTS `user_online_sessions_tbl`;
CREATE TABLE `user_online_sessions_tbl` (
  `user_id` int(10) unsigned NOT NULL,
  `uid` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ukey` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `online_at` int(10) unsigned NOT NULL,
  `online_status` set('online','offline') COLLATE utf8_unicode_ci NOT NULL,
  `offline_from` int(10) unsigned NOT NULL,
  `offline_type` set('logout','gc','twiling_offline','none') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'none',
  `user_agent` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `user_IP` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='пользователи онлайн';

--
-- Dumping data for table `user_online_sessions_tbl`
--

INSERT INTO `user_online_sessions_tbl` (`user_id`, `uid`, `ukey`, `online_at`, `online_status`, `offline_from`, `offline_type`, `user_agent`, `user_IP`) VALUES
(2, '62114229', '3335b5db7a009437c54b2f8864b24525', 1344603954, 'online', 0, 'none', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.60 Safari/537.1', '127.0.0.1'),
(1, '8244243', 'a3ef1c287d3504234495c65881223be6', 1349178390, 'online', 0, 'none', 'Mozilla/5.0 (Windows NT 6.1; rv:14.0) Gecko/20100101 Firefox/14.0.1', '127.0.0.1');



--
-- Структура таблицы `user_socialprofiles_tbl`
--

DROP TABLE IF EXISTS `user_socialprofiles_tbl`;
CREATE TABLE IF NOT EXISTS `user_socialprofiles_tbl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `screen_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sex` tinyint(4) NOT NULL,
  `bdate` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `photo_medium` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `photo_medium_rec` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `photo_big` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `has_mobile` tinyint(4) NOT NULL,
  `activity` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_seen_time` int(10) unsigned NOT NULL,
  `registered_at` int(10) unsigned NOT NULL,
  `user_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='таблица юзер аккаунтов' AUTO_INCREMENT=102 ;

--
-- Дамп данных таблицы `user_socialprofiles_tbl`
--

INSERT INTO `user_socialprofiles_tbl` (`id`, `uid`, `login`, `name`, `first_name`, `last_name`, `screen_name`, `sex`, `bdate`, `timezone`, `photo`, `photo_medium`, `photo_medium_rec`, `photo_big`, `has_mobile`, `activity`, `last_seen_time`, `registered_at`, `user_status`) VALUES
(100, '8244243', 'id8244243', 'Александр Лозовюк', 'Александр', 'Лозовюк', 'id8244243', 2, '25.12.1982', '2', 'http://cs9878.userapi.com/u8244243/e_c437eeee.jpg', 'http://cs9878.userapi.com/u8244243/b_b473b07b.jpg', 'http://cs9878.userapi.com/u8244243/d_6956c568.jpg', 'http://cs9878.userapi.com/u8244243/a_6400c9be.jpg', 1, 'я не психопат, я деятельный социопат', 1344271517, 1344271649, 'user'),
(101, '62114229', 'id62114229', 'Сергей Вербский', 'Сергей', 'Вербский', 'id62114229', 2, '17.11', '2', 'http://cs9292.userapi.com/u62114229/e_d2b56ac8.jpg', 'http://cs9292.userapi.com/u62114229/b_5560f301.jpg', 'http://cs9292.userapi.com/u62114229/d_31daa75c.jpg', 'http://cs9292.userapi.com/u62114229/a_7e9ffb49.jpg', 1, '', 1344674906, 1344674915, 'user');






COMMIT;
