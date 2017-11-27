/*
 Navicat Premium Data Transfer

 Source Server         : vagrant
 Source Server Type    : MySQL
 Source Server Version : 50718
 Source Host           : localhost
 Source Database       : laravel_admin_study

 Target Server Type    : MySQL
 Target Server Version : 50718
 File Encoding         : utf-8

 Date: 11/27/2017 13:35:44 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `phpspider_house`
-- ----------------------------
DROP TABLE IF EXISTS `phpspider_house`;
CREATE TABLE `phpspider_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `money` varchar(25) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `open_time` varchar(100) DEFAULT NULL,
  `property_right` varchar(20) DEFAULT NULL,
  `developer` varchar(50) DEFAULT NULL,
  `permit` varchar(50) DEFAULT NULL,
  `property` varchar(20) DEFAULT NULL,
  `property_money` varchar(30) DEFAULT NULL,
  `score` varchar(10) DEFAULT NULL,
  `telphone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
