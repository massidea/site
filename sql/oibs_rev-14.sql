SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `oibs` DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ;
USE `oibs`;

-- -----------------------------------------------------
-- Table `oibs`.`content_types_cty`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`content_types_cty` (
  `id_cty` INT NOT NULL AUTO_INCREMENT ,
  `key_cty` VARCHAR(10) NOT NULL ,
  `name_cty` VARCHAR(255) NOT NULL ,
  `description_cty` TEXT NULL ,
  `created_cty` DATETIME NULL ,
  `modified_cty` DATETIME NULL ,
  PRIMARY KEY (`id_cty`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`contents_cnt`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`contents_cnt` (
  `id_cnt` INT NOT NULL AUTO_INCREMENT ,
  `id_cty_cnt` INT NOT NULL ,
  `title_cnt` VARCHAR(255) NOT NULL ,
  `lead_cnt` VARCHAR(255) NOT NULL ,
  `body_cnt` TEXT NOT NULL ,
  `views_cnt` INT NULL DEFAULT 0,
  `published_cnt` BOOLEAN NOT NULL DEFAULT FALSE ,
  `created_cnt` DATETIME NULL ,
  `modified_cnt` DATETIME NULL ,
  PRIMARY KEY (`id_cnt`) ,
  INDEX `fk_cty_cnt` (`id_cty_cnt` ASC) ,
  CONSTRAINT `fk_cty_cnt`
    FOREIGN KEY (`id_cty_cnt` )
    REFERENCES `oibs`.`content_types_cty` (`id_cty` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`languages_lng`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`languages_lng` (
  `id_lng` INT NOT NULL AUTO_INCREMENT ,
  `iso6391_lng` VARCHAR(2) NOT NULL ,
  `name_lng` VARCHAR(255) NOT NULL ,
  `created_lng` DATETIME NULL ,
  `modified_lng` DATETIME NULL ,
  PRIMARY KEY (`id_lng`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`users_usr`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`users_usr` (
  `id_usr` INT NOT NULL AUTO_INCREMENT ,
  `id_lng_usr` INT NOT NULL ,
  `login_name_usr` VARCHAR(255) NOT NULL ,
  `password_usr` VARCHAR(50) NOT NULL ,
  `password_salt_usr` VARCHAR(128) NOT NULL ,
  `email_usr` VARCHAR(255) NOT NULL ,
  `last_login_usr` DATETIME NULL ,
  `created_usr` DATETIME NULL ,
  `modified_usr` DATETIME NULL ,
  PRIMARY KEY (`id_usr`) ,
  INDEX `fk_lng_usr` (`id_lng_usr` ASC) ,
  CONSTRAINT `fk_lng_usr`
    FOREIGN KEY (`id_lng_usr` )
    REFERENCES `oibs`.`languages_lng` (`id_lng` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`files_fil`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`files_fil` (
  `id_fil` INT NOT NULL AUTO_INCREMENT ,
  `id_cnt_fil` INT NOT NULL ,
  `id_usr_fil` INT NOT NULL ,
  `filename_fil` VARCHAR(255) NOT NULL ,
  `filesize_fil` INT NOT NULL ,
  `data_fil` LONGBLOB NOT NULL ,
  `filetype_fil` VARCHAR(255) NOT NULL ,
  `created_fil` DATETIME NULL ,
  `modified_fil` DATETIME NULL ,
  PRIMARY KEY (`id_fil`) ,
  INDEX `fk_cnt_fil` (`id_cnt_fil` ASC) ,
  INDEX `fk_usr_fil` (`id_usr_fil` ASC) ,
  CONSTRAINT `fk_cnt_fil`
    FOREIGN KEY (`id_cnt_fil` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usr_fil`
    FOREIGN KEY (`id_usr_fil` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`comments_cmt`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`comments_cmt` (
  `id_cmt` INT NOT NULL AUTO_INCREMENT ,
  `id_cnt_cmt` INT NOT NULL ,
  `id_usr_cmt` INT NOT NULL ,
  `id_parent_cmt` INT NULL DEFAULT 0 ,
  `title_cmt` VARCHAR(255) NOT NULL ,
  `body_cmt` TEXT NOT NULL ,
  `created_cmt` DATETIME NULL ,
  `modified_cmt` DATETIME NULL ,
  PRIMARY KEY (`id_cmt`) ,
  INDEX `fk_cmt_cmt` (`id_parent_cmt` ASC) ,
  INDEX `fk_cmt_cnt` (`id_cnt_cmt` ASC) ,
  INDEX `fk_comments_cmt_users_usr` (`id_usr_cmt` ASC) ,
  CONSTRAINT `fk_cmt_cmt`
    FOREIGN KEY (`id_parent_cmt` )
    REFERENCES `oibs`.`comments_cmt` (`id_cmt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cmt_cnt`
    FOREIGN KEY (`id_cnt_cmt` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_cmt_users_usr`
    FOREIGN KEY (`id_usr_cmt` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`content_ratings_crt`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`content_ratings_crt` (
  `id_crt` INT NOT NULL AUTO_INCREMENT ,
  `id_cnt_crt` INT NOT NULL ,
  `id_usr_crt` INT NOT NULL ,
  `rating_crt` INT NOT NULL ,
  `created_crt` DATETIME NULL ,
  `modified_crt` DATETIME NULL ,
  PRIMARY KEY (`id_crt`) ,
  INDEX `fk_cnt_crt` (`id_cnt_crt` ASC) ,
  CONSTRAINT `fk_cnt_crt`
    FOREIGN KEY (`id_cnt_crt` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`links_lnk`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`links_lnk` (
  `id_lnk` INT NOT NULL AUTO_INCREMENT ,
  `id_cnt_lnk` INT NOT NULL ,
  `id_usr_lnk` INT NOT NULL ,
  `url_lnk` VARCHAR(4000) NOT NULL ,
  `created_lnk` DATETIME NULL ,
  `modified_lnk` DATETIME NULL ,
  PRIMARY KEY (`id_lnk`) ,
  INDEX `fk_cnt_lnk` (`id_cnt_lnk` ASC) ,
  INDEX `fk_usr_lnk` (`id_usr_lnk` ASC) ,
  CONSTRAINT `fk_cnt_lnk`
    FOREIGN KEY (`id_cnt_lnk` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usr_lnk`
    FOREIGN KEY (`id_usr_lnk` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`tags_tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`tags_tag` (
  `id_tag` INT NOT NULL AUTO_INCREMENT ,
  `name_tag` VARCHAR(255) NOT NULL ,
  `views_tag` INT NOT NULL DEFAULT 0 ,
  `description_tag` TEXT NULL ,
  `created_tag` DATETIME NULL ,
  `modified_tag` DATETIME NULL ,
  PRIMARY KEY (`id_tag`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`cnt_has_tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`cnt_has_tag` (
  `id_cnt` INT NOT NULL ,
  `id_tag` INT NOT NULL ,
  PRIMARY KEY (`id_cnt`, `id_tag`) ,
  INDEX `fk_cnt_has_tag_cnt` (`id_cnt` ASC) ,
  INDEX `fk_cnt_has_tag_tag` (`id_tag` ASC) ,
  CONSTRAINT `fk_cnt_has_tag_cnt`
    FOREIGN KEY (`id_cnt` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cnt_has_tag_tag`
    FOREIGN KEY (`id_tag` )
    REFERENCES `oibs`.`tags_tag` (`id_tag` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`usr_images_usi`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`usr_images_usi` (
  `id_usi` INT NOT NULL AUTO_INCREMENT ,
  `id_usr_usi` INT NOT NULL ,
  `thumbnail_usi` LONGBLOB NOT NULL ,
  `image_usi` LONGBLOB NOT NULL ,
  `created_usi` DATETIME NULL ,
  `modified_usi` DATETIME NULL ,
  PRIMARY KEY (`id_usi`) ,
  INDEX `fk_usr_usi` (`id_usr_usi` ASC) ,
  CONSTRAINT `fk_usr_usi`
    FOREIGN KEY (`id_usr_usi` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`usr_profiles_usp`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`usr_profiles_usp` (
  `id_usp` INT NOT NULL AUTO_INCREMENT ,
  `id_usr_usp` INT NOT NULL ,
  `profile_key_usp` VARCHAR(255) NOT NULL ,
  `profile_value_usp` TEXT NOT NULL ,
  `public_usp` BOOLEAN NOT NULL DEFAULT FALSE ,
  `created_usp` DATETIME NULL ,
  `modified_usp` DATETIME NULL ,
  PRIMARY KEY (`id_usp`) ,
  INDEX `fk_usr_usp` (`id_usr_usp` ASC) ,
  CONSTRAINT `fk_usr_usp`
    FOREIGN KEY (`id_usr_usp` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`industries_ind`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`industries_ind` (
  `id_ind` INT NOT NULL AUTO_INCREMENT ,
  `id_lng_ind` INT NOT NULL ,
  `id_parent_ind` INT NULL DEFAULT 0 ,
  `name_ind` VARCHAR(255) NOT NULL ,
  `description_ind` VARCHAR(512) NULL ,
  `created_ind` DATETIME NULL ,
  `modified_ind` DATETIME NULL ,
  PRIMARY KEY (`id_ind`) ,
  INDEX `fk_lng_ind` (`id_lng_ind` ASC) ,
  INDEX `fk_parent_ind_ind` (`id_parent_ind` ASC) ,
  CONSTRAINT `fk_lng_ind`
    FOREIGN KEY (`id_lng_ind` )
    REFERENCES `oibs`.`languages_lng` (`id_lng` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_parent_ind_ind`
    FOREIGN KEY (`id_parent_ind` )
    REFERENCES `oibs`.`industries_ind` (`id_ind` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`innovation_types_ivt`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`innovation_types_ivt` (
  `id_ivt` INT NOT NULL AUTO_INCREMENT ,
  `name_ivt` VARCHAR(255) NOT NULL ,
  `description_ivt` VARCHAR(512) NULL ,
  `created_ivt` DATETIME NULL ,
  `modified_ivt` DATETIME NULL ,
  PRIMARY KEY (`id_ivt`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`cnt_has_ind`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`cnt_has_ind` (
  `id_cnt` INT NOT NULL ,
  `id_ind` INT NOT NULL ,
  PRIMARY KEY (`id_cnt`, `id_ind`) ,
  INDEX `fk_cnt_has_ind_cnt` (`id_cnt` ASC) ,
  INDEX `fk_cnt_has_ind_ind` (`id_ind` ASC) ,
  CONSTRAINT `fk_cnt_has_ind_cnt`
    FOREIGN KEY (`id_cnt` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cnt_has_ind_ind`
    FOREIGN KEY (`id_ind` )
    REFERENCES `oibs`.`industries_ind` (`id_ind` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`cnt_has_ivt`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`cnt_has_ivt` (
  `id_cnt` INT NOT NULL ,
  `id_ivt` INT NOT NULL ,
  PRIMARY KEY (`id_cnt`, `id_ivt`) ,
  INDEX `fk_cnt_has_ivt_cnt` (`id_cnt` ASC) ,
  INDEX `fk_cnt_has_ivt_ivt` (`id_ivt` ASC) ,
  CONSTRAINT `fk_cnt_has_ivt_cnt`
    FOREIGN KEY (`id_cnt` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cnt_has_ivt_ivt`
    FOREIGN KEY (`id_ivt` )
    REFERENCES `oibs`.`innovation_types_ivt` (`id_ivt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`countries_ctr`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`countries_ctr` (
  `id_ctr` INT NOT NULL AUTO_INCREMENT ,
  `iso31663_ctr` VARCHAR(5) NOT NULL ,
  `name_ctr` VARCHAR(255) NOT NULL ,
  `created_ctr` DATETIME NULL ,
  `modified_ctr` DATETIME NULL ,
  PRIMARY KEY (`id_ctr`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oibs`.`cnt_has_cnt`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`cnt_has_cnt` (
  `id_parent_cnt` INT NOT NULL ,
  `id_child_cnt` INT NOT NULL ,
  PRIMARY KEY (`id_parent_cnt`, `id_child_cnt`) ,
  INDEX `fk_cnt_has_cnt_parent_cnt` (`id_parent_cnt` ASC) ,
  INDEX `fk_cnt_has_cnt_child_cnt` (`id_child_cnt` ASC) ,
  CONSTRAINT `fk_cnt_has_cnt_parent_cnt`
    FOREIGN KEY (`id_parent_cnt` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cnt_has_cnt_child_cnt`
    FOREIGN KEY (`id_child_cnt` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`usr_groups_grp`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`usr_groups_grp` (
  `id_grp` INT NOT NULL AUTO_INCREMENT ,
  `group_name_grp` VARCHAR(255) NOT NULL ,
  `description_grp` VARCHAR(255) NULL ,
  `created_grp` DATETIME NULL ,
  `modified_grp` DATETIME NULL ,
  PRIMARY KEY (`id_grp`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`permissions_prm`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`permissions_prm` (
  `id_prm` INT NOT NULL AUTO_INCREMENT ,
  `key_prm` VARCHAR(255) NOT NULL ,
  `value_prm` BOOLEAN NOT NULL ,
  `created_prm` DATETIME NULL ,
  `modified_prm` DATETIME NULL ,
  PRIMARY KEY (`id_prm`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`cnt_publish_times_pbt`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`cnt_publish_times_pbt` (
  `id_pbt` INT NOT NULL AUTO_INCREMENT ,
  `id_cnt_pbt` INT NOT NULL ,
  `id_usr_pbt` INT NOT NULL ,
  `start_time_pbt` DATETIME NOT NULL ,
  `end_time_pbt` DATETIME NOT NULL ,
  `name_pbt` VARCHAR(45) NULL DEFAULT 'Undefined' ,
  `description_pbt` VARCHAR(255) NULL ,
  `created_pbt` DATETIME NULL ,
  `modified_pbt` DATETIME NULL ,
  PRIMARY KEY (`id_pbt`) ,
  INDEX `fk_cnt_pbt` (`id_cnt_pbt` ASC) ,
  INDEX `fk_usr_pbt` (`id_usr_pbt` ASC) ,
  CONSTRAINT `fk_cnt_pbt`
    FOREIGN KEY (`id_cnt_pbt` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usr_pbt`
    FOREIGN KEY (`id_usr_pbt` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`cmt_ratings_cmr`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`cmt_ratings_cmr` (
  `id_cmr` INT NOT NULL AUTO_INCREMENT ,
  `id_usr_cmr` INT NOT NULL ,
  `id_cmt_cmr` INT NOT NULL ,
  `rating_cmr` INT NOT NULL ,
  `created_cmr` DATETIME NULL ,
  `modified_cmr` DATETIME NULL ,
  PRIMARY KEY (`id_cmr`) ,
  INDEX `fk_usr_cmr` (`id_usr_cmr` ASC) ,
  INDEX `fk_cmt_cmr` (`id_cmt_cmr` ASC) ,
  CONSTRAINT `fk_usr_cmr`
    FOREIGN KEY (`id_usr_cmr` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cmt_cmr`
    FOREIGN KEY (`id_cmt_cmr` )
    REFERENCES `oibs`.`comments_cmt` (`id_cmt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`private_messages_pmg`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`private_messages_pmg` (
  `id_pmg` INT NOT NULL AUTO_INCREMENT ,
  `id_sender_pmg` INT NOT NULL ,
  `id_receiver_pmg` INT NOT NULL ,
  `header_pmg` VARCHAR(255) NOT NULL ,
  `message_body_pmg` TEXT NOT NULL ,
  `sender_email_pmg` VARCHAR(255) NULL ,
  `read_pmg` TINYINT(1) NOT NULL,
  `created_pmg` DATETIME NULL ,
  `modified_pmg` DATETIME NULL ,
  PRIMARY KEY (`id_pmg`) ,
  INDEX `fk_sender_usr_pmg` (`id_sender_pmg` ASC) ,
  INDEX `fk_receiver_usr_pmg` (`id_receiver_pmg` ASC) ,
  CONSTRAINT `fk_sender_usr_pmg`
    FOREIGN KEY (`id_sender_pmg` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_receiver_usr_pmg`
    FOREIGN KEY (`id_receiver_pmg` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`usr_has_grp`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`usr_has_grp` (
  `id_usr` INT NOT NULL ,
  `id_grp` INT NOT NULL ,
  PRIMARY KEY (`id_grp`, `id_usr`) ,
  INDEX `fk_usr_has_grp_grp` (`id_grp` ASC) ,
  INDEX `fk_usr_has_grp_usr` (`id_usr` ASC) ,
  CONSTRAINT `fk_usr_has_grp_grp`
    FOREIGN KEY (`id_grp` )
    REFERENCES `oibs`.`usr_groups_grp` (`id_grp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usr_has_grp_usr`
    FOREIGN KEY (`id_usr` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`grp_has_prm`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`grp_has_prm` (
  `id_grp` INT NOT NULL ,
  `id_prm` INT NOT NULL ,
  PRIMARY KEY (`id_grp`, `id_prm`) ,
  INDEX `fk_grp_has_prm_grp` (`id_grp` ASC) ,
  INDEX `fk_grp_has_prm_prm` (`id_prm` ASC) ,
  CONSTRAINT `fk_grp_has_prm_grp`
    FOREIGN KEY (`id_grp` )
    REFERENCES `oibs`.`usr_groups_grp` (`id_grp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_grp_has_prm_prm`
    FOREIGN KEY (`id_prm` )
    REFERENCES `oibs`.`permissions_prm` (`id_prm` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`cnt_has_usr`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`cnt_has_usr` (
  `id_cnt` INT NOT NULL ,
  `id_usr` INT NOT NULL ,
  `owner_cnt_usr` BOOLEAN NOT NULL DEFAULT FALSE ,
  PRIMARY KEY (`id_cnt`, `id_usr`) ,
  INDEX `fk_cnt_has_usr_cnt` (`id_cnt` ASC) ,
  INDEX `fk_cnt_has_usr_usr` (`id_usr` ASC) ,
  CONSTRAINT `fk_cnt_has_usr_cnt`
    FOREIGN KEY (`id_cnt` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cnt_has_usr_usr`
    FOREIGN KEY (`id_usr` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`cnt_has_grp`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`cnt_has_grp` (
  `id_cnt` INT NOT NULL ,
  `id_grp` INT NOT NULL ,
  `owner_cnt_grp` BOOLEAN NOT NULL DEFAULT FALSE ,
  PRIMARY KEY (`id_cnt`, `id_grp`) ,
  INDEX `fk_cnt_has_grp_cnt` (`id_cnt` ASC) ,
  INDEX `fk_cnt_has_grp_grp` (`id_grp` ASC) ,
  CONSTRAINT `fk_cnt_has_grp_cnt`
    FOREIGN KEY (`id_cnt` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cnt_has_grp_grp`
    FOREIGN KEY (`id_grp` )
    REFERENCES `oibs`.`usr_groups_grp` (`id_grp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `oibs`.`cnt_views_vws`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`cnt_views_vws` (
  `id_cnt_vws` INT NOT NULL ,
  `id_usr_vws` INT NOT NULL ,
  `views_vws` INT NULL DEFAULT 0 ,
  PRIMARY KEY (`id_cnt_vws`, `id_usr_vws`) ,
  INDEX `fk_cnt_vws` (`id_cnt_vws` ASC) ,
  INDEX `fk_usr_vws` (`id_usr_vws` ASC) ,
  CONSTRAINT `fk_cnt_vws`
    FOREIGN KEY (`id_cnt_vws` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usr_vws`
    FOREIGN KEY (`id_usr_vws` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
   
-- -----------------------------------------------------
-- Table `oibs`.`help_hlp`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `oibs`.`help_hlp` (
  `id_hlp` INT(11) NOT NULL auto_increment,
  `title_hlp` VARCHAR(50) default NULL,
  `content_hlp` VARCHAR(500) default NULL,
  `author_hlp` VARCHAR(16) default NULL,
  `lang_hlp` VARCHAR(3) default NULL,
  PRIMARY KEY  (`id_hlp`)
) ENGINE=MyISAM;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
