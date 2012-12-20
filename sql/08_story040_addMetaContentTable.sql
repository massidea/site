DROP TABLE IF EXISTS `jobs_job`;
CREATE TABLE IF NOT EXISTS `jobs_job`(
`id_job` INT(11) NOT NULL AUTO_INCREMENT,
`description_job` VARCHAR(255),
PRIMARY KEY (`id_job`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `jobs_job`(`description_job`) VALUES ("Language Teacher");

DROP TABLE IF EXISTS `offer_needs`;
CREATE TABLE IF NOT EXISTS `offer_needs`(
`id_on` INT(11) NOT NULL AUTO_INCREMENT,
`title_on` VARCHAR(255),
PRIMARY KEY (`id_on`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `offer_needs`(`title_on`) VALUES ("Cooperation");

DROP TABLE IF EXISTS `meta`;
CREATE TABLE IF NOT EXISTS `meta` (

`id_meta` INT(11) NOT NULL AUTO_INCREMENT,
`id_job` INT(11) NULL,
`id_ctg` INT(11) NULL,
`location` VARCHAR(255) NULL,
`id_offer` INT(11) NULL,
`id_needs` INT(11) NULL,

PRIMARY KEY (`id_meta`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `meta`
ADD
  CONSTRAINT `jobs_fk`
    FOREIGN KEY (`id_job`)
    REFERENCES `jobs_job`(`id_job`);

ALTER TABLE `meta`
ADD
  CONSTRAINT `categories_fk`
    FOREIGN KEY (`id_ctg`)
    REFERENCES `categories_ctg`(`id_ctg`);

ALTER TABLE `meta`
ADD
  CONSTRAINT `offer_fk`
    FOREIGN KEY (`id_offer`)
    REFERENCES `offer_needs`(`id_on`);

ALTER TABLE `meta`
ADD
  CONSTRAINT `need_fk`
    FOREIGN KEY (`id_needs`)
    REFERENCES `offer_needs`(`id_on`);

INSERT INTO `meta`(`id_job`) VALUES (1);

DROP TABLE IF EXISTS `attributes_atr`;

CREATE TABLE `attributes_atr` (
  `id_atr` int(11) NOT NULL AUTO_INCREMENT,
  `name_atr` varchar(255) NOT NULL,
  PRIMARY KEY (`id_atr`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `meta_has_atr`;

CREATE TABLE `meta_has_atr` (
  `id_meta` int(11) NOT NULL,
  `id_atr` int(11) NOT NULL,
  PRIMARY KEY (`id_meta`, `id_atr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `meta_has_atr`
ADD CONSTRAINT `meta_fk`
FOREIGN KEY (`id_meta`)
REFERENCES `meta`(`id_meta`);

ALTER TABLE `meta_has_atr`
ADD CONSTRAINT `atr_fk`
FOREIGN KEY (`id_atr`)
REFERENCES `attributes_atr`(`id_atr`);

INSERT INTO `attributes_atr`(`name_atr`) VALUES ("test Attribute");
INSERT INTO `meta_has_atr`(`id_meta`, id_atr) VALUES (1, 1);

ALTER TABLE `users_usr`
ADD COLUMN `id_meta` INT(11);

ALTER TABLE `users_usr`
ADD CONSTRAINT `meta_user_fk`
FOREIGN KEY (`id_meta`)
REFERENCES `meta`(`id_meta`);

UPDATE `users_usr` SET `id_meta` = 1 WHERE `id_usr` = 1;

ALTER TABLE `usr_groups_grp`
ADD COLUMN `id_meta` INT(11);

ALTER TABLE `usr_groups_grp`
ADD CONSTRAINT `meta_group_fk`
FOREIGN KEY (`id_meta`)
REFERENCES `meta` (`id_meta`);

UPDATE `usr_groups_grp` SET `id_meta` = 1 WHERE `id_grp` = 1;

ALTER TABLE `usr_groups_grp`
ADD COLUMN `id_usr` INT(11);

ALTER TABLE `usr_groups_grp`
ADD CONSTRAINT `founder_group_fk`
FOREIGN KEY (`id_usr`)
REFERENCES `users_usr` (`id_usr`);

UPDATE `usr_groups_grp` SET `id_usr` = 1 WHERE `id_grp` = 1;


ALTER TABLE `contents_cnt`
ADD COLUMN `id_meta` INT(11);

ALTER TABLE `contents_cnt`
ADD CONSTRAINT `meta_content_fk`
FOREIGN KEY (`id_meta`)
REFERENCES `meta`(`id_meta`);

UPDATE `contents_cnt` SET `id_meta` = 1 WHERE `id_cnt` = 1;

ALTER TABLE `campaigns_cmp`
ADD COLUMN `id_meta` INT(11);

ALTER TABLE `campaigns_cmp`
ADD COLUMN `task` varchar(255);

ALTER TABLE `campaigns_cmp`
ADD COLUMN `id_usr` INT(11);
