-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2012 年 02 月 27 日 21:56
-- 服务器版本: 5.1.47
-- PHP 版本: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `app_kindlesync`
--

-- --------------------------------------------------------

--
-- 表的结构 `kindle_comments`
--

CREATE TABLE IF NOT EXISTS `kindle_comments` (
  `id` int(11) NOT NULL,
  `userid` varchar(30) NOT NULL,
  `username` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `comment` text NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `weibo_txt` varchar(500) NOT NULL COMMENT '微博内容',
  `sina` tinyint(1) NOT NULL DEFAULT '0',
  `qq` tinyint(1) NOT NULL DEFAULT '0',
  `douban` tinyint(1) NOT NULL DEFAULT '0',
  `fanfou` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `kindle_config`
--

CREATE TABLE IF NOT EXISTS `kindle_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` varchar(200) NOT NULL,
  `type` varchar(20) NOT NULL,
  `userid` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=907 ;

-- --------------------------------------------------------

--
-- 表的结构 `kindle_user`
--

CREATE TABLE IF NOT EXISTS `kindle_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `username` varchar(200) NOT NULL,
  `sina` tinyint(1) NOT NULL DEFAULT '0',
  `qq` tinyint(1) NOT NULL DEFAULT '0',
  `douban` tinyint(1) NOT NULL DEFAULT '0',
  `fanfou` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=190 ;
