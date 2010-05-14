-- -----------------------------------------------------
-- Table `oibs`.`files_fil`
-- -----------------------------------------------------
RENAME TABLE `oibs`.`files_fil` to `oibs`.`files_fil_old`;
CREATE  TABLE IF NOT EXISTS `oibs`.`files_fil` (
  `id_fil` INT NOT NULL AUTO_INCREMENT ,
  `id_cnt_fil` INT NOT NULL ,
  `id_usr_fil` INT NOT NULL ,
  `filename_fil` VARCHAR(255) NOT NULL ,
  `filetype_fil` VARCHAR(255) NOT NULL ,
  `hash_fil` VARCHAR(50) NOT NULL,
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

