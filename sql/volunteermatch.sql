-- MySQL dump 10.13  Distrib 5.1.49, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: volmatch
-- ------------------------------------------------------
-- Server version	5.1.49-1ubuntu8.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `mtype` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'superadmin','apples5','admin'),(4,'admin','grape4','manager');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_sessions`
--

DROP TABLE IF EXISTS `admin_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_sessions` (
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `mtype` varchar(20) NOT NULL DEFAULT '',
  `session_id` varchar(50) DEFAULT NULL,
  `logout_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_sessions`
--

LOCK TABLES `admin_sessions` WRITE;
/*!40000 ALTER TABLE `admin_sessions` DISABLE KEYS */;
INSERT INTO `admin_sessions` VALUES ('admin','grape4','manager','1157a04a514189252844767f8cadc16e','2010-10-26 09:27:07');
/*!40000 ALTER TABLE `admin_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL DEFAULT '',
  `best_times` enum('Y','N') DEFAULT 'N',
  `comments_text` text NOT NULL,
  `is_active` enum('Y','N') DEFAULT 'N',
  `display_order` int(10) unsigned NOT NULL DEFAULT '0',
  `submit_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Give an hour lecture','Y','Comments (please include topics you are interested in)','Y',1,'2009-03-13 10:13:10'),(2,'Judge a competition','Y','Competition Details ','Y',8,'2009-03-13 10:13:10'),(3,'Serve on an advisory board','N','','Y',7,'2009-03-13 10:13:48'),(4,'Tour of a company','N','','Y',4,'2009-03-13 10:13:48'),(5,'Internships for students','N','','Y',5,'2009-03-13 10:14:15'),(6,'Summer jobs for students','N','','N',3,'2009-03-13 10:14:15');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `submit_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `counties`
--

DROP TABLE IF EXISTS `counties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `counties` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `county_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `counties`
--

