-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- ホスト: mysql140.phy.lolipop.lan
-- 生成日時: 2019 年 12 月 07 日 21:47
-- サーバのバージョン: 5.6.23-log
-- PHP のバージョン: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `LAA0818998-product910`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `admin_accounts`
--

DROP TABLE IF EXISTS `admin_accounts`;
CREATE TABLE IF NOT EXISTS `admin_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `adsenses`
--

DROP TABLE IF EXISTS `adsenses`;
CREATE TABLE IF NOT EXISTS `adsenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `area` varchar(20) DEFAULT NULL,
  `genre` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `catch` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `valid_start` date NOT NULL,
  `valid_end` date NOT NULL,
  `top_show_flg` int(1) NOT NULL,
  `area_show_flg` int(1) NOT NULL,
  `top_order` int(2) DEFAULT NULL,
  `area_order` int(2) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- テーブルのデータのダンプ `adsenses`
--

INSERT INTO `adsenses` (`id`, `owner_id`, `shop_id`, `type`, `area`, `genre`, `name`, `catch`, `valid_start`, `valid_end`, `top_show_flg`, `area_show_flg`, `top_order`, `area_order`, `created`, `modified`) VALUES
(1, 3, 3, 'main', 'naha', 'cabacula', 'https://drive.google.com/uc?id=1wWygal0ffxCWt8vNr1kz1tGwsLmWWidF', '☺那覇で飲むならclub琉球へ☺', '2019-11-23', '2020-11-30', 1, 1, 1, 1, '2019-08-10 16:58:22', '2019-08-10 16:58:22'),
(2, 7, 7, 'main', 'okinawashi', 'cabacula', 'https://drive.google.com/uc?id=11eatUtDDf0BqFXWaANLNgkAhxlDjS8Km', '☺沖縄市で飲むならClub M -エム-へ☺', '2019-11-23', '2020-11-30', 0, 1, 2, 1, '2019-08-10 16:58:22', '2019-08-10 16:58:22'),
(3, 6, 6, 'main', 'naha', 'snack', 'https://drive.google.com/uc?id=13_OA87Q6X7uTjxK-n5rcx0NIatztK2PY', '☺那覇で飲むならGIZA PALACE -ギザパレス-へ☺', '2019-11-23', '2020-11-30', 0, 1, 3, 2, '2019-08-10 16:58:22', '2019-08-10 16:58:22'),
(4, 1, 1, 'sub', 'urasoe', 'club', 'https://drive.google.com/uc?id=12eB2CTJlntMMHcgw7UvmPJpcsfIc90Pw', '☺那覇で飲むならARENA -アリーナ-へ☺', '2019-11-23', '2020-11-30', 0, 1, 1, 1, '2019-08-10 16:58:22', '2019-08-10 16:58:22'),
(5, 2, 2, 'sub', 'urasoe', 'girlsbar', 'https://drive.google.com/uc?id=11fiDfGF4X5Qi-Txf2AfzpYOanrvT8bl7', '☺浦添で飲むならフェリスへ☺', '2019-11-23', '2020-11-30', 0, 1, 2, 2, '2019-08-10 16:58:22', '2019-08-10 16:58:22'),
(6, 4, 4, 'main', 'naha', 'cabacula', 'https://drive.google.com/uc?id=1IyfWxskShqZ338577E1gzQI3IHw3eTf2', '☺那覇で飲むならShuri -シュリ-へ☺', '2019-11-23', '2020-11-30', 1, 1, 2, 3, '2019-08-10 16:58:22', '2019-08-10 16:58:22'),
(7, 5, 5, 'main', 'naha', 'cabacula', 'https://drive.google.com/uc?id=1lJ74IUkOdV9CfkSaLY79pAMzM1Stk0Iz', '☺那覇で飲むならclub Petit -プティ-へ☺', '2019-11-23', '2020-11-30', 1, 1, 3, 4, '2019-08-10 16:58:22', '2019-08-10 16:58:22');

-- --------------------------------------------------------

