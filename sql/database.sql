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
-- Table structure for table `campaigns_cmp`
--

DROP TABLE IF EXISTS `campaigns_cmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns_cmp` (
  `id_cmp` int(11) NOT NULL AUTO_INCREMENT,
  `id_grp_cmp` int(11) NOT NULL,
  `id_cty_cmp` int(11) NOT NULL,
  `name_cmp` varchar(140) NOT NULL,
  `ingress_cmp` varchar(320) NOT NULL,
  `description_cmp` text NOT NULL,
  `image_cmp` varchar(45) DEFAULT NULL,
  `start_time_cmp` date DEFAULT NULL,
  `end_time_cmp` date DEFAULT NULL,
  `created_cmp` datetime DEFAULT NULL,
  `modified_cmp` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cmp`,`id_grp_cmp`),
  KEY `fk_grp` (`id_grp_cmp`),
  KEY `fk_cty` (`id_cty_cmp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns_cmp`
--

LOCK TABLES `campaigns_cmp` WRITE;
/*!40000 ALTER TABLE `campaigns_cmp` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaigns_cmp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cmp_has_cmp`
--

DROP TABLE IF EXISTS `cmp_has_cmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmp_has_cmp` (
  `id_parent_cmp` int(11) NOT NULL,
  `id_child_cmp` int(11) NOT NULL,
  PRIMARY KEY (`id_parent_cmp`,`id_child_cmp`),
  KEY `fk_cmp_has_cmp_parent_cmp` (`id_parent_cmp`),
  KEY `fk_cmp_has_cmp_child_cmp` (`id_child_cmp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cmp_has_cmp`
--

LOCK TABLES `cmp_has_cmp` WRITE;
/*!40000 ALTER TABLE `cmp_has_cmp` DISABLE KEYS */;
/*!40000 ALTER TABLE `cmp_has_cmp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cmp_has_cnt`
--

DROP TABLE IF EXISTS `cmp_has_cnt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmp_has_cnt` (
  `id_cmp` int(11) NOT NULL,
  `id_cnt` int(11) NOT NULL,
  PRIMARY KEY (`id_cmp`,`id_cnt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cmp_has_cnt`
--

LOCK TABLES `cmp_has_cnt` WRITE;
/*!40000 ALTER TABLE `cmp_has_cnt` DISABLE KEYS */;
/*!40000 ALTER TABLE `cmp_has_cnt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cmp_has_tag`
--

DROP TABLE IF EXISTS `cmp_has_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmp_has_tag` (
  `id_cmp` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  PRIMARY KEY (`id_cmp`,`id_tag`),
  KEY `fk_cpg` (`id_cmp`),
  KEY `fk_tag` (`id_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cmp_has_tag`
--

LOCK TABLES `cmp_has_tag` WRITE;
/*!40000 ALTER TABLE `cmp_has_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `cmp_has_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cmp_weblinks_cwl`
--

DROP TABLE IF EXISTS `cmp_weblinks_cwl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmp_weblinks_cwl` (
  `id_cwl` int(11) NOT NULL AUTO_INCREMENT,
  `id_cmp_cwl` int(11) NOT NULL,
  `name_cwl` varchar(45) NOT NULL,
  `url_cwl` varchar(150) NOT NULL,
  `count_cwl` int(11) NOT NULL,
  `created_cwl` datetime DEFAULT NULL,
  `modified_cwl` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cwl`),
  KEY `fk_cmp_cwl` (`id_cmp_cwl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cmp_weblinks_cwl`
--

LOCK TABLES `cmp_weblinks_cwl` WRITE;
/*!40000 ALTER TABLE `cmp_weblinks_cwl` DISABLE KEYS */;
/*!40000 ALTER TABLE `cmp_weblinks_cwl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cmt_ratings_cmr`
--

DROP TABLE IF EXISTS `cmt_ratings_cmr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmt_ratings_cmr` (
  `id_cmr` int(11) NOT NULL AUTO_INCREMENT,
  `id_usr_cmr` int(11) NOT NULL,
  `id_cmt_cmr` int(11) NOT NULL,
  `rating_cmr` int(11) NOT NULL,
  `created_cmr` datetime DEFAULT NULL,
  `modified_cmr` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cmr`),
  KEY `fk_usr_cmr` (`id_usr_cmr`),
  KEY `fk_cmt_cmr` (`id_cmt_cmr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cmt_ratings_cmr`
--

LOCK TABLES `cmt_ratings_cmr` WRITE;
/*!40000 ALTER TABLE `cmt_ratings_cmr` DISABLE KEYS */;
/*!40000 ALTER TABLE `cmt_ratings_cmr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_has_cnt`
--

DROP TABLE IF EXISTS `cnt_has_cnt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_has_cnt` (
  `id_parent_cnt` int(11) NOT NULL,
  `id_child_cnt` int(11) NOT NULL,
  PRIMARY KEY (`id_parent_cnt`,`id_child_cnt`),
  KEY `fk_cnt_has_cnt_parent_cnt` (`id_parent_cnt`),
  KEY `fk_cnt_has_cnt_child_cnt` (`id_child_cnt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_has_cnt`
--

LOCK TABLES `cnt_has_cnt` WRITE;
/*!40000 ALTER TABLE `cnt_has_cnt` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_has_cnt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_has_fic`
--

DROP TABLE IF EXISTS `cnt_has_fic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_has_fic` (
  `id_cnt` int(11) NOT NULL,
  `id_fic` int(11) NOT NULL,
  PRIMARY KEY (`id_cnt`,`id_fic`),
  KEY `fk_cnt` (`id_cnt`),
  KEY `fk_fic` (`id_fic`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_has_fic`
--

LOCK TABLES `cnt_has_fic` WRITE;
/*!40000 ALTER TABLE `cnt_has_fic` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_has_fic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_has_grp`
--

DROP TABLE IF EXISTS `cnt_has_grp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_has_grp` (
  `id_cnt` int(11) NOT NULL,
  `id_grp` int(11) NOT NULL,
  `owner_cnt_grp` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cnt`,`id_grp`),
  KEY `fk_cnt_has_grp_cnt` (`id_cnt`),
  KEY `fk_cnt_has_grp_grp` (`id_grp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_has_grp`
--

LOCK TABLES `cnt_has_grp` WRITE;
/*!40000 ALTER TABLE `cnt_has_grp` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_has_grp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_has_ind`
--

DROP TABLE IF EXISTS `cnt_has_ind`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_has_ind` (
  `id_cnt` int(11) NOT NULL,
  `id_ind` int(11) NOT NULL,
  PRIMARY KEY (`id_cnt`,`id_ind`),
  KEY `fk_cnt_has_ind_cnt` (`id_cnt`),
  KEY `fk_cnt_has_ind_ind` (`id_ind`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_has_ind`
--

LOCK TABLES `cnt_has_ind` WRITE;
/*!40000 ALTER TABLE `cnt_has_ind` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_has_ind` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_has_ivt`
--

DROP TABLE IF EXISTS `cnt_has_ivt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_has_ivt` (
  `id_cnt` int(11) NOT NULL,
  `id_ivt` int(11) NOT NULL,
  PRIMARY KEY (`id_cnt`,`id_ivt`),
  KEY `fk_cnt_has_ivt_cnt` (`id_cnt`),
  KEY `fk_cnt_has_ivt_ivt` (`id_ivt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_has_ivt`
--

LOCK TABLES `cnt_has_ivt` WRITE;
/*!40000 ALTER TABLE `cnt_has_ivt` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_has_ivt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_has_rec`
--

DROP TABLE IF EXISTS `cnt_has_rec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_has_rec` (
  `id_cnt` int(11) NOT NULL,
  `id_rec` int(11) NOT NULL,
  PRIMARY KEY (`id_cnt`,`id_rec`),
  KEY `fk_cnt` (`id_cnt`),
  KEY `fk_rec` (`id_rec`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_has_rec`
--

LOCK TABLES `cnt_has_rec` WRITE;
/*!40000 ALTER TABLE `cnt_has_rec` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_has_rec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_has_tag`
--

DROP TABLE IF EXISTS `cnt_has_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_has_tag` (
  `id_cnt` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  PRIMARY KEY (`id_cnt`,`id_tag`),
  KEY `fk_cnt_has_tag_cnt` (`id_cnt`),
  KEY `fk_cnt_has_tag_tag` (`id_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_has_tag`
--

LOCK TABLES `cnt_has_tag` WRITE;
/*!40000 ALTER TABLE `cnt_has_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_has_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_has_usr`
--

DROP TABLE IF EXISTS `cnt_has_usr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_has_usr` (
  `id_cnt` int(11) NOT NULL,
  `id_usr` int(11) NOT NULL,
  `owner_cnt_usr` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cnt`,`id_usr`),
  KEY `fk_cnt_has_usr_cnt` (`id_cnt`),
  KEY `fk_cnt_has_usr_usr` (`id_usr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_has_usr`
--

LOCK TABLES `cnt_has_usr` WRITE;
/*!40000 ALTER TABLE `cnt_has_usr` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_has_usr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_publish_times_pbt`
--

DROP TABLE IF EXISTS `cnt_publish_times_pbt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_publish_times_pbt` (
  `id_pbt` int(11) NOT NULL AUTO_INCREMENT,
  `id_cnt_pbt` int(11) NOT NULL,
  `id_usr_pbt` int(11) NOT NULL,
  `start_time_pbt` datetime NOT NULL,
  `end_time_pbt` datetime NOT NULL,
  `name_pbt` varchar(45) DEFAULT 'Undefined',
  `description_pbt` varchar(255) DEFAULT NULL,
  `created_pbt` datetime DEFAULT NULL,
  `modified_pbt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pbt`),
  KEY `fk_cnt_pbt` (`id_cnt_pbt`),
  KEY `fk_usr_pbt` (`id_usr_pbt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_publish_times_pbt`
--

LOCK TABLES `cnt_publish_times_pbt` WRITE;
/*!40000 ALTER TABLE `cnt_publish_times_pbt` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_publish_times_pbt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_views_vws`
--

DROP TABLE IF EXISTS `cnt_views_vws`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_views_vws` (
  `id_cnt_vws` int(11) NOT NULL,
  `id_usr_vws` int(11) NOT NULL,
  `views_vws` int(11) DEFAULT '0',
  `modified_vws` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cnt_vws`,`id_usr_vws`),
  KEY `fk_cnt_vws` (`id_cnt_vws`),
  KEY `fk_usr_vws` (`id_usr_vws`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_views_vws`
--

LOCK TABLES `cnt_views_vws` WRITE;
/*!40000 ALTER TABLE `cnt_views_vws` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_views_vws` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment_flags_cmf`
--

DROP TABLE IF EXISTS `comment_flags_cmf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_flags_cmf` (
  `id_cmf` int(11) NOT NULL AUTO_INCREMENT,
  `id_comment_cmf` int(11) NOT NULL,
  `id_user_cmf` int(11) NOT NULL,
  `flag_cmf` varchar(45) NOT NULL,
  `created_cmf` varchar(45) DEFAULT NULL,
  `modified_cmf` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_cmf`),
  KEY `fk_cmt_cmf` (`id_comment_cmf`),
  KEY `fk_usr_cmf` (`id_user_cmf`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment_flags_cmf`
--

LOCK TABLES `comment_flags_cmf` WRITE;
/*!40000 ALTER TABLE `comment_flags_cmf` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment_flags_cmf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments_cmt`
--

DROP TABLE IF EXISTS `comments_cmt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments_cmt` (
  `id_cmt` int(11) NOT NULL AUTO_INCREMENT,
  `id_target_cmt` int(11) NOT NULL,
  `id_usr_cmt` int(11) NOT NULL,
  `id_parent_cmt` int(11) DEFAULT '0',
  `title_cmt` varchar(255) NOT NULL,
  `body_cmt` text NOT NULL,
  `created_cmt` datetime DEFAULT NULL,
  `modified_cmt` datetime DEFAULT NULL,
  `type_cmt` int(11) NOT NULL,
  PRIMARY KEY (`id_cmt`),
  KEY `fk_cmt_cmt` (`id_parent_cmt`),
  KEY `fk_cmt_cnt` (`id_target_cmt`),
  KEY `fk_comments_cmt_users_usr` (`id_usr_cmt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments_cmt`
--

LOCK TABLES `comments_cmt` WRITE;
/*!40000 ALTER TABLE `comments_cmt` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments_cmt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_flags_cfl`
--

DROP TABLE IF EXISTS `content_flags_cfl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_flags_cfl` (
  `id_cfl` int(11) NOT NULL AUTO_INCREMENT,
  `id_content_cfl` int(11) NOT NULL,
  `id_user_cfl` int(11) NOT NULL,
  `flag_cfl` varchar(45) NOT NULL,
  `created_cfl` varchar(45) DEFAULT NULL,
  `modified_cfl` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_cfl`),
  KEY `fk_cnt_cfl` (`id_content_cfl`),
  KEY `fk_usr_cfl` (`id_user_cfl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_flags_cfl`
--

LOCK TABLES `content_flags_cfl` WRITE;
/*!40000 ALTER TABLE `content_flags_cfl` DISABLE KEYS */;
/*!40000 ALTER TABLE `content_flags_cfl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_ratings_crt`
--

DROP TABLE IF EXISTS `content_ratings_crt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_ratings_crt` (
  `id_crt` int(11) NOT NULL AUTO_INCREMENT,
  `id_cnt_crt` int(11) NOT NULL,
  `id_usr_crt` int(11) NOT NULL,
  `rating_crt` int(11) NOT NULL,
  `created_crt` datetime DEFAULT NULL,
  `modified_crt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_crt`),
  KEY `fk_cnt_crt` (`id_cnt_crt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_ratings_crt`
--

LOCK TABLES `content_ratings_crt` WRITE;
/*!40000 ALTER TABLE `content_ratings_crt` DISABLE KEYS */;
/*!40000 ALTER TABLE `content_ratings_crt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_types_cty`
--

DROP TABLE IF EXISTS `content_types_cty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_types_cty` (
  `id_cty` int(11) NOT NULL AUTO_INCREMENT,
  `key_cty` varchar(10) NOT NULL,
  `name_cty` varchar(255) NOT NULL,
  `description_cty` text,
  `created_cty` datetime DEFAULT NULL,
  `modified_cty` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cty`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_types_cty`
--

LOCK TABLES `content_types_cty` WRITE;
/*!40000 ALTER TABLE `content_types_cty` DISABLE KEYS */;
INSERT INTO `content_types_cty` VALUES (1,'finfo','Future info','','2010-08-18 15:35:51','2010-08-18 15:35:51'),(2,'idea','Ideas','','2010-08-18 15:35:51','2010-08-18 15:35:51'),(3,'problem','Problems','','2010-08-18 15:35:51','2010-08-18 15:35:51');
/*!40000 ALTER TABLE `content_types_cty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contents_cnt`
--

DROP TABLE IF EXISTS `contents_cnt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contents_cnt` (
  `id_cnt` int(11) NOT NULL AUTO_INCREMENT,
  `id_cty_cnt` int(11) NOT NULL,
  `title_cnt` varchar(255) NOT NULL,
  `lead_cnt` text NOT NULL,
  `language_cnt` varchar(5) NOT NULL,
  `body_cnt` text NOT NULL,
  `research_question_cnt` varchar(120) DEFAULT NULL,
  `opportunity_cnt` varchar(120) DEFAULT NULL,
  `threat_cnt` varchar(120) DEFAULT NULL,
  `solution_cnt` varchar(120) DEFAULT NULL,
  `references_cnt` text,
  `views_cnt` int(11) DEFAULT '0',
  `published_cnt` tinyint(1) NOT NULL DEFAULT '0',
  `created_cnt` datetime DEFAULT NULL,
  `modified_cnt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cnt`),
  KEY `fk_cty_cnt` (`id_cty_cnt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contents_cnt`
--

LOCK TABLES `contents_cnt` WRITE;
/*!40000 ALTER TABLE `contents_cnt` DISABLE KEYS */;
/*!40000 ALTER TABLE `contents_cnt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries_ctr`
--

DROP TABLE IF EXISTS `countries_ctr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries_ctr` (
  `iso_ctr` char(2) NOT NULL,
  `name_ctr` varchar(80) NOT NULL,
  `printable_name_ctr` varchar(80) NOT NULL,
  `iso3_ctr` char(3) DEFAULT NULL,
  `numcode_ctr` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`iso_ctr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries_ctr`
--

LOCK TABLES `countries_ctr` WRITE;
/*!40000 ALTER TABLE `countries_ctr` DISABLE KEYS */;
INSERT INTO `countries_ctr` VALUES ('AF','AFGHANISTAN','Afghanistan','AFG',4),('AL','ALBANIA','Albania','ALB',8),('DZ','ALGERIA','Algeria','DZA',12),('AS','AMERICAN SAMOA','American Samoa','ASM',16),('AD','ANDORRA','Andorra','AND',20),('AO','ANGOLA','Angola','AGO',24),('AI','ANGUILLA','Anguilla','AIA',660),('AQ','ANTARCTICA','Antarctica',NULL,NULL),('AG','ANTIGUA AND BARBUDA','Antigua and Barbuda','ATG',28),('AR','ARGENTINA','Argentina','ARG',32),('AM','ARMENIA','Armenia','ARM',51),('AW','ARUBA','Aruba','ABW',533),('AU','AUSTRALIA','Australia','AUS',36),('AT','AUSTRIA','Austria','AUT',40),('AZ','AZERBAIJAN','Azerbaijan','AZE',31),('BS','BAHAMAS','Bahamas','BHS',44),('BH','BAHRAIN','Bahrain','BHR',48),('BD','BANGLADESH','Bangladesh','BGD',50),('BB','BARBADOS','Barbados','BRB',52),('BY','BELARUS','Belarus','BLR',112),('BE','BELGIUM','Belgium','BEL',56),('BZ','BELIZE','Belize','BLZ',84),('BJ','BENIN','Benin','BEN',204),('BM','BERMUDA','Bermuda','BMU',60),('BT','BHUTAN','Bhutan','BTN',64),('BO','BOLIVIA','Bolivia','BOL',68),('BA','BOSNIA AND HERZEGOVINA','Bosnia and Herzegovina','BIH',70),('BW','BOTSWANA','Botswana','BWA',72),('BV','BOUVET ISLAND','Bouvet Island',NULL,NULL),('BR','BRAZIL','Brazil','BRA',76),('IO','BRITISH INDIAN OCEAN TERRITORY','British Indian Ocean Territory',NULL,NULL),('BN','BRUNEI DARUSSALAM','Brunei Darussalam','BRN',96),('BG','BULGARIA','Bulgaria','BGR',100),('BF','BURKINA FASO','Burkina Faso','BFA',854),('BI','BURUNDI','Burundi','BDI',108),('KH','CAMBODIA','Cambodia','KHM',116),('CM','CAMEROON','Cameroon','CMR',120),('CA','CANADA','Canada','CAN',124),('CV','CAPE VERDE','Cape Verde','CPV',132),('KY','CAYMAN ISLANDS','Cayman Islands','CYM',136),('CF','CENTRAL AFRICAN REPUBLIC','Central African Republic','CAF',140),('TD','CHAD','Chad','TCD',148),('CL','CHILE','Chile','CHL',152),('CN','CHINA','China','CHN',156),('CX','CHRISTMAS ISLAND','Christmas Island',NULL,NULL),('CC','COCOS (KEELING) ISLANDS','Cocos (Keeling) Islands',NULL,NULL),('CO','COLOMBIA','Colombia','COL',170),('KM','COMOROS','Comoros','COM',174),('CG','CONGO','Congo','COG',178),('CD','CONGO, THE DEMOCRATIC REPUBLIC OF THE','Congo, the Democratic Republic of the','COD',180),('CK','COOK ISLANDS','Cook Islands','COK',184),('CR','COSTA RICA','Costa Rica','CRI',188),('CI','COTE D\'IVOIRE','Cote D\'Ivoire','CIV',384),('HR','CROATIA','Croatia','HRV',191),('CU','CUBA','Cuba','CUB',192),('CY','CYPRUS','Cyprus','CYP',196),('CZ','CZECH REPUBLIC','Czech Republic','CZE',203),('DK','DENMARK','Denmark','DNK',208),('DJ','DJIBOUTI','Djibouti','DJI',262),('DM','DOMINICA','Dominica','DMA',212),('DO','DOMINICAN REPUBLIC','Dominican Republic','DOM',214),('EC','ECUADOR','Ecuador','ECU',218),('EG','EGYPT','Egypt','EGY',818),('SV','EL SALVADOR','El Salvador','SLV',222),('GQ','EQUATORIAL GUINEA','Equatorial Guinea','GNQ',226),('ER','ERITREA','Eritrea','ERI',232),('EE','ESTONIA','Estonia','EST',233),('ET','ETHIOPIA','Ethiopia','ETH',231),('FK','FALKLAND ISLANDS (MALVINAS)','Falkland Islands (Malvinas)','FLK',238),('FO','FAROE ISLANDS','Faroe Islands','FRO',234),('FJ','FIJI','Fiji','FJI',242),('FI','FINLAND','Finland','FIN',246),('FR','FRANCE','France','FRA',250),('GF','FRENCH GUIANA','French Guiana','GUF',254),('PF','FRENCH POLYNESIA','French Polynesia','PYF',258),('TF','FRENCH SOUTHERN TERRITORIES','French Southern Territories',NULL,NULL),('GA','GABON','Gabon','GAB',266),('GM','GAMBIA','Gambia','GMB',270),('GE','GEORGIA','Georgia','GEO',268),('DE','GERMANY','Germany','DEU',276),('GH','GHANA','Ghana','GHA',288),('GI','GIBRALTAR','Gibraltar','GIB',292),('GR','GREECE','Greece','GRC',300),('GL','GREENLAND','Greenland','GRL',304),('GD','GRENADA','Grenada','GRD',308),('GP','GUADELOUPE','Guadeloupe','GLP',312),('GU','GUAM','Guam','GUM',316),('GT','GUATEMALA','Guatemala','GTM',320),('GN','GUINEA','Guinea','GIN',324),('GW','GUINEA-BISSAU','Guinea-Bissau','GNB',624),('GY','GUYANA','Guyana','GUY',328),('HT','HAITI','Haiti','HTI',332),('HM','HEARD ISLAND AND MCDONALD ISLANDS','Heard Island and Mcdonald Islands',NULL,NULL),('VA','HOLY SEE (VATICAN CITY STATE)','Holy See (Vatican City State)','VAT',336),('HN','HONDURAS','Honduras','HND',340),('HK','HONG KONG','Hong Kong','HKG',344),('HU','HUNGARY','Hungary','HUN',348),('IS','ICELAND','Iceland','ISL',352),('IN','INDIA','India','IND',356),('ID','INDONESIA','Indonesia','IDN',360),('IR','IRAN, ISLAMIC REPUBLIC OF','Iran, Islamic Republic of','IRN',364),('IQ','IRAQ','Iraq','IRQ',368),('IE','IRELAND','Ireland','IRL',372),('IL','ISRAEL','Israel','ISR',376),('IT','ITALY','Italy','ITA',380),('JM','JAMAICA','Jamaica','JAM',388),('JP','JAPAN','Japan','JPN',392),('JO','JORDAN','Jordan','JOR',400),('KZ','KAZAKHSTAN','Kazakhstan','KAZ',398),('KE','KENYA','Kenya','KEN',404),('KI','KIRIBATI','Kiribati','KIR',296),('KP','KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF','Korea, Democratic People\'s Republic of','PRK',408),('KR','KOREA, REPUBLIC OF','Korea, Republic of','KOR',410),('KW','KUWAIT','Kuwait','KWT',414),('KG','KYRGYZSTAN','Kyrgyzstan','KGZ',417),('LA','LAO PEOPLE\'S DEMOCRATIC REPUBLIC','Lao People\'s Democratic Republic','LAO',418),('LV','LATVIA','Latvia','LVA',428),('LB','LEBANON','Lebanon','LBN',422),('LS','LESOTHO','Lesotho','LSO',426),('LR','LIBERIA','Liberia','LBR',430),('LY','LIBYAN ARAB JAMAHIRIYA','Libyan Arab Jamahiriya','LBY',434),('LI','LIECHTENSTEIN','Liechtenstein','LIE',438),('LT','LITHUANIA','Lithuania','LTU',440),('LU','LUXEMBOURG','Luxembourg','LUX',442),('MO','MACAO','Macao','MAC',446),('MK','MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','Macedonia, the Former Yugoslav Republic of','MKD',807),('MG','MADAGASCAR','Madagascar','MDG',450),('MW','MALAWI','Malawi','MWI',454),('MY','MALAYSIA','Malaysia','MYS',458),('MV','MALDIVES','Maldives','MDV',462),('ML','MALI','Mali','MLI',466),('MT','MALTA','Malta','MLT',470),('MH','MARSHALL ISLANDS','Marshall Islands','MHL',584),('MQ','MARTINIQUE','Martinique','MTQ',474),('MR','MAURITANIA','Mauritania','MRT',478),('MU','MAURITIUS','Mauritius','MUS',480),('YT','MAYOTTE','Mayotte',NULL,NULL),('MX','MEXICO','Mexico','MEX',484),('FM','MICRONESIA, FEDERATED STATES OF','Micronesia, Federated States of','FSM',583),('MD','MOLDOVA, REPUBLIC OF','Moldova, Republic of','MDA',498),('MC','MONACO','Monaco','MCO',492),('MN','MONGOLIA','Mongolia','MNG',496),('MS','MONTSERRAT','Montserrat','MSR',500),('MA','MOROCCO','Morocco','MAR',504),('MZ','MOZAMBIQUE','Mozambique','MOZ',508),('MM','MYANMAR','Myanmar','MMR',104),('NA','NAMIBIA','Namibia','NAM',516),('NR','NAURU','Nauru','NRU',520),('NP','NEPAL','Nepal','NPL',524),('NL','NETHERLANDS','Netherlands','NLD',528),('AN','NETHERLANDS ANTILLES','Netherlands Antilles','ANT',530),('NC','NEW CALEDONIA','New Caledonia','NCL',540),('NZ','NEW ZEALAND','New Zealand','NZL',554),('NI','NICARAGUA','Nicaragua','NIC',558),('NE','NIGER','Niger','NER',562),('NG','NIGERIA','Nigeria','NGA',566),('NU','NIUE','Niue','NIU',570),('NF','NORFOLK ISLAND','Norfolk Island','NFK',574),('MP','NORTHERN MARIANA ISLANDS','Northern Mariana Islands','MNP',580),('NO','NORWAY','Norway','NOR',578),('OM','OMAN','Oman','OMN',512),('PK','PAKISTAN','Pakistan','PAK',586),('PW','PALAU','Palau','PLW',585),('PS','PALESTINIAN TERRITORY, OCCUPIED','Palestinian Territory, Occupied',NULL,NULL),('PA','PANAMA','Panama','PAN',591),('PG','PAPUA NEW GUINEA','Papua New Guinea','PNG',598),('PY','PARAGUAY','Paraguay','PRY',600),('PE','PERU','Peru','PER',604),('PH','PHILIPPINES','Philippines','PHL',608),('PN','PITCAIRN','Pitcairn','PCN',612),('PL','POLAND','Poland','POL',616),('PT','PORTUGAL','Portugal','PRT',620),('PR','PUERTO RICO','Puerto Rico','PRI',630),('QA','QATAR','Qatar','QAT',634),('RE','REUNION','Reunion','REU',638),('RO','ROMANIA','Romania','ROM',642),('RU','RUSSIAN FEDERATION','Russian Federation','RUS',643),('RW','RWANDA','Rwanda','RWA',646),('SH','SAINT HELENA','Saint Helena','SHN',654),('KN','SAINT KITTS AND NEVIS','Saint Kitts and Nevis','KNA',659),('LC','SAINT LUCIA','Saint Lucia','LCA',662),('PM','SAINT PIERRE AND MIQUELON','Saint Pierre and Miquelon','SPM',666),('VC','SAINT VINCENT AND THE GRENADINES','Saint Vincent and the Grenadines','VCT',670),('WS','SAMOA','Samoa','WSM',882),('SM','SAN MARINO','San Marino','SMR',674),('ST','SAO TOME AND PRINCIPE','Sao Tome and Principe','STP',678),('SA','SAUDI ARABIA','Saudi Arabia','SAU',682),('SN','SENEGAL','Senegal','SEN',686),('CS','SERBIA AND MONTENEGRO','Serbia and Montenegro',NULL,NULL),('SC','SEYCHELLES','Seychelles','SYC',690),('SL','SIERRA LEONE','Sierra Leone','SLE',694),('SG','SINGAPORE','Singapore','SGP',702),('SK','SLOVAKIA','Slovakia','SVK',703),('SI','SLOVENIA','Slovenia','SVN',705),('SB','SOLOMON ISLANDS','Solomon Islands','SLB',90),('SO','SOMALIA','Somalia','SOM',706),('ZA','SOUTH AFRICA','South Africa','ZAF',710),('GS','SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','South Georgia and the South Sandwich Islands',NULL,NULL),('ES','SPAIN','Spain','ESP',724),('LK','SRI LANKA','Sri Lanka','LKA',144),('SD','SUDAN','Sudan','SDN',736),('SR','SURINAME','Suriname','SUR',740),('SJ','SVALBARD AND JAN MAYEN','Svalbard and Jan Mayen','SJM',744),('SZ','SWAZILAND','Swaziland','SWZ',748),('SE','SWEDEN','Sweden','SWE',752),('CH','SWITZERLAND','Switzerland','CHE',756),('SY','SYRIAN ARAB REPUBLIC','Syrian Arab Republic','SYR',760),('TW','TAIWAN, PROVINCE OF CHINA','Taiwan, Province of China','TWN',158),('TJ','TAJIKISTAN','Tajikistan','TJK',762),('TZ','TANZANIA, UNITED REPUBLIC OF','Tanzania, United Republic of','TZA',834),('TH','THAILAND','Thailand','THA',764),('TL','TIMOR-LESTE','Timor-Leste',NULL,NULL),('TG','TOGO','Togo','TGO',768),('TK','TOKELAU','Tokelau','TKL',772),('TO','TONGA','Tonga','TON',776),('TT','TRINIDAD AND TOBAGO','Trinidad and Tobago','TTO',780),('TN','TUNISIA','Tunisia','TUN',788),('TR','TURKEY','Turkey','TUR',792),('TM','TURKMENISTAN','Turkmenistan','TKM',795),('TC','TURKS AND CAICOS ISLANDS','Turks and Caicos Islands','TCA',796),('TV','TUVALU','Tuvalu','TUV',798),('UG','UGANDA','Uganda','UGA',800),('UA','UKRAINE','Ukraine','UKR',804),('AE','UNITED ARAB EMIRATES','United Arab Emirates','ARE',784),('GB','UNITED KINGDOM','United Kingdom','GBR',826),('US','UNITED STATES','United States','USA',840),('UM','UNITED STATES MINOR OUTLYING ISLANDS','United States Minor Outlying Islands',NULL,NULL),('UY','URUGUAY','Uruguay','URY',858),('UZ','UZBEKISTAN','Uzbekistan','UZB',860),('VU','VANUATU','Vanuatu','VUT',548),('VE','VENEZUELA','Venezuela','VEN',862),('VN','VIET NAM','Viet Nam','VNM',704),('VG','VIRGIN ISLANDS, BRITISH','Virgin Islands, British','VGB',92),('VI','VIRGIN ISLANDS, U.S.','Virgin Islands, U.s.','VIR',850),('WF','WALLIS AND FUTUNA','Wallis and Futuna','WLF',876),('EH','WESTERN SAHARA','Western Sahara','ESH',732),('YE','YEMEN','Yemen','YEM',887),('ZM','ZAMBIA','Zambia','ZMB',894),('ZW','ZIMBABWE','Zimbabwe','ZWE',716);
/*!40000 ALTER TABLE `countries_ctr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files_fil`
--

DROP TABLE IF EXISTS `files_fil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files_fil` (
  `id_fil` int(11) NOT NULL AUTO_INCREMENT,
  `id_cnt_fil` int(11) NOT NULL,
  `id_usr_fil` int(11) NOT NULL,
  `filename_fil` varchar(255) NOT NULL,
  `filetype_fil` varchar(255) NOT NULL,
  `hash_fil` varchar(50) NOT NULL,
  `created_fil` datetime DEFAULT NULL,
  `modified_fil` datetime DEFAULT NULL,
  PRIMARY KEY (`id_fil`),
  KEY `fk_cnt_fil` (`id_cnt_fil`),
  KEY `fk_usr_fil` (`id_usr_fil`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files_fil`
--

LOCK TABLES `files_fil` WRITE;
/*!40000 ALTER TABLE `files_fil` DISABLE KEYS */;
/*!40000 ALTER TABLE `files_fil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files_fil_old`
--

DROP TABLE IF EXISTS `files_fil_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files_fil_old` (
  `id_fil` int(11) NOT NULL AUTO_INCREMENT,
  `id_cnt_fil` int(11) NOT NULL,
  `id_usr_fil` int(11) NOT NULL,
  `filename_fil` varchar(255) NOT NULL,
  `filesize_fil` int(11) NOT NULL,
  `data_fil` longblob NOT NULL,
  `filetype_fil` varchar(255) NOT NULL,
  `created_fil` datetime DEFAULT NULL,
  `modified_fil` datetime DEFAULT NULL,
  PRIMARY KEY (`id_fil`),
  KEY `fk_cnt_fil` (`id_cnt_fil`),
  KEY `fk_usr_fil` (`id_usr_fil`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files_fil_old`
--

LOCK TABLES `files_fil_old` WRITE;
/*!40000 ALTER TABLE `files_fil_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `files_fil_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `futureinfo_classes_fic`
--

DROP TABLE IF EXISTS `futureinfo_classes_fic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `futureinfo_classes_fic` (
  `id_fic` int(11) NOT NULL AUTO_INCREMENT,
  `name_fic` varchar(255) NOT NULL,
  `description_fic` varchar(512) DEFAULT NULL,
  `created_fic` datetime DEFAULT NULL,
  `modified_fic` datetime DEFAULT NULL,
  PRIMARY KEY (`id_fic`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `futureinfo_classes_fic`
--

LOCK TABLES `futureinfo_classes_fic` WRITE;
/*!40000 ALTER TABLE `futureinfo_classes_fic` DISABLE KEYS */;
INSERT INTO `futureinfo_classes_fic` VALUES (1,'Trends and anti-trends','','2010-08-18 15:35:51','2010-08-18 15:35:51'),(2,'Expected future scenarios','','2010-08-18 15:35:51','2010-08-18 15:35:51'),(3,'Emerging weak signals and seeds of change','','2010-08-18 15:35:51','2010-08-18 15:35:51');
/*!40000 ALTER TABLE `futureinfo_classes_fic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_types_gtp`
--

DROP TABLE IF EXISTS `group_types_gtp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_types_gtp` (
  `id_gtp` int(11) NOT NULL AUTO_INCREMENT,
  `key_gtp` varchar(15) NOT NULL,
  `name_gtp` varchar(255) NOT NULL,
  PRIMARY KEY (`id_gtp`),
  UNIQUE KEY `id_gtp` (`id_gtp`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_types_gtp`
--

LOCK TABLES `group_types_gtp` WRITE;
/*!40000 ALTER TABLE `group_types_gtp` DISABLE KEYS */;
INSERT INTO `group_types_gtp` VALUES (1,'open_grp','Open group'),(2,'closed_grp','Closed group');
/*!40000 ALTER TABLE `group_types_gtp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_has_admin_usr`
--

DROP TABLE IF EXISTS `grp_has_admin_usr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grp_has_admin_usr` (
  `id_usr` int(11) NOT NULL,
  `id_grp` int(11) NOT NULL,
  PRIMARY KEY (`id_usr`,`id_grp`),
  KEY `fk_grp_has_admin_usr_grp` (`id_grp`),
  KEY `fk_grp_has_admin_usr_usr` (`id_usr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grp_has_admin_usr`
--

LOCK TABLES `grp_has_admin_usr` WRITE;
/*!40000 ALTER TABLE `grp_has_admin_usr` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_has_admin_usr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_has_grp`
--

DROP TABLE IF EXISTS `grp_has_grp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grp_has_grp` (
  `id_parent_grp` int(11) NOT NULL,
  `id_child_grp` int(11) NOT NULL,
  PRIMARY KEY (`id_parent_grp`,`id_child_grp`),
  KEY `fk_grp_has_grp_parent_grp` (`id_parent_grp`),
  KEY `fk_grp_has_grp_child_grp` (`id_child_grp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grp_has_grp`
--

LOCK TABLES `grp_has_grp` WRITE;
/*!40000 ALTER TABLE `grp_has_grp` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_has_grp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_has_prm`
--

DROP TABLE IF EXISTS `grp_has_prm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grp_has_prm` (
  `id_grp` int(11) NOT NULL,
  `id_prm` int(11) NOT NULL,
  PRIMARY KEY (`id_grp`,`id_prm`),
  KEY `fk_grp_has_prm_grp` (`id_grp`),
  KEY `fk_grp_has_prm_prm` (`id_prm`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grp_has_prm`
--

LOCK TABLES `grp_has_prm` WRITE;
/*!40000 ALTER TABLE `grp_has_prm` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_has_prm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_has_tag`
--

DROP TABLE IF EXISTS `grp_has_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grp_has_tag` (
  `id_grp` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  PRIMARY KEY (`id_grp`,`id_tag`),
  KEY `fk_grp` (`id_grp`),
  KEY `fk_tag` (`id_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grp_has_tag`
--

LOCK TABLES `grp_has_tag` WRITE;
/*!40000 ALTER TABLE `grp_has_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_has_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_weblinks_gwl`
--

DROP TABLE IF EXISTS `grp_weblinks_gwl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grp_weblinks_gwl` (
  `id_gwl` int(11) NOT NULL AUTO_INCREMENT,
  `id_grp_gwl` int(11) NOT NULL,
  `name_gwl` varchar(45) NOT NULL,
  `url_gwl` varchar(150) NOT NULL,
  `count_gwl` int(11) NOT NULL,
  `created_gwl` datetime DEFAULT NULL,
  `modified_gwl` datetime DEFAULT NULL,
  PRIMARY KEY (`id_gwl`),
  KEY `fk_grp_gwl` (`id_grp_gwl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grp_weblinks_gwl`
--

LOCK TABLES `grp_weblinks_gwl` WRITE;
/*!40000 ALTER TABLE `grp_weblinks_gwl` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_weblinks_gwl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `industries_ind`
--

DROP TABLE IF EXISTS `industries_ind`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `industries_ind` (
  `id_ind` int(11) NOT NULL AUTO_INCREMENT,
  `id_lng_ind` int(11) NOT NULL,
  `id_parent_ind` int(11) DEFAULT '0',
  `name_ind` varchar(255) NOT NULL,
  `description_ind` varchar(512) DEFAULT NULL,
  `created_ind` datetime DEFAULT NULL,
  `modified_ind` datetime DEFAULT NULL,
  PRIMARY KEY (`id_ind`),
  KEY `fk_lng_ind` (`id_lng_ind`),
  KEY `fk_parent_ind_ind` (`id_parent_ind`)
) ENGINE=MyISAM AUTO_INCREMENT=1993 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `industries_ind`
--

LOCK TABLES `industries_ind` WRITE;
/*!40000 ALTER TABLE `industries_ind` DISABLE KEYS */;
INSERT INTO `industries_ind` VALUES (1,38,0,'Agriculture, foresty and fishing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(2,38,1,'Crop and animal production, hunting and related service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(3,38,2,'Growing of non-perennial crops',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(4,38,3,'Growing of cereals (except rice), leguminous crops and oil seeds',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(5,38,3,'Growing of rice',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(6,38,3,'Growing of vegetables and melons, roots and tubers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(7,38,3,'Growing of sugar cane',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(8,38,3,'Growing of tobacco',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(9,38,3,'Growing of fibre crops',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(10,38,3,'Growing of other non-perennial crops',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(11,38,2,'Growing of perennial crops',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(12,38,11,'Growing of grapes',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(13,38,11,'Growing of tropical and subtropical fruits',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(14,38,11,'Growing of citrus fruits',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(15,38,11,'Growing of pome fruits and stone fruits',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(16,38,11,'Growing of other tree and bush fruits and nuts',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(17,38,11,'Growing of oleaginous fruits',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(18,38,11,'Growing of beverage crops',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(19,38,11,'Growing of spices, aromatic, drug and pharmaceutical crops',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(20,38,11,'Growing of other perennial crops',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(21,38,2,'Plant propagation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(22,38,21,'Plant propagation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(23,38,2,'Animal production',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(24,38,23,'Raising of dairy cattle',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(25,38,23,'Raising of other cattle and buffaloes',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(26,38,23,'Raising of horses and other equines',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(27,38,23,'Raising of camels and camelids',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(28,38,23,'Raising of sheep and goats',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(29,38,23,'Raising of swine/pigs',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(30,38,23,'Raising of poultry',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(31,38,23,'Raising of other animals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(32,38,2,'Mixed farming',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(33,38,32,'Mixed farming',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(34,38,2,'Support activities to agriculture and post-harvest crop activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(35,38,34,'Support activities for crop production',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(36,38,34,'Support activities for animal production',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(37,38,34,'Post-harvest crop activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(38,38,34,'Seed processing for propagation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(39,38,2,'Hunting, trapping and related service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(40,38,39,'Hunting, trapping and related service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(41,38,1,'Forestry and logging',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(42,38,41,'Silviculture and other forestry activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(43,38,42,'Silviculture and other forestry activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(44,38,41,'Logging',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(45,38,45,'Logging',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(46,38,41,'Gathering of wild growing non-wood products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(47,38,46,'Gathering of wild growing non-wood products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(48,38,41,'Support services to forestry',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(49,38,48,'Support services to forestry',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(50,38,1,'Fishing and aquaculture',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(51,38,50,'Fishing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(52,38,51,'Marine fishing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(53,38,51,'Freshwater fishing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(54,38,50,'Aquaculture',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(55,38,54,'Marine aquaculture',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(56,38,54,'Freshwater aquaculture',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(57,38,0,'Mining and quarrying',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(58,38,57,'Mining of coal and lignite',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(59,38,58,'Mining of hard coal',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(60,38,59,'Mining of hard coal',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(61,38,58,'Mining of lignite',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(62,38,61,'Mining of lignite',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(63,38,57,'Extraction of crude petroleum and natural gas',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(64,38,63,'Extraction of crude petroleum',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(65,38,64,'Extraction of crude petroleum',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(66,38,63,'Extraction of natural gas',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(67,38,66,'Extraction of natural gas',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(68,38,57,'Mining of metal ores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(69,38,68,'Mining of iron ores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(70,38,69,'Mining of iron ores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(71,38,68,'Mining of non-ferrous metal ores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(72,38,71,'Mining of uranium and thorium ores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(73,38,71,'Mining of other non-ferrous metal ores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(74,38,57,'Other mining and quarrying',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(75,38,74,'Quarrying of stone, sand and clay',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(76,38,75,'Quarrying of ornamental and building stone, limestone, gypsum, chalk and slate',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(77,38,75,'Operation of gravel and sand pits; mining of clays and kaolin',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(78,38,74,'Mining and quarrying',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(79,38,78,'Mining of chemical and fertiliser minerals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(80,38,78,'Extraction of peat',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(81,38,78,'Extraction of salt',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(82,38,78,'Other mining and quarrying',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(83,38,57,'Mining support service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(84,38,83,'Support activities for petroleum and natural gas extraction',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(85,38,84,'Support activities for petroleum and natural gas extraction',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(86,38,83,'Support activities for other mining and quarrying',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(87,38,86,'Support activities for other mining and quarrying',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(88,38,0,'Manufacturing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(89,38,88,'Manufacture of food products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(90,38,89,'Processing and preserving of meat and production of meat products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(91,38,90,'Processing and preserving of meat',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(92,38,90,'Processing and preserving of poultry meat',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(93,38,90,'Production of meat and poultry meat products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(94,38,89,'Processing and preserving of fish, crustaceans and molluscs',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(95,38,94,'Processing and preserving of fish, crustaceans and molluscs',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(96,38,89,'Processing and preserving of fruit and vegetables',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(97,38,96,'Processing and preserving of potatoes',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(98,38,96,'Manufacture of fruit and vegetable juice',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(99,38,96,'Other processing and preserving of fruit and vegetables',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(100,38,89,'Manufacture of vegetable and animal oils and fats',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(101,38,100,'Manufacture of oils and fats',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(102,38,100,'Manufacture of margarine and similar edible fats',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(103,38,89,'Manufacture of dairy products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(104,38,103,'Operation of dairies and cheese making',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(105,38,103,'Manufacture of ice cream',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(106,38,89,'Manufacture of grain mill products, starches and starch products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(107,38,106,'Manufacture of grain mill products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(108,38,106,'Manufacture of starches and starch products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(109,38,89,'Manufacture of bakery and farinaceous products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(110,38,109,'Manufacture of bread; manufacture of fresh pastry goods and cakes',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(111,38,109,'Manufacture of rusks and biscuits; manufacture of preserved pastry goods and cakes',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(112,38,109,'Manufacture of macaroni, noodles, couscous and similar farinaceous products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(113,38,89,'Manufacture of other food products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(114,38,113,'Manufacture of sugar',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(115,38,113,'Manufacture of cocoa, chocolate and sugar confectionery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(116,38,113,'Processing of tea and coffee',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(117,38,113,'Manufacture of condiments and seasonings',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(118,38,113,'Manufacture of prepared meals and dishes',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(119,38,113,'Manufacture of homogenised food preparations and dietetic food',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(120,38,113,'Manufacture of other food products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(121,38,89,'Manufacture of prepared animal feeds',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(122,38,121,'Manufacture of prepared feeds for farm animals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(123,38,121,'Manufacture of prepared pet foods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(124,38,88,'Manufacture of beverages',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(125,38,124,'Manufacture of beverages',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(126,38,125,'Distilling, rectifying and blending of spirits',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(127,38,125,'Manufacture of wine from grape',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(128,38,125,'Manufacture of cider and other fruit wines',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(129,38,125,'Manufacture of other non-distilled fermented beverages',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(130,38,125,'Manufacture of beer',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(131,38,125,'Manufacture of malt',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(132,38,125,'Manufacture of soft drinks; production of mineral waters and other bottled waters',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(133,38,88,'Manufacture of tobacco products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(134,38,133,'Manufacture of tobacco products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(135,38,134,'Manufacture of tobacco products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(136,38,88,'Manufacture of textiles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(137,38,136,'Preparation and spinning of textile fibres',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(138,38,137,'Preparation and spinning of textile fibres',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(139,38,136,'Weaving of textiles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(140,38,139,'Weaving of textiles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(141,38,136,'Finishing of textiles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(142,38,141,'Finishing of textiles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(143,38,136,'Manufacture of other textiles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(144,38,143,'Manufacture of knitted and crocheted fabrics',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(145,38,143,'Manufacture of made-up textile articles, except apparel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(146,38,143,'Manufacture of carpets and rugs',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(147,38,143,'Manufacture of cordage, rope, twine and netting',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(148,38,143,'Manufacture of non-wovens and articles made from non-wovens, except apparel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(149,38,143,'Manufacture of other technical and industrial textiles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(150,38,143,'Manufacture of other textiles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(151,38,88,'Manufacture of wearing apparel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(152,38,151,'Manufacture of wearing apparel, except fur apparel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(153,38,152,'Manufacture of leather clothes',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(154,38,152,'Manufacture of workwear',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(155,38,152,'Manufacture of other outerwear',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(156,38,152,'Manufacture of underwear',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(157,38,152,'Manufacture of other wearing apparel and accessories',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(158,38,151,'Manufacture of articles of fur',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(159,38,158,'Manufacture of articles of fur',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(160,38,151,'Manufacture of knitted and crocheted apparel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(161,38,160,'Manufacture of knitted and crocheted hosiery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(162,38,160,'Manufacture of other knitted and crocheted apparel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(163,38,88,'Manufacture of leather and related products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(164,38,163,'Tanning and dressing of leather; manufacture of luggage, handbags, saddlery and harness; dressing and dyeing of fur',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(165,38,164,'Tanning and dressing of leather; dressing and dyeing of fur',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(166,38,164,'Manufacture of luggage, handbags and the like, saddlery and harness',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(167,38,163,'Manufacture of footwear',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(168,38,167,'Manufacture of footwear',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(169,38,88,'Manufacture of wood and of products of wood and cork, except furniture; manufacture of articles of straw and plaiting materials',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(170,38,169,'Sawmilling and planing of wood',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(171,38,170,'Sawmilling and planing of wood',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(172,38,169,'Manufacture of products of wood, cork, straw and plaiting materials',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(173,38,172,'Manufacture of veneer sheets and wood-based panels',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(174,38,172,'Manufacture of assembled parquet floors',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(175,38,172,'Manufacture of other builders\' carpentry and joinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(176,38,172,'Manufacture of wooden containers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(177,38,172,'Manufacture of other products of wood; manufacture of articles of cork, straw and plaiting materials',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(178,38,88,'Manufacture of paper and paper products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(179,38,178,'Manufacture of pulp, paper and paperboard',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(180,38,179,'Manufacture of pulp',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(181,38,179,'Manufacture of paper and paperboard',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(182,38,178,'Manufacture of articles of paper and paperboard',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(183,38,182,'Manufacture of corrugated paper and paperboard and of containers of paper and paperboard',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(184,38,182,'Manufacture of household and sanitary goods and of toilet requisites',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(185,38,182,'Manufacture of paper stationery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(186,38,182,'Manufacture of wallpaper',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(187,38,182,'Manufacture of other articles of paper and paperboard',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(188,38,88,'Printing and reproduction of recorded media',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(189,38,188,'Printing and service activities related to printing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(190,38,189,'Printing of newspapers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(191,38,189,'Other printing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(192,38,189,'Pre-press and pre-media services',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(193,38,189,'Binding and related services',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(194,38,188,'Reproduction of recorded media',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(195,38,194,'Reproduction of recorded media',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(196,38,88,'Manufacture of coke and refined petroleum products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(197,38,196,'Manufacture of coke oven products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(198,38,197,'Manufacture of coke oven products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(199,38,196,'Manufacture of refined petroleum products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(200,38,199,'Manufacture of refined petroleum products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(201,38,88,'Manufacture of chemicals and chemical products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(202,38,201,'Manufacture of basic chemicals, fertilisers and nitrogen compounds, plastics and synthetic rubber in primary forms',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(203,38,202,'Manufacture of industrial gases',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(204,38,202,'Manufacture of dyes and pigments',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(205,38,202,'Manufacture of other inorganic basic chemicals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(206,38,202,'Manufacture of other organic basic chemicals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(207,38,202,'Manufacture of fertilisers and nitrogen compounds',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(208,38,202,'Manufacture of plastics in primary forms',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(209,38,202,'Manufacture of synthetic rubber in primary forms',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(210,38,201,'Manufacture of pesticides and other agrochemical products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(211,38,210,'Manufacture of pesticides and other agrochemical products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(212,38,201,'Manufacture of paints, varnishes and similar coatings, printing ink and mastics',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(213,38,212,'Manufacture of paints, varnishes and similar coatings, printing ink and mastics',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(214,38,201,'Manufacture of soap and detergents, cleaning and polishing preparations, perfumes and toilet preparations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(215,38,214,'Manufacture of soap and detergents, cleaning and polishing preparations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(216,38,214,'Manufacture of perfumes and toilet preparations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(217,38,201,'Manufacture of other chemical products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(218,38,217,'Manufacture of explosives',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(219,38,217,'Manufacture of glues',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(220,38,217,'Manufacture of essential oils',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(221,38,217,'Manufacture of other chemical products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(222,38,201,'Manufacture of man-made fibres',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(223,38,222,'Manufacture of man-made fibres',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(224,38,88,'Manufacture of basic pharmaceutical products and pharmaceutical preparations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(225,38,224,'Manufacture of basic pharmaceutical products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(226,38,225,'Manufacture of basic pharmaceutical products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(227,38,224,'Manufacture of pharmaceutical preparations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(228,38,227,'Manufacture of pharmaceutical preparations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(229,38,88,'Manufacture of rubber and plastic products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(230,38,229,'Manufacture of rubber products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(231,38,230,'Manufacture of rubber tyres and tubes; retreading and rebuilding of rubber tyres',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(232,38,230,'Manufacture of other rubber products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(233,38,229,'Manufacture of plastics products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(234,38,233,'Manufacture of plastic plates, sheets, tubes and profiles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(235,38,233,'Manufacture of plastic packing goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(236,38,233,'Manufacture of builders\' ware of plastic',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(237,38,233,'Manufacture of other plastic products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(238,38,88,'Manufacture of other non-metallic mineral products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(239,38,238,'Manufacture of glass and glass products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(240,38,239,'Manufacture of flat glass',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(241,38,239,'Shaping and processing of flat glass',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(242,38,239,'Manufacture of hollow glass',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(243,38,239,'Manufacture of glass fibres',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(244,38,239,'Manufacture and processing of other glass, including technical glassware',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(245,38,238,'Manufacture of refractory products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(246,38,245,'Manufacture of refractory products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(247,38,238,'Manufacture of clay building materials',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(248,38,247,'Manufacture of ceramic tiles and flags',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(249,38,247,'Manufacture of bricks, tiles and construction products, in baked clay',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(250,38,238,'Manufacture of other porcelain and ceramic products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(251,38,250,'Manufacture of ceramic household and ornamental articles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(252,38,250,'Manufacture of ceramic sanitary fixtures',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(253,38,250,'Manufacture of ceramic insulators and insulating fittings',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(254,38,250,'Manufacture of other technical ceramic products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(255,38,250,'Manufacture of other ceramic products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(256,38,238,'Manufacture of cement, lime and plaster',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(257,38,256,'Manufacture of cement',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(258,38,256,'Manufacture of lime and plaster',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(259,38,256,'Manufacture of articles of concrete, cement and plaster',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(260,38,256,'Manufacture of concrete products for construction purposes',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(261,38,256,'Manufacture of plaster products for construction purposes',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(262,38,256,'Manufacture of ready-mixed concrete',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(263,38,256,'Manufacture of mortars',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(264,38,256,'Manufacture of fibre cement',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(265,38,256,'Manufacture of other articles of concrete, plaster and cement',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(266,38,238,'Cutting, shaping and finishing of stone',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(267,38,266,'Cutting, shaping and finishing of stone',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(268,38,238,'Manufacture of abrasive products and non-metallic mineral products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(269,38,268,'Production of abrasive products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(270,38,268,'Manufacture of other non-metallic mineral products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(271,38,88,'Manufacture of basic metals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(272,38,271,'Manufacture of basic iron and steel and of ferro-alloys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(273,38,272,'Manufacture of basic iron and steel and of ferro-alloys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(274,38,271,'Manufacture of tubes, pipes, hollow profiles and related fittings, of steel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(275,38,274,'Manufacture of tubes, pipes, hollow profiles and related fittings, of steel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(276,38,271,'Manufacture of other products of first processing of steel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(277,38,276,'Cold drawing of bars',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(278,38,276,'Cold rolling of narrow strip',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(279,38,276,'Cold forming or folding',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(280,38,276,'Cold drawing of wire',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(281,38,271,'Manufacture of basic precious and other non-ferrous metals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(282,38,281,'Precious metals production',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(283,38,281,'Aluminium production',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(284,38,281,'Lead, zinc and tin production',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(285,38,281,'Copper production',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(286,38,281,'Other non-ferrous metal production',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(287,38,281,'Processing of nuclear fuel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(288,38,271,'Casting of metals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(289,38,288,'Casting of iron',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(290,38,288,'Casting of steel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(291,38,288,'Casting of light metals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(292,38,288,'Casting of other non-ferrous metals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(293,38,88,'Manufacture of fabricated metal products, except machinery and equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(294,38,293,'Manufacture of structural metal products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(295,38,294,'Manufacture of metal structures and parts of structures',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(296,38,294,'Manufacture of doors and windows of metal',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(297,38,294,'Manufacture of tanks, reservoirs and containers of metal',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(298,38,294,'Manufacture of central heating radiators and boilers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(299,38,294,'Manufacture of other tanks, reservoirs and containers of metal',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(300,38,294,'Manufacture of steam generators, except central heating hot water boilers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(301,38,294,'Manufacture of steam generators, except central heating hot water boilers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(302,38,293,'Manufacture of weapons and ammunition',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(303,38,302,'Manufacture of weapons and ammunition',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(304,38,293,'Forging, pressing, stamping and roll-forming of metal; powder metallurgy',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(305,38,304,'Forging, pressing, stamping and roll-forming of metal; powder metallurgy',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(306,38,293,'Treatment and coating of metals; machining',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(307,38,306,'Treatment and coating of metals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(308,38,306,'Machining',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(309,38,293,'Manufacture of cutlery, tools and general hardware',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(310,38,309,'Manufacture of cutlery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(311,38,309,'Manufacture of locks and hinges',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(312,38,309,'Manufacture of tools',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(313,38,309,'Manufacture of other fabricated metal products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(314,38,309,'Manufacture of steel drums and similar containers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(315,38,309,'Manufacture of light metal packaging',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(316,38,309,'Manufacture of wire products, chain and springs',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(317,38,309,'Manufacture of fasteners and screw machine products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(318,38,309,'Manufacture of other fabricated metal products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(319,38,88,'Manufacture of computer, electronic and optical products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(320,38,319,'Manufacture of electronic components and boards',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(321,38,320,'Manufacture of electronic components',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(322,38,320,'Manufacture of loaded electronic boards',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(323,38,319,'Manufacture of computers and peripheral equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(324,38,323,'Manufacture of computers and peripheral equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(325,38,319,'Manufacture of communication equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(326,38,325,'Manufacture of communication equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(327,38,319,'Manufacture of consumer electronics',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(328,38,327,'Manufacture of consumer electronics',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(329,38,319,'Manufacture of instruments and appliances for measuring, testing and navigation; watches and clocks',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(330,38,329,'Manufacture of instruments and appliances for measuring, testing and navigation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(331,38,329,'Manufacture of watches and clocks',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(332,38,319,'Manufacture of irradiation, electromedical and electrotherapeutic equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(333,38,332,'Manufacture of irradiation, electromedical and electrotherapeutic equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(334,38,332,'Manufacture of optical instruments and photographic equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(335,38,332,'Manufacture of optical instruments and photographic equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(336,38,332,'Manufacture of magnetic and optical media',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(337,38,332,'Manufacture of magnetic and optical media',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(338,38,88,'Manufacture of electrical equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(339,38,338,'Manufacture of electric motors, generators, transformers and electricity distribution and control apparatus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(340,38,339,'Manufacture of electric motors, generators and transformers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(341,38,339,'Manufacture of electricity distribution and control apparatus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(342,38,338,'Manufacture of batteries and accumulators',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(343,38,342,'Manufacture of batteries and accumulators',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(344,38,338,'Manufacture of wiring and wiring devices',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(345,38,344,'Manufacture of fibre optic cables',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(346,38,344,'Manufacture of other electronic and electric wires and cables',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(347,38,344,'Manufacture of wiring devices',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(348,38,338,'Manufacture of electric lighting equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(349,38,348,'Manufacture of electric lighting equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(350,38,338,'Manufacture of domestic appliances',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(351,38,350,'Manufacture of electric domestic appliances',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(352,38,350,'Manufacture of non-electric domestic appliances',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(353,38,338,'Manufacture of other electrical equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(354,38,353,'Manufacture of other electrical equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(355,38,88,'Manufacture of machinery and equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(356,38,355,'Manufacture of general ï¿½ purpose machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(357,38,356,'Manufacture of engines and turbines, except aircraft, vehicle and cycle engines',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(358,38,356,'Manufacture of fluid power equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(359,38,356,'Manufacture of other pumps and compressors',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(360,38,356,'Manufacture of other taps and valves',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(361,38,356,'Manufacture of bearings, gears, gearing and driving elements',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(362,38,355,'Manufacture of other general-purpose machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(363,38,362,'Manufacture of ovens, furnaces and furnace burners',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(364,38,362,'Manufacture of lifting and handling equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(365,38,362,'Manufacture of office machinery and equipment (except computers and peripheral equipment)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(366,38,362,'Manufacture of power-driven hand tools',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(367,38,362,'Manufacture of non-domestic cooling and ventilation equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(368,38,362,'Manufacture of other general-purpose machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(369,38,355,'Manufacture of agricultural and forestry machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(370,38,369,'Manufacture of agricultural and forestry machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(371,38,355,'Manufacture of metal forming machinery and machine tools',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(372,38,371,'Manufacture of metal forming machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(373,38,371,'Manufacture of other machine tools',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(374,38,355,'Manufacture of other special-purpose machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(375,38,374,'Manufacture of machinery for metallurgy',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(376,38,374,'Manufacture of machinery for mining, quarrying and construction',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(377,38,374,'Manufacture of machinery for food, beverage and tobacco processing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(378,38,374,'Manufacture of machinery for textile, apparel and leather production',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(379,38,374,'Manufacture of machinery for paper and paperboard production',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(380,38,374,'Manufacture of plastic and rubber machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(381,38,374,'Manufacture of other special-purpose machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(382,38,88,'Manufacture of motor vehicles, trailers and semi-trailers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(383,38,382,'Manufacture of motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(384,38,383,'Manufacture of motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(385,38,382,'Manufacture of bodies (coachwork) for motor vehicles; manufacture of trailers and semi-trailers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(386,38,385,'Manufacture of bodies (coachwork) for motor vehicles; manufacture of trailers and semi-trailers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(387,38,382,'Manufacture of parts and accessories for motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(388,38,387,'Manufacture of electrical and electronic equipment for motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(389,38,387,'Manufacture of other parts and accessories for motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(390,38,88,'Manufacture of other transport equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(391,38,390,'Building of ships and boats',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(392,38,391,'Building of ships and floating structures',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(393,38,391,'Building of pleasure and sporting boats',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(394,38,390,'Manufacture of railway locomotives and rolling stock',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(395,38,394,'Manufacture of railway locomotives and rolling stock',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(396,38,390,'Manufacture of air and spacecraft and related machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(397,38,396,'Manufacture of air and spacecraft and related machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(398,38,390,'Manufacture of military fighting vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(399,38,398,'Manufacture of military fighting vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(400,38,390,'Manufacture of transport equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(401,38,400,'Manufacture of motorcycles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(402,38,400,'Manufacture of bicycles and invalid carriages',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(403,38,400,'Manufacture of other transport equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(404,38,88,'Manufacture of furniture',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(405,38,404,'Manufacture of furniture',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(406,38,405,'Manufacture of office and shop furniture',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(407,38,405,'Manufacture of kitchen furniture',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(408,38,405,'Manufacture of mattresses',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(409,38,405,'Manufacture of other furniture',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(410,38,88,'Other manufacturing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(411,38,410,'Manufacture of jewellery, bijouterie and related articles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(412,38,411,'Striking of coins',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(413,38,411,'Manufacture of jewellery and related articles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(414,38,411,'Manufacture of imitation jewellery and related articles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(415,38,410,'Manufacture of musical instruments',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(416,38,415,'Manufacture of musical instruments',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(417,38,410,'Manufacture of sports goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(418,38,417,'Manufacture of sports goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(419,38,410,'Manufacture of games and toys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(420,38,419,'Manufacture of games and toys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(421,38,410,'Manufacture of medical and dental instruments and supplies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(422,38,421,'Manufacture of medical and dental instruments and supplies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(423,38,410,'Manufacturing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(424,38,423,'Manufacture of brooms and brushes',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(425,38,423,'Other manufacturing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(426,38,88,'Repair and installation of machinery and equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(427,38,426,'Repair of fabricated metal products, machinery and equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(428,38,427,'Repair of fabricated metal products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(429,38,427,'Repair of machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(430,38,427,'Repair of electronic and optical equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(431,38,427,'Repair of electrical equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(432,38,427,'Repair and maintenance of ships and boats',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(433,38,427,'Repair and maintenance of aircraft and spacecraft',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(434,38,427,'Repair and maintenance of other transport equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(435,38,427,'Repair of other equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(436,38,426,'Installation of industrial machinery and equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(437,38,436,'Installation of industrial machinery and equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(438,38,0,'Electricity, gas, steam and air conditioning supply',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(439,38,438,'Electricity, gas, steam and air conditioning supply',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(440,38,439,'Electric power generation, transmission and distribution',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(441,38,440,'Production of electricity',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(442,38,440,'Transmission of electricity',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(443,38,440,'Distribution of electricity',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(444,38,440,'Trade of electricity',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(445,38,439,'Manufacture of gas; distribution of gaseous fuels through mains',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(446,38,445,'Manufacture of gas',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(447,38,445,'Distribution of gaseous fuels through mains',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(448,38,445,'Trade of gas through mains',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(449,38,439,'Steam and air conditioning supply',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(450,38,449,'Steam and air conditioning supply',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(451,38,0,'Water supply; sewerage, wastmanagement and remediation activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(452,38,451,'Water collection, treatment and supply',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(453,38,452,'Water collection, treatment and supply',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(454,38,453,'Water collection, treatment and supply',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(455,38,451,'Sewerage',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(456,38,455,'Sewerage',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(457,38,456,'Sewerage',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(458,38,451,'Waste collection, treatment and disposal activities; materials recovery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(459,38,458,'Waste collection',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(460,38,459,'Collection of non-hazardous waste',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(461,38,459,'Collection of hazardous waste',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(462,38,458,'Waste treatment and disposal',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(463,38,462,'Treatment and disposal of non-hazardous waste',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(464,38,462,'Treatment and disposal of hazardous waste',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(465,38,458,'Materials recovery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(466,38,465,'Dismantling of wrecks',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(467,38,465,'Recovery of sorted materials',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(468,38,451,'Remediation activities and other waste management services',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(469,38,468,'Remediation activities and other waste management services',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(470,38,469,'Remediation activities and other waste management services',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(471,38,0,'Construction',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(472,38,471,'Construction of buildings',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(473,38,472,'Development of building projects',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(474,38,473,'Development of building projects',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(475,38,472,'Construction of residential and non-residential buildings',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(476,38,475,'Construction of residential and non-residential buildings',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(477,38,471,'Civil engineering',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(478,38,477,'Construction of roads and railways',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(479,38,478,'Construction of roads and motorways',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(480,38,478,'Construction of railways and underground railways',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(481,38,478,'Construction of bridges and tunnels',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(482,38,477,'Construction of utility projects',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(483,38,482,'Construction of utility projects for fluids',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(484,38,482,'Construction of utility projects for electricity and telecommunications',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(485,38,477,'Construction of other civil engineering projects',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(486,38,485,'Construction of water projects',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(487,38,485,'Construction of other civil engineering projects',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(488,38,471,'Specialised construction activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(489,38,488,'Demolition and site preparation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(490,38,489,'Demolition',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(491,38,489,'Site preparation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(492,38,489,'Test drilling and boring',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(493,38,488,'Electrical, plumbing and other construction installation activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(494,38,493,'Electrical installation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(495,38,493,'Plumbing, heat and air conditioning installation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(496,38,493,'Other construction installation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(497,38,488,'Building completion and finishing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(498,38,497,'Plastering',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(499,38,497,'Joinery installation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(500,38,497,'Floor and wall covering',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(501,38,497,'Painting and glazing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(502,38,497,'Other building completion and finishing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(503,38,488,'Other specialised construction activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(504,38,503,'Roofing activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(505,38,503,'Other specialised construction activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(506,38,0,'Wholesale and retail trade; repair of motor vehicles and motorcycles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(507,38,506,'Wholesale and retail trade and repair of motor vehicles and motorcycles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(508,38,507,'Sale of motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(509,38,508,'Sale of cars and light motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(510,38,508,'Sale of other motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(511,38,507,'Maintenance and repair of motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(512,38,511,'Maintenance and repair of motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(513,38,507,'Sale of motor vehicle parts and accessories',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(514,38,513,'Wholesale trade of motor vehicle parts and accessories',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(515,38,513,'Retail trade of motor vehicle parts and accessories',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(516,38,507,'Sale, maintenance and repair of motorcycles and related parts and accessories',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(517,38,516,'Sale, maintenance and repair of motorcycles and related parts and accessories',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(518,38,506,'Wholesale trade, except of motor vehicles and motorcycles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(519,38,518,'Wholesale on a fee or contract basis',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(520,38,519,'Agents involved in the sale of agricultural raw materials, live animals, textile raw materials and semi-finished goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(521,38,519,'Agents involved in the sale of fuels, ores, metals and industrial chemicals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(522,38,519,'Agents involved in the sale of timber and building materials',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(523,38,519,'Agents involved in the sale of machinery, industrial equipment, ships and aircraft',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(524,38,519,'Agents involved in the sale of furniture, household goods, hardware and ironmongery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(525,38,519,'Agents involved in the sale of textiles, clothing, fur, footwear and leather goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(526,38,519,'Agents involved in the sale of food, beverages and tobacco',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(527,38,519,'Agents specialised in the sale of other particular products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(528,38,519,'Agents involved in the sale of a variety of goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(529,38,518,'Wholesale of agricultural raw materials and live animals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(530,38,529,'Wholesale of grain, unmanufactured tobacco, seeds and animal feeds',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(531,38,529,'Wholesale of flowers and plants',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(532,38,529,'Wholesale of live animals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(533,38,529,'Wholesale of hides, skins and leather',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(534,38,518,'Wholesale of food, beverages and tobacco',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(535,38,534,'Wholesale of fruit and vegetables',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(536,38,534,'Wholesale of meat and meat products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(537,38,534,'Wholesale of dairy products, eggs and edible oils and fats',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(538,38,534,'Wholesale of beverages',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(539,38,534,'Wholesale of tobacco products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(540,38,534,'Wholesale of sugar and chocolate and sugar confectionery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(541,38,534,'Wholesale of coffee, tea, cocoa and spices',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(542,38,534,'Wholesale of other food, including fish, crustaceans and molluscs',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(543,38,534,'Non-specialised wholesale of food, beverages and tobacco',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(544,38,518,'Wholesale of household goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(545,38,544,'Wholesale of textiles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(546,38,544,'Wholesale of clothing and footwear',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(547,38,544,'Wholesale of electrical household appliances',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(548,38,544,'Wholesale of china and glassware and cleaning materials',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(549,38,544,'Wholesale of perfume and cosmetics',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(550,38,544,'Wholesale of pharmaceutical goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(551,38,544,'Wholesale of furniture, carpets and lighting equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(552,38,544,'Wholesale of watches and jewellery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(553,38,544,'Wholesale of other household goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(554,38,518,'Wholesale of information and communication equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(555,38,554,'Wholesale of computers, computer peripheral equipment and software',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(556,38,554,'Wholesale of electronic and telecommunications equipment and parts',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(557,38,518,'Wholesale of other machinery, equipment and supplies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(558,38,557,'Wholesale of agricultural machinery, equipment and supplies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(559,38,557,'Wholesale of machine tools',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(560,38,557,'Wholesale of mining, construction and civil engineering machinery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(561,38,557,'Wholesale of machinery for the textile industry and of sewing and knitting machines',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(562,38,557,'Wholesale of office furniture',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(563,38,557,'Wholesale of other office machinery and equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(564,38,557,'Wholesale of other machinery and equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(565,38,518,'Other specialised wholesale',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(566,38,565,'Wholesale of solid, liquid and gaseous fuels and related products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(567,38,565,'Wholesale of metals and metal ores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(568,38,565,'Wholesale of wood, construction materials and sanitary equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(569,38,565,'Wholesale of hardware, plumbing and heating equipment and supplies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(570,38,565,'Wholesale of chemical products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(571,38,565,'Wholesale of other intermediate products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(572,38,565,'Wholesale of waste and scrap',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(573,38,518,'Non-specialised wholesale trade',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(574,38,573,'Non-specialised wholesale trade',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(575,38,506,'Retail trade, except of motor vehicles and motorcycles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(576,38,575,'Retail sale in non-specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(577,38,576,'Retail sale in non-specialised stores with food, beverages or tobacco predominating',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(578,38,576,'Other retail sale in non-specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(579,38,575,'Retail sale of food, beverages and tobacco in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(580,38,579,'Retail sale of fruit and vegetables in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(581,38,579,'Retail sale of meat and meat products in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(582,38,579,'Retail sale of fish, crustaceans and molluscs in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(583,38,579,'Retail sale of bread, cakes, flour confectionery and sugar confectionery in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(584,38,579,'Retail sale of beverages in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(585,38,579,'Retail sale of tobacco products in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(586,38,579,'Other retail sale of food in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(587,38,575,'Retail sale of automotive fuel in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(588,38,587,'Retail sale of automotive fuel in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(589,38,575,'Retail sale of information and communication equipment in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(590,38,589,'Retail sale of computers, peripheral units and software in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(591,38,589,'Retail sale of telecommunications equipment in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(592,38,589,'Retail sale of audio and video equipment in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(593,38,575,'Retail sale of other household equipment in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(594,38,593,'Retail sale of textiles in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(595,38,593,'Retail sale of hardware, paints and glass in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(596,38,593,'Retail sale of carpets, rugs, wall and floor coverings in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(597,38,593,'Retail sale of electrical household appliances in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(598,38,593,'Retail sale of furniture, lighting equipment and other household articles in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(599,38,575,'Retail sale of cultural and recreation goods in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(600,38,599,'Retail sale of books in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(601,38,599,'Retail sale of newspapers and stationery in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(602,38,599,'Retail sale of music and video recordings in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(603,38,599,'Retail sale of sporting equipment in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(604,38,599,'Retail sale of games and toys in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(605,38,575,'Retail sale of other goods in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(606,38,605,'Retail sale of clothing in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(607,38,605,'Retail sale of footwear and leather goods in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(608,38,605,'Dispensing chemist in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(609,38,605,'Retail sale of medical and orthopaedic goods in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(610,38,605,'Retail sale of cosmetic and toilet articles in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(611,38,605,'Retail sale of flowers, plants, seeds, fertilisers, pet animals and pet food in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(612,38,605,'Retail sale of watches and jewellery in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(613,38,605,'Other retail sale of new goods in specialised stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(614,38,605,'Retail sale of second-hand goods in stores',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(615,38,575,'Retail sale via stalls and markets',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(616,38,615,'Retail sale via stalls and markets of food, beverages and tobacco products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(617,38,615,'Retail sale via stalls and markets of textiles, clothing and footwear',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(618,38,615,'Retail sale via stalls and markets of other goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(619,38,575,'Retail trade not in stores, stalls or markets',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(620,38,619,'Retail sale via mail order houses or via Internet',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(621,38,619,'Other retail sale not in stores, stalls or markets',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(622,38,0,'Transportation and storage',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(623,38,622,'Land transport and transport via pipelines',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(624,38,623,'Passenger rail transport, interurban',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(625,38,624,'Passenger rail transport, interurban',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(626,38,623,'Freight rail transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(627,38,626,'Freight rail transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(628,38,623,'Other passenger land transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(629,38,628,'Urban and suburban passenger land transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(630,38,628,'Taxi operation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(631,38,628,'Other passenger land transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(632,38,623,'Freight transport by road and removal services',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(633,38,632,'Freight transport by road',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(634,38,632,'Removal services',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(635,38,632,'Transport via pipeline',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(636,38,632,'Transport via pipeline',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(637,38,622,'Water transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(638,38,637,'Sea and coastal passenger water transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(639,38,638,'Sea and coastal passenger water transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(640,38,637,'Sea and coastal freight water transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(641,38,640,'Sea and coastal freight water transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(642,38,637,'Inland passenger water transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(643,38,642,'Inland passenger water transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(644,38,637,'Inland freight water transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(645,38,644,'Inland freight water transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(646,38,622,'Air transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(647,38,646,'Passenger air transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(648,38,647,'Passenger air transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(649,38,646,'Freight air transport and space transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(650,38,649,'Freight air transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(651,38,649,'Space transport',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(652,38,622,'Warehousing and support activities for transportation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(653,38,652,'Warehousing and storage',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(654,38,653,'Warehousing and storage',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(655,38,652,'Support activities for transportation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(656,38,655,'Service activities incidental to land transportation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(657,38,655,'Service activities incidental to water transportation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(658,38,655,'Service activities incidental to air transportation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(659,38,655,'Cargo handling',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(660,38,655,'Other transportation support activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(661,38,622,'Postal and courier activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(662,38,661,'Postal activities under universal service obligation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(663,38,662,'Postal activities under universal service obligation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(664,38,661,'Other postal and courier activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(665,38,664,'Other postal and courier activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(666,38,0,'Accommodation and food service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(667,38,666,'Accommodation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(668,38,667,'Hotels and similar accommodation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(669,38,668,'Hotels and similar accommodation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(670,38,667,'Holiday and other short-stay accommodation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(671,38,670,'Holiday and other short-stay accommodation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(672,38,667,'Camping grounds, recreational vehicle parks and trailer parks',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(673,38,672,'Camping grounds, recreational vehicle parks and trailer parks',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(674,38,667,'Other accommodation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(675,38,674,'Other accommodation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(676,38,666,'Food and beverage service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(677,38,676,'Restaurants and mobile food service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(678,38,677,'Restaurants and mobile food service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(679,38,676,'Event catering and other food service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(680,38,679,'Event catering activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(681,38,679,'Other food service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(682,38,676,'Beverage serving activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(683,38,682,'Beverage serving activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(684,38,0,'Information and communication',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(685,38,684,'Publishing activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(686,38,685,'Publishing of books, periodicals and other publishing activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(687,38,686,'Book publishing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(688,38,686,'Publishing of directories and mailing lists',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(689,38,686,'Publishing of newspapers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(690,38,686,'Publishing of journals and periodicals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(691,38,686,'Other publishing activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(692,38,685,'Software publishing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(693,38,692,'Publishing of computer games',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(694,38,692,'Other software publishing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(695,38,684,'Motion picture, video and television programme production, sound recording and music publishing activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(696,38,695,'Motion picture, video and television programme activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(697,38,696,'Motion picture, video and television programme production activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(698,38,696,'Motion picture, video and television programme post-production activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(699,38,696,'Motion picture, video and television programme distribution activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(700,38,696,'Motion picture projection activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(701,38,695,'Sound recording and music publishing activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(702,38,701,'Sound recording and music publishing activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(703,38,684,'Programming and broadcasting activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(704,38,703,'Radio broadcasting',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(705,38,704,'Radio broadcasting',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(706,38,703,'Television programming and broadcasting activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(707,38,706,'Television programming and broadcasting activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(708,38,684,'Telecommunications',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(709,38,708,'Wired telecommunications activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(710,38,709,'Wired telecommunications activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(711,38,708,'Wireless telecommunications activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(712,38,711,'Wireless telecommunications activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(713,38,708,'Satellite telecommunications activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(714,38,713,'Satellite telecommunications activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(715,38,708,'Other telecommunications activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(716,38,715,'Other telecommunications activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(717,38,684,'Computer programming, consultancy and related activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(718,38,717,'Computer programming, consultancy and related activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(719,38,718,'Computer programming activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(720,38,718,'Computer consultancy activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(721,38,718,'Computer facilities management activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(722,38,718,'Other information technology and computer service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(723,38,684,'Information service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(724,38,723,'Data processing, hosting and related activities; web portals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(725,38,724,'Data processing, hosting and related activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(726,38,724,'Web portals',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(727,38,723,'Other information service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(728,38,727,'News agency activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(729,38,727,'Other information service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(730,38,0,'Financial and insurance activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(731,38,730,'Financial service activities, except insurance and pension funding',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(732,38,731,'Monetary intermediation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(733,38,732,'Central banking',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(734,38,732,'Other monetary intermediation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(735,38,731,'Activities of holding companies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(736,38,735,'Activities of holding companies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(737,38,731,'Trusts, funds and similar financial entities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(738,38,737,'Trusts, funds and similar financial entities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(739,38,731,'Other financial service activities, except insurance and pension funding',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(740,38,738,'Financial leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(741,38,738,'Other credit granting',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(742,38,738,'Other financial service activities, except insurance and pension funding',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(743,38,730,'Insurance, reinsurance and pension funding, except compulsory social security',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(744,38,743,'Insurance',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(745,38,744,'Life insurance',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(746,38,744,'Non-life insurance',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(747,38,743,'Reinsurance',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(748,38,747,'Reinsurance',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(749,38,743,'Pension funding',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(750,38,749,'Pension funding',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(751,38,730,'Activities auxiliary to financial services and insurance activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(752,38,751,'Activities auxiliary to financial services, except insurance and pension funding',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(753,38,752,'Administration of financial markets',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(754,38,752,'Security and commodity contracts brokerage',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(755,38,752,'Other activities auxiliary to financial services, except insurance and pension funding',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(756,38,751,'Activities auxiliary to insurance and pension funding',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(757,38,756,'Risk and damage evaluation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(758,38,756,'Activities of insurance agents and brokers',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(759,38,756,'Other activities auxiliary to insurance and pension funding',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(760,38,751,'Fund management activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(761,38,760,'Fund management activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(762,38,0,'Real estate activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(763,38,762,'Real estate activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(764,38,763,'Buying and selling of own real estate',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(765,38,764,'Buying and selling of own real estate',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(766,38,763,'Renting and operating of own or leased real estate',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(767,38,766,'Renting and operating of own or leased real estate',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(768,38,763,'Real estate activities on a fee or contract basis',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(769,38,768,'Real estate agencies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(770,38,768,'Management of real estate on a fee or contract basis',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(771,38,0,'Professional, scientific and technical activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(772,38,771,'Legal and accounting activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(773,38,772,'Legal activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(774,38,773,'Legal activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(775,38,772,'Accounting, bookkeeping and auditing activities; tax consultancy',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(776,38,775,'Accounting, bookkeeping and auditing activities; tax consultancy',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(777,38,771,'Activities of head offices; management consultancy activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(778,38,777,'Activities of head offices',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(779,38,779,'Activities of head offices',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(780,38,777,'Management consultancy activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(781,38,780,'Public relations and communication activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(782,38,780,'Business and other management consultancy activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(783,38,771,'Architectural and engineering activities; technical testing and analysis',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(784,38,783,'Architectural and engineering activities and related technical consultancy',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(785,38,784,'Architectural activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(786,38,784,'Engineering activities and related technical consultancy',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(787,38,783,'Technical testing and analysis',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(788,38,787,'Technical testing and analysis',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(789,38,771,'Scientific research and development',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(790,38,789,'Research and experimental development on natural sciences and engineering',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(791,38,790,'Research and experimental development on biotechnology',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(792,38,790,'Other research and experimental development on natural sciences and engineering',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(793,38,789,'Research and experimental development on social sciences and humanities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(794,38,793,'Research and experimental development on social sciences and humanities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(795,38,771,'Advertising and market research',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(796,38,795,'Advertising',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(797,38,796,'Advertising agencies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(798,38,796,'Media representation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(799,38,795,'Market research and public opinion polling',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(800,38,799,'Market research and public opinion polling',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(801,38,771,'Other professional, scientific and technical activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(802,38,801,'Specialised design activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(803,38,802,'Specialised design activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(804,38,801,'Photographic activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(805,38,804,'Photographic activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(806,38,801,'Translation and interpretation activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(807,38,806,'Translation and interpretation activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(808,38,801,'Other professional, scientific and technical activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(809,38,808,'Other professional, scientific and technical activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(810,38,771,'Veterinary activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(811,38,810,'Veterinary activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(812,38,811,'Veterinary activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(813,38,0,'Administrative and support service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(814,38,813,'Rental and leasing activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(815,38,814,'Renting and leasing of motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(816,38,815,'Renting and leasing of cars and light motor vehicles',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(817,38,815,'Renting and leasing of trucks',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(818,38,814,'Renting and leasing of personal and household goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(819,38,818,'Renting and leasing of recreational and sports goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(820,38,818,'Renting of video tapes and disks',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(821,38,818,'Renting and leasing of other personal and household goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(822,38,814,'Renting and leasing of other machinery, equipment and tangible goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(823,38,822,'Renting and leasing of agricultural machinery and equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(824,38,822,'Renting and leasing of construction and civil engineering machinery and equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(825,38,822,'Renting and leasing of office machinery and equipment (including computers)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(826,38,822,'Renting and leasing of water transport equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(827,38,822,'Renting and leasing of air transport equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(828,38,822,'Renting and leasing of other machinery, equipment and tangible goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(829,38,814,'Leasing of intellectual property and similar products, except copyrighted works',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(830,38,829,'Leasing of intellectual property and similar products, except copyrighted works',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(831,38,813,'Employment activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(832,38,831,'Activities of employment placement agencies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(833,38,832,'Activities of employment placement agencies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(834,38,831,'Temporary employment agency activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(835,38,834,'Temporary employment agency activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(836,38,831,'Other human resources provision',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(837,38,836,'Other human resources provision',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(838,38,813,'Travel agency, tour operator reservation service and related activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(839,38,838,'Travel agency and tour operator activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(840,38,839,'Travel agency activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(841,38,839,'Tour operator activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(842,38,838,'Other reservation service and related activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(843,38,842,'Other reservation service and related activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(844,38,813,'Security and investigation activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(845,38,844,'Private security activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(846,38,845,'Private security activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(847,38,844,'Security systems service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(848,38,847,'Security systems service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(849,38,844,'Investigation activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(850,38,849,'Investigation activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(851,38,813,'Services to buildings and landscape activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(852,38,851,'Combined facilities support activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(853,38,852,'Combined facilities support activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(854,38,851,'Cleaning activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(855,38,854,'General cleaning of buildings',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(856,38,854,'Other building and industrial cleaning activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(857,38,854,'Other cleaning activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(858,38,851,'Landscape service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(859,38,858,'Landscape service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(860,38,813,'Office administrative, office support and other business support activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(861,38,860,'Office administrative and support activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(862,38,861,'Combined office administrative service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(863,38,861,'Photocopying, document preparation and other specialised office support activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(864,38,860,'Activities of call centres',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(865,38,864,'Activities of call centres',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(866,38,860,'Organisation of conventions and trade shows',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(867,38,866,'Organisation of conventions and trade shows',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(868,38,860,'Business support service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(869,38,868,'Activities of collection agencies and credit bureaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(870,38,868,'Packaging activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(871,38,868,'Other business support service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(872,38,0,'Public administration and defence; compulsory social security',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(873,38,872,'Public administration and defence; compulsory social security',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(874,38,873,'Administration of the State and the economic and social policy of the community',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(875,38,874,'General public administration activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(876,38,874,'Regulation of the activities of providing health care, education, cultural services and other social services, excluding social security',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(877,38,874,'Regulation of and contribution to more efficient operation of businesses',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(878,38,873,'Provision of services to the community as a whole',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(879,38,878,'Foreign affairs',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(880,38,878,'Defence activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(881,38,878,'Justice and judicial activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(882,38,878,'Public order and safety activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(883,38,878,'Fire service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(884,38,873,'Compulsory social security activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(885,38,884,'Compulsory social security activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(886,38,0,'Education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(887,38,886,'Education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(888,38,887,'Pre-primary education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(889,38,888,'Pre-primary education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(890,38,887,'Primary education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(891,38,890,'Primary education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(892,38,887,'Secondary education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(893,38,892,'General secondary education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(894,38,892,'Technical and vocational secondary education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(895,38,887,'Higher education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(896,38,895,'Post-secondary non-tertiary education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(897,38,895,'Tertiary education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(898,38,887,'Other education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(899,38,898,'Sports and recreation education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(900,38,898,'Cultural education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(901,38,898,'Driving school activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(902,38,898,'Other education',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(903,38,887,'Educational support activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(904,38,903,'Educational support activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(905,38,0,'Human health and social work activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(906,38,905,'Human health activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(907,38,906,'Hospital activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(908,38,907,'Hospital activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(909,38,906,'Medical and dental practice activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(910,38,909,'General medical practice activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(911,38,909,'Specialist medical practice activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(912,38,909,'Dental practice activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(913,38,906,'Other human health activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(914,38,913,'Other human health activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(915,38,905,'Residential care activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(916,38,915,'Residential nursing care activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(917,38,916,'Residential nursing care activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(918,38,915,'Residential care activities for mental retardation, mental health and substance abuse',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(919,38,918,'Residential care activities for mental retardation, mental health and substance abuse',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(920,38,915,'Residential care activities for the elderly and disabled',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(921,38,920,'Residential care activities for the elderly and disabled',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(922,38,915,'Other residential care activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(923,38,922,'Other residential care activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(924,38,905,'Social work activities without accommodation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(925,38,924,'Social work activities without accommodation for the elderly and disabled',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(926,38,925,'Social work activities without accommodation for the elderly and disabled',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(927,38,924,'Other social work activities without accommodation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(928,38,927,'Child day-care activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(929,38,927,'Other social work activities without accommodation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(930,38,0,'Arts, entertainment and recreation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(931,38,930,'Creative, arts and entertainment activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(932,38,931,'Creative, arts and entertainment activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(933,38,932,'Performing arts',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(934,38,932,'Support activities to performing arts',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(935,38,932,'Artistic creation',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(936,38,932,'Operation of arts facilities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(937,38,930,'Libraries, archives, museums and other cultural activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(938,38,937,'Libraries, archives, museums and other cultural activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(939,38,938,'Library and archives activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(940,38,938,'Museums activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(941,38,938,'Operation of historical sites and buildings and similar visitor attractions',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(942,38,938,'Botanical and zoological gardens and nature reserves activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(943,38,930,'Gambling and betting activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(944,38,943,'Gambling and betting activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(945,38,944,'Gambling and betting activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(946,38,930,'Sports activities and amusement and recreation activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(947,38,946,'Sports activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(948,38,947,'Operation of sports facilities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(949,38,947,'Activities of sport clubs',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(950,38,947,'Fitness facilities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(951,38,947,'Other sports activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(952,38,946,'Amusement and recreation activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(953,38,952,'Activities of amusement parks and theme parks',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(954,38,952,'Other amusement and recreation activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(955,38,0,'Other service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(956,38,955,'Activities of membership organisations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(957,38,956,'Activities of business, employers and professional membership organisations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(958,38,957,'Activities of business and employers membership organisations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(959,38,957,'Activities of professional membership organisations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(960,38,956,'Activities of trade unions',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(961,38,960,'Activities of trade unions',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(962,38,956,'Activities of other membership organisations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(963,38,962,'Activities of religious organisations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(964,38,962,'Activities of political organisations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(965,38,962,'Activities of other membership organisations',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(966,38,955,'Repair of computers and personal and household goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(967,38,966,'Repair of computers and communication equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(968,38,967,'Repair of computers and peripheral equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(969,38,967,'Repair of communication equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(970,38,966,'Repair of personal and household goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(971,38,970,'Repair of consumer electronics',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(972,38,970,'Repair of household appliances and home and garden equipment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(973,38,970,'Repair of footwear and leather goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(974,38,970,'Repair of furniture and home furnishings',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(975,38,970,'Repair of watches, clocks and jewellery',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(976,38,970,'Repair of other personal and household goods',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(977,38,955,'Other personal service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(978,38,977,'Other personal service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(979,38,978,'Washing and (dry-)cleaning of textile and fur products',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(980,38,978,'Hairdressing and other beauty treatment',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(981,38,978,'Funeral and related activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(982,38,978,'Physical well-being activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(983,38,978,'Other personal service activities',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(984,38,0,'Activities of households as employers; Undifferetiated goods- and services-producing activities of housholds for own use',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(985,38,984,'Activities of households as employers of domestic personnel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(986,38,985,'Activities of households as employers of domestic personnel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(987,38,986,'Activities of households as employers of domestic personnel',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(988,38,984,'Undifferentiated goods- and services-producing activities of private households for own use',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(989,38,988,'Undifferentiated goods-producing activities of private households for own use',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(990,38,989,'Undifferentiated goods-producing activities of private households for own use',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(991,38,988,'Undifferentiated service-producing activities of private households for own use',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(992,38,991,'Undifferentiated service-producing activities of private households for own use',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(993,38,0,'Activities of extraterritorial organisations and bodies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(994,38,993,'Activities of extraterritorial organisations and bodies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(995,38,994,'Activities of extraterritorial organisations and bodies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(996,38,995,'Activities of extraterritorial organisations and bodies',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(997,45,0,'Maatalous, metsÃ¤talous ja kalatalous',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(998,45,997,'Kasvinviljely ja kotielÃ¤intalous, riistatalous ja niihin liittyvÃ¤t palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(999,45,998,'Yksivuotisten kasvien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1000,45,999,'Viljakasvien (pl. riisin), palkokasvien ja Ã¶ljysiemenkasvien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1001,45,999,'Riisin viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1002,45,999,'Vihannesten ja melonien, juuresten ja mukulakasvien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1003,45,999,'Sokeriruo\'on viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1004,45,999,'Tupakan viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1005,45,999,'Kuitukasvien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1006,45,999,'Muu yksivuotisten ja koristekasvien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1007,45,998,'Monivuotisten kasvien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1008,45,1007,'RypÃ¤leiden viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1009,45,1007,'Trooppisten ja subtrooppisten hedelmien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1010,45,1007,'Sitrushedelmien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1011,45,1007,'Omenoiden, kirsikoiden, luumujen ym. kota- ja kivihedelmien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1012,45,1007,'Marjojen, pÃ¤hkinÃ¶iden ja muiden puissa ja pensaissa kasvavien hedelmien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1013,45,1007,'Ã–ljyÃ¤ sisÃ¤ltÃ¤vien hedelmien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1014,45,1007,'Juomakasvien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1015,45,1007,'Mauste-, aromi-, rohdos- ja lÃ¤Ã¤kekasvien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1016,45,1007,'Muu monivuotisten kasvien viljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1017,45,998,'Taimien kasvatus ja muu kasvien lisÃ¤Ã¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1018,45,1017,'Taimien kasvatus ja muu kasvien lisÃ¤Ã¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1019,45,998,'KotielÃ¤intalous',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1020,45,1019,'Lypsykarjan kasvatus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1021,45,1019,'Muun nautakarjan ja puhvelien kasvatus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1022,45,1019,'Hevosten ja muiden hevoselÃ¤inten kasvatus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1023,45,1019,'Kamelien ja kamelielÃ¤inten kasvatus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1024,45,1019,'Lampaiden ja vuohien kasvatus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1025,45,1019,'Sikojen kasvatus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1026,45,1019,'Siipikarjan kasvatus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1027,45,1019,'Muiden elÃ¤inten kasvatus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1028,45,998,'Yhdistetty kasvinviljely ja kotielÃ¤intalous (sekatilat)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1029,45,1028,'Yhdistetty kasvinviljely ja kotielÃ¤intalous (sekatilat)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1030,45,998,'Maataloutta palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1031,45,1030,'KasvinviljelyÃ¤ palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1032,45,1030,'KotielÃ¤intaloutta palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1033,45,1030,'Sadon jatkokÃ¤sittely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1034,45,1030,'Siementen kÃ¤sittely kasvinviljelyÃ¤ varten',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1035,45,998,'MetsÃ¤stys ja sitÃ¤ palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1036,45,1035,'MetsÃ¤stys ja sitÃ¤ palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1037,45,997,'MetsÃ¤talous ja puunkorjuu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1038,45,1037,'MetsÃ¤nhoito',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1039,45,1038,'MetsÃ¤nhoito',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1040,45,1037,'Puunkorjuu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1041,45,1041,'Puunkorjuu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1042,45,1037,'Luonnon tuotteiden keruu (pl. polttopuu)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1043,45,1042,'Luonnon tuotteiden keruu (pl. polttopuu)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1044,45,1037,'MetsÃ¤taloutta palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1045,45,1044,'MetsÃ¤taloutta palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1046,45,997,'Kalastus ja vesiviljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1047,45,1046,'Kalastus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1048,45,1047,'Merikalastus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1049,45,1047,'SisÃ¤vesikalastus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1050,45,1046,'Vesiviljely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1051,45,1050,'Kalanviljely meressÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1052,45,1050,'Kalanviljely sisÃ¤vesissÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1053,45,0,'Kaivostoiminta ja louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1054,45,1053,'Kivihiilen ja ruskohiilen kaivu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1055,45,1054,'Kivihiilen kaivu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1056,45,1055,'Kivihiilen kaivu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1057,45,1054,'Ruskohiilen kaivu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1058,45,1057,'Ruskohiilen kaivu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1059,45,1053,'RaakaÃ¶ljyn ja maakaasun tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1060,45,1059,'RaakaÃ¶ljyn tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1061,45,1060,'RaakaÃ¶ljyn tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1062,45,1059,'Maakaasun tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1063,45,1062,'Maakaasun tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1064,45,1053,'Metallimalmien louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1065,45,1064,'Rautamalmien louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1066,45,1065,'Rautamalmien louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1067,45,1064,'VÃ¤rimetallimalmien louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1068,45,1067,'Uraani- ja toriummalmien louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1069,45,1067,'Muiden vÃ¤rimetallimalmien louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1070,45,1053,'Muu kaivostoiminta ja louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1071,45,1070,'Kiven louhinta, hiekan ja saven otto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1072,45,1071,'Koriste- ja rakennuskiven, kalkkikiven, kipsin, liidun ja liuskekiven louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1073,45,1071,'Soran, hiekan, saven ja kaoliinin otto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1074,45,1070,'Muu mineraalien kaivu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1075,45,1074,'Kemiallisten ja lannoitemineraalien louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1076,45,1074,'Turpeen nosto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1077,45,1074,'Suolan tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1078,45,1074,'Muualla luokittelematon kaivostoiminta ja louhinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1079,45,1053,'Kaivostoimintaa palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1080,45,1079,'RaakaÃ¶ljyn ja maakaasun tuotantoa palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1081,45,1080,'RaakaÃ¶ljyn ja maakaasun tuotantoa palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1082,45,1079,'Muuta kaivostoimintaa ja louhintaa palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1083,45,1082,'Muuta kaivostoimintaa ja louhintaa palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1084,45,0,'Teollisuus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1085,45,1084,'Elintarvikkeiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1086,45,1085,'Teurastus, lihan sÃ¤ilyvyyskÃ¤sittely ja lihatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1087,45,1086,'Teurastus ja lihan sÃ¤ilyvyyskÃ¤sittely (pl. siipikarja)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1088,45,1086,'Siipikarjan teurastus ja lihan sÃ¤ilyvyyskÃ¤sittely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1089,45,1086,'Liha- ja siipikarjatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1090,45,1085,'Kalan, Ã¤yriÃ¤isten ja nilviÃ¤isten jalostus ja sÃ¤ilÃ¶ntÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1091,45,1090,'Kalan, Ã¤yriÃ¤isten ja nilviÃ¤isten jalostus ja sÃ¤ilÃ¶ntÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1092,45,1085,'Hedelmien ja kasvisten jalostus ja sÃ¤ilÃ¶ntÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1093,45,1092,'Perunoiden jalostus ja sÃ¤ilÃ¶ntÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1094,45,1092,'HedelmÃ¤-, marja- ja kasvismehujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1095,45,1092,'Muu hedelmien, marjojen ja kasvisten jalostus ja sÃ¤ilÃ¶ntÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1096,45,1085,'Kasvi- ja elÃ¤inÃ¶ljyjen ja -rasvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1097,45,1096,'Kasvi- ja elÃ¤inperÃ¤isten Ã¶ljyjen ja -rasvojen valmistus (pl. ravintorasvat)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1098,45,1096,'Margariinin ja sen kaltaisten ravintorasvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1099,45,1085,'Maitotaloustuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1100,45,1099,'Maitotaloustuotteiden ja juuston valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1101,45,1099,'JÃ¤Ã¤telÃ¶n valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1102,45,1085,'Mylly- ja tÃ¤rkkelystuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1103,45,1102,'Myllytuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1104,45,1102,'TÃ¤rkkelyksen ja tÃ¤rkkelystuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1105,45,1085,'Leipomotuotteiden, makaronien yms. valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1106,45,1105,'LeivÃ¤n valmistus; tuoreiden leivonnaisten ja kakkujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1107,45,1105,'NÃ¤kkileivÃ¤n ja keksien valmistus; sÃ¤ilyvien leivonnaisten ja kakkujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1108,45,1105,'Makaronin, nuudelien, kuskusin ja vastaavien jauhotuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1109,45,1085,'Muiden elintarvikkeiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1110,45,1109,'Sokerin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1111,45,1109,'Kaakaon, suklaan ja makeisten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1112,45,1109,'Teen ja kahvin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1113,45,1109,'Mausteiden ja maustekastikkeiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1114,45,1109,'Einesten ja valmisruokien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1115,45,1109,'Homogenoitujen ravintovalmisteiden ja dieettiruokien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1116,45,1109,'Muualla luokittelematon elintarvikkeiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1117,45,1085,'ElÃ¤inten ruokien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1118,45,1117,'KotielÃ¤inten rehujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1119,45,1117,'LemmikkielÃ¤inten ruokien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1120,45,1084,'Juomien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1121,45,1120,'Juomien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1122,45,1121,'Alkoholijuomien tislaus ja sekoittaminen; etyylialkoholin valmistus kÃ¤ymisteitse',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1123,45,1121,'Viinin valmistus rypÃ¤leistÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1124,45,1121,'Siiderin, hedelmÃ¤- ja marjaviinien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1125,45,1121,'Muiden tislaamattomien juomien valmistus kÃ¤ymisteitse',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1126,45,1121,'Oluen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1127,45,1121,'Maltaiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1128,45,1121,'Virvoitusjuomien valmistus; kivennÃ¤isvesien ja muiden pullotettujen vesien tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1129,45,1084,'Tupakkatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1130,45,1129,'Tupakkatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1131,45,1130,'Tupakkatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1132,45,1084,'Tekstiilien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1133,45,1132,'Tekstiilikuitujen valmistelu ja kehruu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1134,45,1133,'Tekstiilikuitujen valmistelu ja kehruu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1135,45,1132,'Kankaiden kudonta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1136,45,1135,'Kankaiden kudonta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1137,45,1132,'Tekstiilien viimeistely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1138,45,1137,'Tekstiilien viimeistely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1139,45,1132,'Muiden tekstiilituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1140,45,1139,'Neulosten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1141,45,1139,'Sovitettujen tekstiilituotteiden valmistus (pl. vaatteet)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1142,45,1139,'Mattojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1143,45,1139,'Purjelankojen, nuoran, sidelangan ja verkkojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1144,45,1139,'Kuitukankaiden ja kuitukangastuotteiden valmistus (pl. vaatteet)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1145,45,1139,'Teknisten ja teollisuustekstiilien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1146,45,1139,'Muualla luokittelematon tekstiilituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1147,45,1084,'Vaatteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1148,45,1147,'Vaatteiden valmistus (pl. turkisvaatteet)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1149,45,1148,'Nahkavaatteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1150,45,1148,'TyÃ¶vaatteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1151,45,1148,'Muu takkien, pukujen, housujen, hameiden yms. valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1152,45,1148,'Alusvaatteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1153,45,1148,'Muiden vaatteiden ja asusteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1154,45,1147,'Turkisvaatteiden ja -tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1155,45,1154,'Turkisvaatteiden ja -tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1156,45,1147,'Neulevaatteiden ja sukkien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1157,45,1156,'Sukkien ja sukkahousujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1158,45,1156,'Muiden neulevaatteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1159,45,1084,'Nahan ja nahkatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1160,45,1159,'Nahan parkitseminen ja muokkaus; matka- ja kÃ¤silaukkujen, satuloiden ja valjaiden valmistus; turkisten muokkaus ja vÃ¤rjÃ¤ys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1161,45,1160,'Turkisten ja nahan muokkaus ja vÃ¤rjÃ¤ys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1162,45,1160,'Matka-, kÃ¤si- ym. laukkujen, satuloiden ja valjaiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1163,45,1159,'Jalkineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1164,45,1163,'Jalkineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1165,45,1084,'Sahatavaran sekÃ¤ puu- ja korkkituotteiden valmistus (pl. huonekalut; olki- ja punontatuotteiden valmistus)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1166,45,1165,'Puun sahaus, hÃ¶ylÃ¤ys ja kyllÃ¤stys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1167,45,1166,'Puun sahaus, hÃ¶ylÃ¤ys ja kyllÃ¤stys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1168,45,1165,'Puu-, korkki-, olki- ja punontatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1169,45,1168,'Vaneriviilun ja puupaneelien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1170,45,1168,'Asennettavien parkettilevyjen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1171,45,1168,'Muiden rakennuspuusepÃ¤ntuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1172,45,1168,'Puupakkausten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1173,45,1168,'Muiden puutuotteiden valmistus; korkki-, olki- ja punontatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1174,45,1084,'Paperin, paperi- ja kartonkituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1175,45,1174,'Massan, paperin, kartongin ja pahvin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1176,45,1175,'Massan valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1177,45,1175,'Paperin, kartongin ja pahvin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1178,45,1174,'Paperi-, kartonki- ja pahvituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1179,45,1178,'Aaltopaperin ja -pahvin sekÃ¤ paperi-, kartonki- ja pahvipakkausten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1180,45,1178,'Paperisten talous- ja hygieniatarvikkeiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1181,45,1178,'Paperikauppatavaroiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1182,45,1178,'Tapettien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1183,45,1178,'Muiden paperi-, kartonki- ja pahvituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1184,45,1084,'Painaminen ja tallenteiden jÃ¤ljentÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1185,45,1184,'Painaminen ja siihen liittyvÃ¤t palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1186,45,1185,'Sanomalehtien painaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1187,45,1185,'Muu painaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1188,45,1185,'Painamista ja julkaisemista edeltÃ¤vÃ¤t palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1189,45,1185,'Sidonta ja siihen liittyvÃ¤t palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1190,45,1184,'Ã„Ã¤ni-, kuva- ja atk-tallenteiden tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1191,45,1190,'Ã„Ã¤ni-, kuva- ja atk-tallenteiden tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1192,45,1084,'Koksin ja jalostettujen Ã¶ljytuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1193,45,1192,'Koksituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1194,45,1193,'Koksituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1195,45,1192,'Jalostettujen Ã¶ljytuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1196,45,1195,'Jalostettujen Ã¶ljytuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1197,45,1084,'Kemikaalien ja kemiallisten tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1198,45,1197,'Peruskemikaalien, lannoitteiden ja typpiyhdisteiden, muoviaineiden ja synteettisen kumiraaka-aineen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1199,45,1198,'Teollisuuskaasujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1200,45,1198,'VÃ¤rien ja pigmenttien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1201,45,1198,'Muiden epÃ¤orgaanisten peruskemikaalien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1202,45,1198,'Muiden orgaanisten peruskemikaalien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1203,45,1198,'Lannoitteiden ja typpiyhdisteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1204,45,1198,'Muoviaineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1205,45,1198,'Synteettisen kumiraaka-aineen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1206,45,1197,'Torjunta-aineiden ja muiden maatalouskemikaalien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1207,45,1206,'Torjunta-aineiden ja muiden maatalouskemikaalien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1208,45,1197,'Maalien, lakan, painovÃ¤rien yms. valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1209,45,1208,'Maalien, lakan, painovÃ¤rien yms. valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1210,45,1197,'Saippuan, pesu-, puhdistus- ja kiillotusaineiden; hajuvesien ja hygieniatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1211,45,1210,'Saippuan, pesu-, puhdistus- ja kiillotusaineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1212,45,1210,'Hajuvesien ja hygieniatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1213,45,1197,'Muiden kemiallisten tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1214,45,1213,'RÃ¤jÃ¤hdysaineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1215,45,1213,'Liimojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1216,45,1213,'Eteeristen Ã¶ljyjen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1217,45,1213,'Muualla luokittelematon kemiallisten tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1218,45,1197,'Tekokuitujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1219,45,1218,'Tekokuitujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1220,45,1084,'LÃ¤Ã¤keaineiden ja lÃ¤Ã¤kkeiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1221,45,1220,'LÃ¤Ã¤keaineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1222,45,1221,'LÃ¤Ã¤keaineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1223,45,1220,'LÃ¤Ã¤kkeiden ja muiden lÃ¤Ã¤kevalmisteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1224,45,1223,'LÃ¤Ã¤kkeiden ja muiden lÃ¤Ã¤kevalmisteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1225,45,1084,'Kumi- ja muovituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1226,45,1225,'Kumituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1227,45,1226,'Renkaiden valmistus ja uudelleenpinnoitus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1228,45,1226,'Muiden kumituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1229,45,1225,'Muovituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1230,45,1229,'Muovilevyjen, -kalvojen, -putkien ja -profiilien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1231,45,1229,'Muovipakkausten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1232,45,1229,'Rakennusmuovien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1233,45,1229,'Muiden muovituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1234,45,1084,'Muiden ei-metallisten mineraalituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1235,45,1234,'Lasin ja lasituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1236,45,1235,'Tasolasin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1237,45,1235,'Tasolasin muotoilu ja muokkaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1238,45,1235,'Onton lasin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1239,45,1235,'Lasikuitujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1240,45,1235,'Muu lasin valmistus ja muokkaus, mukaan lukien tekniset lasituotteet',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1241,45,1234,'TulenkestÃ¤vien keraamisten tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1242,45,1241,'TulenkestÃ¤vien keraamisten tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1243,45,1234,'Keraamisten rakennusaineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1244,45,1243,'Keraamisten tiilien ja laattojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1245,45,1243,'Poltettujen tiilien ja muun rakennuskeramiikan valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1246,45,1234,'Muiden posliini- ja keramiikkatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1247,45,1246,'Keraamisten talous- ja koriste-esineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1248,45,1246,'Keraamisten saniteettikalusteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1249,45,1246,'Keraamisten eristystuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1250,45,1246,'Muiden teknisten keraamisten tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1251,45,1246,'Muiden keramiikkatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1252,45,1234,'Sementin, kalkin ja kipsin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1253,45,1252,'Sementin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1254,45,1252,'Kalkin ja kipsin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1255,45,1252,'Betoni-, kipsi- ja sementtituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1256,45,1252,'Betonituotteiden valmistus rakennustarkoituksiin',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1257,45,1252,'Kipsituotteiden valmistus rakennustarkoituksiin',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1258,45,1252,'Valmisbetonin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1259,45,1252,'Muurauslaastin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1260,45,1252,'Kuitusementin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1261,45,1252,'Muiden betoni-, kipsi- ja sementtituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1262,45,1234,'Kiven leikkaaminen, muotoilu ja viimeistely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1263,45,1262,'Kiven leikkaaminen, muotoilu ja viimeistely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1264,45,1234,'Hiontatuotteiden ja muualla luokittelemattomien ei-metallisten mineraalituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1265,45,1264,'Hiontatuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1266,45,1264,'Muualla luokittelemattomien ei-metallisten mineraalituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1267,45,1084,'Metallien jalostus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1268,45,1267,'Raudan, terÃ¤ksen ja rautaseosten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1269,45,1268,'Raudan, terÃ¤ksen ja rautaseosten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1270,45,1267,'Putkien, profiiliputkien ja niihin liittyvien tarvikkeiden valmistus terÃ¤ksestÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1271,45,1270,'Putkien, profiiliputkien ja niihin liittyvien tarvikkeiden valmistus terÃ¤ksestÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1272,45,1267,'Muu terÃ¤ksen jalostus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1273,45,1272,'Raudan ja terÃ¤ksen kylmÃ¤vetÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1274,45,1272,'Rainan kylmÃ¤valssaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1275,45,1272,'KylmÃ¤muovaus tai kylmÃ¤taitto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1276,45,1272,'TerÃ¤slangan veto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1277,45,1267,'Jalometallien ja muiden vÃ¤rimetallien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1278,45,1277,'Jalometallien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1279,45,1277,'Alumiinin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1280,45,1277,'Lyijyn, sinkin ja tinan valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1281,45,1277,'Kuparin valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1282,45,1277,'Muiden vÃ¤rimetallien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1283,45,1277,'Ydinpolttoaineen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1284,45,1267,'Metallien valu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1285,45,1284,'Raudan valu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1286,45,1284,'TerÃ¤ksen valu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1287,45,1284,'Kevytmetallien valu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1288,45,1284,'Muiden vÃ¤rimetallien valu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1289,45,1084,'Metallituotteiden valmistus (pl. koneet ja laitteet)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1290,45,1289,'Metallirakenteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1291,45,1290,'Metallirakenteiden ja niiden osien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1292,45,1290,'Metalliovien ja -ikkunoiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1293,45,1290,'MetallisÃ¤iliÃ¶iden ja -altaiden yms. valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1294,45,1290,'KeskuslÃ¤mmityspatterien ja kuumavesivaraajien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1295,45,1290,'Muiden metallisÃ¤iliÃ¶iden ja -altaiden yms. valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1296,45,1290,'HÃ¶yrykattiloiden valmistus (pl. keskuslÃ¤mmityslaitteet)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1297,45,1290,'HÃ¶yrykattiloiden valmistus (pl. keskuslÃ¤mmityslaitteet)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1298,45,1289,'Aseiden ja ammusten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1299,45,1298,'Aseiden ja ammusten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1300,45,1289,'Metallin takominen, puristaminen, meistÃ¤minen ja valssaus; jauhemetallurgia',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1301,45,1300,'Metallin takominen, puristaminen, meistÃ¤minen ja valssaus; jauhemetallurgia',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1302,45,1289,'Metallien kÃ¤sittely, pÃ¤Ã¤llystÃ¤minen ja tyÃ¶stÃ¶',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1303,45,1302,'Metallien kÃ¤sittely ja pÃ¤Ã¤llystÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1304,45,1302,'Metallien tyÃ¶stÃ¶',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1305,45,1289,'Ruokailu- ja leikkuuvÃ¤lineiden yms. sekÃ¤ tyÃ¶kalujen ja rautatavaran valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1306,45,1305,'Ruokailu- ja leikkuuvÃ¤lineiden yms. valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1307,45,1305,'Lukkojen ja saranoiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1308,45,1305,'TyÃ¶kalujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1309,45,1305,'Muu metallituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1310,45,1305,'Metallipakkausten ja -astioiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1311,45,1305,'Kevytmetallipakkausten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1312,45,1305,'Metallilankatuotteiden, ketjujen ja jousien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1313,45,1305,'Kiinnittimien ja ruuvituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1314,45,1305,'Muiden metallituotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1315,45,1084,'Tietokoneiden sekÃ¤ elektronisten ja optisten tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1316,45,1315,'Elektronisten komponenttien ja piirilevyjen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1317,45,1316,'Elektronisten komponenttien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1318,45,1316,'Kalustettujen piirilevyjen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1319,45,1315,'Tietokoneiden ja niiden oheislaitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1320,45,1319,'Tietokoneiden ja niiden oheislaitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1321,45,1315,'ViestintÃ¤laitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1322,45,1321,'ViestintÃ¤laitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1323,45,1315,'Viihde-elektroniikan valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1324,45,1323,'Viihde-elektroniikan valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1325,45,1315,'Mittaus-, testaus- ja navigointivÃ¤lineiden ja -laitteiden valmistus; kellot',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1326,45,1325,'Mittaus-, testaus- ja navigointivÃ¤lineiden ja -laitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1327,45,1325,'Kellojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1328,45,1315,'SÃ¤teilylaitteiden sekÃ¤ elektronisten lÃ¤Ã¤kintÃ¤- ja terapialaitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1329,45,1328,'SÃ¤teilylaitteiden sekÃ¤ elektronisten lÃ¤Ã¤kintÃ¤- ja terapialaitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1330,45,1328,'Optisten instrumenttien ja valokuvausvÃ¤lineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1331,45,1328,'Optisten instrumenttien ja valokuvausvÃ¤lineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1332,45,1328,'TallennevÃ¤lineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1333,45,1328,'TallennevÃ¤lineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1334,45,1084,'SÃ¤hkÃ¶laitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1335,45,1334,'SÃ¤hkÃ¶moottorien, generaattorien, muuntajien sekÃ¤ sÃ¤hkÃ¶njakelu- ja valvontalaitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1336,45,1335,'SÃ¤hkÃ¶moottorien, generaattorien ja muuntajien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1337,45,1335,'SÃ¤hkÃ¶njakelu- ja valvontalaitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1338,45,1334,'Paristojen ja akkujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1339,45,1338,'Paristojen ja akkujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1340,45,1334,'SÃ¤hkÃ¶johtojen ja kytkentÃ¤laitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1341,45,1340,'Optisten kuitukaapelien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1342,45,1340,'Muiden elektronisten ja sÃ¤hkÃ¶johtojen sekÃ¤ -kaapelien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1343,45,1340,'KytkentÃ¤laitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1344,45,1334,'SÃ¤hkÃ¶lamppujen ja valaisimien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1345,45,1344,'SÃ¤hkÃ¶lamppujen ja valaisimien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1346,45,1334,'Kodinkoneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1347,45,1346,'SÃ¤hkÃ¶isten kodinkoneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1348,45,1346,'SÃ¤hkÃ¶istÃ¤mÃ¤ttÃ¶mien kodinkoneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1349,45,1334,'Muiden sÃ¤hkÃ¶laitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1350,45,1349,'Muiden sÃ¤hkÃ¶laitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1351,45,1084,'Muiden koneiden ja laitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1352,45,1351,'YleiskÃ¤yttÃ¶Ã¶n tarkoitettujen voimakoneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1353,45,1352,'Moottorien ja turbiinien valmistus (pl. lentokoneiden ja ajoneuvojen moottorit)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1354,45,1352,'Hydraulisten voimalaitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1355,45,1352,'Pumppujen ja kompressoreiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1356,45,1352,'Muiden hanojen ja venttiilien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1357,45,1352,'Laakereiden, hammaspyÃ¶rien, vaihteisto- ja ohjauselementtien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1358,45,1351,'Muiden yleiskÃ¤yttÃ¶Ã¶n tarkoitettujen koneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1359,45,1358,'Teollisuusuunien, lÃ¤mmitysjÃ¤rjestelmien ja tulipesÃ¤polttimien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1360,45,1358,'Nosto- ja siirtolaitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1361,45,1358,'Konttorikoneiden ja -laitteiden valmistus (pl. tietokoneet ja niiden oheislaitteet)2817',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1362,45,1358,'VoimakÃ¤yttÃ¶isten kÃ¤sityÃ¶kalujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1363,45,1358,'Muuhun kuin kotitalouskÃ¤yttÃ¶Ã¶n tarkoitettujen jÃ¤Ã¤hdytys- ja tuuletuslaitteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1364,45,1358,'Muualla luokittelematon yleiskÃ¤yttÃ¶Ã¶n tarkoitettujen koneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1365,45,1351,'Maa- ja metsÃ¤talouskoneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1366,45,1365,'Maa- ja metsÃ¤talouskoneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1367,45,1351,'Metallin tyÃ¶stÃ¶koneiden ja konetyÃ¶kalujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1368,45,1367,'Metallin tyÃ¶stÃ¶koneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1369,45,1367,'Muiden konetyÃ¶kalujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1370,45,1351,'Muiden erikoiskoneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1371,45,1370,'Metallinjalostuskoneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1372,45,1370,'Kaivos-, louhinta- ja rakennuskoneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1373,45,1370,'Elintarvike-, juoma- ja tupakkateollisuuden koneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1374,45,1370,'Tekstiili-, vaate- ja nahkateollisuuden koneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1375,45,1370,'Paperi-, kartonki- ja pahviteollisuuden koneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1376,45,1370,'Muovi- ja kumiteollisuuden koneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1377,45,1370,'Muualla luokittelematon erikoiskoneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1378,45,1084,'Moottoriajoneuvojen, perÃ¤vaunujen ja puoliperÃ¤vaunujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1379,45,1378,'Moottoriajoneuvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1380,45,1379,'Moottoriajoneuvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1381,45,1378,'Moottoriajoneuvojen korien valmistus; perÃ¤vaunujen ja puoliperÃ¤vaunujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1382,45,1381,'Moottoriajoneuvojen korien valmistus; perÃ¤vaunujen ja puoliperÃ¤vaunujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1383,45,1378,'Osien ja tarvikkeiden valmistus moottoriajoneuvoihin',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1384,45,1383,'SÃ¤hkÃ¶- ja elektroniikkalaitteiden valmistus moottoriajoneuvoihin',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1385,45,1383,'Muiden osien ja tarvikkeiden valmistus moottoriajoneuvoihin',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1386,45,1084,'Muiden kulkuneuvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1387,45,1386,'Laivojen ja veneiden rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1388,45,1387,'Laivojen ja kelluvien rakenteiden rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1389,45,1387,'Huvi- ja urheiluveneiden rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1390,45,1386,'Raideliikenteen kulkuneuvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1391,45,1390,'Raideliikenteen kulkuneuvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1392,45,1386,'Ilma- ja avaruusalusten ja niihin liittyvien koneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1393,45,1392,'Ilma- ja avaruusalusten ja niihin liittyvien koneiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1394,45,1386,'Taisteluajoneuvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1395,45,1394,'Taisteluajoneuvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1396,45,1386,'Muualla luokittelematon kulkuneuvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1397,45,1396,'MoottoripyÃ¶rien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1398,45,1396,'PolkupyÃ¶rien ja invalidiajoneuvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1399,45,1396,'Muiden muualla luokittelemattomien kulkuneuvojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1400,45,1084,'Huonekalujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1401,45,1400,'Huonekalujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1402,45,1401,'Konttori- ja myymÃ¤lÃ¤kalusteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1403,45,1401,'KeittiÃ¶kalusteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1404,45,1401,'Patjojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1405,45,1401,'Muiden huonekalujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1406,45,1084,'Muu valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1407,45,1406,'Korujen, kultasepÃ¤ntuotteiden ja muiden vastaavien tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1408,45,1407,'Kolikoiden ja mitalien valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1409,45,1407,'Jalokivikorujen ja muiden kultasepÃ¤ntuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1410,45,1407,'JÃ¤ljitelmÃ¤korujen ja muiden vastaavien tuotteiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1411,45,1406,'Soitinten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1412,45,1411,'Soitinten valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1413,45,1406,'UrheiluvÃ¤lineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1414,45,1413,'UrheiluvÃ¤lineiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1415,45,1406,'Pelien ja leikkikalujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1416,45,1415,'Pelien ja leikkikalujen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1417,45,1406,'LÃ¤Ã¤kintÃ¤- ja hammaslÃ¤Ã¤kintÃ¤instrumenttien ja -tarvikkeiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1418,45,1417,'LÃ¤Ã¤kintÃ¤- ja hammaslÃ¤Ã¤kintÃ¤instrumenttien ja -tarvikkeiden valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1419,45,1406,'Muualla luokittelematon valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1420,45,1419,'Luutien ja harjojen valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1421,45,1419,'Muu muualla luokittelematon valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1422,45,1084,'Koneiden ja laitteiden korjaus, huolto ja asennus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1423,45,1422,'Metallituotteiden, teollisuuden koneiden ja laitteiden korjaus ja huolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1424,45,1423,'Metallituotteiden korjaus ja huolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1425,45,1423,'Teollisuuden koneiden ja laitteiden korjaus ja huolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1426,45,1423,'Elektronisten ja optisten laitteiden korjaus ja huolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1427,45,1423,'SÃ¤hkÃ¶laitteiden korjaus ja huolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1428,45,1423,'Laivojen ja veneiden korjaus ja huolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1429,45,1423,'Ilma- ja avaruusalusten korjaus ja huolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1430,45,1423,'Muiden kulkuneuvojen korjaus ja huolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1431,45,1423,'Muiden laitteiden korjaus ja huolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1432,45,1422,'Teollisuuden koneiden ja laitteiden ym. asennus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1433,45,1432,'Teollisuuden koneiden ja laitteiden ym. asennus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1434,45,0,'SÃ¤hkÃ¶-, kaasu-, lÃ¤mpÃ¶- ja ilmastointihuolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1435,45,1434,'SÃ¤hkÃ¶-, kaasu-, lÃ¤mpÃ¶- ja ilmastointihuolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1436,45,1435,'SÃ¤hkÃ¶voiman tuotanto, siirto ja jakelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1437,45,1436,'SÃ¤hkÃ¶n tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1438,45,1436,'SÃ¤hkÃ¶n siirto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1439,45,1436,'SÃ¤hkÃ¶n jakelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1440,45,1436,'SÃ¤hkÃ¶n kauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1441,45,1435,'Kaasun tuotanto; kaasumaisten polttoaineiden jakelu putkiverkossa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1442,45,1441,'Kaasun tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1443,45,1441,'Kaasumaisten polttoaineiden jakelu putkiverkossa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1444,45,1441,'Kaasun kauppa putkiverkossa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1445,45,1435,'LÃ¤mmÃ¶n ja kylmÃ¤n tuotanto ja jakelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1446,45,1445,'LÃ¤mmÃ¶n ja kylmÃ¤n tuotanto ja jakelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1447,45,0,'Vesihuolto, viemÃ¤ri- ja jÃ¤tevesihuolto, jÃ¤tehuolto ja muu ympÃ¤ristÃ¶n puhtaanapito',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1448,45,1447,'Veden otto, puhdistus ja jakelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1449,45,1448,'Veden otto, puhdistus ja jakelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1450,45,1449,'Veden otto, puhdistus ja jakelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1451,45,1447,'ViemÃ¤ri- ja jÃ¤tevesihuolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1452,45,1451,'ViemÃ¤ri- ja jÃ¤tevesihuolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1453,45,1452,'ViemÃ¤ri- ja jÃ¤tevesihuolto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1454,45,1447,'JÃ¤tteen keruu, kÃ¤sittely ja loppusijoitus; materiaalien kierrÃ¤tys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1455,45,1454,'JÃ¤tteen keruu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1456,45,1455,'Tavanomaisen jÃ¤tteen keruu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1457,45,1455,'OngelmajÃ¤tteen keruu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1458,45,1454,'JÃ¤tteen kÃ¤sittely ja loppusijoitus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1459,45,1458,'Tavanomaisen jÃ¤tteen kÃ¤sittely ja loppusijoitus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1460,45,1458,'OngelmajÃ¤tteen kÃ¤sittely, loppusijoitus ja hÃ¤vittÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1461,45,1454,'Materiaalien kierrÃ¤tys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1462,45,1461,'Romujen purkaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1463,45,1461,'Lajiteltujen materiaalien kierrÃ¤tys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1464,45,1447,'MaaperÃ¤n ja vesistÃ¶jen kunnostus ja muut ympÃ¤ristÃ¶nhuoltopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1465,45,1464,'MaaperÃ¤n ja vesistÃ¶jen kunnostus ja muut ympÃ¤ristÃ¶nhuoltopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1466,45,1465,'MaaperÃ¤n ja vesistÃ¶jen kunnostus ja muut ympÃ¤ristÃ¶nhuoltopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1467,45,0,'Rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1468,45,1467,'Talonrakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1469,45,1468,'Rakennuttaminen ja rakennushankkeiden kehittÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1470,45,1469,'Rakennuttaminen ja rakennushankkeiden kehittÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1471,45,1468,'Asuin- ja muiden rakennusten rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1472,45,1471,'Asuin- ja muiden rakennusten rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1473,45,1467,'Maa- ja vesirakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1474,45,1473,'Teiden ja rautateiden rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1475,45,1474,'Teiden ja moottoriteiden rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1476,45,1474,'Rautateiden ja metrolinjojen rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1477,45,1474,'Siltojen ja tunneleiden rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1478,45,1473,'Yleisten jakeluverkkojen rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1479,45,1478,'Yleisten jakeluverkkojen rakentaminen nestemÃ¤isiÃ¤ ja kaasumaisia aineita varten',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1480,45,1478,'SÃ¤hkÃ¶- ja tietoliikenneverkkojen rakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1481,45,1473,'Muu maa- ja vesirakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1482,45,1481,'Vesirakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1483,45,1481,'Muualla luokittelematon maa- ja vesirakentaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1484,45,1467,'Erikoistunut rakennustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1485,45,1484,'Rakennusten ja rakennelmien purku ja rakennuspaikan valmistelutyÃ¶t',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1486,45,1485,'Rakennusten ja rakennelmien purku',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1487,45,1485,'Rakennuspaikan valmistelutyÃ¶t',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1488,45,1485,'Koeporaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1489,45,1484,'SÃ¤hkÃ¶-, vesijohto- ja muu rakennusasennus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1490,45,1489,'SÃ¤hkÃ¶asennus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1491,45,1489,'LÃ¤mpÃ¶-, vesijohto- ja ilmastointiasennus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1492,45,1489,'Muu rakennusasennus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1493,45,1484,'Rakennusten ja rakennelmien viimeistely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1494,45,1493,'Rappaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1495,45,1493,'RakennuspuusepÃ¤n asennustyÃ¶t',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1496,45,1493,'LattianpÃ¤Ã¤llystys ja seinien verhoilu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1497,45,1493,'Maalaus ja lasitus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1498,45,1493,'Muu rakennusten viimeistely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1499,45,1484,'Muu erikoistunut rakennustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1500,45,1499,'Kattorakenteiden asennus ja kattaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1501,45,1499,'Muualla luokittelematon erikoistunut rakennustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1502,45,0,'Tukku- ja vÃ¤hittÃ¤iskauppa; moottoriajoneuvojen ja moottoripyÃ¶rien korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1503,45,1502,'Moottoriajoneuvojen ja moottoripyÃ¶rien tukku- ja vÃ¤hittÃ¤iskauppa sekÃ¤ korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1504,45,1503,'Moottoriajoneuvojen kauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1505,45,1504,'HenkilÃ¶autojen ja kevyiden moottoriajoneuvojen kauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1506,45,1504,'Muiden moottoriajoneuvojen myynti',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1507,45,1503,'Moottoriajoneuvojen huolto ja korjaus (pl. moottoripyÃ¶rÃ¤t)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1508,45,1507,'Moottoriajoneuvojen huolto ja korjaus (pl. moottoripyÃ¶rÃ¤t)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1509,45,1503,'Moottoriajoneuvojen osien ja varusteiden kauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1510,45,1509,'Moottoriajoneuvojen osien ja varusteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1511,45,1509,'Moottoriajoneuvojen osien ja varusteiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1512,45,1503,'MoottoripyÃ¶rien sekÃ¤ niiden osien ja varusteiden myynti, huolto ja korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1513,45,1512,'MoottoripyÃ¶rien sekÃ¤ niiden osien ja varusteiden myynti, huolto ja korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1514,45,1502,'Tukkukauppa (pl. moottoriajoneuvojen ja moottoripyÃ¶rien kauppa)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1515,45,1514,'Agentuuritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1516,45,1515,'Maatalousraaka-aineiden, elÃ¤vien elÃ¤inten, tekstiiliraaka-aineiden sekÃ¤ puolivalmisteiden agentuuritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1517,45,1515,'Polttoaineiden, malmien, metallien ja teollisuuskemikaalien agentuuritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1518,45,1515,'Puutavaran ja rakennusmateriaalien agentuuritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1519,45,1515,'Koneiden ja laitteiden agentuuritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1520,45,1515,'Huonekalujen, taloustavaroiden ja rautakauppatavaroiden agentuuritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1521,45,1515,'Tekstiilien, vaatteiden, jalkineiden ja nahkavalmisteiden agentuuritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1522,45,1515,'Elintarvikkeiden, juomien ja tupakan agentuuritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1523,45,1515,'Muu erikoistunut agentuuritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1524,45,1515,'Yleisagentuuritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1525,45,1514,'MaatalousperÃ¤isten raaka-aineiden ja elÃ¤vien elÃ¤inten tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1526,45,1525,'Viljan, raakatupakan, siementen ja elÃ¤inrehujen tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1527,45,1525,'Kukkien ja taimien tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1528,45,1525,'ElÃ¤vien elÃ¤inten tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1529,45,1525,'Turkisten ja nahkojen tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1530,45,1514,'Elintarvikkeiden, juomien ja tupakan tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1531,45,1530,'Juures-, vihannes- marja- ja hedelmÃ¤tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1532,45,1530,'Lihan ja lihatuotteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1533,45,1530,'Maitotaloustuotteiden, munien sekÃ¤ ravintoÃ¶ljyjen ja -rasvojen tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1534,45,1530,'Alkoholi- ja muiden juomien tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1535,45,1530,'Tupakkatuotteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1536,45,1530,'Sokerin, suklaan, makeisten ja leipomotuotteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1537,45,1530,'Kahvin, teen, kaakaon ja mausteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1538,45,1530,'Kalan, luontaistuotteiden ja muiden elintarvikkeiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1539,45,1530,'Elintarvikkeiden, juomien ja tupakan yleistukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1540,45,1514,'Taloustavaroiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1541,45,1540,'Tekstiilien tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1542,45,1540,'Vaatteiden ja jalkineiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1543,45,1540,'Kodinkoneiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1544,45,1540,'Posliini-, lasi- ja muiden taloustavaroiden sekÃ¤ puhdistusaineiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1545,45,1540,'Hajuvesien ja kosmetiikan tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1546,45,1540,'Farmaseuttisten tuotteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1547,45,1540,'Huonekalujen, mattojen ja valaisimien tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1548,45,1540,'Kellojen ja korujen tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1549,45,1540,'Muiden taloustavaroiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1550,45,1514,'Tieto- ja viestintÃ¤teknisten laitteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1551,45,1550,'Tietokoneiden, oheislaitteiden ja ohjelmistojen tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1552,45,1550,'Elektroniikka- ja viestintÃ¤laitteiden ja osien tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1553,45,1514,'Muiden koneiden, laitteiden ja tarvikkeiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1554,45,1553,'Maa- ja metsÃ¤talouskoneiden ja -tarvikkeiden tukkukauppa mukaanlukien traktorit',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1555,45,1553,'TyÃ¶stÃ¶koneiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1556,45,1553,'Kaivos- ja rakennuskoneiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1557,45,1553,'Tekstiiliteollisuuden koneiden sekÃ¤ ompelu- ja kutomakoneiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1558,45,1553,'Toimitilakalusteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1559,45,1553,'Muiden konttorikoneiden ja -laitteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1560,45,1553,'Muiden koneiden, laitteiden ja tarvikkeiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1561,45,1514,'Muu erikoistunut tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1562,45,1561,'Kiinteiden, nestemÃ¤isten ja kaasumaisten polttoaineiden yms. tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1563,45,1561,'Raakametallien ja metallimalmien tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1564,45,1561,'Puun, rakennusmateriaalien ja saniteettilaitteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1565,45,1561,'Rautakauppatavaroiden, lvi-laitteiden ja -tarvikkeiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1566,45,1561,'Peruskemikaalien, lannoitteiden yms. tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1567,45,1561,'Muiden vÃ¤lituotteiden tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1568,45,1561,'JÃ¤tteen ja romun tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1569,45,1514,'Muu tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1570,45,1569,'Muu tukkukauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1571,45,1502,'VÃ¤hittÃ¤iskauppa (pl. moottoriajoneuvojen ja moottoripyÃ¶rien kauppa)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1572,45,1571,'VÃ¤hittÃ¤iskauppa erikoistumattomissa myymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1573,45,1572,'Elintarvikkeiden, juomien ja tupakan erikoistumaton vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1574,45,1572,'Muu vÃ¤hittÃ¤iskauppa erikoistumattomissa myymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1575,45,1571,'Elintarvikkeiden, juomien ja tupakan vÃ¤hittÃ¤iskauppa erikoismyymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1576,45,1575,'Hedelmien, marjojen ja vihannesten vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1577,45,1575,'Lihan ja lihatuotteiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1578,45,1575,'Kalan, Ã¤yriÃ¤isten ja nilviÃ¤isten vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1579,45,1575,'Leipomotuotteiden ja makeisten vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1580,45,1575,'Alkoholi- ja muiden juomien vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1581,45,1575,'Tupakkatuotteiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1582,45,1575,'Muu vÃ¤hittÃ¤iskauppa erikoismyymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1583,45,1571,'Ajoneuvojen polttoaineen vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1584,45,1583,'Ajoneuvojen polttoaineen vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1585,45,1571,'Tieto- ja viestintÃ¤teknisten laitteiden vÃ¤hittÃ¤iskauppa erikoismyymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1586,45,1585,'Tietokoneiden, niiden oheislaitteiden ja ohjelmistojen vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1587,45,1585,'TeleviestintÃ¤laitteiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1588,45,1585,'Viihde-elektroniikan vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1589,45,1571,'Muiden kotitaloustavaroiden vÃ¤hittÃ¤iskauppa erikoismyymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1590,45,1589,'Tekstiilien vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1591,45,1589,'Rautakauppatavaran, maalien ja lasin vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1592,45,1589,'Mattojen, tapettien ja lattianpÃ¤Ã¤llysteiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1593,45,1589,'SÃ¤hkÃ¶isten kodinkoneiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1594,45,1589,'Huonekalujen, valaisimien ja muualla luokittelemattomien taloustarvikkeiden vÃ¤hittÃ¤iskauppa erikoismyymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1595,45,1571,'Kulttuuri- ja vapaa-ajan tuotteiden vÃ¤hittÃ¤iskauppa erikoismyymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1596,45,1595,'Kirjojen vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1597,45,1595,'Sanomalehtien ja paperitavaran vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1598,45,1595,'Musiikki- ja videotallenteiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1599,45,1595,'UrheiluvÃ¤lineiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1600,45,1595,'Pelien ja leikkikalujen vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1601,45,1571,'Muiden tavaroiden vÃ¤hittÃ¤iskauppa erikoismyymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1602,45,1601,'Vaatteiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1603,45,1601,'Jalkineiden ja nahkatavaroiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1604,45,1601,'Apteekit',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1605,45,1601,'Terveydenhoitotarvikkeiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1606,45,1601,'Kosmetiikka- ja hygieniatuotteiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1607,45,1601,'Kukkien, kasvien, siementen, lannoitteiden, lemmikkielÃ¤inten ja niiden ruokien vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1608,45,1601,'KultasepÃ¤nteosten ja kellojen vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1609,45,1601,'Muu uusien tavaroiden vÃ¤hittÃ¤iskauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1610,45,1601,'KÃ¤ytettyjen tavaroiden vÃ¤hittÃ¤iskauppa myymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1611,45,1571,'Tori- ja markkinakauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1612,45,1611,'Elintarvikkeiden, juomien ja tupakkatuotteiden vÃ¤hittÃ¤iskauppa kojuista ja toreilla',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1613,45,1611,'Tekstiilien, vaatteiden ja jalkineiden vÃ¤hittÃ¤iskauppa kojuista ja toreilla',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1614,45,1611,'Muiden tavaroiden vÃ¤hittÃ¤iskauppa kojuista ja toreilla',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1615,45,1571,'VÃ¤hittÃ¤iskauppa muualla kuin myymÃ¤lÃ¶issÃ¤ (pl. tori- ja markkinakauppa)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1616,45,1615,'VÃ¤hittÃ¤iskauppa postimyyntiliikkeiden tai internetin vÃ¤lityksellÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1617,45,1615,'Muu vÃ¤hittÃ¤iskauppa muualla kuin myymÃ¤lÃ¶issÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1618,45,0,'Kuljetus ja varastointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1619,45,1618,'Maaliikenne ja putkijohtokuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1620,45,1619,'Rautateiden henkilÃ¶liikenne, kaukoliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1621,45,1620,'Rautateiden henkilÃ¶liikenne, kaukoliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1622,45,1619,'Rautateiden tavaraliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1623,45,1622,'Rautateiden tavaraliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1624,45,1619,'Muu maaliikenteen henkilÃ¶liikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1625,45,1624,'Paikallisliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1626,45,1624,'Taksiliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1627,45,1624,'Muualla luokittelematon maaliikenteen henkilÃ¶liikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1628,45,1619,'Tieliikenteen tavarakuljetus ja muuttopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1629,45,1628,'Tieliikenteen tavarakuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1630,45,1628,'Muuttopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1631,45,1628,'Putkijohtokuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1632,45,1628,'Putkijohtokuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1633,45,1618,'Vesiliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1634,45,1633,'Meri- ja rannikkovesiliikenteen henkilÃ¶kuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1635,45,1634,'Meri- ja rannikkovesiliikenteen henkilÃ¶kuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1636,45,1633,'Meri- ja rannikkovesiliikenteen tavarakuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1637,45,1636,'Meri- ja rannikkovesiliikenteen tavarakuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1638,45,1633,'SisÃ¤vesiliikenteen henkilÃ¶kuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1639,45,1638,'SisÃ¤vesiliikenteen henkilÃ¶kuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1640,45,1633,'SisÃ¤vesiliikenteen tavarakuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1641,45,1640,'SisÃ¤vesiliikenteen tavarakuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1642,45,1618,'Ilmaliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1643,45,1642,'Matkustajalentoliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1644,45,1643,'Matkustajalentoliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1645,45,1642,'Lentoliikenteen tavarakuljetus ja avaruusliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1646,45,1645,'Lentoliikenteen tavarakuljetus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1647,45,1645,'Avaruusliikenne',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1648,45,1618,'Varastointi ja liikennettÃ¤ palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1649,45,1648,'Varastointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1650,45,1649,'Varastointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1651,45,1648,'LiikennettÃ¤ palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1652,45,1651,'MaaliikennettÃ¤ palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1653,45,1651,'VesiliikennettÃ¤ palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1654,45,1651,'IlmaliikennettÃ¤ palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1655,45,1651,'LastinkÃ¤sittely',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1656,45,1651,'Muu liikennettÃ¤ palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1657,45,1618,'Posti- ja kuriiritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1658,45,1657,'Postin yleispalvelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1659,45,1658,'Postin yleispalvelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1660,45,1657,'Muu posti-, jakelu- ja kuriiritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1661,45,1660,'Muu posti-, jakelu- ja kuriiritoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1662,45,0,'Majoitus- ja ravitsemistoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1663,45,1662,'Majoitus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1664,45,1663,'Hotellit ja vastaavat majoitusliikkeet',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1665,45,1664,'Hotellit ja vastaavat majoitusliikkeet',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1666,45,1663,'LomakylÃ¤t, retkeilymajat yms. majoitus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1667,45,1666,'LomakylÃ¤t, retkeilymajat yms. majoitus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1668,45,1663,'LeirintÃ¤alueet, asuntovaunu- ja matkailuvaunualueet',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1669,45,1668,'LeirintÃ¤alueet, asuntovaunu- ja matkailuvaunualueet',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1670,45,1663,'Muu majoitus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1671,45,1670,'Muu majoitus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1672,45,1662,'Ravitsemistoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1673,45,1672,'Ravintolat ja vastaava ravitsemistoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1674,45,1673,'Ravintolat ja vastaava ravitsemistoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1675,45,1672,'Ateriapalvelut ja muut ravitsemispalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1676,45,1675,'Pitopalvelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1677,45,1675,'HenkilÃ¶stÃ¶- ja laitosruokalat',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1678,45,1672,'Baarit ja kahvilat',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1679,45,1678,'Baarit ja kahvilat',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1680,45,0,'Informaatio ja viestintÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1681,45,1680,'Kustannustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1682,45,1681,'Kirjojen ja lehtien kustantaminen ja muu kustannustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1683,45,1682,'Kirjojen kustantaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1684,45,1682,'Hakemistojen ja postituslistojen julkaiseminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1685,45,1682,'Sanomalehtien kustantaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1686,45,1682,'Aikakauslehtien ja harvemmin ilmestyvien sanomalehtien kustantaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1687,45,1682,'Muu kustannustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1688,45,1681,'Ohjelmistojen kustantaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1689,45,1688,'Tietokonepelien kustantaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1690,45,1688,'Muu ohjelmistojen kustantaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1691,45,1680,'Elokuva-, video- ja televisio-ohjelmatuotanto, Ã¤Ã¤nitteiden ja musiikin kustantaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1692,45,1691,'Elokuva-, video- ja televisio-ohjelmatoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1693,45,1692,'Elokuvien, videoiden ja televisio-ohjelmien tuotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1694,45,1692,'Elokuvien, video- ja televisio-ohjelmien jÃ¤lkituotanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1695,45,1692,'Elokuvien, videoiden ja televisio-ohjelmien levitys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1696,45,1692,'Elokuvien esittÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1697,45,1691,'Ã„Ã¤nitysstudiot; Ã¤Ã¤nitteiden ja musiikin kustantaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1698,45,1697,'Ã„Ã¤nitysstudiot; Ã¤Ã¤nitteiden ja musiikin kustantaminen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1699,45,1680,'Radio- ja televisiotoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1700,45,1699,'Radio-ohjelmien tuottaminen ja lÃ¤hettÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1701,45,1700,'Radio-ohjelmien tuottaminen ja lÃ¤hettÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1702,45,1699,'Televisio-ohjelmien tuottaminen ja lÃ¤hettÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1703,45,1702,'Televisio-ohjelmien tuottaminen ja lÃ¤hettÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1704,45,1680,'TeleviestintÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1705,45,1704,'KiinteÃ¤n puhelinverkon hallinta ja palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1706,45,1705,'KiinteÃ¤n puhelinverkon hallinta ja palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1707,45,1704,'Langattoman verkon hallinta ja palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1708,45,1707,'Langattoman verkon hallinta ja palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1709,45,1704,'SatelliittiviestintÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1710,45,1709,'SatelliittiviestintÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1711,45,1704,'Muut televiestintÃ¤palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1712,45,1711,'Muut televiestintÃ¤palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1713,45,1680,'Ohjelmistot, konsultointi ja siihen liittyvÃ¤ toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1714,45,1713,'Ohjelmistot, konsultointi ja siihen liittyvÃ¤ toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1715,45,1714,'Ohjelmistojen suunnittelu ja valmistus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1716,45,1714,'Atk-laitteisto- ja ohjelmistokonsultointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1717,45,1714,'TietojenkÃ¤sittelyn ja laitteistojen kÃ¤yttÃ¶- ja hallintapalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1718,45,1714,'Muu laitteisto- ja tietotekninen palvelutoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1719,45,1680,'Tietopalvelutoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1720,45,1719,'TietojenkÃ¤sittely, palvelintilan vuokraus ja niihin liittyvÃ¤t palvelut;verkkoportaalit',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1721,45,1720,'TietojenkÃ¤sittely, palvelintilan vuokraus ja niihin liittyvÃ¤t palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1722,45,1720,'Verkkoportaalit',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1723,45,1719,'Muu tietopalvelutoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1724,45,1723,'Uutistoimistot',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1725,45,1723,'Muualla luokittelematon tietopalvelutoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1726,45,0,'Rahoitus- ja vakuutustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1727,45,1726,'Rahoituspalvelut (pl. vakuutus- ja elÃ¤kevakuutustoiminta)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1728,45,1727,'Pankkitoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1729,45,1728,'Keskuspankkitoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1730,45,1728,'Muu pankkitoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1731,45,1727,'Rahoitusalan holdingyhtiÃ¶iden toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1732,45,1731,'Rahoitusalan holdingyhtiÃ¶iden toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1733,45,1727,'SÃ¤Ã¤tiÃ¶t, rahastot ja muut varainhoitoyhteisÃ¶t',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1734,45,1733,'SÃ¤Ã¤tiÃ¶t, rahastot ja muut varainhoitoyhteisÃ¶t',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1735,45,1727,'Muut rahoituspalvelut (pl. vakuutus- ja elÃ¤kevakuutustoiminta)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1736,45,1734,'Rahoitusleasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1737,45,1734,'Muu luotonanto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1738,45,1734,'Muualla luokittelemattomat rahoituspavelut (pl. vakuutus- ja elÃ¤kevakuutustoiminta)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1739,45,1726,'Vakuutus-, jÃ¤lleenvakuutus- ja elÃ¤kevakuutustoiminta (pl. pakollinen sosiaalivakuutus)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1740,45,1739,'Vakuutustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1741,45,1740,'Henkivakuutustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1742,45,1740,'Muu vakuutustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1743,45,1739,'JÃ¤lleenvakuutustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1744,45,1743,'JÃ¤lleenvakuutustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1745,45,1739,'ElÃ¤kevakuutustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1746,45,1745,'ElÃ¤kevakuutustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1747,45,1726,'Rahoitusta ja vakuuttamista palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1748,45,1747,'Rahoitusta ja vakuuttamista palveleva toiminta (pl. vakuutus- ja elÃ¤kevakuutustoiminta)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1749,45,1748,'PÃ¶rssitoiminta ja rahoitusmarkkinoiden hallinnolliset tukipalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1750,45,1748,'Arvopaperien ja raaka-ainesopimusten vÃ¤littÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1751,45,1748,'Muu rahoitusta palveleva toiminta (pl. vakuutus- ja elÃ¤kevakuutustoiminta)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1752,45,1747,'Vakuutus- ja elÃ¤kevakuutustoimintaa avustava toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1753,45,1752,'Riskin- ja vahingonarviointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1754,45,1752,'Vakuutusasiamiesten ja -vÃ¤littÃ¤jien toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1755,45,1752,'Muu vakuutus- ja elÃ¤kevakuutustoimintaa avustava toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1756,45,1747,'Omaisuudenhoitotoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1757,45,1756,'Omaisuudenhoitotoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1758,45,0,'KiinteistÃ¶alan toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1759,45,1758,'KiinteistÃ¶alan toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1760,45,1759,'Omien kiinteistÃ¶jen kauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1761,45,1760,'Omien kiinteistÃ¶jen kauppa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1762,45,1759,'Omien tai leasing-kiinteistÃ¶jen vuokraus ja hallinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1763,45,1762,'Omien tai leasing-kiinteistÃ¶jen vuokraus ja hallinta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1764,45,1759,'KiinteistÃ¶alan toiminta palkkio- tai sopimusperusteisesti',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1765,45,1764,'KiinteistÃ¶nvÃ¤litys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1766,45,1764,'KiinteistÃ¶jen isÃ¤nnÃ¶inti',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1767,45,0,'Ammatillinen, tieteellinen ja tekninen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1768,45,1767,'Lakiasiain- ja laskentatoimen palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1769,45,1768,'Lakiasiainpalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1770,45,1769,'Lakiasiainpalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1771,45,1768,'Laskentatoimi, kirjanpito ja tilintarkastus; veroneuvonta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1772,45,1771,'Laskentatoimi, kirjanpito ja tilintarkastus; veroneuvonta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1773,45,1767,'PÃ¤Ã¤konttorien toiminta; liikkeenjohdon konsultointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1774,45,1773,'PÃ¤Ã¤konttorien toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1775,45,1775,'PÃ¤Ã¤konttorien toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1776,45,1773,'Liikkeenjohdon konsultointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1777,45,1776,'Suhdetoiminta ja viestintÃ¤',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1778,45,1776,'Muu liikkeenjohdon konsultointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1779,45,1767,'Arkkitehti- ja insinÃ¶Ã¶ripalvelut; tekninen testaus ja analysointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1780,45,1779,'Arkkitehti- ja insinÃ¶Ã¶ripalvelut ja niihin liittyvÃ¤ tekninen konsultointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1781,45,1780,'Arkkitehtipalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1782,45,1780,'InsinÃ¶Ã¶ripalvelut ja niihin liittyvÃ¤ tekninen konsultointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1783,45,1779,'Tekninen testaus ja analysointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1784,45,1783,'Tekninen testaus ja analysointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1785,45,1767,'Tieteellinen tutkimus ja kehittÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1786,45,1785,'Luonnontieteen ja tekniikan tutkimus ja kehittÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1787,45,1786,'Biotekninen tutkimus ja kehittÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1788,45,1786,'Muu luonnontieteellinen ja tekninen tutkimus ja kehittÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1789,45,1785,'Yhteiskuntatieteellinen ja humanistinen tutkimus ja kehittÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1790,45,1789,'Yhteiskuntatieteellinen ja humanistinen tutkimus ja kehittÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1791,45,1767,'Mainostoiminta ja markkinatutkimus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1792,45,1791,'Mainostoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1793,45,1792,'Mainostoimistot ja mainospalvelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1794,45,1792,'Mainostilan vuokraus ja myynti',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1795,45,1791,'Markkina- ja mielipidetutkimukset',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1796,45,1795,'Markkina- ja mielipidetutkimukset',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1797,45,1767,'Muut erikoistuneet palvelut liike-elÃ¤mÃ¤lle',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1798,45,1797,'Taideteollinen muotoilu ja suunnittelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1799,45,1798,'Taideteollinen muotoilu ja suunnittelu',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1800,45,1797,'Valokuvaustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1801,45,1800,'Valokuvaustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1802,45,1797,'KÃ¤Ã¤ntÃ¤minen ja tulkkaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1803,45,1802,'KÃ¤Ã¤ntÃ¤minen ja tulkkaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1804,45,1797,'Muualla luokittelemattomat erikoistuneet palvelut liike-elÃ¤mÃ¤lle',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1805,45,1804,'Muualla luokittelemattomat erikoistuneet palvelut liike-elÃ¤mÃ¤lle',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1806,45,1767,'ElÃ¤inlÃ¤Ã¤kintÃ¤palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1807,45,1806,'ElÃ¤inlÃ¤Ã¤kintÃ¤palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1808,45,1807,'ElÃ¤inlÃ¤Ã¤kintÃ¤palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1809,45,0,'Hallinto- ja tukipalvelutoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1810,45,1809,'Vuokraus- ja leasingtoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1811,45,1810,'Moottoriajoneuvojen vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1812,45,1811,'Autojen ja kevyiden moottoriajoneuvojen vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1813,45,1811,'Kuorma-autojen ja muiden raskaiden ajoneuvojen vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1814,45,1810,'HenkilÃ¶kohtaisten ja kotitaloustavaroiden vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1815,45,1814,'Vapaa-ajan ja urheiluvÃ¤lineiden vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1816,45,1814,'Videofilmien vuokraus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1817,45,1814,'Muiden henkilÃ¶kohtaisten ja kotitaloustavaroiden vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1818,45,1810,'Koneiden ja laitteiden vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1819,45,1818,'Maatalouskoneiden ja -laitteiden vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1820,45,1818,'Rakennuskoneiden ja -laitteiden vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1821,45,1818,'Toimistokoneiden ja -laitteiden sekÃ¤ tietokoneiden vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1822,45,1818,'VesiliikennevÃ¤lineiden vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1823,45,1818,'IlmaliikennevÃ¤lineiden vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1824,45,1818,'Muiden koneiden ja laitteiden vuokraus ja leasing',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1825,45,1810,'Henkisen omaisuuden ja vastaavien tuotteiden leasing (pl. tekijÃ¤noikeuden suojaamat teokset)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1826,45,1825,'Henkisen omaisuuden ja vastaavien tuotteiden leasing (pl. tekijÃ¤noikeuden suojaamat teokset)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1827,45,1809,'TyÃ¶llistÃ¤mistoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1828,45,1827,'TyÃ¶nvÃ¤litystoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1829,45,1828,'TyÃ¶nvÃ¤litystoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1830,45,1827,'TyÃ¶voiman vuokraus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1831,45,1830,'TyÃ¶voiman vuokraus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1832,45,1827,'Muut henkilÃ¶stÃ¶n hankintapalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1833,45,1832,'Muut henkilÃ¶stÃ¶n hankintapalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1834,45,1809,'Matkatoimistojen ja matkanjÃ¤rjestÃ¤jien toiminta; varauspalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1835,45,1834,'Matkatoimistojen ja matkanjÃ¤rjestÃ¤jien toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1836,45,1835,'Matkatoimistojen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1837,45,1835,'MatkanjÃ¤rjestÃ¤jien toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1838,45,1834,'Varauspalvelut, matkaoppaiden palvelut ym.',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1839,45,1838,'Varauspalvelut, matkaoppaiden palvelut ym.',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1840,45,1809,'Turvallisuus-, vartiointi- ja etsivÃ¤palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1841,45,1840,'Yksityiset turvallisuuspalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1842,45,1841,'Yksityiset turvallisuuspalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1843,45,1840,'TurvallisuusjÃ¤rjestelmÃ¤t',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1844,45,1843,'TurvallisuusjÃ¤rjestelmÃ¤t',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1845,45,1840,'EtsivÃ¤toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1846,45,1845,'EtsivÃ¤toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1847,45,1809,'KiinteistÃ¶n- ja maisemanhoito',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1848,45,1847,'KiinteistÃ¶nhoito',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1849,45,1848,'KiinteistÃ¶nhoito',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1850,45,1847,'Siivouspalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1851,45,1850,'KiinteistÃ¶jen siivous',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1852,45,1850,'Muu rakennus- ja teollisuussiivous',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1853,45,1850,'Muu siivoustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1854,45,1847,'Maisemanhoitopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1855,45,1854,'Maisemanhoitopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1856,45,1809,'Hallinto- ja tukipalvelut liike-elÃ¤mÃ¤lle',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1857,45,1856,'Hallinto- ja toimistopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1858,45,1857,'Yhdistetyt toimistopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1859,45,1857,'Sihteeri-, toimisto- ja postituspalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1860,45,1856,'Puhelinpalvelukeskusten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1861,45,1860,'Puhelinpalvelukeskusten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1862,45,1856,'Messujen ja kongressien jÃ¤rjestÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1863,45,1862,'Messujen ja kongressien jÃ¤rjestÃ¤minen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1864,45,1856,'Muu liike-elÃ¤mÃ¤Ã¤ palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1865,45,1864,'PerintÃ¤- ja luottotietopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1866,45,1864,'Pakkauspalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1867,45,1864,'Muut palvelut liike-elÃ¤mÃ¤lle',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1868,45,0,'Julkinen hallinto ja maanpuolustu3s; pakollinen sosiaalivakuutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1869,45,1868,'Julkinen hallinto ja maanpuolustus; pakollinen sosiaalivakuutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1870,45,1869,'Julkinen hallinto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1871,45,1870,'Julkinen yleishallinto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1872,45,1870,'Terveydenhuollon, opetuksen, kulttuurin ja muiden yhteiskuntapalvelujen hallinto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1873,45,1870,'TyÃ¶voima- ja elinkeinoasiain hallinto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1874,45,1869,'Ulkoasian hallinto, maanpuolustus ja jÃ¤rjestystoimi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1875,45,1874,'Ulkoasiainhallinto',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1876,45,1874,'Maanpuolustus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1877,45,1874,'Oikeustoimi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1878,45,1874,'Poliisitoimi ja rajojen vartiointi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1879,45,1874,'Palo- ja pelastustoimi',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1880,45,1869,'Pakollinen sosiaalivakuutustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1881,45,1880,'Pakollinen sosiaalivakuutustoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1882,45,0,'Koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1883,45,1882,'Koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1884,45,1883,'Esiasteen koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1885,45,1884,'Esiasteen koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1886,45,1883,'Alemman perusasteen koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1887,45,1886,'Alemman perusasteen koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1888,45,1883,'YlemmÃ¤n perusasteen ja keskiasteen koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1889,45,1888,'YlemmÃ¤n perusasteen koulutus ja lukiokoulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1890,45,1888,'Keskiasteen ammatillinen koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1891,45,1883,'Korkea-asteen koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1892,45,1891,'Korkea-asteen koulutus (pl. yliopistot ja ammattikorkeakoulut)',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1893,45,1891,'Korkea-asteen koulutus yliopistoissa ja ammattikorkeakouluissa',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1894,45,1883,'Muu koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1895,45,1894,'Urheilu- ja liikuntakoulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1896,45,1894,'Taiteen ja musiikin koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1897,45,1894,'Kuljettajakoulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1898,45,1894,'Muualla luokittelematon koulutus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1899,45,1883,'Koulutusta palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1900,45,1899,'Koulutusta palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1901,45,0,'Terveys- ja sosiaalipalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1902,45,1901,'Terveyspalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1903,45,1902,'Terveydenhuollon laitospalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1904,45,1903,'Terveydenhuollon laitospalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1905,45,1902,'LÃ¤Ã¤kÃ¤ri- ja hammaslÃ¤Ã¤kÃ¤ripalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1906,45,1905,'Terveyskeskus- ja vastaavat yleislÃ¤Ã¤kÃ¤ripalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1907,45,1905,'LÃ¤Ã¤kÃ¤riasemat, yksityislÃ¤Ã¤kÃ¤rit ja vastaavat erikoislÃ¤Ã¤kÃ¤ripalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1908,45,1905,'HammaslÃ¤Ã¤kÃ¤ripalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1909,45,1902,'Muut terveydenhuoltopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1910,45,1909,'Muut terveydenhuoltopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1911,45,1901,'Sosiaalihuollon laitospalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1912,45,1911,'Sosiaalihuollon hoitolaitokset',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1913,45,1912,'Sosiaalihuollon hoitolaitokset',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1914,45,1911,'Kehitysvammaisten sekÃ¤ mielenterveys- ja pÃ¤ihdeongelmaisten asumispalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1915,45,1914,'Kehitysvammaisten sekÃ¤ mielenterveys- ja pÃ¤ihdeongelmaisten asumispalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1916,45,1911,'Vanhusten ja vammaisten asumispalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1917,45,1916,'Vanhusten ja vammaisten asumispalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1918,45,1911,'Muut sosiaalihuollon laitospalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1919,45,1918,'Muut sosiaalihuollon laitospalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1920,45,1901,'Sosiaalihuollon avopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1921,45,1920,'Vanhusten ja vammaisten sosiaalihuollon avopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1922,45,1921,'Vanhusten ja vammaisten sosiaalihuollon avopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1923,45,1920,'Muut sosiaalihuollon avopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1924,45,1923,'Lasten pÃ¤ivÃ¤hoitopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1925,45,1923,'Muualla luokittelemattomat sosiaalihuollon avopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1926,45,0,'Taiteet, viihde ja virkistys',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1927,45,1926,'Kulttuuri- ja viihdetoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1928,45,1927,'Kulttuuri- ja viihdetoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1929,45,1928,'EsittÃ¤vÃ¤t taiteet',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1930,45,1928,'EsittÃ¤viÃ¤ taiteita palveleva toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1931,45,1928,'Taiteellinen luominen',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1932,45,1928,'Taidelaitosten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1933,45,1926,'Kirjastojen, arkistojen, museoiden ja muiden kulttuurilaitosten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1934,45,1933,'Kirjastojen, arkistojen, museoiden ja muiden kulttuurilaitosten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1935,45,1934,'Kirjastojen ja arkistojen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1936,45,1934,'Museoiden toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1937,45,1934,'Historiallisten nÃ¤htÃ¤vyyksien, rakennusten ja vastaavien kohteiden toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1938,45,1934,'Kasvitieteellisten puutarhojen, elÃ¤intarhojen ja luonnonpuistojen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1939,45,1926,'Rahapeli- ja vedonlyÃ¶ntipalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1940,45,1939,'Rahapeli- ja vedonlyÃ¶ntipalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1941,45,1940,'Rahapeli- ja vedonlyÃ¶ntipalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1942,45,1926,'Urheilutoiminta sekÃ¤ huvi- ja virkistyspalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1943,45,1942,'Urheilutoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1944,45,1943,'Urheilulaitosten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1945,45,1943,'Urheiluseurojen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1946,45,1943,'Kuntokeskukset',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1947,45,1943,'Muu urheilutoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1948,45,1942,'Huvi- ja virkistystoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1949,45,1948,'Huvi- ja teemapuistojen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1950,45,1948,'Muu huvi- ja virkistystoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1951,45,0,'Muu palvelutoiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1952,45,1951,'JÃ¤rjestÃ¶jen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1953,45,1952,'ElinkeinoelÃ¤mÃ¤n, tyÃ¶nantaja- ja ammattialajÃ¤rjestÃ¶jen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1954,45,1953,'ElinkeinoelÃ¤mÃ¤n ja tyÃ¶nantajajÃ¤rjestÃ¶jen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1955,45,1953,'AmmattialajÃ¤rjestÃ¶jen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1956,45,1952,'Ammattiyhdistysten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1957,45,1956,'Ammattiyhdistysten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1958,45,1952,'Muiden jÃ¤rjestÃ¶jen toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1959,45,1958,'Seurakunnat ja uskonnolliset jÃ¤rjestÃ¶t',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1960,45,1958,'Poliittiset jÃ¤rjestÃ¶t',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1961,45,1958,'Muut jÃ¤rjestÃ¶t',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1962,45,1951,'Tietokoneiden, henkilÃ¶kohtaisten ja kotitaloustavaroiden korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1963,45,1962,'Tietokoneiden ja viestintÃ¤laitteiden korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1964,45,1963,'Tietokoneiden ja niiden oheislaitteiden korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1965,45,1963,'ViestintÃ¤laitteiden korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1966,45,1962,'HenkilÃ¶kohtaisten ja kotitaloustavaroiden korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1967,45,1966,'Viihde-elektroniikan korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1968,45,1966,'Kotitalouskoneiden sekÃ¤ kodin ja puutarhan laitteiden korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1969,45,1966,'Jalkineiden ja nahkatavaroiden korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1970,45,1966,'Huonekalujen ja kodin kalusteiden korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1971,45,1966,'Kellojen ja korujen korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1972,45,1966,'Muiden henkilÃ¶kohtaisten ja kotitaloustavaroiden korjaus',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1973,45,1951,'Muut henkilÃ¶kohtaiset palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1974,45,1973,'Muut henkilÃ¶kohtaiset palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1975,45,1974,'Pesulapalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1976,45,1974,'Kampaamo- ja kauneudenhoitopalvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1977,45,1974,'Hautaustoimistojen palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1978,45,1974,'Kylpylaitokset, saunat, solariumit yms. palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1979,45,1974,'Muualla luokittelemattomat henkilÃ¶kohtaiset palvelut',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1980,45,0,'Kotitalouksien toiminta tyÃ¶nantajina; kotitalouksien eriyttÃ¤mÃ¤tÃ¶n toiminta tavaroiden ja palvelujen tuottamiseksi omaan kÃ¤yttÃ¶Ã¶n',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1981,45,1980,'Kotitalouksien toiminta kotitaloustyÃ¶ntekijÃ¶iden tyÃ¶nantajina',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1982,45,1981,'Kotitalouksien toiminta kotitaloustyÃ¶ntekijÃ¶iden tyÃ¶nantajina',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1983,45,1982,'Kotitalouksien toiminta kotitaloustyÃ¶ntekijÃ¶iden tyÃ¶nantajina',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1984,45,1980,'Kotitalouksien eriyttÃ¤mÃ¤tÃ¶n toiminta tavaroiden ja palvelujen tuottamiseksi omaan kÃ¤yttÃ¶Ã¶n',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1985,45,1984,'Kotitalouksien eriyttÃ¤mÃ¤tÃ¶n toiminta tavaroiden tuottamiseksi omaan kÃ¤yttÃ¶Ã¶n',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1986,45,1985,'Kotitalouksien eriyttÃ¤mÃ¤tÃ¶n toiminta tavaroiden tuottamiseksi omaan kÃ¤yttÃ¶Ã¶n',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1987,45,1984,'Kotitalouksien eriyttÃ¤mÃ¤tÃ¶n toiminta palvelujen tuottamiseksi omaan kÃ¤yttÃ¶Ã¶n',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1988,45,1987,'Kotitalouksien eriyttÃ¤mÃ¤tÃ¶n toiminta palvelujen tuottamiseksi omaan kÃ¤yttÃ¶Ã¶n',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1989,45,0,'KansainvÃ¤listen organisaatioiden ja toimielinten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1990,45,1989,'KansainvÃ¤listen organisaatioiden ja toimielinten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1991,45,1990,'KansainvÃ¤listen organisaatioiden ja toimielinten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51'),(1992,45,1991,'KansainvÃ¤listen organisaatioiden ja toimielinten toiminta',NULL,'2010-08-18 15:35:51','2010-08-18 15:35:51');
/*!40000 ALTER TABLE `industries_ind` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `innovation_types_ivt`
--

DROP TABLE IF EXISTS `innovation_types_ivt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `innovation_types_ivt` (
  `id_ivt` int(11) NOT NULL AUTO_INCREMENT,
  `name_ivt` varchar(255) NOT NULL,
  `description_ivt` varchar(512) DEFAULT NULL,
  `created_ivt` datetime DEFAULT NULL,
  `modified_ivt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_ivt`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `innovation_types_ivt`
--

LOCK TABLES `innovation_types_ivt` WRITE;
/*!40000 ALTER TABLE `innovation_types_ivt` DISABLE KEYS */;
INSERT INTO `innovation_types_ivt` VALUES (1,'Product innovation','','2010-08-18 15:35:51','2010-08-18 15:35:51'),(2,'Process innovation','','2010-08-18 15:35:51','2010-08-18 15:35:51'),(3,'Organisational innovation','','2010-08-18 15:35:51','2010-08-18 15:35:51'),(4,'Structural innovation','','2010-08-18 15:35:51','2010-08-18 15:35:51'),(5,'Market innovation','','2010-08-18 15:35:51','2010-08-18 15:35:51');
/*!40000 ALTER TABLE `innovation_types_ivt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages_lng`
--

DROP TABLE IF EXISTS `languages_lng`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages_lng` (
  `id_lng` int(11) NOT NULL AUTO_INCREMENT,
  `iso6391_lng` varchar(5) NOT NULL,
  `name_lng` varchar(255) NOT NULL,
  `created_lng` datetime DEFAULT NULL,
  `modified_lng` datetime DEFAULT NULL,
  PRIMARY KEY (`id_lng`)
) ENGINE=MyISAM AUTO_INCREMENT=186 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages_lng`
--

LOCK TABLES `languages_lng` WRITE;
/*!40000 ALTER TABLE `languages_lng` DISABLE KEYS */;
INSERT INTO `languages_lng` VALUES (1,'af','Afrikaans','2010-08-18 15:49:06','2010-08-18 15:49:06'),(2,'sq','Albanian','2010-08-18 15:49:06','2010-08-18 15:49:06'),(3,'am','Amharic','2010-08-18 15:49:06','2010-08-18 15:49:06'),(4,'ar','Arabic','2010-08-18 15:49:06','2010-08-18 15:49:06'),(5,'hy','Armenian','2010-08-18 15:49:06','2010-08-18 15:49:06'),(6,'az','Azerbaijani','2010-08-18 15:49:06','2010-08-18 15:49:06'),(7,'eu','Basque','2010-08-18 15:49:06','2010-08-18 15:49:06'),(8,'be','Belarusian','2010-08-18 15:49:06','2010-08-18 15:49:06'),(9,'bn','Bengali','2010-08-18 15:49:06','2010-08-18 15:49:06'),(10,'bh','Bihari','2010-08-18 15:49:06','2010-08-18 15:49:06'),(11,'bg','Bulgarian','2010-08-18 15:49:06','2010-08-18 15:49:06'),(12,'my','Burmese','2010-08-18 15:49:06','2010-08-18 15:49:06'),(13,'ca','Catalan','2010-08-18 15:49:06','2010-08-18 15:49:06'),(14,'chr','Cherokee','2010-08-18 15:49:06','2010-08-18 15:49:06'),(15,'zh','Chinese','2010-08-18 15:49:06','2010-08-18 15:49:06'),(16,'zh-CN','Chinese_simplified','2010-08-18 15:49:06','2010-08-18 15:49:06'),(17,'zh-TW','Chinese_traditional','2010-08-18 15:49:06','2010-08-18 15:49:06'),(18,'hr','Croatian','2010-08-18 15:49:06','2010-08-18 15:49:06'),(19,'cs','Czech','2010-08-18 15:49:06','2010-08-18 15:49:06'),(20,'da','Danish','2010-08-18 15:49:06','2010-08-18 15:49:06'),(21,'dv','Dhivehi','2010-08-18 15:49:06','2010-08-18 15:49:06'),(22,'nl','Dutch','2010-08-18 15:49:06','2010-08-18 15:49:06'),(23,'en','English','2010-08-18 15:49:06','2010-08-18 15:49:06'),(24,'eo','Esperanto','2010-08-18 15:49:06','2010-08-18 15:49:06'),(25,'et','Estonian','2010-08-18 15:49:06','2010-08-18 15:49:06'),(26,'tl','Filipino','2010-08-18 15:49:06','2010-08-18 15:49:06'),(27,'fi','Finnish','2010-08-18 15:49:06','2010-08-18 15:49:06'),(28,'fr','French','2010-08-18 15:49:06','2010-08-18 15:49:06'),(29,'gl','Galician','2010-08-18 15:49:06','2010-08-18 15:49:06'),(30,'ka','Georgian','2010-08-18 15:49:06','2010-08-18 15:49:06'),(31,'de','German','2010-08-18 15:49:06','2010-08-18 15:49:06'),(32,'el','Greek','2010-08-18 15:49:06','2010-08-18 15:49:06'),(33,'gn','Guarani','2010-08-18 15:49:06','2010-08-18 15:49:06'),(34,'gu','Gujarati','2010-08-18 15:49:06','2010-08-18 15:49:06'),(35,'iw','Hebrew','2010-08-18 15:49:06','2010-08-18 15:49:06'),(36,'hi','Hindi','2010-08-18 15:49:06','2010-08-18 15:49:06'),(37,'hu','Hungarian','2010-08-18 15:49:06','2010-08-18 15:49:06'),(38,'is','Icelandic','2010-08-18 15:49:06','2010-08-18 15:49:06'),(39,'id','Indonesian','2010-08-18 15:49:06','2010-08-18 15:49:06'),(40,'iu','Inuktitut','2010-08-18 15:49:06','2010-08-18 15:49:06'),(41,'ga','Irish','2010-08-18 15:49:06','2010-08-18 15:49:06'),(42,'it','Italian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(43,'ja','Japanese','2010-08-18 15:49:07','2010-08-18 15:49:07'),(44,'kn','Kannada','2010-08-18 15:49:07','2010-08-18 15:49:07'),(45,'kk','Kazakh','2010-08-18 15:49:07','2010-08-18 15:49:07'),(46,'km','Khmer','2010-08-18 15:49:07','2010-08-18 15:49:07'),(47,'ko','Korean','2010-08-18 15:49:07','2010-08-18 15:49:07'),(48,'ku','Kurdish','2010-08-18 15:49:07','2010-08-18 15:49:07'),(49,'ky','Kyrgyz','2010-08-18 15:49:07','2010-08-18 15:49:07'),(50,'lo','Laothian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(51,'lv','Latvian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(52,'lt','Lithuanian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(53,'mk','Macedonian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(54,'ms','Malay','2010-08-18 15:49:07','2010-08-18 15:49:07'),(55,'ml','Malayalam','2010-08-18 15:49:07','2010-08-18 15:49:07'),(56,'mt','Maltese','2010-08-18 15:49:07','2010-08-18 15:49:07'),(57,'mr','Marathi','2010-08-18 15:49:07','2010-08-18 15:49:07'),(58,'mn','Mongolian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(59,'ne','Nepali','2010-08-18 15:49:07','2010-08-18 15:49:07'),(60,'no','Norwegian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(61,'or','Oriya','2010-08-18 15:49:07','2010-08-18 15:49:07'),(62,'ps','Pashto','2010-08-18 15:49:07','2010-08-18 15:49:07'),(63,'fa','Persian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(64,'pl','Polish','2010-08-18 15:49:07','2010-08-18 15:49:07'),(65,'pt-PT','Portuguese','2010-08-18 15:49:07','2010-08-18 15:49:07'),(66,'pa','Punjabi','2010-08-18 15:49:07','2010-08-18 15:49:07'),(67,'ro','Romanian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(68,'ru','Russian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(69,'sa','Sanskrit','2010-08-18 15:49:07','2010-08-18 15:49:07'),(70,'sr','Serbian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(71,'sd','Sindhi','2010-08-18 15:49:07','2010-08-18 15:49:07'),(72,'si','Sinhalese','2010-08-18 15:49:07','2010-08-18 15:49:07'),(73,'sk','Slovak','2010-08-18 15:49:07','2010-08-18 15:49:07'),(74,'sl','Slovenian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(75,'es','Spanish','2010-08-18 15:49:07','2010-08-18 15:49:07'),(76,'sw','Swahili','2010-08-18 15:49:07','2010-08-18 15:49:07'),(77,'sv','Swedish','2010-08-18 15:49:07','2010-08-18 15:49:07'),(78,'tg','Tajik','2010-08-18 15:49:07','2010-08-18 15:49:07'),(79,'ta','Tamil','2010-08-18 15:49:07','2010-08-18 15:49:07'),(80,'tl','Tagalog','2010-08-18 15:49:07','2010-08-18 15:49:07'),(81,'te','Telugu','2010-08-18 15:49:07','2010-08-18 15:49:07'),(82,'th','Thai','2010-08-18 15:49:07','2010-08-18 15:49:07'),(83,'bo','Tibetan','2010-08-18 15:49:07','2010-08-18 15:49:07'),(84,'tr','Turkish','2010-08-18 15:49:07','2010-08-18 15:49:07'),(85,'uk','Ukrainian','2010-08-18 15:49:07','2010-08-18 15:49:07'),(86,'ur','Urdu','2010-08-18 15:49:07','2010-08-18 15:49:07'),(87,'uz','Uzbek','2010-08-18 15:49:07','2010-08-18 15:49:07'),(88,'ug','Uighur','2010-08-18 15:49:07','2010-08-18 15:49:07'),(89,'vi','Vietnamese','2010-08-18 15:49:07','2010-08-18 15:49:07'),(90,'cy','Welsh','2010-08-18 15:49:07','2010-08-18 15:49:07'),(91,'yi','Yiddish','2010-08-18 15:49:07','2010-08-18 15:49:07');
/*!40000 ALTER TABLE `languages_lng` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `links_lnk`
--

DROP TABLE IF EXISTS `links_lnk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `links_lnk` (
  `id_lnk` int(11) NOT NULL AUTO_INCREMENT,
  `id_cnt_lnk` int(11) NOT NULL,
  `id_usr_lnk` int(11) NOT NULL,
  `url_lnk` varchar(4000) NOT NULL,
  `created_lnk` datetime DEFAULT NULL,
  `modified_lnk` datetime DEFAULT NULL,
  PRIMARY KEY (`id_lnk`),
  KEY `fk_cnt_lnk` (`id_cnt_lnk`),
  KEY `fk_usr_lnk` (`id_usr_lnk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `links_lnk`
--

LOCK TABLES `links_lnk` WRITE;
/*!40000 ALTER TABLE `links_lnk` DISABLE KEYS */;
/*!40000 ALTER TABLE `links_lnk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications_ntf`
--

DROP TABLE IF EXISTS `notifications_ntf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications_ntf` (
  `id_ntf` int(11) NOT NULL,
  `notification_ntf` varchar(20) DEFAULT NULL,
  `description_ntf` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_ntf`),
  UNIQUE KEY `id_ntf` (`id_ntf`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications_ntf`
--

LOCK TABLES `notifications_ntf` WRITE;
/*!40000 ALTER TABLE `notifications_ntf` DISABLE KEYS */;
INSERT INTO `notifications_ntf` VALUES (1,'privmsg','New private message'),(2,'comment','New comment on content'),(3,'link','New content to content link');
/*!40000 ALTER TABLE `notifications_ntf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_types_ptp`
--

DROP TABLE IF EXISTS `page_types_ptp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_types_ptp` (
  `id_ptp` int(11) NOT NULL AUTO_INCREMENT,
  `type_ptp` int(11) NOT NULL,
  `type_name_ptp` varchar(50) NOT NULL,
  PRIMARY KEY (`id_ptp`),
  UNIQUE KEY `id_ctp` (`id_ptp`),
  UNIQUE KEY `type_ctp` (`type_ptp`),
  UNIQUE KEY `id_ptp` (`id_ptp`),
  UNIQUE KEY `type_ptp` (`type_ptp`),
  UNIQUE KEY `type_name_ptp` (`type_name_ptp`),
  KEY `fk_type_ctp` (`type_ptp`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_types_ptp`
--

LOCK TABLES `page_types_ptp` WRITE;
/*!40000 ALTER TABLE `page_types_ptp` DISABLE KEYS */;
INSERT INTO `page_types_ptp` VALUES (1,4,'group'),(2,1,'account'),(3,2,'content'),(4,3,'campaign');
/*!40000 ALTER TABLE `page_types_ptp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions_prm`
--

DROP TABLE IF EXISTS `permissions_prm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions_prm` (
  `id_prm` int(11) NOT NULL AUTO_INCREMENT,
  `key_prm` varchar(255) NOT NULL,
  `value_prm` tinyint(1) NOT NULL,
  `created_prm` datetime DEFAULT NULL,
  `modified_prm` datetime DEFAULT NULL,
  PRIMARY KEY (`id_prm`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions_prm`
--

LOCK TABLES `permissions_prm` WRITE;
/*!40000 ALTER TABLE `permissions_prm` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions_prm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `private_messages_pmg`
--

DROP TABLE IF EXISTS `private_messages_pmg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `private_messages_pmg` (
  `id_pmg` int(11) NOT NULL AUTO_INCREMENT,
  `id_sender_pmg` int(11) NOT NULL,
  `id_receiver_pmg` int(11) NOT NULL,
  `header_pmg` varchar(255) NOT NULL,
  `message_body_pmg` text NOT NULL,
  `sender_email_pmg` varchar(255) DEFAULT NULL,
  `read_pmg` tinyint(4) DEFAULT NULL,
  `created_pmg` datetime DEFAULT NULL,
  `modified_pmg` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pmg`),
  KEY `fk_sender_usr_pmg` (`id_sender_pmg`),
  KEY `fk_receiver_usr_pmg` (`id_receiver_pmg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `private_messages_pmg`
--

LOCK TABLES `private_messages_pmg` WRITE;
/*!40000 ALTER TABLE `private_messages_pmg` DISABLE KEYS */;
/*!40000 ALTER TABLE `private_messages_pmg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `related_companies_rec`
--

DROP TABLE IF EXISTS `related_companies_rec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `related_companies_rec` (
  `id_rec` int(11) NOT NULL AUTO_INCREMENT,
  `name_rec` varchar(255) NOT NULL,
  `description_rec` text,
  `created_rec` datetime DEFAULT NULL,
  `modified_rec` datetime DEFAULT NULL,
  PRIMARY KEY (`id_rec`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `related_companies_rec`
--

LOCK TABLES `related_companies_rec` WRITE;
/*!40000 ALTER TABLE `related_companies_rec` DISABLE KEYS */;
/*!40000 ALTER TABLE `related_companies_rec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rss_feeds_rss`
--

DROP TABLE IF EXISTS `rss_feeds_rss`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rss_feeds_rss` (
  `id_rss` int(11) NOT NULL AUTO_INCREMENT,
  `id_target_rss` int(11) NOT NULL,
  `url_rss` text NOT NULL,
  `created_rss` datetime DEFAULT NULL,
  `modified_rss` datetime DEFAULT NULL,
  `type_rss` int(11) NOT NULL,
  PRIMARY KEY (`id_rss`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rss_feeds_rss`
--

LOCK TABLES `rss_feeds_rss` WRITE;
/*!40000 ALTER TABLE `rss_feeds_rss` DISABLE KEYS */;
/*!40000 ALTER TABLE `rss_feeds_rss` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stylesheets_sth`
--

DROP TABLE IF EXISTS `stylesheets_sth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stylesheets_sth` (
  `id_sth` int(11) NOT NULL AUTO_INCREMENT,
  `id_usr_sth` int(11) NOT NULL,
  `name_sth` varchar(512) NOT NULL,
  `created_sth` datetime DEFAULT NULL,
  `modified_sth` datetime DEFAULT NULL,
  PRIMARY KEY (`id_sth`),
  KEY `fk_stylesheets_sth_users_usr1` (`id_usr_sth`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stylesheets_sth`
--

LOCK TABLES `stylesheets_sth` WRITE;
/*!40000 ALTER TABLE `stylesheets_sth` DISABLE KEYS */;
/*!40000 ALTER TABLE `stylesheets_sth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags_tag`
--

DROP TABLE IF EXISTS `tags_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags_tag` (
  `id_tag` int(11) NOT NULL AUTO_INCREMENT,
  `name_tag` varchar(255) NOT NULL,
  `views_tag` int(11) NOT NULL DEFAULT '0',
  `description_tag` text,
  `created_tag` datetime DEFAULT NULL,
  `modified_tag` datetime DEFAULT NULL,
  PRIMARY KEY (`id_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags_tag`
--

LOCK TABLES `tags_tag` WRITE;
/*!40000 ALTER TABLE `tags_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timezones_tmz`
--

DROP TABLE IF EXISTS `timezones_tmz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timezones_tmz` (
  `id_tmz` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `timezone_location_tmz` varchar(30) NOT NULL DEFAULT '',
  `gmt_tmz` varchar(11) NOT NULL DEFAULT '',
  `offset_tmz` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tmz`)
) ENGINE=MyISAM AUTO_INCREMENT=143 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timezones_tmz`
--

LOCK TABLES `timezones_tmz` WRITE;
/*!40000 ALTER TABLE `timezones_tmz` DISABLE KEYS */;
INSERT INTO `timezones_tmz` VALUES (1,'International Date Line West','(GMT-12:00)',-12),(2,'Midway Island','(GMT-11:00)',-11),(3,'Samoa','(GMT-11:00)',-11),(4,'Hawaii','(GMT-10:00)',-10),(5,'Alaska','(GMT-09:00)',-9),(6,'Pacific Time (US & Canada)','(GMT-08:00)',-8),(7,'Tijuana','(GMT-08:00)',-8),(8,'Arizona','(GMT-07:00)',-7),(9,'Mountain Time (US & Canada)','(GMT-07:00)',-7),(10,'Chihuahua','(GMT-07:00)',-7),(11,'La Paz','(GMT-07:00)',-7),(12,'Mazatlan','(GMT-07:00)',-7),(13,'Central Time (US & Canada)','(GMT-06:00)',-6),(14,'Central America','(GMT-06:00)',-6),(15,'Guadalajara','(GMT-06:00)',-6),(16,'Mexico City','(GMT-06:00)',-6),(17,'Monterrey','(GMT-06:00)',-6),(18,'Saskatchewan','(GMT-06:00)',-6),(19,'Eastern Time (US & Canada)','(GMT-05:00)',-5),(20,'Indiana (East)','(GMT-05:00)',-5),(21,'Bogota','(GMT-05:00)',-5),(22,'Lima','(GMT-05:00)',-5),(23,'Quito','(GMT-05:00)',-5),(24,'Atlantic Time (Canada)','(GMT-04:00)',-4),(25,'Caracas','(GMT-04:00)',-4),(26,'La Paz','(GMT-04:00)',-4),(27,'Santiago','(GMT-04:00)',-4),(28,'Newfoundland','(GMT-03:30)',-3),(29,'Brasilia','(GMT-03:00)',-3),(30,'Buenos Aires','(GMT-03:00)',-3),(31,'Georgetown','(GMT-03:00)',-3),(32,'Greenland','(GMT-03:00)',-3),(33,'Mid-Atlantic','(GMT-02:00)',-2),(34,'Azores','(GMT-01:00)',-1),(35,'Cape Verde Is.','(GMT-01:00)',-1),(36,'Casablanca','(GMT)',0),(37,'Dublin','(GMT)',0),(38,'Edinburgh','(GMT)',0),(39,'Lisbon','(GMT)',0),(40,'London','(GMT)',0),(41,'Monrovia','(GMT)',0),(42,'Amsterdam','(GMT+01:00)',1),(43,'Belgrade','(GMT+01:00)',1),(44,'Berlin','(GMT+01:00)',1),(45,'Bern','(GMT+01:00)',1),(46,'Bratislava','(GMT+01:00)',1),(47,'Brussels','(GMT+01:00)',1),(48,'Budapest','(GMT+01:00)',1),(49,'Copenhagen','(GMT+01:00)',1),(50,'Ljubljana','(GMT+01:00)',1),(51,'Madrid','(GMT+01:00)',1),(52,'Paris','(GMT+01:00)',1),(53,'Prague','(GMT+01:00)',1),(54,'Rome','(GMT+01:00)',1),(55,'Sarajevo','(GMT+01:00)',1),(56,'Skopje','(GMT+01:00)',1),(57,'Stockholm','(GMT+01:00)',1),(58,'Vienna','(GMT+01:00)',1),(59,'Warsaw','(GMT+01:00)',1),(60,'West Central Africa','(GMT+01:00)',1),(61,'Zagreb','(GMT+01:00)',1),(62,'Athens','(GMT+02:00)',2),(63,'Bucharest','(GMT+02:00)',2),(64,'Cairo','(GMT+02:00)',2),(65,'Harare','(GMT+02:00)',2),(66,'Helsinki','(GMT+02:00)',2),(67,'Istanbul','(GMT+02:00)',2),(68,'Jerusalem','(GMT+02:00)',2),(69,'Kyev','(GMT+02:00)',2),(70,'Minsk','(GMT+02:00)',2),(71,'Pretoria','(GMT+02:00)',2),(72,'Riga','(GMT+02:00)',2),(73,'Sofia','(GMT+02:00)',2),(74,'Tallinn','(GMT+02:00)',2),(75,'Vilnius','(GMT+02:00)',2),(76,'Baghdad','(GMT+03:00)',3),(77,'Kuwait','(GMT+03:00)',3),(78,'Moscow','(GMT+03:00)',3),(79,'Nairobi','(GMT+03:00)',3),(80,'Riyadh','(GMT+03:00)',3),(81,'St. Petersburg','(GMT+03:00)',3),(82,'Volgograd','(GMT+03:00)',3),(83,'Tehran','(GMT+03:30)',3),(84,'Abu Dhabi','(GMT+04:00)',4),(85,'Baku','(GMT+04:00)',4),(86,'Muscat','(GMT+04:00)',4),(87,'Tbilisi','(GMT+04:00)',4),(88,'Yerevan','(GMT+04:00)',4),(89,'Kabul','(GMT+04:30)',4),(90,'Ekaterinburg','(GMT+05:00)',5),(91,'Islamabad','(GMT+05:00)',5),(92,'Karachi','(GMT+05:00)',5),(93,'Tashkent','(GMT+05:00)',5),(94,'Chennai','(GMT+05:30)',5),(95,'Kolkata','(GMT+05:30)',5),(96,'Mumbai','(GMT+05:30)',5),(97,'New Delhi','(GMT+05:30)',5),(98,'Kathmandu','(GMT+05:45)',5),(99,'Almaty','(GMT+06:00)',6),(100,'Astana','(GMT+06:00)',6),(101,'Dhaka','(GMT+06:00)',6),(102,'Novosibirsk','(GMT+06:00)',6),(103,'Sri Jayawardenepura','(GMT+06:00)',6),(104,'Rangoon','(GMT+06:30)',6),(105,'Bangkok','(GMT+07:00)',7),(106,'Hanoi','(GMT+07:00)',7),(107,'Jakarta','(GMT+07:00)',7),(108,'Krasnoyarsk','(GMT+07:00)',7),(109,'Beijing','(GMT+08:00)',8),(110,'Chongqing','(GMT+08:00)',8),(111,'Hong Kong','(GMT+08:00)',8),(112,'Irkutsk','(GMT+08:00)',8),(113,'Kuala Lumpur','(GMT+08:00)',8),(114,'Perth','(GMT+08:00)',8),(115,'Singapore','(GMT+08:00)',8),(116,'Taipei','(GMT+08:00)',8),(117,'Ulaan Bataar','(GMT+08:00)',8),(118,'Urumqi','(GMT+08:00)',8),(119,'Osaka','(GMT+09:00)',9),(120,'Sapporo','(GMT+09:00)',9),(121,'Seoul','(GMT+09:00)',9),(122,'Tokyo','(GMT+09:00)',9),(123,'Yakutsk','(GMT+09:00)',9),(124,'Adelaide','(GMT+09:30)',9),(125,'Darwin','(GMT+09:30)',9),(126,'Brisbane','(GMT+10:00)',10),(127,'Canberra','(GMT+10:00)',10),(128,'Guam','(GMT+10:00)',10),(129,'Hobart','(GMT+10:00)',10),(130,'Melbourne','(GMT+10:00)',10),(131,'Port Moresby','(GMT+10:00)',10),(132,'Sydney','(GMT+10:00)',10),(133,'Vladivostok','(GMT+10:00)',10),(134,'Magadan','(GMT+11:00)',11),(135,'New Caledonia','(GMT+11:00)',11),(136,'Solomon Is.','(GMT+11:00)',11),(137,'Auckland','(GMT+12:00)',12),(138,'Fiji','(GMT+12:00)',12),(139,'Kamchatka','(GMT+12:00)',12),(140,'Marshall Is.','(GMT+12:00)',12),(141,'Wellington','(GMT+12:00)',12),(142,'Nuku\'alofa','(GMT+13:00)',13);
/*!40000 ALTER TABLE `timezones_tmz` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `urr_has_upr`
--

DROP TABLE IF EXISTS `urr_has_upr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `urr_has_upr` (
  `id_urr` int(11) NOT NULL,
  `id_upr` int(11) NOT NULL,
  `allow` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_urr`,`id_upr`),
  KEY `fk_urr` (`id_urr`),
  KEY `fk_upr` (`id_upr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `urr_has_upr`
--

LOCK TABLES `urr_has_upr` WRITE;
/*!40000 ALTER TABLE `urr_has_upr` DISABLE KEYS */;
/*!40000 ALTER TABLE `urr_has_upr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_usr`
--

DROP TABLE IF EXISTS `users_usr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_usr` (
  `id_usr` int(11) NOT NULL AUTO_INCREMENT,
  `id_lng_usr` int(11) NOT NULL,
  `login_name_usr` varchar(255) NOT NULL,
  `password_usr` varchar(50) NOT NULL,
  `password_salt_usr` varchar(128) NOT NULL,
  `email_usr` varchar(255) NOT NULL,
  `gravatar_usr` tinyint(2) NOT NULL DEFAULT '0',
  `first_name_usr` varchar(255) DEFAULT NULL,
  `surname_usr` varchar(255) DEFAULT NULL,
  `gender_usr` enum('F','M','U') DEFAULT NULL,
  `last_login_usr` datetime DEFAULT NULL,
  `created_usr` datetime DEFAULT NULL,
  `modified_usr` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usr`),
  KEY `fk_lng_usr` (`id_lng_usr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_usr`
--

LOCK TABLES `users_usr` WRITE;
/*!40000 ALTER TABLE `users_usr` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_usr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_apikeys_uak`
--

DROP TABLE IF EXISTS `usr_apikeys_uak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_apikeys_uak` (
  `id_uak` int(11) NOT NULL AUTO_INCREMENT,
  `id_usr_uak` int(11) NOT NULL,
  `apikey_uak` varchar(128) NOT NULL,
  `created_uak` datetime DEFAULT NULL,
  `modified_uak` datetime DEFAULT NULL,
  PRIMARY KEY (`id_uak`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_apikeys_uak`
--

LOCK TABLES `usr_apikeys_uak` WRITE;
/*!40000 ALTER TABLE `usr_apikeys_uak` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_apikeys_uak` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_groups_grp`
--

DROP TABLE IF EXISTS `usr_groups_grp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_groups_grp` (
  `id_grp` int(11) NOT NULL AUTO_INCREMENT,
  `id_sth_grp` int(11) NOT NULL,
  `group_name_grp` varchar(140) NOT NULL,
  `description_grp` varchar(320) DEFAULT NULL,
  `body_grp` text NOT NULL,
  `has_blog_grp` tinyint(1) NOT NULL,
  `image_grp` varchar(45) DEFAULT NULL,
  `created_grp` datetime DEFAULT NULL,
  `modified_grp` datetime DEFAULT NULL,
  `id_type_grp` int(11) NOT NULL,
  PRIMARY KEY (`id_grp`),
  KEY `fk_usr_groups_grp_stylesheets_sth1` (`id_sth_grp`),
  KEY `fk_id_type_grp` (`id_type_grp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_groups_grp`
--

LOCK TABLES `usr_groups_grp` WRITE;
/*!40000 ALTER TABLE `usr_groups_grp` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_groups_grp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_has_fvr`
--

DROP TABLE IF EXISTS `usr_has_fvr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_has_fvr` (
  `id_cnt` int(11) NOT NULL,
  `id_usr` int(11) NOT NULL,
  `content_edited` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_cnt`,`id_usr`),
  KEY `fk_usr_favourites_fvr_cnt` (`id_cnt`),
  KEY `fk_usr_favourites_fvr_usr` (`id_usr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_has_fvr`
--

LOCK TABLES `usr_has_fvr` WRITE;
/*!40000 ALTER TABLE `usr_has_fvr` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_has_fvr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_has_grp`
--

DROP TABLE IF EXISTS `usr_has_grp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_has_grp` (
  `id_usr` int(11) NOT NULL,
  `id_grp` int(11) NOT NULL,
  PRIMARY KEY (`id_grp`,`id_usr`),
  KEY `fk_usr_has_grp_grp` (`id_grp`),
  KEY `fk_usr_has_grp_usr` (`id_usr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_has_grp`
--

LOCK TABLES `usr_has_grp` WRITE;
/*!40000 ALTER TABLE `usr_has_grp` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_has_grp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_has_grp_waiting`
--

DROP TABLE IF EXISTS `usr_has_grp_waiting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_has_grp_waiting` (
  `id_usr` int(11) NOT NULL,
  `id_grp` int(11) NOT NULL,
  PRIMARY KEY (`id_grp`,`id_usr`),
  KEY `fk_usr_has_grp_waiting_grp` (`id_grp`),
  KEY `fk_usr_has_grp_waiting_usr` (`id_usr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_has_grp_waiting`
--

LOCK TABLES `usr_has_grp_waiting` WRITE;
/*!40000 ALTER TABLE `usr_has_grp_waiting` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_has_grp_waiting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_has_npwd`
--

DROP TABLE IF EXISTS `usr_has_npwd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_has_npwd` (
  `id_npwd` int(11) NOT NULL AUTO_INCREMENT,
  `id_usr_npwd` int(11) NOT NULL,
  `key_npwd` varchar(32) NOT NULL,
  `expire_date_npwd` datetime NOT NULL,
  PRIMARY KEY (`id_npwd`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_has_npwd`
--

LOCK TABLES `usr_has_npwd` WRITE;
/*!40000 ALTER TABLE `usr_has_npwd` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_has_npwd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_has_ntf`
--

DROP TABLE IF EXISTS `usr_has_ntf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_has_ntf` (
  `id_usr` int(11) NOT NULL,
  `id_ntf` int(11) NOT NULL,
  PRIMARY KEY (`id_ntf`,`id_usr`),
  KEY `fk_usr` (`id_usr`),
  KEY `fk_ntf` (`id_ntf`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_has_ntf`
--

LOCK TABLES `usr_has_ntf` WRITE;
/*!40000 ALTER TABLE `usr_has_ntf` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_has_ntf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_has_urr`
--

DROP TABLE IF EXISTS `usr_has_urr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_has_urr` (
  `id_usr` int(11) NOT NULL,
  `id_urr` int(11) NOT NULL,
  PRIMARY KEY (`id_usr`,`id_urr`),
  KEY `fk_usr` (`id_usr`),
  KEY `fk_urr` (`id_urr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_has_urr`
--

LOCK TABLES `usr_has_urr` WRITE;
/*!40000 ALTER TABLE `usr_has_urr` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_has_urr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_images_usi`
--

DROP TABLE IF EXISTS `usr_images_usi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_images_usi` (
  `id_usi` int(11) NOT NULL AUTO_INCREMENT,
  `id_usr_usi` int(11) NOT NULL,
  `thumbnail_usi` longblob NOT NULL,
  `image_usi` longblob NOT NULL,
  `created_usi` datetime DEFAULT NULL,
  `modified_usi` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usi`),
  KEY `fk_usr_usi` (`id_usr_usi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_images_usi`
--

LOCK TABLES `usr_images_usi` WRITE;
/*!40000 ALTER TABLE `usr_images_usi` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_images_usi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_privileges_upr`
--

DROP TABLE IF EXISTS `usr_privileges_upr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_privileges_upr` (
  `id_upr` int(11) NOT NULL AUTO_INCREMENT,
  `id_urc` int(11) NOT NULL,
  `name_upr` varchar(255) NOT NULL,
  PRIMARY KEY (`id_upr`,`id_urc`),
  KEY `fk_urc` (`id_urc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_privileges_upr`
--

LOCK TABLES `usr_privileges_upr` WRITE;
/*!40000 ALTER TABLE `usr_privileges_upr` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_privileges_upr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_profiles_usp`
--

DROP TABLE IF EXISTS `usr_profiles_usp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_profiles_usp` (
  `id_usp` int(11) NOT NULL AUTO_INCREMENT,
  `id_usr_usp` int(11) NOT NULL,
  `profile_key_usp` varchar(255) NOT NULL,
  `profile_value_usp` text NOT NULL,
  `public_usp` tinyint(1) NOT NULL DEFAULT '0',
  `created_usp` datetime DEFAULT NULL,
  `modified_usp` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usp`),
  KEY `fk_usr_usp` (`id_usr_usp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_profiles_usp`
--

LOCK TABLES `usr_profiles_usp` WRITE;
/*!40000 ALTER TABLE `usr_profiles_usp` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_profiles_usp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_resources_urc`
--

DROP TABLE IF EXISTS `usr_resources_urc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_resources_urc` (
  `id_urc` int(11) NOT NULL AUTO_INCREMENT,
  `name_urc` varchar(255) NOT NULL,
  PRIMARY KEY (`id_urc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_resources_urc`
--

LOCK TABLES `usr_resources_urc` WRITE;
/*!40000 ALTER TABLE `usr_resources_urc` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_resources_urc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_roles_urr`
--

DROP TABLE IF EXISTS `usr_roles_urr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_roles_urr` (
  `id_urr` int(11) NOT NULL AUTO_INCREMENT,
  `name_urr` varchar(255) NOT NULL,
  `created_urr` datetime DEFAULT NULL,
  `modified_urr` datetime DEFAULT NULL,
  PRIMARY KEY (`id_urr`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_roles_urr`
--

LOCK TABLES `usr_roles_urr` WRITE;
/*!40000 ALTER TABLE `usr_roles_urr` DISABLE KEYS */;
INSERT INTO `usr_roles_urr` VALUES (1,'user','2010-08-18 15:35:51','2010-08-18 15:35:51'),(2,'admin','2010-08-18 15:35:51','2010-08-18 15:35:51'),(3,'moderator','2010-08-18 15:35:51','2010-08-18 15:35:51'),(4,'translator','2010-08-18 15:35:51','2010-08-18 15:35:51');
/*!40000 ALTER TABLE `usr_roles_urr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usr_weblinks_uwl`
--

DROP TABLE IF EXISTS `usr_weblinks_uwl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usr_weblinks_uwl` (
  `id_uwl` int(11) NOT NULL AUTO_INCREMENT,
  `id_usr_uwl` int(11) NOT NULL,
  `name_uwl` varchar(45) NOT NULL,
  `url_uwl` varchar(150) NOT NULL,
  `count_uwl` int(11) NOT NULL,
  `created_uwl` datetime DEFAULT NULL,
  `modified_uwl` datetime DEFAULT NULL,
  PRIMARY KEY (`id_uwl`),
  KEY `fk_usr_uwl` (`id_usr_uwl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usr_weblinks_uwl`
--

LOCK TABLES `usr_weblinks_uwl` WRITE;
/*!40000 ALTER TABLE `usr_weblinks_uwl` DISABLE KEYS */;
/*!40000 ALTER TABLE `usr_weblinks_uwl` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-08-18 15:50:13

-- Adds created time for linked contents so that they can be sorted out by date
alter table cnt_has_cnt add column `created_cnt` datetime NOT NULL DEFAULT '0000-0-0 00:00:00';

 -- Changes favourites to more like subscribing to content
alter table usr_has_fvr drop content_edited;
alter table usr_has_fvr add last_checked datetime NOT NULL DEFAULT '0000-00-00 00:00:00';


-- Creating follows_flw table to users so they can follow their own contents
-- or follow their favourites. It is used to list options that can be followed when content is set to be followed.
-- For now it only has comment, rating and linking (translation does not exist yet)
-- usr_flw_cnt is meant to hold data of actions that has happened in contents that are followed.
-- bit in followed_cnt refers to follows_flw table's bit so we know what id the id_flw is (is it comment id, or rating id etc.)
DROP TABLE IF EXISTS `follows_flw`;
CREATE TABLE `follows_flw` (
  `bit` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  PRIMARY KEY (`bit`)
);

INSERT INTO `follows_flw` (`bit`, `name`) VALUES(1,'comment'),(2,'rating'),(4,'linking'),(8,'translation'),(16,'modified');

ALTER TABLE `cnt_has_usr` add `last_checked` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';


--
-- Table structure for table `file_links_fli`
--
DROP TABLE IF EXISTS `file_links_fli`;
CREATE TABLE `file_links_fli` (
  `id_target_fli` varchar(50) NOT NULL,
  `id_type_fli` int(11) NOT NULL,
  `id_file` int(11) NOT NULL,
  KEY `id_file` (`id_file`)
) ENGINE=MyISAM;


--
-- adds a flag to the language table for active languages to be displayed in the language selection
--
ALTER TABLE languages_lng
ADD COLUMN active_lng BOOLEAN DEFAULT false;

UPDATE languages_lng
SET active_lng=true
WHERE (name_lng='English') OR (name_lng='Finnish') OR (name_lng='German');

--
--
--
UPDATE content_types_cty SET key_cty= 'vision', name_cty= 'Visions' WHERE id_cty = 1;
UPDATE content_types_cty SET key_cty= 'idea', name_cty= 'Ideas' WHERE id_cty = 2;
UPDATE content_types_cty SET key_cty= 'challenge', name_cty= 'Challenges' WHERE id_cty = 3;



