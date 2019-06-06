-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2019 年 6 月 01 日 21:38
-- サーバのバージョン： 5.6.42
-- PHP Version: 7.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `okiyoru_db`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `admin_accounts`
--

DROP TABLE IF EXISTS `admin_accounts`;
CREATE TABLE `admin_accounts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `body` text,
  `published` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `title`, `slug`, `body`, `published`, `created`, `modified`) VALUES
(1, 1, 'First Post', 'first-post', 'This is the first post.This is the first post.This is the first post.This is the first post.This is the first post.This is the first post.This is the first post.', 1, '2018-12-26 20:50:09', '2018-12-31 12:29:33'),
(24, 1, 'ffaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ffaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 0, '2018-12-30 21:54:10', '2018-12-31 12:10:24'),
(25, 1, 'title1title1title1title1', 'title1title1title1title1', 'body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1body1', 0, '2018-12-30 22:32:45', '2018-12-30 22:32:45'),
(26, 1, 'title2title2title2title2title2', 'title2title2title2title2title2', 'body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2body2', 0, '2018-12-30 22:33:07', '2018-12-31 09:07:13'),
(27, 1, 'tomori1tomori1tomori1tomori1tomori1', 'tomori1tomori1tomori1tomori1tomori1', 'tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1tomori1', 0, '2018-12-31 12:35:53', '2018-12-31 12:35:53'),
(29, 2, 'tomori1の投稿', 'tomori1no-tou-gao', 'tomori1の投稿tomori1の投稿tomori1の投稿tomori1の投稿tomori1の投稿tomori1の投稿tomori1の投稿tomori1の投稿', 0, '2018-12-31 16:37:42', '2018-12-31 16:37:42');

-- --------------------------------------------------------

--
-- テーブルの構造 `articles_tags`
--

DROP TABLE IF EXISTS `articles_tags`;
CREATE TABLE `articles_tags` (
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `articles_tags`
--

INSERT INTO `articles_tags` (`article_id`, `tag_id`) VALUES
(26, 1),
(1, 2),
(25, 2),
(24, 5),
(1, 7);

-- --------------------------------------------------------

--
-- テーブルの構造 `casts`
--

DROP TABLE IF EXISTS `casts`;
CREATE TABLE `casts` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `role` varchar(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `three_size` varchar(10) DEFAULT NULL,
  `blood_type` varchar(20) DEFAULT NULL,
  `constellation` varchar(20) DEFAULT NULL,
  `age` varchar(5) DEFAULT NULL,
  `message` varchar(50) DEFAULT NULL,
  `holiday` varchar(50) DEFAULT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL,
  `image5` varchar(255) DEFAULT NULL,
  `image6` varchar(255) DEFAULT NULL,
  `image7` varchar(255) DEFAULT NULL,
  `image8` varchar(255) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `delete_flag` int(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `casts`
--

INSERT INTO `casts` (`id`, `shop_id`, `role`, `name`, `nickname`, `email`, `password`, `birthday`, `three_size`, `blood_type`, `constellation`, `age`, `message`, `holiday`, `image1`, `image2`, `image3`, `image4`, `image5`, `image6`, `image7`, `image8`, `dir`, `remember_token`, `status`, `delete_flag`, `created`, `modified`) VALUES
(1, 38, 'cast', '山田　敏子', 'ＨＡＮＡ', 'okiyoru3@gmail.com', '$2y$10$3Y5FfELSqG8hCwipy1VWNOvPn0A07QPRl0B7QVbgqtplAjRRFBfDC', '1966-04-27 00:00:00', NULL, 'blood_type4', 'constellation3', '36', 'ｄｄtrrtera\r\nｒffdffdsswsdsdsdsdsdsdds\r\n\r\nfdtete', NULL, 'd850c55c7674f7c5b0ca5ee9c4d9bc798010c417.jpg', '09c81edb1ee4c46cb2aeffd4a0c7fca2762f9b53.jpg', '1fa3eb548fdd4a092d84c992964663aae1064f84.jpg', '6ba1b8364eefaa8cdeba8399382926a6e8c10fd4.jpg', '', '', '', '', '00002', NULL, 1, NULL, '2019-03-23 16:51:25', '2019-05-06 14:50:40'),
(13, 38, 'cast', '友利拓真', 'wwww', 't.takuma830@gmail.com', '$2y$10$4u6PBUIBjyWsPKhDndEa4eUm30Ym9JEls.OVRyCSeqO/YuuwWOgsC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00003', 'f52389fbef05bdeff3b3f0f1af80f56be8afc552f99348ca49702b9e3315b427', 1, NULL, '2019-04-03 19:47:43', '2019-05-14 19:43:38');

-- --------------------------------------------------------

--
-- テーブルの構造 `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `from_day` datetime NOT NULL,
  `to_day` datetime NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `coupons`
