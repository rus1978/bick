/*
Navicat MySQL Data Transfer

Source Server         : bick.bondar.rv.ua
Source Server Version : 50629
Source Host           : bick.bondar.rv.ua:3306
Source Database       : bick_db

Target Server Type    : MYSQL
Target Server Version : 50629
File Encoding         : 65001

Date: 2016-04-22 09:20:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `countries`
-- ----------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of countries
-- ----------------------------
INSERT INTO `countries` VALUES ('1', 'Afganistan');
INSERT INTO `countries` VALUES ('2', 'Aland Islands');
INSERT INTO `countries` VALUES ('3', 'Albania');
INSERT INTO `countries` VALUES ('4', 'Algeria');
INSERT INTO `countries` VALUES ('5', 'American Samoa');
INSERT INTO `countries` VALUES ('6', 'Andorra');
INSERT INTO `countries` VALUES ('7', 'Angola');
INSERT INTO `countries` VALUES ('8', 'Anguilla');
INSERT INTO `countries` VALUES ('9', 'Antarctica');
INSERT INTO `countries` VALUES ('10', 'Antarctica');

-- ----------------------------
-- Table structure for `user_emails`
-- ----------------------------
DROP TABLE IF EXISTS `user_emails`;
CREATE TABLE `user_emails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0',
  `email` varchar(100) NOT NULL DEFAULT '',
  `published` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_emails
-- ----------------------------
INSERT INTO `user_emails` VALUES ('35', '2', 'email@com.com', '1');
INSERT INTO `user_emails` VALUES ('36', '3', 'email6@com.com', '0');
INSERT INTO `user_emails` VALUES ('5', '3', 'email3@com.com', '1');
INSERT INTO `user_emails` VALUES ('24', '1', 'email4@com.com', '1');
INSERT INTO `user_emails` VALUES ('30', '1', 'email5@com.com', '0');
INSERT INTO `user_emails` VALUES ('34', '2', 'email2@com.com', '1');

-- ----------------------------
-- Table structure for `user_phones`
-- ----------------------------
DROP TABLE IF EXISTS `user_phones`;
CREATE TABLE `user_phones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0',
  `phone` varchar(100) NOT NULL DEFAULT '',
  `published` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_phones
-- ----------------------------
INSERT INTO `user_phones` VALUES ('1', '2', '+38 000 000 00 02', '1');
INSERT INTO `user_phones` VALUES ('2', '2', '+38 000 000 00 03', '1');
INSERT INTO `user_phones` VALUES ('3', '2', '+38 000 000 00 04', '0');
INSERT INTO `user_phones` VALUES ('4', '2', '+38 000 000 00 05', '1');
INSERT INTO `user_phones` VALUES ('5', '2', '+38 000 000 00 06', '1');
INSERT INTO `user_phones` VALUES ('13', '1', '+38 000 000 00 00', '1');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `country_id` int(3) DEFAULT NULL,
  `city` varchar(100) NOT NULL DEFAULT '',
  `address` text,
  `published` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'anna1980', 'a6c6deb707f6a8fec6f202951ee40f6c', 'Анна', 'Мороз', '10', 'London', '2324, sdsdsdsd Street', '1');
INSERT INTO `users` VALUES ('2', 'boby', 'c83e4046a7c5d3c4bf4c292e1e6ec681', 'Боб', 'Сердюк', '3', 'Madrid', '2324, sdsdsdsd Street', '1');
INSERT INTO `users` VALUES ('3', 'charly', 'a6d4ef4dd38b1bb016d250c16a680470', 'Чарли', 'Чаплин', '5', '	Los Angeles', '2324, sdsdsdsd Street', '0');
