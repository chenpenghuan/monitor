-- MySQL dump 10.15  Distrib 10.0.25-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: winnerlook
-- ------------------------------------------------------
-- Server version	10.0.25-MariaDB-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cont_conf`
--

DROP TABLE IF EXISTS `cont_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cont_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` tinyint(3) NOT NULL,
  `cont_sec` tinyint(3) NOT NULL,
  `cont_title` varchar(45) NOT NULL,
  `cont_var` varchar(45) NOT NULL,
  `cont_url` varchar(45) NOT NULL,
  `item_type` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cont_conf`
--

LOCK TABLES `cont_conf` WRITE;
/*!40000 ALTER TABLE `cont_conf` DISABLE KEYS */;
INSERT INTO `cont_conf` VALUES (1,4,1,'用户名','username','http://localhost/monitor/index.php/Home/Index',2),(2,4,2,'今日发送','todaysend','http://localhost/monitor/index.php/Home/Index',2),(3,15,2,'测试22','test2','http://localhost/monitor/index.php/Home/Index',2),(4,15,1,'属于二级测试1','test1','http://localhost/monitor/index.php/Home/Index',2),(5,4,2,'10M延迟','10Mdelay','http://localhost/monitor/index.php/Home/suiyi',2),(6,4,2,'10M发送','10Msend','http://localhost/monitor/index.php/Home/suiyi',2),(7,4,3,'昨日发送','yestsend','http://localhost/monitor/index.php/Home/Index',2),(8,11,1,'第一测试1','test1','http://localhost/monitor/index.php/Home/Index',1),(9,11,2,'第二测试','test2','http://localhost/monitor/index.php/Home/Index',1);
/*!40000 ALTER TABLE `cont_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contents`
--

DROP TABLE IF EXISTS `contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cont_id` int(11) NOT NULL,
  `cont_text` varchar(45) DEFAULT NULL,
  `update_sec` int(10) unsigned NOT NULL,
  `update_date` char(20) DEFAULT NULL,
  `isshow` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contents`
--

LOCK TABLES `contents` WRITE;
/*!40000 ALTER TABLE `contents` DISABLE KEYS */;
INSERT INTO `contents` VALUES (1,1,'测试1的内容',0,'',0),(4,2,'测试2第二次内容',1,'',0),(5,1,'测试1的第二次刷新',1,'',0),(6,3,'测试3的第二次刷新',1,'',0),(7,3,'测试3的第一次刷新',0,'',0),(8,2,'测试2的第一次刷新',0,'',0),(9,2,'测试2的第三次刷新',2,'',0),(10,4,'测试4的第三次刷新',2,'',0),(11,5,'1090',1,'',0),(12,5,'1190',2,'',0),(13,1,'user1',1,'',0),(14,1,'user2',2,'',0),(15,2,'1000',1,'',0),(16,2,'900',2,'',0),(27,5,'1090',1,'2016-06-08 13:37:59',0),(28,5,'1190',2,'2016-06-08 13:37:59',0),(29,5,'3000',3,'2016-06-08 13:37:59',0),(30,2,'1000',1,'2016-06-08 13:37:59',0),(31,2,'900',2,'2016-06-08 13:37:59',0),(32,2,'3000',3,'2016-06-08 13:37:59',0),(33,1,'user1',1,'2016-06-08 13:37:59',0),(34,1,'user2',2,'2016-06-08 13:37:59',0),(35,1,'user3',3,'2016-06-08 13:37:59',0),(36,5,'1090',1,'2016-06-08 13:37:59',0),(37,5,'1190',2,'2016-06-08 13:37:59',0),(38,5,'3000',3,'2016-06-08 13:37:59',0),(39,1,'user1',1,'2016-06-08 13:37:59',0),(40,1,'user2',2,'2016-06-08 13:37:59',0),(41,1,'user3',3,'2016-06-08 13:37:59',0),(42,2,'1000',1,'2016-06-08 13:37:59',0),(43,2,'900',2,'2016-06-08 13:37:59',0),(44,2,'3000',3,'2016-06-08 13:37:59',0),(45,2,'1000',1,'2016-06-08 13:37:59',0),(46,2,'900',2,'2016-06-08 13:37:59',0),(47,2,'3000',3,'2016-06-08 13:37:59',0),(48,5,'1090',1,'2016-06-08 13:37:59',0),(49,5,'1190',2,'2016-06-08 13:37:59',0),(50,5,'3000',3,'2016-06-08 13:37:59',0),(51,1,'user1',1,'2016-06-08 13:37:59',0),(52,1,'user2',2,'2016-06-08 13:37:59',0),(53,1,'user3',3,'2016-06-08 13:37:59',0),(54,5,'1090',1,'2016-06-08 13:37:59',0),(55,5,'1190',2,'2016-06-08 13:37:59',0),(56,5,'3000',3,'2016-06-08 13:37:59',0),(57,1,'user1',1,'2016-06-08 13:37:59',0),(58,1,'user2',2,'2016-06-08 13:37:59',0),(59,1,'user3',3,'2016-06-08 13:37:59',0),(60,2,'1000',1,'2016-06-08 13:37:59',0),(61,2,'900',2,'2016-06-08 13:37:59',0),(62,2,'3000',3,'2016-06-08 13:37:59',0),(63,5,'1090',1,'2016-06-08 13:37:59',0),(64,5,'1190',2,'2016-06-08 13:37:59',0),(65,5,'3000',3,'2016-06-08 13:37:59',0),(66,1,'user1',1,'2016-06-08 13:37:59',0),(67,1,'user2',2,'2016-06-08 13:37:59',0),(68,1,'user3',3,'2016-06-08 13:37:59',0),(69,2,'1000',1,'2016-06-08 13:37:59',0),(70,2,'900',2,'2016-06-08 13:37:59',0),(71,2,'3000',3,'2016-06-08 13:37:59',0),(72,5,'1090',1,'2016-06-08 13:37:59',0),(73,5,'1190',2,'2016-06-08 13:37:59',0),(74,5,'3000',3,'2016-06-08 13:37:59',0),(75,1,'user1',1,'2016-06-08 13:37:59',0),(76,1,'user2',2,'2016-06-08 13:37:59',0),(77,1,'user3',3,'2016-06-08 13:37:59',0),(78,2,'1000',1,'2016-06-08 13:37:59',0),(79,2,'900',2,'2016-06-08 13:37:59',0),(80,2,'3000',3,'2016-06-08 13:37:59',0),(81,5,'1090',1,'2016-06-08 13:37:59',0),(82,5,'1190',2,'2016-06-08 13:37:59',0),(83,5,'3000',3,'2016-06-08 13:37:59',0),(84,1,'user1',1,'2016-06-08 13:37:59',0),(85,1,'user2',2,'2016-06-08 13:37:59',0),(86,1,'user3',3,'2016-06-08 13:37:59',0),(87,2,'1000',1,'2016-06-08 13:37:59',0),(88,2,'900',2,'2016-06-08 13:37:59',0),(89,2,'3000',3,'2016-06-08 13:37:59',0),(90,5,'1090',1,'2016-06-08 13:37:59',0),(91,5,'1190',2,'2016-06-08 13:37:59',0),(92,5,'3000',3,'2016-06-08 13:37:59',0),(93,1,'user1',1,'2016-06-08 13:37:59',0),(94,1,'user2',2,'2016-06-08 13:37:59',0),(95,1,'user3',3,'2016-06-08 13:37:59',0),(96,2,'1000',1,'2016-06-08 13:37:59',0),(97,2,'900',2,'2016-06-08 13:37:59',0),(98,2,'3000',3,'2016-06-08 13:37:59',0),(99,5,'1090',1,'2016-06-08 13:37:59',0),(100,5,'1190',2,'2016-06-08 13:37:59',0),(101,5,'3000',3,'2016-06-08 13:37:59',0),(102,1,'user1',1,'2016-06-08 13:37:59',0),(103,1,'user2',2,'2016-06-08 13:37:59',0),(104,1,'user3',3,'2016-06-08 13:37:59',0),(105,5,'1090',1,'2016-06-08 13:37:59',0),(106,5,'1190',2,'2016-06-08 13:37:59',0),(107,5,'3000',3,'2016-06-08 13:37:59',0),(108,2,'1000',1,'2016-06-08 13:37:59',0),(109,2,'900',2,'2016-06-08 13:37:59',0),(110,2,'3000',3,'2016-06-08 13:37:59',0),(111,3,'又是一段内容',1,'2016-06-08 13:37:59',0),(112,3,'又是一段内容',2,'2016-06-08 13:37:59',0),(113,4,'一段内容',1,'2016-06-08 13:37:59',0),(114,4,'一段内容',2,'2016-06-08 13:37:59',0),(115,4,'一段内容',1,'2016-06-08 13:37:59',0),(116,4,'一段内容',2,'2016-06-08 13:37:59',0),(117,3,'又是一段内容',1,'2016-06-08 13:37:59',0),(118,3,'又是一段内容',2,'2016-06-08 13:37:59',0),(119,5,'1090',1,'2016-06-08 13:37:59',0),(120,5,'1190',2,'2016-06-08 13:37:59',0),(121,5,'3000',3,'2016-06-08 13:37:59',0),(122,1,'user1',1,'2016-06-08 13:37:59',0),(123,1,'user2',2,'2016-06-08 13:37:59',0),(124,1,'user3',3,'2016-06-08 13:37:59',0),(125,2,'1000',1,'2016-06-08 13:37:59',0),(126,2,'900',2,'2016-06-08 13:37:59',0),(127,2,'3000',3,'2016-06-08 13:37:59',0),(128,2,'1000',1,'2016-06-08 13:37:59',0),(129,2,'900',2,'2016-06-08 13:37:59',0),(130,2,'3000',3,'2016-06-08 13:37:59',0),(131,1,'user1',1,'2016-06-08 13:37:59',0),(132,1,'user2',2,'2016-06-08 13:37:59',0),(133,1,'user3',3,'2016-06-08 13:37:59',0),(134,5,'1090',1,'2016-06-08 13:37:59',0),(135,5,'1190',2,'2016-06-08 13:37:59',0),(136,5,'3000',3,'2016-06-08 13:37:59',0),(137,5,'1090',1,'2016-06-08 13:37:59',0),(138,5,'1190',2,'2016-06-08 13:37:59',0),(139,5,'3000',3,'2016-06-08 13:37:59',0),(140,2,'1000',1,'2016-06-08 13:37:59',0),(141,2,'900',2,'2016-06-08 13:37:59',0),(142,2,'3000',3,'2016-06-08 13:37:59',0),(143,1,'user1',1,'2016-06-08 13:37:59',0),(144,1,'user2',2,'2016-06-08 13:37:59',0),(145,1,'user3',3,'2016-06-08 13:37:59',0),(146,2,'1000',1,'2016-06-08 13:37:59',0),(147,2,'900',2,'2016-06-08 13:37:59',0),(148,2,'3000',3,'2016-06-08 13:37:59',0),(149,5,'1090',1,'2016-06-08 13:37:59',0),(150,5,'1190',2,'2016-06-08 13:37:59',0),(151,5,'3000',3,'2016-06-08 13:37:59',0),(152,1,'user1',1,'2016-06-08 13:37:59',0),(153,1,'user2',2,'2016-06-08 13:37:59',0),(154,1,'user3',3,'2016-06-08 13:37:59',0),(155,1,'user1',1,'2016-06-08 13:37:59',0),(156,1,'user2',2,'2016-06-08 13:37:59',0),(157,1,'user3',3,'2016-06-08 13:37:59',0),(158,2,'1000',1,'2016-06-08 13:37:59',0),(159,2,'900',2,'2016-06-08 13:37:59',0),(160,2,'3000',3,'2016-06-08 13:37:59',0),(161,5,'1090',1,'2016-06-08 13:37:59',0),(162,5,'1190',2,'2016-06-08 13:37:59',0),(163,5,'3000',3,'2016-06-08 13:37:59',0),(164,1,'user1',1,'2016-06-08 13:37:59',0),(165,1,'user2',2,'2016-06-08 13:37:59',0),(166,1,'user3',3,'2016-06-08 13:37:59',0),(167,2,'1000',1,'2016-06-08 13:37:59',0),(168,2,'900',2,'2016-06-08 13:37:59',0),(169,2,'3000',3,'2016-06-08 13:37:59',0),(170,5,'1090',1,'2016-06-08 13:37:59',0),(171,5,'1190',2,'2016-06-08 13:37:59',0),(172,5,'3000',3,'2016-06-08 13:37:59',0),(173,5,'1090',1,'2016-06-08 13:37:59',0),(174,5,'1190',2,'2016-06-08 13:37:59',0),(175,5,'3000',3,'2016-06-08 13:37:59',0),(176,1,'user1',1,'2016-06-08 13:37:59',0),(177,1,'user2',2,'2016-06-08 13:37:59',0),(178,1,'user3',3,'2016-06-08 13:37:59',0),(179,2,'1000',1,'2016-06-08 13:37:59',0),(180,2,'900',2,'2016-06-08 13:37:59',0),(181,2,'3000',3,'2016-06-08 13:37:59',0),(182,4,'一段内容',1,'2016-06-08 13:37:59',1),(183,4,'一段内容',2,'2016-06-08 13:37:59',1),(184,3,'又是一段内容',1,'2016-06-08 13:37:59',1),(185,3,'又是一段内容',2,'2016-06-08 13:37:59',1),(186,5,'1090',1,'2016-06-08 13:37:59',0),(187,5,'3000',2,'2016-06-08 13:37:59',0),(188,1,'user1',1,'2016-06-08 13:37:59',0),(189,1,'user2',2,'2016-06-08 13:37:59',0),(190,1,'user3',3,'2016-06-08 13:37:59',0),(191,2,'1000',1,'2016-06-08 13:37:59',0),(192,2,'900',2,'2016-06-08 13:37:59',0),(193,2,'3000',3,'2016-06-08 13:37:59',0),(194,1,'user1',1,'2016-06-08 13:37:59',1),(195,1,'user2',2,'2016-06-08 13:37:59',1),(196,1,'user3',3,'2016-06-08 13:37:59',1),(197,2,'1000',1,'2016-06-08 13:37:59',1),(198,2,'900',2,'2016-06-08 13:37:59',1),(199,2,'3000',3,'2016-06-08 13:37:59',1),(200,5,'1090',1,'2016-06-08 13:37:59',1),(201,5,'3000',2,'2016-06-08 13:37:59',1),(202,6,'1090',1,'2016-06-08 13:37:59',1),(203,6,'1190',2,'2016-06-08 13:37:59',1),(204,6,'3000',3,'2016-06-08 13:37:59',1);
/*!40000 ALTER TABLE `contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item1_conf`
--

DROP TABLE IF EXISTS `item1_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item1_conf` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `item1_num` tinyint(2) NOT NULL,
  `item1_title` varchar(45) NOT NULL,
  `item1_chan` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item1_conf`
--

LOCK TABLES `item1_conf` WRITE;
/*!40000 ALTER TABLE `item1_conf` DISABLE KEYS */;
INSERT INTO `item1_conf` VALUES (2,2,'通道相关',0),(3,3,'用户相关',0),(5,5,'系统设置',1),(6,4,'系统相关',0),(11,1,'一级测试',0);
/*!40000 ALTER TABLE `item1_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item2_conf`
--

DROP TABLE IF EXISTS `item2_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item2_conf` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `item1_num` tinyint(4) NOT NULL,
  `item2_num` tinyint(4) NOT NULL,
  `item2_title` varchar(45) NOT NULL,
  `item2_chan` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item2_conf`
--

LOCK TABLES `item2_conf` WRITE;
/*!40000 ALTER TABLE `item2_conf` DISABLE KEYS */;
INSERT INTO `item2_conf` VALUES (4,2,2,'待发量监测',0),(6,5,1,'菜单设置',1),(13,3,1,'添加用户',0),(14,2,3,'二级测试',0),(15,2,1,'二级测试1',0),(16,5,2,'报警设置',0);
/*!40000 ALTER TABLE `item2_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_2_4`
--

DROP TABLE IF EXISTS `item_2_4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_2_4` (
  `test1` tinytext,
  `test2` varchar(45) DEFAULT '正在更新',
  `test3` varchar(45) DEFAULT '正在更新'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_2_4`
--

LOCK TABLES `item_2_4` WRITE;
/*!40000 ALTER TABLE `item_2_4` DISABLE KEYS */;
INSERT INTO `item_2_4` VALUES ('这是一段测试','正在更新','正在更新'),('这是第二段测试','这是更新后的内容','正在更新');
/*!40000 ALTER TABLE `item_2_4` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pass_config`
--

DROP TABLE IF EXISTS `pass_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pass_config` (
  `setmanu` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pass_config`
--

LOCK TABLES `pass_config` WRITE;
/*!40000 ALTER TABLE `pass_config` DISABLE KEYS */;
INSERT INTO `pass_config` VALUES (0);
/*!40000 ALTER TABLE `pass_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pass_monitor`
--

DROP TABLE IF EXISTS `pass_monitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pass_monitor` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PASS_NUM` varchar(45) DEFAULT NULL,
  `PASS_NAME` varchar(60) DEFAULT NULL,
  `PASS_WAIT` varchar(11) DEFAULT NULL,
  `MONI_TIME` varchar(17) DEFAULT NULL,
  `SEND_DELAY` varchar(11) DEFAULT NULL,
  `ACOUNT_TIME` varchar(17) DEFAULT NULL,
  `TODAY_SEND` varchar(11) DEFAULT NULL,
  `YESTO_SEND` varchar(11) DEFAULT NULL,
  `10M_SEND` varchar(11) DEFAULT NULL,
  `10M_BACK` varchar(11) DEFAULT NULL,
  `BACK_SUCC` varchar(11) DEFAULT NULL,
  `BACK_TIME` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pass_monitor`
--

LOCK TABLES `pass_monitor` WRITE;
/*!40000 ALTER TABLE `pass_monitor` DISABLE KEYS */;
INSERT INTO `pass_monitor` VALUES (1,'27','0240移动强制扩展','327','2015/6/23 10:37','6','2015/6/23 10:30','320542','301,245','458','419','398','8.46'),(2,'18','三网合一联通','214','2015/6/23 10:30','13','2015/6/23 10:30','80648','61,548','98','78','69','12.21'),(3,'38','289-ZJ电信会员','2','2015/6/23 10:30','35','2015/6/23 10:30','1068','1205','9','9','3','27.11'),(4,'94','LSS移动','100','2015/6/23 10:30','18','2015/6/23 10:30','3215','2947','59','39','31','9.43');
/*!40000 ALTER TABLE `pass_monitor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-01 11:12:50