--

INSERT INTO `coupons` (`id`, `shop_id`, `status`, `from_day`, `to_day`, `title`, `content`, `created`, `modified`) VALUES
(11, 2, 0, '2019-02-05 00:00:00', '2019-02-13 00:00:00', 'タイトル１', 'タイトル１タイトル１', '2019-02-12 23:04:16', '2019-02-12 23:04:16'),
(12, 57, 0, '2019-02-11 00:00:00', '2019-02-13 00:00:00', 'existsInexistsIn', 'existsInexistsInexistsIn', '2019-02-12 23:45:12', '2019-02-12 23:45:12'),
(13, 57, 0, '2019-02-12 00:00:00', '2019-02-19 00:00:00', 'ｆｆｆｆ', 'ｆｄｆｄｆｄｄ', '2019-02-13 00:36:09', '2019-02-13 00:36:09'),
(14, 57, 0, '2019-02-04 00:00:00', '2019-02-28 00:00:00', 'ｆｆｄｄｆ', 'ｆｄｆ', '2019-02-13 18:09:26', '2019-02-13 18:09:26'),
(42, 38, 1, '2019-02-01 00:00:00', '2019-02-02 00:00:00', 'クーポンタイトル１クーポンタイトル２クーポンタイトル２クーポンタイトル２', 'クーポン内容１クーポン内容１\r\nクーポン内容１クーポン内容１\r\nクーポン内容１クーポン内容１クーポン内容１クーポン内容１\r\nクーポン内容１クーポン内容１', '2019-02-23 00:16:08', '2019-03-21 17:50:21'),
(44, 38, 1, '2019-02-22 00:00:00', '2019-03-18 00:00:00', 'クーポンタイトル２クーポンタイトル２クーポンタイトル２', '	\r\nクーポン内容２クーポン内容２\r\nクーポン内容２クーポン内容２\r\nクーポン内容２クーポン内容２', '2019-03-03 01:12:48', '2019-03-17 15:14:23');

-- --------------------------------------------------------

--
-- テーブルの構造 `developers`
--

DROP TABLE IF EXISTS `developers`;
CREATE TABLE `developers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `developers`
--

INSERT INTO `developers` (`id`, `email`, `password`, `created`, `modified`) VALUES
(2, 'dev3@gmail.com', '$2y$10$nL/kI91hjg11GHNPafsH0OVn9rq5TwMHYmmSEMSaIoGDu.Ua0sCp2', '2019-01-06 21:23:05', '2019-01-13 18:46:10'),
(3, 'dev4@gmail.com', '$2y$10$XcLOtHKPdLvHofbqqbpX1u7/Wiosc5NaTjmHfRTIwEcMQ8Vunw896', '2019-01-11 21:19:43', '2019-01-13 18:47:16');

-- --------------------------------------------------------

--
-- テーブルの構造 `diarys`
--

DROP TABLE IF EXISTS `diarys`;
CREATE TABLE `diarys` (
  `id` int(11) NOT NULL,
  `cast_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(255) NOT NULL,
  `image1` varchar(100) DEFAULT NULL,
  `image2` varchar(100) DEFAULT NULL,
  `image3` varchar(100) DEFAULT NULL,
  `image4` varchar(100) DEFAULT NULL,
  `image5` varchar(100) DEFAULT NULL,
  `image6` varchar(100) DEFAULT NULL,
  `image7` varchar(100) DEFAULT NULL,
  `image8` varchar(100) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `diarys`
