-- -----------------------------------------------------
-- Table `oibs`.`rss_feeds_rss`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `rss_feeds_rss` (
  `id_rss` int(11) NOT NULL AUTO_INCREMENT,
  `id_target_rss` int(11) NOT NULL,
  `url_rss` text NOT NULL,
  `created_rss` datetime DEFAULT NULL,
  `modified_rss` datetime DEFAULT NULL,
  `type_rss` int(11) NOT NULL,
  PRIMARY KEY (`id_rss`)
) ENGINE=MyISAM;
