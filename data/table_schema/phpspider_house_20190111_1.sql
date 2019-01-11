# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.42)
# Database: laravel_admin_study
# Generation Time: 2019-01-11 10:44:51 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table phpspider_house
# ------------------------------------------------------------

DROP TABLE IF EXISTS `phpspider_house`;

CREATE TABLE `phpspider_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `url` varchar(100) DEFAULT NULL COMMENT '网址',
  `village` varchar(25) DEFAULT NULL COMMENT '小区',
  `area` varchar(25) DEFAULT NULL COMMENT '区域：所属市六区',
  `region` varchar(25) DEFAULT NULL COMMENT '所在区域',
  `total_price` varchar(25) DEFAULT NULL COMMENT '总价',
  `unit_price` varchar(25) DEFAULT NULL COMMENT '单价',
  `house_type` varchar(25) DEFAULT NULL COMMENT '户型',
  `acreage` varchar(25) DEFAULT NULL COMMENT '面积',
  `floor` varchar(25) DEFAULT NULL COMMENT '楼层',
  `orientation` varchar(25) DEFAULT NULL COMMENT '朝向',
  `renovation` varchar(25) DEFAULT NULL COMMENT '装修',
  `property_right` varchar(25) DEFAULT NULL COMMENT '产权',
  `heating` varchar(25) DEFAULT NULL COMMENT '供暖',
  `floor_scale` varchar(25) DEFAULT NULL COMMENT '梯户比例',
  `building_type` varchar(25) DEFAULT NULL COMMENT '建筑类型',
  `elevator` varchar(25) DEFAULT NULL COMMENT '配备电梯',
  `listing_time` varchar(25) DEFAULT NULL COMMENT '挂牌时间',
  `power_belong` varchar(25) DEFAULT NULL COMMENT '权属',
  `house_age` varchar(25) DEFAULT NULL COMMENT '房屋年限 如：满五',
  `house_purpose` varchar(25) DEFAULT NULL COMMENT '房屋用途',
  `mortgage` varchar(25) DEFAULT NULL COMMENT '抵押情况',
  `favorite` tinyint(3) DEFAULT '0',
  `updated_at` datetime DEFAULT '2019-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