--

INSERT INTO `diarys` (`id`, `cast_id`, `title`, `content`, `image1`, `image2`, `image3`, `image4`, `image5`, `image6`, `image7`, `image8`, `dir`, `created`, `modified`) VALUES
(105, 1, 'tset', 'testdadyd', '09c81edb1ee4c46cb2aeffd4a0c7fca2762f9b53.jpg', '11d8110b1aad2b537bc79006557f7ae8d044f923.png', '', '', '', '', '', NULL, '2019/201905/20190503/20190503_024338', '2019-05-03 02:43:38', '2019-05-03 13:53:21'),
(107, 1, 'fd', 'ftb', '9db0f572df6d410790a594802e18acc229ee7208.jpg', '969ee3a90e0eaa4df14ddc5205bd4f7c02c4f827.jpg', '2c12dfae9753435e1e1d5a6b2d4ad015bfcc0662.jpg', '', '', '', NULL, NULL, '2019/201905/20190503/20190503_130732', '2019-05-03 13:07:33', '2019-05-03 15:30:24'),
(108, 1, 'fdffd', 'fdff', '83b68eb74cffa94e0479e4727d70702f3014dc11.jpg', '969ee3a90e0eaa4df14ddc5205bd4f7c02c4f827.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_153612', '2019-05-03 15:36:30', '2019-05-03 15:36:30'),
(109, 1, 'fdf', 'fdfdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_154100', '2019-05-03 15:41:03', '2019-05-03 15:41:03'),
(110, 1, 'ssd', 'dssdsd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_154123', '2019-05-03 15:41:29', '2019-05-03 15:41:29'),
(111, 1, 'fdfdfd', 'fdffdfdf', '969ee3a90e0eaa4df14ddc5205bd4f7c02c4f827.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_154847', '2019-05-03 15:48:48', '2019-05-03 15:51:01'),
(112, 1, 'fdf', 'fdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_154904', '2019-05-03 15:49:04', '2019-05-03 15:49:04'),
(113, 1, 'dssd', 'dsdsd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_154913', '2019-05-03 15:49:13', '2019-05-03 15:49:13'),
(114, 1, 'fdfdfdf', 'fdfdfd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_155009', '2019-05-03 15:50:09', '2019-05-03 15:50:09'),
(115, 1, 'fdf', 'fdfd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_155019', '2019-05-03 15:50:20', '2019-05-03 15:50:20'),
(116, 1, 'sduk', 'sddfdffdfdfdfdfj\r\n\r\n\r\ntytytyy\r\n\r\n', '83b68eb74cffa94e0479e4727d70702f3014dc11.jpg', '2c12dfae9753435e1e1d5a6b2d4ad015bfcc0662.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_155040', '2019-05-03 15:50:40', '2019-05-03 15:52:50'),
(117, 1, 'fdfff', 'fdfdfdfdfdfd', '9db0f572df6d410790a594802e18acc229ee7208.jpg', 'd850c55c7674f7c5b0ca5ee9c4d9bc798010c417.jpg', '09c81edb1ee4c46cb2aeffd4a0c7fca2762f9b53.jpg', '1fa3eb548fdd4a092d84c992964663aae1064f84.jpg', '6ba1b8364eefaa8cdeba8399382926a6e8c10fd4.jpg', '112786fba33c77ce659f05ac31f9842546970058.jpg', '58d0733b578610160243d78f3e9ab37b9ba1727a.jpg', '83b68eb74cffa94e0479e4727d70702f3014dc11.jpg', '2019/201905/20190503/20190503_155203', '2019-05-03 15:52:03', '2019-05-03 15:52:03'),
(118, 1, 'ｆｄｆｄｆｄ', 'ｆｄｄｆyd', '969ee3a90e0eaa4df14ddc5205bd4f7c02c4f827.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_161956', '2019-05-03 16:19:56', '2019-05-03 16:19:56'),
(119, 1, 'ｆｄｆｄｆｄ', 'ｆｄｄｆydｈｇｈ', '969ee3a90e0eaa4df14ddc5205bd4f7c02c4f827.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_162048', '2019-05-03 16:20:49', '2019-05-03 16:20:49'),
(120, 1, 'ｆｄｆｄｆｄ', 'ｆｄｄｆydｈｇｈchromeｓtetstettet\r\ntettetette', '969ee3a90e0eaa4df14ddc5205bd4f7c02c4f827.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190503/20190503_162124', '2019-05-03 16:21:24', '2019-05-06 13:39:27'),
(121, 2, 'test', 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 1, 'tes2', 'dtest', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 1, 'fffffｄｆ', 'fdfｆｆfdfdfDfESft', '11d8110b1aad2b537bc79006557f7ae8d044f923.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201905/20190511/20190511_234836', '2019-05-11 23:48:36', '2019-05-11 23:50:56'),
(125, 1, '??????????', '??????????????????????????????❗️❗️❗️❗️❗️????????????????????', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019/201906/20190601/20190601_213124', '2019-06-01 21:31:24', '2019-06-01 21:31:24');

-- --------------------------------------------------------

--
-- テーブルの構造 `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_type_id` int(11) DEFAULT NULL,
  `cast_id` int(11) NOT NULL,
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
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `events`
--

INSERT INTO `events` (`id`, `event_type_id`, `cast_id`, `title`, `details`, `start`, `end`, `time_start`, `time_end`, `all_day`, `status`, `active`, `created`, `modified`) VALUES
(268, NULL, 1, '仕事', NULL, '2019-04-06 00:30:00', '2019-04-06 03:00:00', '00:30', '03:00', '1', NULL, 1, '2019-04-27 00:32:11', '2019-04-27 00:32:11'),
(269, NULL, 1, '休み', NULL, '2019-04-10 00:00:00', '2019-04-10 04:00:00', '00:00', '04:00', '1', NULL, 1, '2019-04-27 00:32:41', '2019-04-27 00:33:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `event_types`
--

DROP TABLE IF EXISTS `event_types`;
CREATE TABLE `event_types` (
  `id` int(11) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `industry` varchar(30) DEFAULT NULL,
  `job_type` varchar(30) DEFAULT NULL,
  `work_from_time` time DEFAULT NULL,
  `work_to_time` time DEFAULT NULL,
  `work_time_hosoku` varchar(50) DEFAULT NULL,
  `from_age` varchar(2) DEFAULT NULL,
  `to_age` varchar(2) DEFAULT NULL,
  `qualification_hosoku` varchar(50) DEFAULT NULL,
  `holiday` varchar(50) DEFAULT NULL,
  `holiday_hosoku` varchar(50) DEFAULT NULL,
  `treatment` varchar(255) DEFAULT NULL,
  `pr` varchar(100) DEFAULT NULL,
  `tel1` varchar(15) DEFAULT NULL,
  `tel2` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `lineid` varchar(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `jobs`
--

INSERT INTO `jobs` (`id`, `shop_id`, `industry`, `job_type`, `work_from_time`, `work_to_time`, `work_time_hosoku`, `from_age`, `to_age`, `qualification_hosoku`, `holiday`, `holiday_hosoku`, `treatment`, `pr`, `tel1`, `tel2`, `email`, `lineid`, `created`, `modified`) VALUES
(1, 38, '時間制(キャバクラ)', 'レディスタッフ・キャスト', '12:00:00', '12:00:00', 'work_time_hosoku', '21', '24', 'qualification_hosoku', '水,土,日', 'holiday_hosoku', '経験者優遇,ドレス・制服貸与あり,友達紹介料あり,モノレール駅からすぐ,送迎あり,個人ロッカーあり', 'PR文PR文PR文PR文PR文PR文PR文\r\nPR文PR文PR文PR文PR文PR文PR文PR文PR文PR文PR文PR文PR文PR文\r\nPR文PR文PR文PR文PR文PR文PR文', '09037968838', '08037968838', 't.takuma830@gmail.com', 'testLINEID', '2019-03-20 00:06:19', '2019-03-26 23:48:18'),
(2, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2019-03-20 00:06:19', '2019-03-20 00:06:19');

-- --------------------------------------------------------

--
-- テーブルの構造 `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `diary_id` int(11) NOT NULL,
  `cast_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `likes`
--

INSERT INTO `likes` (`id`, `diary_id`, `cast_id`, `user_id`, `created`, `modified`) VALUES
(0, 105, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1, 110, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 111, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 999, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 105, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 111, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 111, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 107, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 121, 2, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `master_codes`
--

DROP TABLE IF EXISTS `master_codes`;
CREATE TABLE `master_codes` (
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
CREATE TABLE `master_role` (
  `id` int(11) NOT NULL,
  `role` varchar(64) NOT NULL COMMENT 'ロール名',
  `role_name` varchar(64) NOT NULL COMMENT 'ロール名',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ロール';

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
CREATE TABLE `owners` (
  `id` int(11) NOT NULL,
  `role` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `owners`
--

INSERT INTO `owners` (`id`, `role`, `email`, `password`, `area`, `genre`, `dir`, `remember_token`, `status`, `created`, `modified`) VALUES
(1, 'owner', 't.takuma830@gmail.com', '$2y$10$IvHwLrvdmHfFQgw88nlOD.DD1jnVFy52vG.auT04dLts3W1c2xcxu', 'naha', 'cabacula', NULL, NULL, 0, '2019-03-23 10:38:14', '2019-03-23 10:38:14'),
(57, 'owner', 'okiyoru1@gmail.com', '$2y$10$vBxr/LLpjQ07C1LpN2bvWuJ8LNVy2vtvpx1qZgd1VousDjoN83qVq', 'naha', 'snack', '00005', '862070a51ad92647e24097ce208688e0cc92082438b4d0bd7c9ce2526d7b0830', 1, '2019-02-12 21:42:22', '2019-03-15 18:15:38');

-- --------------------------------------------------------

--
-- テーブルの構造 `shops`
--

DROP TABLE IF EXISTS `shops`;
CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `top_image` varchar(100) DEFAULT NULL,
  `catch` varchar(100) DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `staff` varchar(255) DEFAULT NULL,
  `bus_from_time` time DEFAULT NULL,
  `bus_to_time` time DEFAULT NULL,
  `bus_hosoku` varchar(255) DEFAULT NULL,
  `system` varchar(255) DEFAULT NULL,
  `credit` varchar(255) DEFAULT NULL,
  `cast` varchar(255) DEFAULT NULL,
  `pref21` varchar(3) DEFAULT NULL,
  `addr21` varchar(10) DEFAULT NULL,
  `strt21` varchar(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `shops`
--

INSERT INTO `shops` (`id`, `owner_id`, `area`, `genre`, `dir`, `name`, `top_image`, `catch`, `tel`, `staff`, `bus_from_time`, `bus_to_time`, `bus_hosoku`, `system`, `credit`, `cast`, `pref21`, `addr21`, `strt21`, `created`, `modified`) VALUES
(2, 2, NULL, NULL, NULL, NULL, NULL, 'fdffdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-12 21:42:56', '2019-02-16 13:49:27'),
(38, 57, 'naha', 'snack', '00005', 'OKIYORUGO', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '那覇のキャバクラをお探しなら〇〇\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(39, 58, 'naha', 'cabacula', '00005', '那覇店舗名１', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '那覇のキャバクラをお探しなら那覇店舗名１\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(40, 59, 'naha', 'cabacula', '00005', '那覇店舗名２', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '那覇のキャバクラをお探しなら那覇店舗名２\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(41, 60, 'naha', 'cabacula', '00005', '那覇店舗名３', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '那覇のキャバクラをお探しなら那覇店舗名３\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(42, 61, 'naha', 'cabacula', '00005', '那覇店舗名４', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '那覇のキャバクラをお探しなら那覇店舗名４\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(43, 62, 'naha', 'girlsbar', '00005', '那覇店舗名５', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '那覇のガールズバーをお探しなら那覇店舗名５\r\n時間制・飲み放題で安心のガールズバーです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(44, 63, 'naha', 'snack', '00005', '那覇店舗名６', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '那覇のスナックをお探しなら那覇店舗名６\r\n時間制・飲み放題で安心のスナックです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(45, 64, 'miyakojima', 'cabacula', '00005', '宮古島店舗名１', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '宮古島のキャバクラをお探しなら宮古島店舗名１\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(46, 65, 'miyakojima', 'cabacula', '00005', '宮古島店舗名２', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '宮古島のキャバクラをお探しなら宮古島店舗名２\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(47, 66, 'miyakojima', 'cabacula', '00005', '宮古島店舗名３', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '宮古島のキャバクラをお探しなら宮古島店舗名３\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(48, 67, 'miyakojima', 'cabacula', '00005', '宮古島店舗名４', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '宮古島のキャバクラをお探しなら宮古島店舗名４\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(49, 68, 'miyakojima', 'girlsbar', '00005', '宮古島店舗名５', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '宮古島のガールズバーをお探しなら宮古島店舗名５\r\n時間制・飲み放題で安心のガールズバーです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(50, 69, 'miyakojima', 'snack', '00005', '宮古島店舗名６', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '宮古島のスナックをお探しなら宮古島店舗名６\r\n時間制・飲み放題で安心のスナックです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32');

-- --------------------------------------------------------

--
-- テーブルの構造 `shop_infos`
--

DROP TABLE IF EXISTS `shop_infos`;
CREATE TABLE `shop_infos` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `tags`
--

INSERT INTO `tags` (`id`, `title`, `created`, `modified`) VALUES
(1, 'testtag', '2018-12-30 21:53:07', '2018-12-30 21:53:07'),
(2, 'tag1', '2018-12-30 22:12:12', '2018-12-30 22:12:12'),
(3, 'tag2', '2018-12-30 22:12:30', '2018-12-30 22:12:30'),
(4, 'tag3', '2018-12-30 22:12:41', '2018-12-30 22:12:41'),
(5, 'tag4', '2018-12-31 10:17:24', '2018-12-31 10:17:24'),
(6, 'tag5', '2018-12-31 10:25:15', '2018-12-31 10:25:15'),
(7, 'tag6', '2018-12-31 10:33:44', '2018-12-31 10:33:44');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created`, `modified`) VALUES
(1, 'user0@gmail.com', '$2y$10$3C/Y5drnCFszyr3VIC1rYOqtNP4eq7bLazPB2NuBPwD20wqmhKEe6', '2018-12-26 19:56:45', '2019-01-13 18:50:32'),
(2, 'user1@gmail.com', '$2y$10$pqGXOHd/SsqZpxiWIavel.xXZMp6OB3dJ39XN3Xs/zczVX/emgOma', '2018-12-31 12:35:03', '2019-01-13 19:21:50'),
(4, 'user2@gmail.com', '$2y$10$XX2efgIYRB1FyO36LtUo8eAWOZgENhH5S3acDf1mnaMMaU5xzvPd2', '2019-01-14 00:27:29', '2019-01-14 00:27:29'),
(5, 'user3@gmail.com', '$2y$10$7VwUs..iiR49F/3mQ/1Af.rwcvLKmpPmicWNPt0IKffw5kQMNJROG', '2019-01-22 18:21:26', '2019-01-22 18:21:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `articles_tags`
--
ALTER TABLE `articles_tags`
  ADD PRIMARY KEY (`article_id`,`tag_id`),
  ADD KEY `tag_key` (`tag_id`);

--
-- Indexes for table `casts`
--
ALTER TABLE `casts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_key` (`shop_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_key` (`shop_id`);

--
-- Indexes for table `developers`
--
ALTER TABLE `developers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diarys`
--
ALTER TABLE `diarys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cast_key` (`cast_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cast_key` (`cast_id`);

--
-- Indexes for table `event_types`
--
ALTER TABLE `event_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_key` (`shop_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `diary_key` (`diary_id`);

--
-- Indexes for table `master_role`
--
ALTER TABLE `master_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `shop_infos`
--
ALTER TABLE `shop_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_key` (`shop_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `casts`
--
ALTER TABLE `casts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `developers`
--
ALTER TABLE `developers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `diarys`
--
ALTER TABLE `diarys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;

--
-- AUTO_INCREMENT for table `event_types`
--
ALTER TABLE `event_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `master_role`
--
ALTER TABLE `master_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `shop_infos`
--
ALTER TABLE `shop_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
