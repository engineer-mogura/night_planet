-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2019 年 9 月 04 日 20:52
-- サーバのバージョン： 5.6.42
-- PHP Version: 7.3.8

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
(1, 38, 'cast', '山田　敏子２', 'ＨＡＮＡ', 'okiyoru3@gmail.com', '$2y$10$3Y5FfELSqG8hCwipy1VWNOvPn0A07QPRl0B7QVbgqtplAjRRFBfDC', '2000-02-14 00:00:00', NULL, 'blood_type1', 'constellation4', '22', 'メッセージメッセージメッセージメッセージメッセージメッセージメッセージメセージメッセー', NULL, '0ba3ed5e90b78afc0d5a901caa1c76d7e36c4ed9.jpg', 'aa58c6f495d90f42f7053a3fdeefe9d1508792d9.jpg', '58223027c4bfbf1f31839cb91519aeebee455f81.jpg', 'c49b4b37b6f6de03c7cd49119b379d6dbfee2551.jpg', '5b549191eae4ee3a10368ca69facdd331fb6ffce.jpg', '015b84f204f727ec6cb23d7ed5e07e5b36fd21fa.jpg', '', '', '00002', NULL, 1, 0, '2019-03-23 16:51:25', '2019-08-17 22:33:25'),
(33, 38, 'cast', '鈴木　一郎', 'イチロー', 'okiyoru99@gmail.com', '$2y$10$CQjIhOficE9SZJ5lgggxVuq4cUSwZG0O9CX6WC2GWbQBdQvPBYyda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a674a57e5e8e5a289a7ae1b294cef79b42e3d3ab.jpg', '79d52ab7a9b86eb47fef0200f1779bf72bc55719.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '00003', NULL, 1, 0, '2019-07-31 19:22:54', '2019-07-31 23:30:57'),
(35, 54, 'cast', 'なな', 'なな', 'okiyoru99@gmail.com', '$2y$10$Op0.Cgd9vufJu7f6azq53OjtUaeDXUBZJ6jsgQAq3AeywhRkPJ0j.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a3e108605eacb4c5bb871b6e4db8a7837d76be6d.jpg', 'd401a3c19c54430f0639406d7859cf556a5db10d.jpg', '', '', NULL, NULL, NULL, NULL, '00001', NULL, 1, 0, '2019-08-08 23:37:31', '2019-08-17 03:04:40'),
(41, 54, 'cast', 'りおな', 'りおな', 'okiyoru99@gmail.com', '$2y$10$OeB2W0ufPZJpOKoZngWFpOrkNeFSETNOMPl/adL5DhpSrts0Cfrpe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'c120ffc5a0456f7fc5ff6387110b2748a5d13e4d.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00002', NULL, 1, 0, '2019-08-09 00:01:51', '2019-08-09 00:04:54'),
(42, 54, 'cast', 'ゆいか', 'ゆいか', 'okiyoru99@gmail.com', '$2y$10$2ksjTbJZy//cLD.fK6rypeYmfSLPckgcNoxn.2pPLN4ovl9jIc5ae', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'c2f848d69842167ee88896fc783d44ffdaf3d010.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00003', NULL, 1, 0, '2019-08-09 20:20:25', '2019-08-09 20:22:56'),
(43, 54, 'cast', 'まみ', 'まみ', 'okiyoru99@gmail.com', '$2y$10$zMiLChXeQlZ1VGnNHfivTewm7eWrHxOmw24oOxd24eXzvEcshFf/C', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'fa2855911bc0a7a8f5fdc76ac35a8e40d7ed3a84.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00004', NULL, 1, 0, '2019-08-09 20:23:37', '2019-08-09 20:25:11'),
(44, 54, 'cast', 'あやの', 'あやの', 'okiyoru99@gmail.com', '$2y$10$yKV7WFDrJtjI/3bG.HKcIOHdKWcMGkAMkb0i78eigS2LMn7F8FV4O', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9854e59f26988a7683e1e01ad3a2499acb38bb20.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00005', NULL, 1, 0, '2019-08-09 20:26:27', '2019-08-09 20:27:56'),
(45, 54, 'cast', 'さくら', 'さくら', 'okiyoru99@gmail.com', '$2y$10$ZK7IpBPgr3ZQiwxFOn/ZpOGxodptM.mq5E45v3giYcPoNcjo57WU6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '7a47074961b6a86944148e149343fd62980f6ac0.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00006', NULL, 1, 0, '2019-08-09 20:33:22', '2019-08-09 20:34:59'),
(46, 54, 'cast', 'あや', 'あや', 'okiyoru99@gmail.com', '$2y$10$hlJU3b8NhEM/O34sBLjRGu7MLqDx4/K5HEi/9nykdHgnUUeHfFAXa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'f4dc7a2bc75622f394ffffdc5d3f7a665af88c89.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00007', NULL, 1, 0, '2019-08-09 20:37:42', '2019-08-09 20:39:20'),
(47, 54, 'cast', 'ひな', 'ひな', 'okiyoru99@gmail.com', '$2y$10$AyXZpXWXKYZfs.kZL3V8ueCyxgip9ipQLMhuSo6EawXFs4pY1Y1Ja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '73c7542a0c03b7100b32a0f298e15d9d964f44ff.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00008', NULL, 1, 0, '2019-08-09 20:39:57', '2019-08-09 20:40:46'),
(48, 55, 'cast', 'しほ', 'しほ', 'okiyoru99@gmail.com', '$2y$10$TXeMOwhsK/4vPQoJn.ZCN.PxboDRp8jSYurqQkuVwfqWKlKTWdiXy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '88b11d8760b460a4c10ca19abb7180331e7b88c5.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00001', NULL, 1, 0, '2019-08-10 16:11:29', '2019-08-10 16:15:43'),
(49, 55, 'cast', 'るか', 'るか', 'okiyoru99@gmail.com', '$2y$10$AoA.bJqZuQC0SSjDCRvK4uiZQeIRtpM/U/6udQp/mes77GXgemtJK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '930d1d60205746c6e73a82f6bc32d1d19462433a.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00002', NULL, 1, 0, '2019-08-10 16:17:02', '2019-08-10 16:17:53'),
(50, 55, 'cast', 'あーす', 'あーす', 'okiyoru99@gmail.com', '$2y$10$nZe1EOBngjM3iDxMVLLPNuBXc8b.1DOWhNrdk3WGJ.qhPSbY4Qimi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'e4ab0de9584c92d4b0763b092e37992ba3717aca.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00003', NULL, 1, 0, '2019-08-10 16:18:42', '2019-08-10 16:19:36'),
(51, 55, 'cast', 'みずき', 'みずき', 'okiyoru99@gmail.com', '$2y$10$/6K5phAfNciUFN.6lAh/0OftmW4kArVeGsekekfWjzJyIOV8WAetW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2946854fe5bf4f0a9541842605059a3fe4ea461d.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00004', NULL, 1, 0, '2019-08-10 16:20:27', '2019-08-10 16:21:36'),
(52, 55, 'cast', 'しの', 'しの', 'okiyoru99@gmail.com', '$2y$10$dzUNVjZvry8zpdHXf7Xr8O84YzhGfSG.MXsJm/uBNKz2yk.M5.zNm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'd51bc787af1247d4779e00edc7ba5ef0d3eb2393.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00005', NULL, 1, 0, '2019-08-10 16:21:58', '2019-08-10 16:22:51'),
(53, 56, 'cast', 'なお', 'なお', 'okiyoru1@gmail.com', '$2y$10$QiCOJSSG9UQv9QOeOLTgZOJF6HfBw7BmtOCcu3xHx5XZwlRgsFNNG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2601bea1201c70056cc5a0f340823fea49fc6dfd.jpg', '', NULL, NULL, NULL, NULL, NULL, NULL, '00001', NULL, 1, 0, '2019-08-10 17:23:36', '2019-09-04 20:50:40'),
(54, 56, 'cast', 'にーな', 'にーな', 'okiyoru2@gmail.com', '$2y$10$5bX0v1ycjzISzcMkWIHXYOa5Pgbiio0idh7NuPZMzddUse3YYWKT2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4f2d3399eba5620b3663a96e4a3404f4275ab9ba.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00002', NULL, 1, 0, '2019-08-10 17:27:06', '2019-08-10 17:28:07');

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
  `title` text CHARACTER SET utf8mb4 NOT NULL,
  `content` text CHARACTER SET utf8mb4 NOT NULL,
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
(44, 38, 1, '2019-02-02 00:00:00', '2019-03-18 00:00:00', 'クーポンタイトル２クーポンタイトル２クーポンタイトル２', 'クーポン内容２クーポン内\r\nクーポン内容２クーポン内容２\r\nクーポン内容２クーポン内容２クーポン内容２クーポン', '2019-03-03 01:12:48', '2019-07-31 23:43:48'),
(45, 38, 1, '2000-02-02 00:00:00', '2000-02-23 00:00:00', 'クーポン内容1クーポン内容1', 'クーポン内容1クーポン内容1', '0000-00-00 00:00:00', '2019-07-30 00:17:20'),
(47, 0, 0, '2019-06-07 00:00:00', '2019-06-18 00:00:00', 'クーポン内容4クーポン内容4 クーポン内容4クーポン内容4 クーポン内容4クーポン内容4', 'クーポン内容4クーポン内容4\r\nクーポン内容4クーポン内容4\r\nクーポン内容4クーポン内容4', '2019-06-30 20:15:12', '2019-06-30 20:15:12'),
(48, 0, 0, '2019-06-04 00:00:00', '2019-06-06 00:00:00', 'クーポン内容4クーポン内容4 クーポン内容4クーポン内容4 クーポン内容4クーポン内容4', 'クーポン内容4クーポン内容4\r\nクーポン内容4クーポン内容4\r\nクーポン内容4クーポン内容4', '2019-06-30 20:16:22', '2019-06-30 20:16:22'),
(49, 0, 0, '2019-06-07 00:00:00', '2019-06-25 00:00:00', 'testt', 'tteete', '2019-06-30 21:28:28', '2019-06-30 21:28:28'),
(50, 0, 0, '2019-02-01 00:00:00', '2019-03-19 00:00:00', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４\r\nクーポンタイトル４クーポンタイトル４クーポンタイトル４\r\nクーポンタイトル４クーポンタイトル４クーポンタイトル４', '2019-06-30 21:31:26', '2019-06-30 21:31:26'),
(51, 0, 0, '2019-06-05 00:00:00', '2019-06-26 00:00:00', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４\r\nクーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４', '2019-06-30 21:33:07', '2019-06-30 21:33:07'),
(52, 0, 0, '2019-06-05 00:00:00', '2019-06-26 00:00:00', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４\r\nクーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４', '2019-06-30 21:42:09', '2019-06-30 21:42:09'),
(53, 0, 0, '2019-06-05 00:00:00', '2019-06-26 00:00:00', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４\r\nクーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４', '2019-06-30 21:42:18', '2019-06-30 21:42:18'),
(54, 0, 0, '2019-06-05 00:00:00', '2019-06-26 00:00:00', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４\r\nクーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４クーポンタイトル４', '2019-06-30 21:43:16', '2019-06-30 21:43:16'),
(55, 38, 1, '2019-06-01 00:00:00', '2019-06-26 00:00:00', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４', 'クーポンタイトル４クーポンタイトル４クーポンタイトル４\r\nクーポンタイトル４クーポンタイトル４クーポンタイトル４\r\nクーポンタイトル４クーポンタイトル４クーポンタイトル４', '2019-06-30 21:45:20', '2019-07-30 00:16:59'),
(56, 38, 1, '2019-07-05 00:00:00', '2019-07-23 00:00:00', 'クーポン 編集押下処理クーポン 編集押下処理', 'クーポン 編集押下処理クーポン 編集押下処理クーポン 編集押下処理クーポン 編集押下処理クーポン 編集押下処理クーポン 編集押下処理クーポン 編集押下処理クーポン 編', '2019-07-30 00:05:03', '2019-07-31 23:44:04'),
(57, 54, 1, '2019-08-08 00:00:00', '2019-09-30 00:00:00', 'ウェルカムドリンク1杯サービス or 時間+10分サービス', '入店時にこちらのクーポンをお見せいただくと、ウェルカムドリンク1杯サービス、\r\nまたは時間＋１０分延長サービスになります。', '2019-08-08 23:31:00', '2019-08-08 23:31:18'),
(58, 56, 1, '2019-08-10 00:00:00', '2020-08-10 00:00:00', '初回セット料金1000円OFF', '初回セット料金1000円OFFになります。是非ご利用ください!! スタッフ、キャスト一同心よりお待ちしております🙇‍♂️', '2019-08-10 17:31:09', '2019-09-03 21:35:01');

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
  `content` varchar(600) NOT NULL,
  `image1` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `image2` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `image3` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `image4` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `image5` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `image6` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `image7` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `image8` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `dir` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `diarys`
--

INSERT INTO `diarys` (`id`, `cast_id`, `title`, `content`, `image1`, `image2`, `image3`, `image4`, `image5`, `image6`, `image7`, `image8`, `dir`, `created`, `modified`) VALUES
(144, 1, '山田　敏子２', '山田　敏子２\r\n山田　敏子２\r\n山田　敏子２', '3f369fed66ebb90cc6fa446e06b32f5d98233e1b.jpg', '1bd801217d84a5f82ba87f69b71c7203f2a0b853.jpg', '37e2236aa27c59127655b78596d57b5561c95a63.jpg', NULL, NULL, NULL, NULL, NULL, '/2019/07/25/20190725_203603', '2019-07-25 20:36:03', '2019-07-25 20:36:03'),
(145, 1, '山田　敏子２山田　敏子２山田　敏子２山田　敏子２', '山田　敏子２山田　敏子２', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/07/25/20190725_204108', '2019-07-25 20:41:09', '2019-07-25 20:41:09'),
(146, 1, '山田　敏子２山田　敏子２山田　敏子２山田　敏子２', '山田　敏子２山田　敏子２', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/07/25/20190725_204727', '2019-07-25 20:47:29', '2019-07-25 20:47:29'),
(148, 1, 'OKIYORU GoOKIYORU Go', 'OKIYORU GoOKIYORU GoOKIYORU Go\r\nOKIYORU Go', '1bd801217d84a5f82ba87f69b71c7203f2a0b853.jpg', '163d8a810ff8ab5b3340a733816274c87f4739c5.jpg', '3ba104a478ebba864b54afbff6b5805ae50fa886.jpg', '0f8664b3dc1a6edea7f8348bb5979c388004407d.jpg', '', '', NULL, NULL, '/2019/07/25/20190725_211615', '2019-07-25 21:16:17', '2019-07-29 00:56:51'),
(149, 1, 'OKIYORU GoOKIYORU Go', 'OKIYORU ', '', '', '', '', '', NULL, NULL, NULL, '/2019/07/25/20190725_211740', '2019-07-25 21:17:41', '2019-07-27 20:30:29'),
(151, 1, 'テスト投稿テスト投稿テスト投稿テスト投稿', 'テスト投稿テスト投稿テスト投稿テスト投稿テスト投稿😎😎😎😎😎\r\nテスト投稿テスト投稿😎😎😎😎😎😎😎😎😎😎😎\r\n😎😎\r\n😎テスト投稿テスト投稿テスト投稿テスト投稿テスト投稿テスト投稿😎😎😎😎😎\r\nテスト投稿テスト投稿😎😎😎😎😎😎😎😎😎😎😎\r\n😎😎\r\n😎テスト投稿', '3ba104a478ebba864b54afbff6b5805ae50fa886.jpg', 'be10f0392bcdf18c7561d92ad8ca8ff67d7101d4.jpg', '012a9371cc88777fb4dd52c592ad6aff4db2b9b0.jpg', 'ddad50142979ef1dd8962a53e18215adeb3f9240.jpg', '', NULL, NULL, NULL, '/2019/07/26/20190726_231704', '2019-07-26 23:17:04', '2019-08-11 22:40:38'),
(152, 54, '初投稿でーす！', '初投稿初投稿初投稿初投稿初投稿初投稿\r\n初投稿初投稿😎初投稿初投稿初投稿初投稿初投稿初投稿\r\n初投稿初投稿😎\r\n\r\n初投稿初投稿初投稿初投稿初投稿初投稿\r\n初投稿初投稿😎', 'e3c691e39afbeafd0b1e0239081f11a4a775661d.jpg', 'c3ea8c0d696599242bd81a3a99cbcfea994956e5.jpg', '16c1c6a16561329f97706c99044d2cfa5fc11b3b.jpg', '1fe9570a2933846042e23ec01dada580feea1ba7.jpg', '944f1549f6e6daa6c0defcaf026a9077a96d1bf7.jpg', 'e8869192005a49ef63caf51bfe604bdae8cbf181.jpg', '56dbd13a6f3ea70c5a32edd22e0656db40ab8f42.jpg', NULL, '/2019/08/11/20190811_165616', '2019-08-11 16:56:16', '2019-08-11 22:30:15'),
(153, 54, '投稿２回目ー', '初投稿初投稿初投稿初投稿初投稿初投稿\r\n初投稿初投稿😎初投稿初投稿初投稿初投稿初投稿初投稿\r\n\r\n初投稿初投稿😎\r\n\r\n初投稿初投稿初投稿初投稿初投稿初投稿\r\n初投稿初投稿初投稿初投稿初投稿初投稿初投稿\r\n初投稿初投稿😎初投稿初投稿初投稿初投稿初投稿初投稿\r\n初投稿初投稿😎\r\n\r\n初投稿初投稿初投稿初投稿初投稿初投稿\r\n初投稿初初投稿初投稿初投稿初投稿初投稿初投稿\r\n初投稿初投稿😎初投稿初投稿初投稿初投稿初投稿初投稿\r\n初投稿初投稿😎\r\n\r\n初投稿初投稿初投稿初投稿', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/11/20190811_225921', '2019-08-11 22:59:21', '2019-08-11 22:59:21'),
(154, 1, '久しぶりの投稿ー！', '久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー久しぶりの投稿ー😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥😥', '118388e959075dc80651da190750aa2c49857510.jpg', '828d5ce2c318a1fe2bb17a194196056af98d2170.jpg', '099399482ff6b17222bff4cd1d1ec7ff022dda55.jpg', NULL, NULL, NULL, NULL, NULL, '/2019/08/11/20190811_230316', '2019-08-11 23:03:16', '2019-08-14 00:32:34'),
(155, 1, '日記😎日記😎日記😎日記😎日', '日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎日記😎', '099399482ff6b17222bff4cd1d1ec7ff022dda55.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/13/20190813_232147', '2019-08-13 23:21:48', '2019-08-13 23:21:48'),
(156, 1, '銀次郎に行ってきた！', 'ハイボールの美味い店に行ってきたよー🍺🍺🍺🍺🍺🍺🍺🍺🍺🍺🍺🍺🍺😥🍺\r\n', '099399482ff6b17222bff4cd1d1ec7ff022dda55.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/14/20190814_002817', '2019-08-14 00:28:17', '2019-08-14 15:57:25'),
(157, 1, 'shah', 'ちやのゆならゆひやむなり\r\n', '85ed5122f6e95af18d455e0cc6b82b15a2293517.jpg', '099399482ff6b17222bff4cd1d1ec7ff022dda55.jpg', '163d8a810ff8ab5b3340a733816274c87f4739c5.jpg', '11d3fe5c2ee169d8eb73e15c28719a168e358c3e.jpg', '', NULL, NULL, NULL, '/2019/08/14/20190814_005025', '2019-08-14 00:50:25', '2019-08-14 21:28:21'),
(158, 1, 'てｓってｔｔｔ', 'shehshdhdudj\r\n\r\n', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/14/20190814_144755', '2019-08-14 14:47:55', '2019-08-14 20:46:48'),
(159, 1, 'test', 'gssdq\r\n\r\n\r\nsqzaa', '', '', '', '', '', '', NULL, NULL, '/2019/08/14/20190814_145318', '2019-08-14 14:53:18', '2019-08-14 20:42:38'),
(160, 1, 'tet', 'tets', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/14/20190814_213009', '2019-08-14 21:30:09', '2019-08-14 21:30:09'),
(161, 1, 'fd', 'fd', '099399482ff6b17222bff4cd1d1ec7ff022dda55.jpg', '7312b6518e65551a5517314fe358150e4417fb20.jpg', '163d8a810ff8ab5b3340a733816274c87f4739c5.jpg', '828d5ce2c318a1fe2bb17a194196056af98d2170.jpg', NULL, NULL, NULL, NULL, '/2019/08/14/20190814_213231', '2019-08-14 21:32:31', '2019-08-14 21:32:31'),
(162, 1, 'fdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdf', 'DfESｄｆｄ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/14/20190814_234916', '2019-08-14 23:49:16', '2019-08-14 23:49:16'),
(163, 35, 'アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺', 'アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺\r\n\r\n\r\nアリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺\r\n\r\n\r\nアリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺\r\nアリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺\r\nアリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな☺\r\n\r\n\r\nアリーナ所属なな☺アリーナ所属なな☺アリーナ所属なな', '21d40d047d9d90084fb29d69370fd29d23b776a9.jpg', '9355809fc1364bf5f28f3306c564aa27d62e0a0d.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/15/20190815_035620', '2019-08-15 03:56:20', '2019-08-15 21:10:01'),
(164, 53, '初投稿！', '初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！\r\n\r\n初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！初投稿！\r\n\r\n\r\n\r\n初投稿！初投稿！初投稿！初投稿！', 'e14d1188951cb045657ca5db338208105ad17124.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/09/01/20190901_160348', '2019-09-01 16:03:50', '2019-09-01 16:03:50'),
(166, 53, '初投稿２！初投稿２！初投稿２！初投稿２！初投稿２！', '初投稿２！初投稿２！初投稿２！初投稿２！', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/09/03/20190903_211229', '2019-09-03 21:12:29', '2019-09-03 21:12:29');

-- --------------------------------------------------------

--
-- テーブルの構造 `diary_likes`
--

DROP TABLE IF EXISTS `diary_likes`;
CREATE TABLE `diary_likes` (
  `id` int(11) NOT NULL,
  `diary_id` int(11) NOT NULL,
  `cast_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `diary_likes`
--

INSERT INTO `diary_likes` (`id`, `diary_id`, `cast_id`, `user_id`, `created`, `modified`) VALUES
(1, 164, 53, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

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
(282, NULL, 1, '休み', NULL, '2019-07-01 00:00:00', '2019-07-01 00:00:00', NULL, NULL, '1', NULL, 1, '2019-07-27 17:16:03', '2019-07-27 17:16:03'),
(284, NULL, 1, '休み', NULL, '2019-07-05 00:00:00', '2019-07-05 00:00:00', NULL, NULL, '1', NULL, 1, '2019-07-27 17:16:11', '2019-07-27 17:16:11'),
(285, NULL, 1, '仕事', NULL, '2019-07-02 03:30:00', '2019-07-02 04:00:00', '03:30', '04:00', '1', NULL, 1, '2019-07-27 17:16:25', '2019-07-27 17:18:37'),
(287, NULL, 1, '仕事', NULL, '2019-07-04 00:00:00', '2019-07-04 00:00:00', '00:00', '00:00', '1', NULL, 1, '2019-07-27 17:17:47', '2019-07-27 17:17:47'),
(288, NULL, 1, '休み', NULL, '2019-07-06 00:00:00', '2019-07-06 00:00:00', NULL, NULL, '1', NULL, 1, '2019-07-27 17:18:44', '2019-07-27 17:18:44'),
(289, NULL, 1, '仕事', NULL, '2019-06-04 00:30:00', '2019-06-04 07:00:00', '00:30', '07:00', '1', NULL, 1, '2019-07-27 20:28:59', '2019-07-27 20:28:59'),
(291, NULL, 1, '休み', NULL, '2019-07-04 00:00:00', '2019-07-04 00:00:00', NULL, NULL, '1', NULL, 1, '2019-07-29 01:02:53', '2019-07-29 01:02:53'),
(292, NULL, 53, '仕事', NULL, '2019-09-04 13:00:00', '2019-09-04 00:00:00', '13:00', NULL, '1', NULL, 1, '2019-09-04 20:40:43', '2019-09-04 20:41:54');

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
  `pr` varchar(400) CHARACTER SET utf8mb4 DEFAULT NULL,
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
(1, 38, '時間制(キャバクラ)', 'レディスタッフ・キャスト', '21:00:00', NULL, 'work_time_hosoku', '21', '24', 'qualification_hosoku', '水,木,金,日', 'holiday_hosoku', '送迎あり,個人ロッカーあり', 'PR文PR文PR文PR文PR文PR文PR文\r\nPR文PR文PR文PR文PR文PR文PR文PR文PR文PR文PR文PR文PR文PR文\r\nPR文PR文PR文PR文PR文PR文PR文', '09037968838', '08037968838', 't.takuma830@gmail.com', 'testLINEID', '2019-03-20 00:06:19', '2019-08-10 11:38:08'),
(2, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 54, '時間制(キャバクラ)', 'レディスタッフ・キャスト', '21:00:00', NULL, '時間相談に応じます 1日2～3時間の勤務もOKです', '20', '30', '※初心者大歓迎', '土', '	勤務日数はおまかせします。', '体験入店あり,日払い可,レンタル衣装あり,ノルマなし,未経験者歓迎,各種バックあり,送迎あり,経験者優遇,ドレス・制服貸与あり', '浦添エリアの人気店!! 【体験保証時給3,000円】 未経験者大歓迎!在籍中キャストのほとんどが掛け持ちや週1～3出勤など 自分に合ったスタイルで働いております。貴女も是非「体験入店」でお試し下さい! 掛け持ちオッケー(掛け持ちの方も多数在籍してます) 即日体験入店OK、スタッフ専用駐車場完備(30台) お酒飲まなくてもOK(車で出勤して飲まずにお仕事する方も多いですよ!) 送迎もあります!', '09097874621', '0988786792', NULL, 'kaitok0502', '2019-08-07 23:56:04', '2019-08-10 14:50:33'),
(102, 55, 'ガールズバー', 'カウンターレディ', '20:00:00', NULL, '※時間相談に応じます、３ｈ～の短時間でもOK', '18', '30', '※初心者・学生・主婦・シングルマザー大歓迎', NULL, '週１日～勤務ＯＫ、週末のみでもＯＫ', '日払い可,各種バックあり,ノルマなし,送迎あり,未経験者歓迎', '楽しく働くならここ！ まだまだオープンしたばかりのガールズバーです♪ 時給１,２００円～１,５００円以上！ 完全日払い制!! 送迎有り（中南部） お友達同士の応募、一日体験もオッケイです♪ 気軽にお問い合わせください♪＼(^o^)／', '09068653218', '', NULL, '', '2019-08-10 15:16:08', '2019-08-10 16:29:44'),
(105, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 19:24:23', '2019-08-18 19:24:23'),
(106, 56, '時間制(キャバクラ)', 'レディスタッフ・キャスト', '21:00:00', NULL, '21:00～LASTまでの間でお好きな時間', '18', '35', '（高校生不可）', NULL, '', '体験入店あり,お友達と一緒に面接可,未経験者歓迎,週末のみ可,ノルマなし,レンタル衣装あり,週１から可,大型連休あり,モノレール駅からすぐ,日払い可,送迎あり,給与支給2回,ヘアメイクあり,各種バックあり,経験者優遇,ドレス・制服貸与あり,個人ロッカーあり,友達紹介料あり', '初めまして、店長の仁科です。\r\n求人を見ていただきありがとうございます(^^)\r\n少なからずこのホームページを開いたこの瞬間は期待と不安が入り混じっているかと思います！\r\nまた、未経験の方はさらに一歩が踏み出せない、なんてこともありますよね！\r\n僕自身も同じ経験がありますので一歩踏み出す勇気がどれほど大変なのかわかります！\r\nだからこそ一度勇気を出して電話を下さい！\r\nしっかりした対応、サポートをさせていただきますのでどんな小さなことでもお気軽に問い合わせのほうをしてきてください！\r\nお待ちしています(^_^)', '0120596307', '', NULL, '', '0000-00-00 00:00:00', '2019-08-25 16:30:11');

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
  `name` varchar(45) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
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
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `owners`
--

INSERT INTO `owners` (`id`, `name`, `image`, `role`, `tel`, `email`, `password`, `gender`, `age`, `dir`, `remember_token`, `status`, `created`, `modified`) VALUES
(1, '', NULL, 'owner', '', 't.takuma830@gmail.com', '$2y$10$IvHwLrvdmHfFQgw88nlOD.DD1jnVFy52vG.auT04dLts3W1c2xcxu', 0, '', NULL, NULL, 0, '2019-03-23 10:38:14', '2019-03-23 10:38:14'),
(57, '', NULL, 'owner', '', 'okiyoru99@gmail.com', '$2y$10$vBxr/LLpjQ07C1LpN2bvWuJ8LNVy2vtvpx1qZgd1VousDjoN83qVq', 0, '', '00005', NULL, 1, '2019-02-12 21:42:22', '2019-03-15 18:15:38'),
(92, '', NULL, 'owner', '', 'okiyoru2@gmail.com', '$2y$10$K0knx7GKbgg8BIYt5sUZ7.1KSX2s2sL/GuNT5H.WalyeWiq09JF0O', 0, '', '00002', NULL, 1, '2019-08-07 23:50:33', '2019-08-07 23:55:25'),
(93, '', NULL, 'owner', '', 'okiyoru3@gmail.com', '$2y$10$JQueljItW8sT42R1z2weG.Jc/z/ziO8AuoUrLXvAk9NjxQXw7hscG', 0, '', '00001', NULL, 1, '2019-08-10 15:10:55', '2019-08-10 15:16:07'),
(94, '鈴木太郎', 'd42936d7112ea0ce41934e98cba8a8735bcf16c3.jpg', 'owner', '09012341234', 'okiyoru1@gmail.com', '$2y$10$M68eGdvgIadmxzRw6.1EHu5gEN3fA7DShvvz1KjGTzBbR/giYsd6O', 1, '36', '00002', NULL, 1, '2019-08-10 16:55:43', '2019-08-18 22:20:28'),
(98, '鈴木次郎', 'e3babc50ddfc45ec880aaf82424bfa24839f92a4.jpg', 'owner', '09012341234', 'okiyoru99@gmail.com', '$2y$10$PojdyaiQojD22nYfrmObv.H3ARmAWV3PTZK.6qGkjOEE..1y7yCm6', 1, '33', '00001', NULL, 1, '2019-08-18 17:07:25', '2019-08-18 22:17:22');

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
  `name` varchar(40) DEFAULT NULL,
  `top_image` varchar(100) DEFAULT NULL,
  `image1` varchar(100) DEFAULT NULL,
  `image2` varchar(100) DEFAULT NULL,
  `image3` varchar(100) DEFAULT NULL,
  `image4` varchar(100) DEFAULT NULL,
  `image5` varchar(100) DEFAULT NULL,
  `image6` varchar(100) DEFAULT NULL,
  `image7` varchar(100) DEFAULT NULL,
  `image8` varchar(100) DEFAULT NULL,
  `catch` varchar(100) DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `staff` varchar(255) DEFAULT NULL,
  `bus_from_time` time DEFAULT NULL,
  `bus_to_time` time DEFAULT NULL,
  `bus_hosoku` varchar(255) DEFAULT NULL,
  `system` varchar(600) DEFAULT NULL,
  `credit` varchar(255) DEFAULT NULL,
  `cast` varchar(400) DEFAULT NULL,
  `pref21` varchar(3) DEFAULT NULL,
  `addr21` varchar(10) DEFAULT NULL,
  `strt21` varchar(30) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `shops`
--

INSERT INTO `shops` (`id`, `owner_id`, `area`, `genre`, `dir`, `name`, `top_image`, `image1`, `image2`, `image3`, `image4`, `image5`, `image6`, `image7`, `image8`, `catch`, `tel`, `staff`, `bus_from_time`, `bus_to_time`, `bus_hosoku`, `system`, `credit`, `cast`, `pref21`, `addr21`, `strt21`, `created`, `modified`) VALUES
(38, 57, 'naha', 'snack', '00005', 'OKIYORUGO', 'd4498a1ad55a9f1c62175e30e63913633458dc61.jpg', '', '', '', '', '', '', '', NULL, '沖縄県浦添市にある総在籍数70名を誇る県内最大級時間制クラブ。\r\nエリアNo.1クラスの実績と自信。接待向けのお店。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '20:00:00', '03:00:00', '※日曜日も営業しております！', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'AmericanExpress,Diners,VISA,MasterCard', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-08-10 13:49:39'),
(39, 57, 'naha', 'cabacula', '00006', '那覇店舗名１', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '那覇のキャバクラをお探しなら那覇店舗名１\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(40, 59, 'naha', 'cabacula', '00007', '那覇店舗名２', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '那覇のキャバクラをお探しなら那覇店舗名２\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(41, 60, 'naha', 'cabacula', '00008', '那覇店舗名３', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '那覇のキャバクラをお探しなら那覇店舗名３\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(42, 61, 'naha', 'cabacula', '00005', '那覇店舗名４', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '那覇のキャバクラをお探しなら那覇店舗名４\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(43, 62, 'naha', 'girlsbar', '00005', '那覇店舗名５', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '那覇のガールズバーをお探しなら那覇店舗名５\r\n時間制・飲み放題で安心のガールズバーです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(44, 63, 'naha', 'snack', '00005', '那覇店舗名６', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '那覇のスナックをお探しなら那覇店舗名６\r\n時間制・飲み放題で安心のスナックです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(45, 64, 'miyakojima', 'cabacula', '00005', '宮古島店舗名１', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '宮古島のキャバクラをお探しなら宮古島店舗名１\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(46, 65, 'miyakojima', 'cabacula', '00005', '宮古島店舗名２', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '宮古島のキャバクラをお探しなら宮古島店舗名２\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(47, 66, 'miyakojima', 'cabacula', '00005', '宮古島店舗名３', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '宮古島のキャバクラをお探しなら宮古島店舗名３\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(48, 67, 'miyakojima', 'cabacula', '00005', '宮古島店舗名４', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '宮古島のキャバクラをお探しなら宮古島店舗名４\r\n時間制・飲み放題で安心のキャバクラです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(49, 68, 'miyakojima', 'girlsbar', '00005', '宮古島店舗名５', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '宮古島のガールズバーをお探しなら宮古島店舗名５\r\n時間制・飲み放題で安心のガールズバーです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(50, 69, 'miyakojima', 'snack', '00005', '宮古島店舗名６', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '宮古島のスナックをお探しなら宮古島店舗名６\r\n時間制・飲み放題で安心のスナックです。', '09012341234', '全国各地から集まった20歳～30歳の明るい女のコ多数', '14:30:00', '20:00:00', '※日曜日も営業しております。', '時間制 1時間飲み放題\r\nお一人様（税・サービス料込）\r\n￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）\r\n★ＶＩＰルーム、カラオケ完備', 'MasterCard,Diners,AmericanExpress,VISA,JCB', NULL, '沖縄県', '浦添市', '〇〇〇ＸＸ－ＸＸ－ＸＸ', '2019-02-12 21:42:56', '2019-05-14 19:44:32'),
(54, 92, 'urasoe', 'club', '00001', 'ARENA -アリーナ-', 'fa897ef69c325e84d4a73e1d681e103d2de24f61.jpg', '91527b5da301b543245f4b929c8ad7d7887f12e9.jpg', 'd49f191774e3397fe75b26393f090fa3bd7b5341.jpg', '8010a4cdc60e90a6d90b33b6428624b2f49d6172.jpg', NULL, NULL, NULL, NULL, NULL, '沖縄県浦添市にある総在籍数70名を誇る県内最大級時間制クラブ。\r\nエリアNo.1クラスの実績と自信。接待向けのお店', '09097874621', '', '22:00:00', '04:00:00', '・(金・土・祝日前日)22:00～05:00 ・年中無休', '★SET料金★\r\n・御1人様	70分/10,000円\r\n・2名様以上	70分/5,000円\r\n・御延長	上記同額\r\n★指名★\r\n・本指名	1,000円\r\n・場内指名	1,000円\r\n★ドリンク★\r\n・ビール	1,000円(グラス1杯)\r\n・カクテル	各1,000円(グラス1杯)\r\n・ワイン	各1,000円(グラス1杯)\r\n★ボトル★\r\n・泡盛	3,000円～\r\n・焼酎	3,000円～\r\n・ワイン	8,000円～\r\n・ウィスキー	15,000円\r\n・シャンパン	8,000円～\r\n★VIP Room★\r\n室料	20,000円\r\n★その他★\r\n・団体	～100名まで可', 'VISA,JCB,MasterCard,AmericanExpress', NULL, '沖縄県', '浦添市', '城間3-5-1 MSシュタークビル2F', '2019-08-07 23:55:27', '2019-08-08 23:26:45'),
(55, 93, 'urasoe', 'girlsbar', '00001', 'フェリス', '7676cf4feb6ceae2268ea8dcdb8e8b4f8df8d503.jpg', '692f892c1595fcb00f92f4e54045454af122a08a.jpg', '99f5cd8e2119337534464897a275d08f7eab2d6c.jpg', '1ff52888b3b1d3414df850e25dfd0ee95be2af55.jpg', '9563142cd343c4ddcea6787c4f2825207ee3f6ee.jpg', '582296c1dc294d3a1721f536337a5f706bf16355.jpg', NULL, NULL, NULL, 'カラオケもダーツも楽しめちゃうイベント盛りだくさんのガールズバー♡', '09068653218', '', '21:00:00', NULL, '不定休', '★ブロンズコース♡７０分♡	￥２,５００円★\r\n泡盛／かりゆし・瑞穂　酎ハイ／レモン・緑茶・ウーロン　カクテル／ピーチウーロン・カルアミルク・カシスオレンジ・カシスウーロン・カシスソーダ・モスコミュール・ジントニック・カシスミルク　ソフトドリンク/コーラ・オレンジ・ウーロン茶・緑茶\r\n★シルバーコース♡７０分♡	￥３,０００円★\r\nブロンズメニュー ＋ 泡盛／菊ブラ・久米仙・残波・残黒　ビール　焼酎／鏡月　カクテル／サングリア\r\n★ゴールドコース♡７０分♡	￥３,５００円★\r\nブロンズメニュー・シルバーメニュー ＋ 泡盛／ＶＩＰゴールド・北谷長老・琉球王朝　焼酎／二階堂・吉四六\r\n★ＳｔａｆＤｒｉｎｋ★\r\nＡＬＬ １,０００円\r\n★カラオケ１曲★\r\n２００円\r\n★歌い放題（時間内）★\r\n１,０００円\r\n★女性グールプのみ★\r\n１,０００円 ＯＦＦ\r\n★ダーツ投げ放題★\r\n５００円 \r\n★時間無制限 飲み放題★\r\n５,０００円 \r\n★単品メニュー５,０００円～有り★', 'MasterCard,VISA', NULL, '沖縄県', '浦添市', '経塚518 テナントビルてぃーだ 2F', '2019-08-10 15:16:07', '2019-08-10 15:52:53'),
(56, 94, 'naha', 'cabacula', '00002', 'Club琉球', 'dca447d4540d4441ef6f0a7495ebea7d983f00e3.jpg', '70cf8ef198ee22f098d31c96a13b6db700085850.jpg', '5f8dc463c62d717694d2e07b5fa3254523f1bf5c.jpg', '99d384a724fb9aab1972772b18992f80b09d2c67.jpg', '3ba90b2743d559bc1c2623b85a4b4d43bd1741ec.jpg', '75d5ca39e15124b9802aab78b64bccc07fd387c1.jpg', '6ff046e9668601d80c548fb6edead4f6e0414964.jpg', '', '', '那覇市松山にGRANDOPEN!!\r\nKING of RESORT!! CLUB 琉球', '0989757973', '', '21:00:00', NULL, '月曜定休日', '★1time 60min★\r\n・保証	５，０００円\r\n・マンツーマン	８，０００円\r\n・ＶＩＰ	１，０００円\r\n・指名料	１，０００円\r\n・場内指名料	１，０００円\r\n・ＳＣ	１５％　（税込）\r\n・PRIVATE ROOM　１５，０００円（ＳＣ　２５％　税込）\r\n・SECRET ROOM　２０，０００円（ＳＣ　２５％　税込）\r\n★Free Drink★\r\n・MAIN Floor	ビール、泡盛、焼酎、ソフトドリンク各種\r\n・VIP Floor	ビール、ハイボール、泡盛(古酒)、焼酎、ソフトドリンク各種', 'MasterCard,VISA,JCB,AmericanExpress,Diners', NULL, '沖縄県', '那覇市', '松山2-9-17 カーニバルビル4F 5F', '2019-08-10 16:58:22', '2019-09-03 21:32:56'),
(63, 98, 'ginowan', 'girlsbar', '00001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 19:24:23', '2019-08-18 19:24:23');

-- --------------------------------------------------------

--
-- テーブルの構造 `shop_infos`
--

DROP TABLE IF EXISTS `shop_infos`;
CREATE TABLE `shop_infos` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(600) NOT NULL,
  `image1` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `image2` varchar(100) DEFAULT NULL,
  `image3` varchar(100) DEFAULT NULL,
  `image4` varchar(100) DEFAULT NULL,
  `image5` varchar(100) DEFAULT NULL,
  `image6` varchar(100) DEFAULT NULL,
  `image7` varchar(100) DEFAULT NULL,
  `image8` varchar(100) DEFAULT NULL,
  `dir` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `shop_infos`
--

INSERT INTO `shop_infos` (`id`, `shop_id`, `title`, `content`, `image1`, `image2`, `image3`, `image4`, `image5`, `image6`, `image7`, `image8`, `dir`, `created`, `modified`) VALUES
(1, 56, '店舗からのお知らせ😎店舗からのお知らせ😎店舗からのお知らせ😎店舗からのお知らせ😎', '店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?店舗からのお知らせ?', '099399482ff6b17222bff4cd1d1ec7ff022dda55.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/13/20190813_233634', '2019-08-13 23:37:01', '2019-08-13 23:37:01'),
(2, 56, '店舗からのお知らせ２😎店舗からのお知らせ２😎', '店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?\r\n\r\n店舗からのお知らせ２?店舗からのお知らせ２?\r\n店舗からのお知らせ２?店舗からのお知らせ２?\r\n\r\n\r\n店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?店舗からのお知らせ２?\r\n\r\n店舗からのお知らせ２?', '21d40d047d9d90084fb29d69370fd29d23b776a9.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/14/20190814_214500', '2019-08-14 21:45:00', '2019-08-14 21:45:00'),
(3, 56, '店舗からのお知らせ３😎店舗からのお知らせ３😎店舗からのお知らせ３😎', '店舗からのお知らせ３?店舗からのお知らせ３?店舗からのお知らせ３?店舗からのお知らせ３?\r\n\r\n店舗からのお知らせ３?店舗からのお知らせ３?店舗からのお知らせ３?店舗からのお知らせ３?\r\n店舗からのお知らせ３?店舗からのお知らせ３?店舗からのお知らせ３?店舗からのお知らせ３?店舗からのお知らせ３?店舗からのお知らせ３?店舗からのお知らせ３?ｖ', '21d40d047d9d90084fb29d69370fd29d23b776a9.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/14/20190814_214806', '2019-08-14 21:48:06', '2019-08-14 21:48:06'),
(4, 56, '店舗からのお知らせ４😎店舗からのお知らせ４😎店舗からのお知らせ４😎店舗からのお知らせ４😎', '店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?\r\n\r\n店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?\r\n\r\n\r\n\r\n店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?店舗からのお知らせ４?\r\n\r\n\r\n店舗からのお知らせ４?店舗からのお知らせ４?', '21d40d047d9d90084fb29d69370fd29d23b776a9.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/14/20190814_215031', '2019-08-14 21:50:31', '2019-08-14 21:50:31'),
(5, 56, '店舗からのお知らせ５😎店舗からのお知らせ５😎店舗からのお知らせ５😎店舗からのお知らせ５😎', '店舗からのお知らせ５😎店舗からのお知らせ５😎店舗からのお知らせ５😎店舗からのお知らせ５😎店舗からのお知らせ５😎店舗からのお知らせ５😎店舗からのお知らせ５😎店舗からのお知らせ５😎店舗からのお知らせ５😎店舗からのお知らせ５😎', '21d40d047d9d90084fb29d69370fd29d23b776a9.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/14/20190814_215514', '2019-08-14 21:55:14', '2019-08-14 21:55:14'),
(6, 56, '店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎', '店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎\r\n\r\n\r\n\r\n店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎\r\n店舗からのお知らせ６😎店舗からのお知らせ６😎店舗からのお知らせ６😎', '21d40d047d9d90084fb29d69370fd29d23b776a9.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/14/20190814_220035', '2019-08-14 22:00:35', '2019-08-14 22:00:35'),
(7, 56, '店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎', '店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎店舗からのお知らせ７😎', '5aca4cb44dd89079e8e5a943eefa7a0376c56168.jpg', '9355809fc1364bf5f28f3306c564aa27d62e0a0d.jpg', '7901c9530e1859aed224e88790820b581249afc4.jpg', '9ea987da0941b85733d31d17137da730deed392a.jpg', '82000f9dff198e6afd62698a25c49141129f140f.jpg', '6a77222419fc5c7e1dba7d035ba10c7347431cb5.jpg', NULL, NULL, '/2019/08/14/20190814_235405', '2019-08-14 23:54:05', '2019-08-28 23:38:47'),
(8, 55, 'フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎', 'フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎\r\n\r\n\r\nフェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎\r\n\r\n\r\nフェリスからのお知らせ😎フェリスからのお知らせ😎\r\n\r\nフェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎\r\n\r\n\r\n\r\nフェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎フェリスからのお知らせ😎', 'c9d451af222e1e9a50243d2946c78660f4ada2ba.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/08/15/20190815_160853', '2019-08-15 16:08:53', '2019-08-15 16:08:53'),
(9, 56, '店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎', '店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎店舗からのお知らせ８😎', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/2019/09/01/20190901_223334', '2019-09-01 22:33:34', '2019-09-02 22:13:35');

-- --------------------------------------------------------

--
-- テーブルの構造 `shop_info_likes`
--

DROP TABLE IF EXISTS `shop_info_likes`;
CREATE TABLE `shop_info_likes` (
  `id` int(11) NOT NULL,
  `shop_info_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `shop_info_likes`
--

INSERT INTO `shop_info_likes` (`id`, `shop_info_id`, `shop_id`, `user_id`, `created`, `modified`) VALUES
(1, 9, 56, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `snss`
--

DROP TABLE IF EXISTS `snss`;
CREATE TABLE `snss` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `cast_id` int(11) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `line` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `snss`
--

INSERT INTO `snss` (`id`, `shop_id`, `cast_id`, `facebook`, `twitter`, `instagram`, `line`, `created`, `modified`) VALUES
(2, 56, NULL, 'Night-Planet-101941477849319', 'OkinawaHack', 't.a.k.u.m.a_', '', '2019-08-31 01:09:16', '2019-08-31 12:19:41');

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
-- テーブルの構造 `updates`
--

DROP TABLE IF EXISTS `updates`;
CREATE TABLE `updates` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `cast_id` int(11) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `updates`
--

INSERT INTO `updates` (`id`, `shop_id`, `cast_id`, `type`, `content`, `created`, `modified`) VALUES
(1, 56, 1, 'EVENT', '店舗からのお知らせ1が追加されました。', '2019-09-01 23:04:16', '2019-09-01 23:04:16'),
(2, 56, 1, 'EVENT', '店舗からのお知らせ2が追加されました。', '2019-09-01 23:27:17', '2019-09-01 23:27:17'),
(3, 56, 1, 'EVENT', '店舗からのお知らせ3が追加されました。', '2019-08-15 23:27:17', '2019-09-01 23:27:17'),
(4, NULL, NULL, 'SYSTEM', '店舗情報が更新されました。', '2019-09-03 00:43:23', '2019-09-03 00:43:23'),
(5, 56, NULL, 'CAST', 'なおさんがギャラリーを追加しました。', '2019-09-03 00:46:07', '2019-09-03 00:46:07'),
(7, 56, 53, 'DIARY', 'なおさんが日記を追加しました。', '2019-09-03 21:12:32', '2019-09-03 21:12:32'),
(9, 56, NULL, '', '店舗画像が更新されました。', '2019-09-03 21:25:10', '2019-09-03 21:25:10'),
(10, 56, NULL, '', '店舗画像が更新されました。', '2019-09-03 21:32:56', '2019-09-03 21:32:56'),
(11, 56, NULL, 'SHOP-GALLERY', '店内ギャラリーが更新されました。', '2019-09-03 21:35:46', '2019-09-03 21:35:46'),
(12, 56, 0, 'EVENT', '店舗からのお知らせ3が追加されました。', '2019-09-01 23:04:16', '2019-09-01 23:04:16');

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
-- Indexes for table `diary_likes`
--
ALTER TABLE `diary_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `diary_like_key` (`diary_id`);

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
-- Indexes for table `shop_info_likes`
--
ALTER TABLE `shop_info_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_info_key` (`shop_info_id`);

--
-- Indexes for table `snss`
--
ALTER TABLE `snss`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `cast_id` (`cast_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `cast_id` (`cast_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `developers`
--
ALTER TABLE `developers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `diarys`
--
ALTER TABLE `diarys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `diary_likes`
--
ALTER TABLE `diary_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;

--
-- AUTO_INCREMENT for table `event_types`
--
ALTER TABLE `event_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `master_role`
--
ALTER TABLE `master_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `shop_infos`
--
ALTER TABLE `shop_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `shop_info_likes`
--
ALTER TABLE `shop_info_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `snss`
--
ALTER TABLE `snss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
