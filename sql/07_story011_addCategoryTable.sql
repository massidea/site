CREATE TABLE IF NOT EXISTS `categories_ctg` (
  `id_ctg` int(11) NOT NULL AUTO_INCREMENT,
  `title_ctg` varchar(50) NOT NULL,
  PRIMARY KEY (`id_ctg`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `categories_ctg`(`title_ctg`) VALUES ("IT");
INSERT INTO `categories_ctg`(`title_ctg`) VALUES ("Natural sciences");
INSERT INTO `categories_ctg`(`title_ctg`) VALUES ("Astronomy");
INSERT INTO `categories_ctg`(`title_ctg`) VALUES ("Business");
INSERT INTO `categories_ctg`(`title_ctg`) VALUES ("Medicine");
INSERT INTO `categories_ctg`(`title_ctg`) VALUES ("Mechanics");
INSERT INTO `categories_ctg`(`title_ctg`) VALUES ("Agriculture");
INSERT INTO `categories_ctg`(`title_ctg`) VALUES ("Environment");
INSERT INTO `categories_ctg`(`title_ctg`) VALUES ("Art");
INSERT INTO `categories_ctg`(`title_ctg`) VALUES ("Others");

ALTER TABLE `contents_cnt` ADD `id_ctg` int(11) DEFAULT 10;

ALTER TABLE `contents_cnt`
ADD CONSTRAINT `category_fk`
FOREIGN KEY (`id_ctg`)
REFERENCES `categories_ctg`(`id_ctg`);