LOCK TABLES `counties` WRITE;
/*!40000 ALTER TABLE `counties` DISABLE KEYS */;
/*!40000 ALTER TABLE `counties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_subs`
--

DROP TABLE IF EXISTS `news_subs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_subs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `submit_time` datetime NOT NULL,
  `activation_code` varchar(50) NOT NULL,
  `email_verified` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_subs`
--

LOCK TABLES `news_subs` WRITE;
/*!40000 ALTER TABLE `news_subs` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_subs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schools`
--

DROP TABLE IF EXISTS `schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schools` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `county` varchar(255) NOT NULL DEFAULT '',
  `district` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL,
  `state` varchar(200) NOT NULL,
  `zip` varchar(25) NOT NULL,
  `school_name` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schools`
--

LOCK TABLES `schools` WRITE;
/*!40000 ALTER TABLE `schools` DISABLE KEYS */;
/*!40000 ALTER TABLE `schools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) DEFAULT NULL,
  `member_id` int(10) unsigned NOT NULL DEFAULT '0',
  `member_type` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) DEFAULT NULL,
  `logout_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (149,'6ae872f5e0a2b5e98ace24315fdebed1',5,'volunteer','mark.martin@design4x.com','2011-03-08 04:24:21');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `settingid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `settinggroupid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `grouptitle` varchar(200) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `varname` varchar(100) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  `optioncode` mediumtext NOT NULL,
  `displayorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`settingid`),
  KEY `varname` (`varname`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,1,'Turn Website On and Off','Website Active','website_active','1','From time to time, you may want to turn your website off to the public while you perform maintenance, update versions, etc. When you turn your website off, visitors will receive a message that states that the website is temporarily unavailable. <b>Administrators will still be able to see the website</b></p> <p>Use this as a master switch for your website','yesno',1),(2,1,'Turn Website On and Off','Reason for turning Website off','websiteclosedreason','Volunteer Match Resource is currently down for maintainence purposes. We will be up in a short while. Please visit us later.','The text that is presented when the website is closed.</p> <p>Note: you, as an administrator, will be able to see the store as usual, even when you have turned them off to the public. ','textarea',2),(3,2,'Website Information','Information Email','do_not_reply_email','mark.martin@design4x.com','This is the email \r\n\r\naddress to which members can ask for more information / clarification.','text',1),(5,2,'Website Information','Admin Email','admin_email','mark.martin@design4x.com','This is the email \r\n\r\nfrom which registration emails are sent','text',3),(7,2,'Website Information','Website Title','website_title','SVE Volunteer Match Resource','This is the title of the website \r\n\r\nwhich will be used through out the website','text',3),(8,2,'Website Information','Email Activation Required','activation_required','0','It checks if email activation is required in the registration process','yesno',4);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `states` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(100) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `abbrev` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `states`
--

LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;
INSERT INTO `states` VALUES (1,'US','ALABAMA','AL'),(2,'US','ALASKA','AK'),(3,'US','AMERICAN SAMOA','AS'),(4,'US','ARIZONA','AZ'),(5,'US','ARKANSAS','AR'),(6,'US','CALIFORNIA','CA'),(7,'US','COLORADO','CO'),(8,'US','CONNECTICUT','CT'),(9,'US','DELAWARE','DE'),(10,'US','DISTRICT OF COLUMBIA','DC'),(11,'US','FEDERATED STATES OF MICRONESIA','FM'),(12,'US','FLORIDA','FL'),(13,'US','GEORGIA','GA'),(14,'US','GUAM','GU'),(15,'US','HAWAII','HI'),(16,'US','IDAHO','ID'),(17,'US','ILLINOIS','IL'),(18,'US','INDIANA','IN'),(19,'US','IOWA','IA'),(20,'US','KANSAS','KS'),(21,'US','KENTUCKY','KY'),(22,'US','LOUISIANA','LA'),(23,'US','MAINE','ME'),(24,'US','MARSHALL ISLANDS','MH'),(25,'US','MARYLAND','MD'),(26,'US','MASSACHUSETTS','MA'),(27,'US','MICHIGAN','MI'),(28,'US','MINNESOTA','MN'),(29,'US','MISSISSIPPI','MS'),(30,'US','MISSOURI','MO'),(31,'US','MONTANA','MT'),(32,'US','NEBRASKA','NE'),(33,'US','NEVADA','NV'),(34,'US','NEW HAMPSHIRE','NH'),(35,'US','NEW JERSEY','NJ'),(36,'US','NEW MEXICO','NM'),(37,'US','NEW YORK','NY'),(38,'US','NORTH CAROLINA','NC'),(39,'US','NORTH DAKOTA','ND'),(40,'US','NORTHERN MARIANA ISLANDS','MP'),(41,'US','OHIO','OH'),(42,'US','OKLAHOMA','OK'),(43,'US','OREGON','OR'),(44,'US','PALAU','PW'),(45,'US','PENNSYLVANIA','PA'),(46,'US','PUERTO RICO','PR'),(47,'US','RHODE ISLAND','RI'),(48,'US','SOUTH CAROLINA','SC'),(49,'US','SOUTH DAKOTA','SD'),(50,'US','TENNESSEE','TN'),(51,'US','TEXAS','TX'),(52,'US','UTAH','UT'),(53,'US','VERMONT','VT'),(54,'US','VIRGIN ISLANDS','VI'),(55,'US','VIRGINIA','VA'),(56,'US','WASHINGTON','WA'),(57,'US','WEST VIRGINIA','WV'),(58,'US','WISCONSIN','WI'),(59,'US','WYOMING','WY');
/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tars`
--

DROP TABLE IF EXISTS `tars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` int(10) unsigned NOT NULL DEFAULT '0',
  `teacher_fname` varchar(200) DEFAULT NULL,
  `teacher_lname` varchar(200) DEFAULT NULL,
  `county_name` varchar(255) NOT NULL DEFAULT '',
  `district_name` varchar(255) NOT NULL DEFAULT '',
  `school_name` varchar(255) NOT NULL DEFAULT '',
  `school_city` varchar(255) NOT NULL,
  `school_zip` varchar(25) NOT NULL,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `category` varchar(200) NOT NULL DEFAULT '',
  `category_name` varchar(255) NOT NULL DEFAULT '',
  `best_times` varchar(255) NOT NULL DEFAULT '',
  `months` varchar(200) NOT NULL DEFAULT '',
  `details` text NOT NULL,
  `grades` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `students` int(10) unsigned NOT NULL DEFAULT '0',
  `submit_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tar_status` enum('pending','complete','admin deleted','teacher deleted') NOT NULL DEFAULT 'pending',
  `volunteer` int(10) unsigned NOT NULL DEFAULT '0',
  `rating` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `complete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comments` text NOT NULL,
  `email_status` enum('Open','In-Progress','Scheduled') NOT NULL DEFAULT 'Open',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tars`
--

LOCK TABLES `tars` WRITE;
/*!40000 ALTER TABLE `tars` DISABLE KEYS */;
/*!40000 ALTER TABLE `tars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tars_emails`
--

DROP TABLE IF EXISTS `tars_emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tars_emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tar_id` int(10) unsigned NOT NULL DEFAULT '0',
  `teacher_id` int(10) unsigned NOT NULL DEFAULT '0',
  `county_name` varchar(255) NOT NULL DEFAULT '',
  `district_name` varchar(255) NOT NULL DEFAULT '',
  `school_name` varchar(255) NOT NULL DEFAULT '',
  `school_city` varchar(255) NOT NULL,
  `school_zip` varchar(25) NOT NULL,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `category` varchar(200) NOT NULL DEFAULT '',
  `category_name` varchar(255) NOT NULL DEFAULT '',
  `best_times` varchar(255) NOT NULL DEFAULT '',
  `months` varchar(200) NOT NULL DEFAULT '',
  `details` text NOT NULL,
  `grades` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `students` int(10) unsigned NOT NULL DEFAULT '0',
  `submit_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `email_message` text NOT NULL,
  `email_status` enum('pending','complete','admin deleted','teacher deleted') NOT NULL DEFAULT 'pending',
  `email_dated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `volunteer` int(10) unsigned NOT NULL DEFAULT '0',
  `rating` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `complete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rand_id` varchar(100) NOT NULL,
  `other_volunteers` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tars_emails`
--

LOCK TABLES `tars_emails` WRITE;
/*!40000 ALTER TABLE `tars_emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `tars_emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teachers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `fname` varchar(200) NOT NULL DEFAULT '',
  `lname` varchar(200) NOT NULL DEFAULT '',
  `phone` varchar(100) NOT NULL,
  `county` varchar(255) NOT NULL DEFAULT '',
  `school` text NOT NULL,
  `details` text NOT NULL,
  `activation_code` varchar(100) NOT NULL DEFAULT '',
  `account_status` enum('Unconfirmed','Active','Inactive') NOT NULL DEFAULT 'Unconfirmed',
  `comments` text NOT NULL,
  `submit_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteers`
--

DROP TABLE IF EXISTS `volunteers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `fname` varchar(200) NOT NULL DEFAULT '',
  `lname` varchar(200) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `company` varchar(255) NOT NULL DEFAULT '',
  `industry` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `details` text NOT NULL,
  `account_status` enum('Unconfirmed','Active','Inactive') NOT NULL DEFAULT 'Unconfirmed',
  `comments` text NOT NULL,
  `activation_code` varchar(100) NOT NULL DEFAULT '',
  `submit_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteers`
--

LOCK TABLES `volunteers` WRITE;
/*!40000 ALTER TABLE `volunteers` DISABLE KEYS */;
/*!40000 ALTER TABLE `volunteers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-04  0:19:35
