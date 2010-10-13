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