--
-- テーブルの構造 `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `body` text,
  `published` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `articles_tags`
--

DROP TABLE IF EXISTS `articles_tags`;
CREATE TABLE IF NOT EXISTS `articles_tags` (
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`tag_id`),
  KEY `tag_key` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `casts`
--

DROP TABLE IF EXISTS `casts`;
CREATE TABLE IF NOT EXISTS `casts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL,
  `role` varchar(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `nickname` varchar(30) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `three_size` varchar(10) DEFAULT NULL,
  `blood_type` varchar(20) DEFAULT NULL,
  `constellation` varchar(20) DEFAULT NULL,
  `age` varchar(5) DEFAULT NULL,
  `message` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `holiday` varchar(50) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `delete_flag` int(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_key` (`shop_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- テーブルのデータのダンプ `casts`
--

INSERT INTO `casts` (`id`, `shop_id`, `role`, `name`, `nickname`, `email`, `password`, `birthday`, `three_size`, `blood_type`, `constellation`, `age`, `message`, `holiday`, `dir`, `remember_token`, `status`, `delete_flag`, `created`, `modified`) VALUES
(1, 1, 'cast', 'なな', 'なな', 'okiyoru99@gmail.com', '$2y$10$Op0.Cgd9vufJu7f6azq53OjtUaeDXUBZJ6jsgQAq3AeywhRkPJ0j.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00001', NULL, 1, 0, '2019-08-08 23:37:31', '2019-09-05 23:27:03'),
(2, 1, 'cast', 'りおな', 'りおな', 'okiyoru99@gmail.com', '$2y$10$OeB2W0ufPZJpOKoZngWFpOrkNeFSETNOMPl/adL5DhpSrts0Cfrpe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00002', NULL, 1, 0, '2019-08-09 00:01:51', '2019-09-05 23:28:00'),
(3, 1, 'cast', 'ゆいか', 'ゆいか', 'okiyoru99@gmail.com', '$2y$10$2ksjTbJZy//cLD.fK6rypeYmfSLPckgcNoxn.2pPLN4ovl9jIc5ae', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00003', NULL, 1, 0, '2019-08-09 20:20:25', '2019-08-09 20:22:56'),
(4, 1, 'cast', 'まみ', 'まみ', 'okiyoru99@gmail.com', '$2y$10$zMiLChXeQlZ1VGnNHfivTewm7eWrHxOmw24oOxd24eXzvEcshFf/C', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00004', NULL, 1, 0, '2019-08-09 20:23:37', '2019-09-05 23:29:37'),
(5, 1, 'cast', 'あやの', 'あやの', 'okiyoru99@gmail.com', '$2y$10$yKV7WFDrJtjI/3bG.HKcIOHdKWcMGkAMkb0i78eigS2LMn7F8FV4O', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00005', NULL, 1, 0, '2019-08-09 20:26:27', '2019-09-05 23:30:19'),
(6, 1, 'cast', 'さくら', 'さくら', 'okiyoru99@gmail.com', '$2y$10$ZK7IpBPgr3ZQiwxFOn/ZpOGxodptM.mq5E45v3giYcPoNcjo57WU6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00006', NULL, 1, 0, '2019-08-09 20:33:22', '2019-09-05 23:34:48'),
(7, 1, 'cast', 'あや', 'あや', 'okiyoru99@gmail.com', '$2y$10$hlJU3b8NhEM/O34sBLjRGu7MLqDx4/K5HEi/9nykdHgnUUeHfFAXa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00007', NULL, 1, 0, '2019-08-09 20:37:42', '2019-09-05 23:35:57'),
(8, 1, 'cast', 'ひな', 'ひな', 'okiyoru99@gmail.com', '$2y$10$AyXZpXWXKYZfs.kZL3V8ueCyxgip9ipQLMhuSo6EawXFs4pY1Y1Ja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00008', NULL, 1, 0, '2019-08-09 20:39:57', '2019-09-05 23:36:30'),
(9, 2, 'cast', 'しほ', 'しほ', 'okiyoru99@gmail.com', '$2y$10$TXeMOwhsK/4vPQoJn.ZCN.PxboDRp8jSYurqQkuVwfqWKlKTWdiXy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00001', NULL, 1, 0, '2019-08-10 16:11:29', '2019-09-05 23:38:32'),
(10, 2, 'cast', 'るか', 'るか', 'okiyoru99@gmail.com', '$2y$10$AoA.bJqZuQC0SSjDCRvK4uiZQeIRtpM/U/6udQp/mes77GXgemtJK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00002', NULL, 1, 0, '2019-08-10 16:17:02', '2019-09-05 23:39:26'),
(11, 2, 'cast', 'あーす', 'あーす', 'okiyoru99@gmail.com', '$2y$10$nZe1EOBngjM3iDxMVLLPNuBXc8b.1DOWhNrdk3WGJ.qhPSbY4Qimi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00003', NULL, 1, 0, '2019-08-10 16:18:42', '2019-09-05 23:39:47'),
(12, 2, 'cast', 'みずき', 'みずき', 'okiyoru99@gmail.com', '$2y$10$/6K5phAfNciUFN.6lAh/0OftmW4kArVeGsekekfWjzJyIOV8WAetW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00004', NULL, 1, 0, '2019-08-10 16:20:27', '2019-09-05 23:40:10'),
(13, 2, 'cast', 'しの', 'しの', 'okiyoru99@gmail.com', '$2y$10$dzUNVjZvry8zpdHXf7Xr8O84YzhGfSG.MXsJm/uBNKz2yk.M5.zNm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00005', NULL, 1, 0, '2019-08-10 16:21:58', '2019-09-09 00:41:32'),
(14, 3, 'cast', 'なお', 'なお', 'okiyoru1@gmail.com', '$2y$10$9vJ4TwXPELht86ugs9.05u3ef5vgj/OPtrozkOISVnJG93yssCbua', NULL, NULL, 'blood_type1', 'constellation3', '22', 'CLUB琉球をよろしくで~す', NULL, '00001', NULL, 1, 0, '2019-08-30 17:23:36', '2019-11-17 16:53:01'),
(15, 3, 'cast', 'にーな', 'にーな', 'okiyoru2@gmail.com', '$2y$10$IccvqXgH5z6UxsUctPg03u8vacc2vepP3JoJ2L7AZMi0/NTP3ol7.', NULL, NULL, '', '', '', 'club琉球をよろしくお願いします！', NULL, '00002', '0', 1, 0, '2019-08-10 17:27:06', '2019-12-07 02:35:18'),
(18, 7, 'cast', 'リエ', 'リエ', 'okiyoru99@gmail.com', '$2y$10$1k6VOOYYhrFWRdI3gDX9suL6uqXH.Afq7LslSe1OumDBene7y3Wt.', NULL, NULL, 'blood_type4', 'constellation9', '27', '', NULL, '00001', NULL, 1, 0, '2019-11-21 22:32:25', '2019-11-21 23:03:20'),
(19, 7, 'cast', 'リン', 'リン', 'okiyoru3@gmail.com', '$2y$10$iCoE.fqQaAWQze/N.8ELYOl/kjYvQtCgrij7gZEJaw7rQjTkGI0Am', NULL, NULL, '', 'constellation6', '29', '', NULL, '00002', NULL, 1, 0, '2019-11-21 23:06:00', '2019-11-21 23:07:58');

-- --------------------------------------------------------

--
-- テーブルの構造 `cast_schedules`
--

DROP TABLE IF EXISTS `cast_schedules`;
CREATE TABLE IF NOT EXISTS `cast_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL,
  `cast_id` int(11) NOT NULL,
  `event_type_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `time_start` varchar(20) DEFAULT NULL,
  `time_end` varchar(20) DEFAULT NULL,
  `all_day` varchar(1) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- テーブルのデータのダンプ `cast_schedules`
--

INSERT INTO `cast_schedules` (`id`, `shop_id`, `cast_id`, `event_type_id`, `title`, `details`, `start`, `end`, `time_start`, `time_end`, `all_day`, `status`, `active`, `created`, `modified`) VALUES
(1, 7, 19, NULL, '休み', NULL, '2019-11-07 00:00:00', '2019-11-07 00:00:00', NULL, NULL, '1', NULL, 1, '2019-11-23 00:58:33', '2019-11-23 00:58:33'),
(2, 7, 19, NULL, '休み', NULL, '2019-11-08 00:00:00', '2019-11-08 00:00:00', NULL, NULL, '1', NULL, 1, '2019-11-23 00:58:39', '2019-11-23 00:58:39'),
(3, 7, 19, NULL, '仕事', NULL, '2019-11-24 00:00:00', '2019-11-24 00:00:00', '00:00', NULL, '1', NULL, 1, '2019-11-23 00:59:16', '2019-11-23 00:59:16'),
(4, 7, 19, NULL, '仕事', NULL, '2019-11-25 21:00:00', '2019-11-25 03:00:00', '21:00', '03:00', '1', NULL, 1, '2019-11-23 01:00:00', '2019-11-23 01:00:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `from_day` datetime NOT NULL,
  `to_day` datetime NOT NULL,
  `title` text CHARACTER SET utf8mb4 NOT NULL,
  `content` text CHARACTER SET utf8mb4 NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_key` (`shop_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- テーブルのデータのダンプ `coupons`
--

INSERT INTO `coupons` (`id`, `shop_id`, `status`, `from_day`, `to_day`, `title`, `content`, `created`, `modified`) VALUES
(1, 1, 1, '2019-08-08 00:00:00', '2019-09-30 00:00:00', 'ウェルカムドリンク1杯サービス or 時間+10分サービス', '入店時にこちらのクーポンをお見せいただくと、ウェルカムドリンク1杯サービス、\r\nまたは時間＋１０分延長サービスになります。', '2019-08-08 23:31:00', '2019-08-08 23:31:18'),
(2, 2, 1, '2019-08-10 00:00:00', '2020-08-10 00:00:00', '初回セット料金1000円OFF', '初回セット料金1000円OFFになります。\r\n是非ご利用ください!! スタッフ、キャスト一同心よりお待ちしております?‍♂️', '2019-08-10 17:31:09', '2019-09-18 19:06:52'),
(3, 5, 1, '2019-11-01 00:00:00', '2020-11-01 00:00:00', '?新規オープンキャンペーンにつきまして?', '来店の際始めに男子従業員にﾅｲﾌﾟﾗを見たと伝えて頂くと、初回1時間4000円でご案内させていただきます!', '2019-11-21 00:28:36', '2019-11-21 00:28:43'),
(4, 6, 1, '2019-11-01 00:00:00', '2020-11-01 00:00:00', 'GIZA PALACEのクーポン１', 'ウェルカムドリンク1杯サービスor時間+10分サービス', '2019-11-21 19:53:04', '2019-11-21 19:53:09'),
(5, 7, 1, '2019-11-01 00:00:00', '2020-11-01 00:00:00', 'New Club Mのクーポン１', 'ﾅｲﾌﾟﾗをご利用のお客様に限り、時間サービス+カラオケ&乾杯ビールサービス', '2019-11-21 20:13:52', '2019-11-21 20:13:52');

-- --------------------------------------------------------

--
-- テーブルの構造 `developers`
--

DROP TABLE IF EXISTS `developers`;
CREATE TABLE IF NOT EXISTS `developers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `diarys`
--

DROP TABLE IF EXISTS `diarys`;
CREATE TABLE IF NOT EXISTS `diarys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cast_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(600) NOT NULL,
  `dir` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cast_key` (`cast_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

--
-- テーブルのデータのダンプ `diarys`
--

INSERT INTO `diarys` (`id`, `cast_id`, `title`, `content`, `dir`, `created`, `modified`) VALUES
(1, 15, 'テスト', 'テスト', '/2019/12/07/20191207_023747', '2019-12-07 02:37:47', '2019-12-07 02:37:47');

-- --------------------------------------------------------

--
-- テーブルの構造 `diary_likes`
--

DROP TABLE IF EXISTS `diary_likes`;
CREATE TABLE IF NOT EXISTS `diary_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `diary_id` int(11) NOT NULL,
  `cast_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `diary_like_key` (`diary_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `event_types`
--

DROP TABLE IF EXISTS `event_types`;
CREATE TABLE IF NOT EXISTS `event_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL,
  `industry` varchar(30) DEFAULT NULL,
  `job_type` varchar(30) DEFAULT NULL,
  `work_from_time` time DEFAULT NULL,
  `work_to_time` time DEFAULT NULL,
  `work_time_hosoku` varchar(50) DEFAULT NULL,
  `from_age` varchar(2) DEFAULT NULL,
  `to_age` varchar(2) DEFAULT NULL,
  `qualification_hosoku` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `holiday` varchar(50) DEFAULT NULL,
  `holiday_hosoku` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `treatment` varchar(255) DEFAULT NULL,
  `pr` varchar(400) CHARACTER SET utf8mb4 DEFAULT NULL,
  `tel1` varchar(15) DEFAULT NULL,
  `tel2` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `lineid` varchar(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_key` (`shop_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- テーブルのデータのダンプ `jobs`
--

INSERT INTO `jobs` (`id`, `shop_id`, `industry`, `job_type`, `work_from_time`, `work_to_time`, `work_time_hosoku`, `from_age`, `to_age`, `qualification_hosoku`, `holiday`, `holiday_hosoku`, `treatment`, `pr`, `tel1`, `tel2`, `email`, `lineid`, `created`, `modified`) VALUES
(1, 1, '時間制(キャバクラ)', 'レディスタッフ・キャスト', '21:00:00', NULL, '時間相談に応じます 1日2～3時間の勤務もOKです', '20', '30', '※初心者大歓迎', '土', '	勤務日数はおまかせします。', '体験入店あり,日払い可,レンタル衣装あり,ノルマなし,未経験者歓迎,各種バックあり,送迎あり,経験者優遇,ドレス・制服貸与あり', '浦添エリアの人気店!! 【体験保証時給3,000円】 未経験者大歓迎!在籍中キャストのほとんどが掛け持ちや週1～3出勤など 自分に合ったスタイルで働いております。貴女も是非「体験入店」でお試し下さい! 掛け持ちオッケー(掛け持ちの方も多数在籍してます) 即日体験入店OK、スタッフ専用駐車場完備(30台) お酒飲まなくてもOK(車で出勤して飲まずにお仕事する方も多いですよ!) 送迎もあります!', '09097874621', '0988786792', NULL, 'kaitok0502', '2019-08-07 23:56:04', '2019-08-10 14:50:33'),
(2, 2, 'ガールズバー', 'カウンターレディ', '20:00:00', NULL, '※時間相談に応じます、３ｈ～の短時間でもOK', '18', '30', '※初心者・学生・主婦・シングルマザー大歓迎', NULL, '週１日～勤務ＯＫ、週末のみでもＯＫ', '日払い可,各種バックあり,ノルマなし,送迎あり,未経験者歓迎', '楽しく働くならここ！ まだまだオープンしたばかりのガールズバーです♪ 時給１,２００円～１,５００円以上！ 完全日払い制!! 送迎有り（中南部） お友達同士の応募、一日体験もオッケイです♪ 気軽にお問い合わせください♪＼(^o^)／', '09068653218', '', NULL, '', '2019-08-10 15:16:08', '2019-08-10 16:29:44'),
(3, 3, '時間制(キャバクラ)', 'レディスタッフ・キャスト', '21:00:00', NULL, '21:00～LASTまでの間でお好きな時間', '18', '35', '（高校生不可）', NULL, '', '体験入店あり,お友達と一緒に面接可,未経験者歓迎,週末のみ可,ノルマなし,レンタル衣装あり,週１から可,大型連休あり,モノレール駅からすぐ,日払い可,送迎あり,給与支給2回,ヘアメイクあり,各種バックあり,経験者優遇,ドレス・制服貸与あり,個人ロッカーあり,友達紹介料あり', '初めまして、店長の仁科です。\r\n求人を見ていただきありがとうございます(^^)\r\n少なからずこのホームページを開いたこの瞬間は期待と不安が入り混じっているかと思います！\r\nまた、未経験の方はさらに一歩が踏み出せない、なんてこともありますよね！\r\n僕自身も同じ経験がありますので一歩踏み出す勇気がどれほど大変なのかわかります！\r\nだからこそ一度勇気を出して電話を下さい！\r\nしっかりした対応、サポートをさせていただきますのでどんな小さなことでもお気軽に問い合わせのほうをしてきてください！\r\nお待ちしています(^_^)', '0120596307', '', NULL, '', '0000-00-00 00:00:00', '2019-08-25 16:30:11'),
(4, 4, '時間制(キャバクラ)', 'レディスタッフ・キャスト', '21:00:00', NULL, '', '20', '30', '18歳～30歳位迄　※経験者大歓迎　※未経験者大歓迎', NULL, '', '体験入店あり,お友達と一緒に面接可,週末のみ可,週１から可,大型連休あり,日払い可,各種バックあり,友達紹介料あり,未経験者歓迎,ノルマなし,モノレール駅からすぐ,送迎あり,ヘアメイクあり,経験者優遇,ドレス・制服貸与あり,個人ロッカーあり', '体験入店時給000円～5000円以上!!\r\n時給3000円～5000円以上!!\r\n＋売上バック10～20%!!ドリンクバック、ボトルバック等、各種高額バック有り!!\r\n新しい豪華な店内でお仕事出来ます♪\r\nお酒が飲めないコでも全然大丈夫だし、未経験のコは自分のペースでゆっくりお仕事していってください!\r\n\r\nキャストさんが働き易い環境作りを準備してお待ちしています。心機一転、一緒にがんばって行きましょう!!\r\nわからない事や不安な事は遠慮なく聞いてくださいね！そして当店では頑張る貴女を応援します。', '09097836829', '0988617771', NULL, 'agarie0823', '2019-11-20 23:54:43', '2019-11-21 00:12:49'),
(5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-11-21 00:25:37', '2019-11-21 00:25:37'),
(6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-11-21 19:50:48', '2019-11-21 19:50:48'),
(7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-11-21 20:11:20', '2019-11-21 20:11:20'),
(8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-11-27 22:40:52', '2019-11-27 22:40:52');

-- --------------------------------------------------------

--
-- テーブルの構造 `master_codes`
--

DROP TABLE IF EXISTS `master_codes`;
CREATE TABLE IF NOT EXISTS `master_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `code_name` varchar(255) NOT NULL,
  `code_group` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `delete_flag` char(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `master_codes`
--

INSERT INTO `master_codes` (`id`, `code`, `code_name`, `code_group`, `sort`, `delete_flag`, `created`, `modified`) VALUES
(1, 'naha', '那覇', 'area', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(2, 'tomigusuku', '豊見城', 'area', 2, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(3, 'nanjo', '南城', 'area', 3, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(4, 'itoman', '糸満', 'area', 4, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(5, 'urasoe', '浦添', 'area', 5, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(6, 'ginowan', '宜野湾', 'area', 6, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(7, 'okinawashi', '沖縄市', 'area', 7, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(8, 'uruma', 'うるま', 'area', 8, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(9, 'nago', '名護', 'area', 9, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(10, 'miyakojima', '宮古島', 'area', 10, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(11, 'ishigakijima', '石垣島', 'area', 11, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(12, 'cabacula', 'キャバクラ', 'genre', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(13, 'girlsbar', 'ガールズバー', 'genre', 2, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(14, 'snack', 'スナック', 'genre', 3, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(15, 'club', 'クラブ', 'genre', 4, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(16, 'lounge', 'ラウンジ', 'genre', 5, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(17, 'pub', 'パブ', 'genre', 6, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(18, 'bar', 'バー', 'genre', 7, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(19, 'MasterCard', 'マスターカード', 'credit', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(20, 'VISA', 'ビサ', 'credit', 2, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(21, 'JCB', 'ジェイシービー', 'credit', 3, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(22, 'AmericanExpress', 'アメリカン・エクスプレス', 'credit', 4, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(23, 'Diners', 'ダイナース', 'credit', 5, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(24, 'time_caba', '時間制(キャバクラ)', 'industry', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(25, 'time_snack', '時間制(スナック))', 'industry', 2, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(26, 'time_lounge', '時間制(ラウンジ)', 'industry', 3, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(27, 'time_club', '時間制(クラブ)', 'industry', 4, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(28, 'bottle_caba', 'ボトル制(キャバクラ)', 'industry', 5, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(29, 'bottle_snack', 'ボトル制(スナック))', 'industry', 6, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(30, 'bottle_lounge', 'ボトル制(ラウンジ)', 'industry', 7, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(31, 'bottle_club', 'ボトル制(クラブ)', 'industry', 8, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(32, 'girsbar', 'ガールズバー', 'industry', 9, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(33, 'bar', 'バー', 'industry', 10, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(34, 'job_type1', 'レディスタッフ・キャスト', 'job_type', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(35, 'job_type2', 'カウンターレディ', 'job_type', 2, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(36, 'job_type3', 'ママ・チーママ', 'job_type', 3, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(37, 'job_type4', 'バーテンダー', 'job_type', 4, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(38, 'job_type5', '幹部候補・店長候補', 'job_type', 5, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(39, 'job_type6', 'キッチンスタッフ', 'job_type', 6, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(40, 'job_type7', '送迎スタッフ', 'job_type', 7, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(41, 'treatment1', '体験入店あり', 'treatment', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(42, 'treatment2', 'お友達と一緒に面接可', 'treatment', 2, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(43, 'treatment3', '週末のみ可', 'treatment', 3, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(44, 'treatment4', '週１から可', 'treatment', 4, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(45, 'treatment5', '大型連休あり', 'treatment', 5, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(46, 'treatment6', '日払い可', 'treatment', 6, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(47, 'treatment7', '給与支給2回', 'treatment', 7, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(48, 'treatment8', '各種バックあり', 'treatment', 8, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(49, 'treatment9', '友達紹介料あり', 'treatment', 9, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(50, 'treatment10', '未経験者歓迎', 'treatment', 10, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(51, 'treatment11', 'ノルマなし', 'treatment', 11, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(52, 'treatment12', 'レンタル衣装あり', 'treatment', 12, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(53, 'treatment13', 'モノレール駅からすぐ', 'treatment', 13, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(54, 'treatment14', '送迎あり', 'treatment', 14, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(55, 'treatment15', 'ヘアメイクあり', 'treatment', 15, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(56, 'treatment16', '経験者優遇', 'treatment', 16, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(57, 'treatment17', 'ドレス・制服貸与あり', 'treatment', 17, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(58, 'treatment18', '個人ロッカーあり', 'treatment', 18, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(59, 'monday', '月', 'day', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(60, 'tuesday', '火', 'day', 2, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(61, 'wednesday', '水', 'day', 3, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(62, 'thursday', '木', 'day', 4, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(63, 'friday', '金', 'day', 5, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(64, 'saturday', '土', 'day', 6, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(65, 'sunday', '日', 'day', 7, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(66, '00:00', '00:00', 'time', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(67, '00:30', '00:30', 'time', 2, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(68, '01:00', '01:00', 'time', 3, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(69, '01:30', '01:30', 'time', 4, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(70, '02:00', '02:00', 'time', 5, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(71, '02:30', '02:30', 'time', 6, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(72, '03:00', '03:00', 'time', 7, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(73, '03:30', '03:30', 'time', 8, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(74, '04:00', '04:00', 'time', 9, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(75, '04:30', '04:30', 'time', 10, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(76, '05:00', '05:00', 'time', 11, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(77, '05:30', '05:30', 'time', 12, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(78, '06:00', '06:00', 'time', 13, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(79, '06:30', '06:30', 'time', 14, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(80, '07:00', '07:00', 'time', 15, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(81, '07:30', '07:30', 'time', 16, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(82, '08:00', '08:00', 'time', 17, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(83, '08:30', '08:30', 'time', 18, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(84, '09:00', '09:00', 'time', 19, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(85, '09:30', '09:30', 'time', 20, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(86, '10:00', '10:00', 'time', 21, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(87, '10:30', '10:30', 'time', 22, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(88, '11:00', '11:00', 'time', 23, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(89, '11:30', '11:30', 'time', 24, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(90, '12:00', '12:00', 'time', 25, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(91, '12:30', '12:30', 'time', 26, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(92, '13:00', '13:00', 'time', 27, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(93, '13:30', '13:30', 'time', 28, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(94, '14:00', '14:00', 'time', 29, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(95, '14:30', '14:30', 'time', 30, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(96, '15:00', '15:00', 'time', 31, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(97, '15:30', '15:30', 'time', 32, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(98, '16:00', '16:00', 'time', 33, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(99, '16:30', '16:30', 'time', 34, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(100, '17:00', '17:00', 'time', 35, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(101, '17:30', '17:30', 'time', 36, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(102, '18:00', '18:00', 'time', 37, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(103, '18:30', '18:30', 'time', 38, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(104, '19:00', '19:00', 'time', 39, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(105, '19:30', '19:30', 'time', 40, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(106, '20:00', '20:00', 'time', 41, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(107, '20:30', '20:30', 'time', 42, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(108, '21:00', '21:00', 'time', 43, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(109, '21:30', '21:30', 'time', 44, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(110, '22:00', '22:00', 'time', 45, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(111, '22:30', '22:30', 'time', 46, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(112, '23:00', '23:00', 'time', 47, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(113, '23:30', '23:30', 'time', 48, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(114, 'constellation1', 'おひつじ座', 'constellation', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(115, 'constellation2', 'おうし座', 'constellation', 2, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(116, 'constellation3', 'ふたご座', 'constellation', 3, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(117, 'constellation4', 'かに座', 'constellation', 4, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(118, 'constellation5', 'しし座', 'constellation', 5, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(119, 'constellation6', 'おとめ座', 'constellation', 6, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(120, 'constellation7', 'てんびん座', 'constellation', 7, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(121, 'constellation8', 'さそり座', 'constellation', 8, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(122, 'constellation9', 'いて座', 'constellation', 9, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(123, 'constellation10', 'やぎ座', 'constellation', 10, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(124, 'constellation11', 'みずがめ座', 'constellation', 11, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(125, 'constellation12', 'うお座', 'constellation', 11, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(126, 'blood_type1', 'A型', 'blood_type', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(127, 'blood_type2', 'B型', 'blood_type', 2, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(128, 'blood_type3', 'O型', 'blood_type', 3, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(129, 'blood_type4', 'AB型', 'blood_type', 4, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(130, 'work', '仕事', 'event', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(131, 'holiday', '休み', 'event', 1, NULL, '2019-01-06 21:23:05', '2019-01-13 18:46:10');

-- --------------------------------------------------------

--
-- テーブルの構造 `master_role`
--

DROP TABLE IF EXISTS `master_role`;
CREATE TABLE IF NOT EXISTS `master_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(64) NOT NULL COMMENT 'ロール名',
  `role_name` varchar(64) NOT NULL COMMENT 'ロール名',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='ロール' AUTO_INCREMENT=5 ;

--
-- テーブルのデータのダンプ `master_role`
--

INSERT INTO `master_role` (`id`, `role`, `role_name`, `created`, `modified`) VALUES
(1, 'owner', 'オーナー', '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(2, 'shop', 'マネージャー', '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(3, 'cast', 'キャスト', '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(4, 'user', 'ユーザー', '2019-01-06 21:23:05', '2019-01-13 18:46:10');

-- --------------------------------------------------------

--
-- テーブルの構造 `owners`
--

DROP TABLE IF EXISTS `owners`;
CREATE TABLE IF NOT EXISTS `owners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `role` varchar(10) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` int(1) NOT NULL,
  `age` varchar(5) NOT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- テーブルのデータのダンプ `owners`
--

INSERT INTO `owners` (`id`, `name`, `role`, `tel`, `email`, `password`, `gender`, `age`, `dir`, `remember_token`, `status`, `created`, `modified`) VALUES
(1, 'ARENA -アリーナ-  オーナー', 'owner', '09012341234', 'okiyoru99@gmail.com', '$2y$10$K0knx7GKbgg8BIYt5sUZ7.1KSX2s2sL/GuNT5H.WalyeWiq09JF0O', 1, '30', '00001', NULL, 1, '2019-08-07 23:50:33', '2019-08-07 23:55:25'),
(2, 'フェリス オーナー', 'owner', '09012341234', 'okiyoru99@gmail.com', '$2y$10$HlUiI8DOHGKWFyOOhgxcTe.jie/qWlDAA3hp/p3I7yecULEarklL.', 1, '30', '00002', NULL, 1, '2019-08-10 15:10:55', '2019-11-17 13:42:55'),
(3, 'club 琉球 オーナー', 'owner', '09012341234', 'okiyoru99@gmail.com', '$2y$10$M68eGdvgIadmxzRw6.1EHu5gEN3fA7DShvvz1KjGTzBbR/giYsd6O', 1, '30', '00003', NULL, 1, '2019-08-10 16:55:43', '2019-09-09 23:26:05'),
(4, 'Shuri -シュリ- オーナー', 'owner', '09012341234', 'okiyoru99@gmail.com', '$2y$10$oHZcgvU1lfAUUPtmM8B8LurxPaeNjgzFTh4RjVtyumn6LxmZycFXW', 1, '30', '00004', NULL, 1, '2019-11-20 23:51:45', '2019-11-20 23:52:59'),
(5, 'club Petit -プティ-', 'owner', '09012341234', 'okiyoru2@gmail.com', '$2y$10$NC5kjf30ZxtvY1NKkrvvteT7bqZTPBZganLpnqNdwrlXKmoDWdEKe', 1, '30', '00005', NULL, 1, '2019-11-21 00:23:05', '2019-11-21 00:23:26'),
(6, 'GIZA PALACE -ギザパレス- オーナー', 'owner', '09012341234', 'okiyoru3@gmail.com', '$2y$10$UGdfqZx38e8tQ4Qafw9ro.AOYBEuEf0ZfRHJh6LwMTcJOIFaRCMpi', 1, '30', '00006', NULL, 1, '2019-11-21 19:46:57', '2019-11-21 19:49:04'),
(7, 'New Club M -エム- オーナー', 'owner', '09012341234', 'okiyoru1@gmail.com', '$2y$10$.KD/sPE6iXYYt6Lns8MnceMSKMA8AyrN.vtu163udkmz1OwTTcMIi', 1, '30', '00007', NULL, 1, '2019-11-21 20:05:56', '2019-11-21 20:09:59'),
(8, '西銘亮子', 'owner', '09084083832', 'undecided-ryono@q.vodafone.ne.jp', '$2y$10$mItFFaOhGAmSmAGBJNHBzeCnJMVukAQCCXMVCsiK9p9YDXhCpsDG.', 0, '34', '00008', '73fafe36112be6367a6f0ec4731078770c5c55a4b98e9515f79c21407131456b', 1, '2019-11-27 22:37:08', '2019-11-27 22:38:17');

-- --------------------------------------------------------

--
-- テーブルの構造 `servece_plans`
--

DROP TABLE IF EXISTS `servece_plans`;
CREATE TABLE IF NOT EXISTS `servece_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `current_plan` varchar(20) NOT NULL,
  `previous_plan` varchar(20) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- テーブルのデータのダンプ `servece_plans`
--

INSERT INTO `servece_plans` (`id`, `owner_id`, `current_plan`, `previous_plan`, `created`, `modified`) VALUES
(1, 1, 'free', 'light', '2019-11-11 18:36:08', '2019-11-11 18:36:08'),
(2, 2, 'free', 'light', '2019-11-12 21:10:36', '2019-11-12 21:10:36'),
(3, 3, 'premium_s', 'light', '2019-11-12 23:20:36', '2019-11-12 23:20:36'),
(4, 4, 'free', 'light', '2019-11-20 23:53:00', '2019-11-20 23:53:00'),
(5, 5, 'free', 'light', '2019-11-21 00:23:26', '2019-11-21 00:23:26'),
(6, 6, 'free', 'light', '2019-11-21 19:49:04', '2019-11-21 19:49:04'),
(7, 7, 'free', 'light', '2019-11-21 20:09:59', '2019-11-21 20:09:59'),
(8, 8, 'premium_s', 'light', '2019-11-27 22:38:19', '2019-11-27 22:38:19');

-- --------------------------------------------------------

--
-- テーブルの構造 `shops`
--

DROP TABLE IF EXISTS `shops`;
CREATE TABLE IF NOT EXISTS `shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `catch` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `staff` varchar(255) DEFAULT NULL,
  `bus_from_time` time DEFAULT NULL,
  `bus_to_time` time DEFAULT NULL,
  `bus_hosoku` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `system` varchar(900) CHARACTER SET utf8mb4 DEFAULT NULL,
  `credit` varchar(255) DEFAULT NULL,
  `pref21` varchar(3) DEFAULT NULL,
  `addr21` varchar(10) DEFAULT NULL,
  `strt21` varchar(30) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- テーブルのデータのダンプ `shops`
--

INSERT INTO `shops` (`id`, `owner_id`, `area`, `genre`, `dir`, `name`, `catch`, `tel`, `staff`, `bus_from_time`, `bus_to_time`, `bus_hosoku`, `system`, `credit`, `pref21`, `addr21`, `strt21`, `created`, `modified`) VALUES
(1, 1, 'urasoe', 'club', '00001', 'ARENA -アリーナ-', '沖縄県浦添市にある総在籍数70名を誇る県内最大級時間制クラブ。\r\nエリアNo.1クラスの実績と自信。接待向けのお店', '09097874621', '', '22:00:00', '04:00:00', '・(金・土・祝日前日)22:00～05:00 ・年中無休', '★SET料金★\r\n・御1人様	70分/10,000円\r\n・2名様以上	70分/5,000円\r\n・御延長	上記同額\r\n★指名★\r\n・本指名	1,000円\r\n・場内指名	1,000円\r\n★ドリンク★\r\n・ビール	1,000円(グラス1杯)\r\n・カクテル	各1,000円(グラス1杯)\r\n・ワイン	各1,000円(グラス1杯)\r\n★ボトル★\r\n・泡盛	3,000円～\r\n・焼酎	3,000円～\r\n・ワイン	8,000円～\r\n・ウィスキー	15,000円\r\n・シャンパン	8,000円～\r\n★VIP Room★\r\n室料	20,000円\r\n★その他★\r\n・団体	～100名まで可', 'VISA,JCB,MasterCard,AmericanExpress', '沖縄県', '浦添市', '城間3-5-1 MSシュタークビル2F', '2019-08-07 23:55:27', '2019-08-08 23:26:45'),
(2, 2, 'urasoe', 'girlsbar', '00001', 'フェリス', 'カラオケもダーツも楽しめちゃうイベント盛りだくさんのガールズバー♡', '09068653218', '', '21:00:00', NULL, '不定休', '★ブロンズコース♡７０分♡	￥２,５００円★\r\n泡盛／かりゆし・瑞穂　酎ハイ／レモン・緑茶・ウーロン　カクテル／ピーチウーロン・カルアミルク・カシスオレンジ・カシスウーロン・カシスソーダ・モスコミュール・ジントニック・カシスミルク　ソフトドリンク/コーラ・オレンジ・ウーロン茶・緑茶\r\n★シルバーコース♡７０分♡	￥３,０００円★\r\nブロンズメニュー ＋ 泡盛／菊ブラ・久米仙・残波・残黒　ビール　焼酎／鏡月　カクテル／サングリア\r\n★ゴールドコース♡７０分♡	￥３,５００円★\r\nブロンズメニュー・シルバーメニュー ＋ 泡盛／ＶＩＰゴールド・北谷長老・琉球王朝　焼酎／二階堂・吉四六\r\n★ＳｔａｆＤｒｉｎｋ★\r\nＡＬＬ １,０００円\r\n★カラオケ１曲★\r\n２００円\r\n★歌い放題（時間内）★\r\n１,０００円\r\n★女性グールプのみ★\r\n１,０００円 ＯＦＦ\r\n★ダーツ投げ放題★\r\n５００円 \r\n★時間無制限 飲み放題★\r\n５,０００円 \r\n★単品メニュー５,０００円～有り★', 'MasterCard,VISA', '沖縄県', '浦添市', '経塚518 テナントビルてぃーだ 2F', '2019-08-10 15:16:07', '2019-08-10 15:52:53'),
(3, 3, 'naha', 'cabacula', '00001', 'Club 琉球', '那覇市松山にGRANDOPEN!!\r\nKING of RESORT!! CLUB 琉球', '0989757973', '', '21:00:00', NULL, '月曜定休日', '★1time 60min★\r\n・保証	５，０００円\r\n・マンツーマン	８，０００円\r\n・ＶＩＰ	１，０００円\r\n・指名料	１，０００円\r\n・場内指名料	１，０００円\r\n・ＳＣ	１５％　（税込）\r\n・PRIVATE ROOM　１５，０００円（ＳＣ　２５％　税込）\r\n・SECRET ROOM　２０，０００円（ＳＣ　２５％　税込）\r\n★Free Drink★\r\n・MAIN Floor	ビール、泡盛、焼酎、ソフトドリンク各種\r\n・VIP Floor	ビール、ハイボール、泡盛(古酒)、焼酎、ソフトドリンク各種', 'MasterCard,VISA,JCB,AmericanExpress,Diners', '沖縄県', '那覇市', '松山2-9-17 カーニバルビル4F 5F', '2019-08-10 16:58:22', '2019-10-05 12:08:16'),
(4, 4, 'naha', 'cabacula', '00002', 'Shuri -シュリ-', NULL, '0988634729', '', '21:00:00', NULL, '定休日　年中無休', '・2名様以上\r\n　21:00～LAST（60min）￥5000\r\n・1名様\r\n　21:00～LAST（60min）￥10000\r\n\r\n・税金・サービス料	20％\r\n・目安予算 	￥5,100～￥8,000\r\n・VIP料金	￥20,000\r\n・指名料	￥1000\r\n・場内指名料	￥1000\r\n・延長方法	確認有り\r\n・予約	可\r\n・お気軽にお電話下さい。\r\n・服装	スーツ\r\n・カラオケ	有り\r\n・駐車場	無し', 'VISA,JCB,Diners,MasterCard', '沖縄県', '那覇市', '松山1-4-8 フラワードリームビル2F', '2019-11-20 23:54:43', '2019-11-21 00:07:56'),
(5, 5, 'naha', 'cabacula', '00003', 'club Petit -プティ-', '安心!信用!を第1にお客様が心から楽しめる空間をご用意させて頂いています!\r\n個性豊かな女の子ばかりなので必ずご希望にあった女の子が見つかるはず!^_^\r\n松山で飲む際は是非CLUBプティへ!', '0989880690', '', '21:00:00', NULL, '', '・お二人様以上\r\n　21時〜21時59分 保証 5000円\r\n　22時〜ラスト 保証 6000円\r\n　Single Charge マンツー 3000円\r\n・お一人様\r\n　1セット60分 8000円\r\n　TAX15%\r\n・団体	～20名まで可\r\n・朝キャバ	無し\r\n・カラオケ	有り', '', '沖縄県', '那覇市', '松山2-16-16 K1ビル 5階', '2019-11-21 00:25:36', '2019-11-21 00:31:23'),
(6, 6, 'naha', 'snack', '00001', 'GIZA PALACE -ギザパレス-', 'ステージのあるお店で楽しい時間を過ごしませんか❔', '0988661159', '', '20:00:00', NULL, '休日 年中無休', '★お1人様(60分)\r\n　10000円\r\n★お2人様以上(60分)\r\n　5000円\r\n★指名料\r\n　1000円\r\n★パーティープランのご予約承ります\r\n　[2次会・親睦会・バースデー等]\r\n　(2時間半飲み放題)カラオケ有り\r\n　男性 3,000円\r\n　女性 2,500円\r\n\r\n■カクテル\r\n■ハイボール\r\n■泡盛\r\n■ソフトドリンク\r\n\r\n★VIPルーム・個室もございます!\r\n\r\n　お問い合わせは\r\n　090-3792-8415[仲里]\r\n　までお気軽にご連絡下さい', '', '沖縄県', '那覇市', 'おもろまち4-8-9 フェイスビル4F', '2019-11-21 19:50:48', '2019-11-21 19:57:34'),
(7, 7, 'okinawashi', 'cabacula', '00001', 'New Club M -エム-', NULL, '08064901426', '', '22:00:00', NULL, '定休日	日曜', 'お一人様 8,000円×60分\r\nお二人様以上1人4,000円×60分\r\n･指名料 1,000円\r\n･ｶﾗｵｹ 1曲200円\r\n･飲み放題ｼｽﾃﾑ\r\n\r\n◆ﾌﾘｰﾄﾞﾘﾝｸ\r\n･ﾊｳｽﾎﾞﾄﾙ(泡盛)\r\n･ｳｰﾛﾝ茶\r\n･緑茶\r\n･ﾌﾞﾗｯｸｺｰﾋｰ\r\n･ｽﾄﾚｰﾄﾃｨ\r\n\r\n★ｵﾌﾟｼｮﾝ★\r\n◆ｶｸﾃﾙ･ﾋﾞｰﾙ\r\n･ﾋﾞｰﾙ1,000円\r\n･ﾉﾝｱﾙｺｰﾙ･ﾋﾞｰﾙ1,000円\r\n･ｸﾞﾗｽﾜｲﾝ(赤)1,000円\r\n･ｸﾞﾗｽﾜｲﾝ(白)1,000円\r\n･ｼｬﾝﾃﾞｨｶﾞﾌ1,000円\r\n･ﾚｯﾄﾞｱｲ1,000円\r\n･ｶｼｽｳｰﾛﾝ1,000円\r\n･ｶﾙｱﾐﾙｸ1,000円\r\n･ﾌｧｼﾞｰﾈｰﾌﾞﾙ1,000円\r\n･ﾋﾟｰﾁｳｰﾛﾝ1,000円\r\n･ﾊｲﾎﾞｰﾙ1,000円\r\n･ﾃｷｰﾗﾛｰｽﾞ1,000円\r\n\r\n◆ﾎﾞﾄﾙ\r\n･菊の露 親方の酒2,000円\r\n･菊の露 VIPｺﾞｰﾙﾄﾞ5,000円\r\n･黒霧島2,000円\r\n･赤霧島5,000円\r\n･ｼｯｸｽｴｲﾄﾅｲﾝ10,000円\r\n･山崎15,000円\r\n･ﾍﾈｼｰx.o 30,000円\r\n\r\n◆ｼｬﾝﾊﾟﾝ\r\n･ｶﾌｪﾄﾞ･ﾊﾟﾘ8,000円\r\n･ﾓｴ･ｼｬﾝﾄﾞﾝ25,000円\r\n･ﾓｴ･ｼｬﾝﾄﾞﾝﾛｾﾞ35,000円\r\n･ﾍﾞﾙ･ｴﾎﾟｯｸ60,000円\r\n･ﾍﾞﾙ･ｴﾎﾟｯｸﾛｾﾞ120,000円', 'MasterCard,VISA,JCB,AmericanExpress,Diners', '沖縄県', '沖縄市', '上地1-13-13 ヤング5ビル5F', '2019-11-21 20:11:20', '2019-11-21 20:30:35'),
(8, 8, 'urasoe', 'snack', '00001', 'Lounge Dear...  ディアー', NULL, '09084083832', '30代〜40代の元気な女性', '21:00:00', NULL, '今のところ定休日無し！', 'ボトル制', '', '沖縄県', '浦添市', '屋富祖4-5-13 コスモビル301', '2019-11-27 22:40:50', '2019-11-27 22:52:39');

-- --------------------------------------------------------

--
-- テーブルの構造 `shop_infos`
--

DROP TABLE IF EXISTS `shop_infos`;
CREATE TABLE IF NOT EXISTS `shop_infos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(600) NOT NULL,
  `dir` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_key` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `shop_info_likes`
--

DROP TABLE IF EXISTS `shop_info_likes`;
CREATE TABLE IF NOT EXISTS `shop_info_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_info_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_info_key` (`shop_info_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `snss`
--

DROP TABLE IF EXISTS `snss`;
CREATE TABLE IF NOT EXISTS `snss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT NULL,
  `cast_id` int(11) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `line` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`),
  KEY `cast_id` (`cast_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- テーブルのデータのダンプ `snss`
--

INSERT INTO `snss` (`id`, `shop_id`, `cast_id`, `facebook`, `twitter`, `instagram`, `line`, `created`, `modified`) VALUES
(1, 7, NULL, '', '', 't.a.k.u.m.a_', '', '2019-11-22 18:09:55', '2019-11-22 18:09:55'),
(2, 8, NULL, '', '', 'dear_inst', '', '2019-11-27 22:43:59', '2019-11-27 22:43:59'),
(3, 3, NULL, NULL, NULL, 'clubryukyu', NULL, '2019-11-29 00:00:00', '2019-11-29 00:00:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(191) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `updates`
--

DROP TABLE IF EXISTS `updates`;
CREATE TABLE IF NOT EXISTS `updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT NULL,
  `cast_id` int(11) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`),
  KEY `cast_id` (`cast_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- テーブルのデータのダンプ `updates`
--

INSERT INTO `updates` (`id`, `shop_id`, `cast_id`, `type`, `content`, `created`, `modified`) VALUES
(1, 4, NULL, 'shop_top_image', 'トップ画像を更新しました。', '2019-11-20 23:56:09', '2019-11-20 23:56:09'),
(2, 4, NULL, 'shop_gallery', '店内ギャラリーを更新しました。', '2019-11-20 23:58:59', '2019-11-20 23:58:59'),
(3, 4, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 00:02:42', '2019-11-21 00:02:42'),
(4, 4, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 00:04:11', '2019-11-21 00:04:11'),
(5, 4, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 00:07:11', '2019-11-21 00:07:11'),
(6, 4, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 00:07:56', '2019-11-21 00:07:56'),
(7, 5, NULL, 'shop_top_image', 'トップ画像を更新しました。', '2019-11-21 00:26:08', '2019-11-21 00:26:08'),
(8, 5, NULL, 'shop_gallery', '店内ギャラリーを更新しました。', '2019-11-21 00:26:54', '2019-11-21 00:26:54'),
(9, 5, NULL, 'coupon', 'クーポン情報を更新しました。', '2019-11-21 00:28:36', '2019-11-21 00:28:36'),
(10, 5, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 00:30:51', '2019-11-21 00:30:51'),
(11, 5, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 00:31:23', '2019-11-21 00:31:23'),
(12, 6, NULL, 'shop_top_image', 'トップ画像を更新しました。', '2019-11-21 19:51:50', '2019-11-21 19:51:50'),
(13, 6, NULL, 'coupon', 'クーポン情報を更新しました。', '2019-11-21 19:53:04', '2019-11-21 19:53:04'),
(14, 6, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 19:57:34', '2019-11-21 19:57:34'),
(15, 6, NULL, 'shop_gallery', '店内ギャラリーを更新しました。', '2019-11-21 19:58:18', '2019-11-21 19:58:18'),
(16, 7, NULL, 'shop_top_image', 'トップ画像を更新しました。', '2019-11-21 20:11:51', '2019-11-21 20:11:51'),
(17, 7, NULL, 'shop_gallery', '店内ギャラリーを更新しました。', '2019-11-21 20:12:21', '2019-11-21 20:12:21'),
(18, 7, NULL, 'coupon', 'クーポン情報を更新しました。', '2019-11-21 20:13:52', '2019-11-21 20:13:52'),
(19, 7, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 20:16:22', '2019-11-21 20:16:22'),
(20, 7, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 20:23:26', '2019-11-21 20:23:26'),
(21, 7, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 20:24:13', '2019-11-21 20:24:13'),
(22, 7, NULL, 'system', '店舗情報を更新しました。', '2019-11-21 20:30:35', '2019-11-21 20:30:35'),
(23, 7, 16, 'profile', 'リエさんがプロフィールアイコンを更新しました。', '2019-11-21 20:50:04', '2019-11-21 20:50:04'),
(24, 7, 16, 'profile', 'リエさんがプロフィールを更新しました。', '2019-11-21 20:50:16', '2019-11-21 20:50:16'),
(25, NULL, NULL, 'diary', '新しいキャストを追加しました。', '2019-11-21 22:22:55', '2019-11-21 22:22:55'),
(26, NULL, NULL, 'diary', '新しいキャストを追加しました。', '2019-11-21 22:40:19', '2019-11-21 22:40:19'),
(27, 7, 18, 'profile', 'リエさんがプロフィールアイコンを更新しました。', '2019-11-21 22:53:19', '2019-11-21 22:53:19'),
(28, 7, 18, 'profile', 'リエさんがプロフィールを更新しました。', '2019-11-21 22:55:01', '2019-11-21 22:55:01'),
(29, 7, 18, 'profile', 'リエさんがプロフィールを更新しました。', '2019-11-21 23:03:20', '2019-11-21 23:03:20'),
(30, NULL, NULL, 'diary', '新しいキャストを追加しました。', '2019-11-21 23:06:20', '2019-11-21 23:06:20'),
(31, 7, 19, 'profile', 'リンさんがプロフィールアイコンを更新しました。', '2019-11-21 23:07:07', '2019-11-21 23:07:07'),
(32, 7, 19, 'profile', 'リンさんがプロフィールを更新しました。', '2019-11-21 23:07:25', '2019-11-21 23:07:25'),
(33, 7, 19, 'cast_gallery', 'リンさんがギャラリーを追加しました。', '2019-11-21 23:09:49', '2019-11-21 23:09:49'),
(34, 7, NULL, 'event', '店舗からのお知らせを追加しました。', '2019-11-22 21:20:07', '2019-11-22 21:20:07'),
(35, 8, NULL, 'shop_top_image', 'トップ画像を更新しました。', '2019-11-27 22:42:10', '2019-11-27 22:42:10'),
(36, 8, NULL, 'system', '店舗情報を更新しました。', '2019-11-27 22:52:39', '2019-11-27 22:52:39'),
(37, 3, 15, 'diary', 'にーなさんが日記を追加しました。', '2019-12-07 02:37:47', '2019-12-07 02:37:47'),
(38, 3, 15, 'cast_gallery', 'にーなさんがギャラリーを追加しました。', '2019-12-07 02:40:22', '2019-12-07 02:40:22');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `work_schedules`
--

DROP TABLE IF EXISTS `work_schedules`;
CREATE TABLE IF NOT EXISTS `work_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL,
  `cast_ids` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
