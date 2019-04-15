-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: rrcc_pearl_db
-- ------------------------------------------------------
-- Server version	5.7.20-log

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
-- Table structure for table `acknowledgement`
--

DROP TABLE IF EXISTS `acknowledgement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acknowledgement` (
  `notificationID` int(15) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `viewedYN` tinyint(4) NOT NULL DEFAULT '0',
  `ackDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notificationID`,`phone`),
  KEY `ack_phone_fk_idx` (`phone`),
  CONSTRAINT `ackNotiID_notiNotiID` FOREIGN KEY (`notificationID`) REFERENCES `notification` (`notificationID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ack_phone_fk` FOREIGN KEY (`phone`) REFERENCES `user` (`phone`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acknowledgement`
--

LOCK TABLES `acknowledgement` WRITE;
/*!40000 ALTER TABLE `acknowledgement` DISABLE KEYS */;
/*!40000 ALTER TABLE `acknowledgement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authorization`
--

DROP TABLE IF EXISTS `authorization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authorization` (
  `authID` int(2) NOT NULL,
  `authName` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`authID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authorization`
--

LOCK TABLES `authorization` WRITE;
/*!40000 ALTER TABLE `authorization` DISABLE KEYS */;
INSERT INTO `authorization` VALUES (1,'unauthorized'),(2,'employee'),(3,'deptHead'),(4,'admin');
/*!40000 ALTER TABLE `authorization` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `deptID` int(2) NOT NULL,
  `deptName` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`deptID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (1,'HR'),(2,'admin'),(3,'sales'),(4,'production'),(5,'operations'),(6,'foodAndBev'),(7,'garage');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification` (
  `notificationID` int(15) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` varchar(2500) DEFAULT NULL,
  `attachment` varchar(45) DEFAULT NULL,
  `activeYN` tinyint(4) DEFAULT NULL,
  `webAppYN` tinyint(4) NOT NULL DEFAULT '1',
  `postDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `surveyLink` varchar(100) DEFAULT NULL,
  `sentBy` int(15) NOT NULL,
  `viewableBy` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`notificationID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification`
--

LOCK TABLES `notification` WRITE;
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
INSERT INTO `notification` VALUES
(1, 'Convention Center will be closed on August 24, 2018', 'Due to incoming weather in the Rochester region, the convention center will be closed to ensure the safety of its workers.

 The convention center expects to be open again on August 25, 2018 at its regular working hours. If you have any questions or concerns please refer to the department head of your department.', NULL, 1, 1, CURRENT_TIMESTAMP, NULL, 1, '1234567');
 INSERT INTO `notification` VALUES
(2, 'New policy to take effect  on November 2, 2018', 'The new policy regarding all workers to wear mandatory company outfits will take place on this date. To ensure your name is on the roster to receive the new uniform please check the document attached.

 Also included is a survey link that addresses your opinions on the new company outfits, any feedback will further help us with the continuation of this policy.', 'TEST.txt', 1, 1, CURRENT_TIMESTAMP, 'https://goo.gl/forms/KWvKttAvOhu3ob143', 1, '1234567');
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `phone` varchar(11) NOT NULL,
  `fName` varchar(26) DEFAULT NULL,
  `lName` varchar(26) DEFAULT NULL,
  `tempPassYN` tinyint(4) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `deptID` int(2) DEFAULT NULL,
  `authID` int(2) DEFAULT NULL,
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `activeYN` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`phone`),
  UNIQUE KEY `userID_UNIQUE` (`userID`),
  UNIQUE KEY `phone_UNIQUE` (`phone`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `user_dept_fk_idx` (`deptID`),
  KEY `user_auth_fk_idx` (`authID`),
  CONSTRAINT `user_auth_fk` FOREIGN KEY (`authID`) REFERENCES `authorization` (`authID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_dept_fk` FOREIGN KEY (`deptID`) REFERENCES `department` (`deptID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('1234567890','builtin','Admin',0,'$2y$10$ma5.dowSWAGPa.FDwBXH2uV81dgiu9HZ3NUtnoB8Dpep7hjBJ6H4i','test@email.com',2,4,1,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `videoID` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(200) NOT NULL,
  PRIMARY KEY (`videoID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video`
--

LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
INSERT INTO `video` VALUES (1,'https://www.youtube.com/embed/nAAkkexZ9xA');
INSERT INTO `video` VALUES (2,'https://www.youtube.com/embed/sv87eEc--kk');
INSERT INTO `video` VALUES (3,'https://www.youtube.com/embed/_WcUqxcWMes');
INSERT INTO `video` VALUES (4,'https://www.youtube.com/embed/R_mu_kjulD4');
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-23 10:53:27
