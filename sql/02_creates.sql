DROP TABLE IF EXISTS `oibs`.`comment_flags_cmf`;

-- -----------------------------------------------------
-- Table `oibs`.`comment_flags_cmf`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`comment_flags_cmf` (
  `id_cmf` INT NOT NULL AUTO_INCREMENT ,
  `id_comment_cmf` INT NOT NULL ,
  `id_user_cmf` INT NOT NULL ,
  `flag_cmf` VARCHAR(45) NOT NULL ,
  `created_cmf` VARCHAR(45) NULL ,
  `modified_cmf` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_cmf`) ,
  INDEX `fk_cmt_cmf` (`id_comment_cmf` ASC) ,
  INDEX `fk_usr_cmf` (`id_user_cmf` ASC) ,
  CONSTRAINT `fk_cmt_cmf`
    FOREIGN KEY (`id_comment_cmf` )
    REFERENCES `oibs`.`comments_cmt` (`id_cmt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usr_cmf`
    FOREIGN KEY (`id_user_cmf` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;

-- -----------------------------------------------------
-- Table `oibs`.`content_flags_cfl`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oibs`.`content_flags_cfl` (
  `id_cfl` INT NOT NULL AUTO_INCREMENT ,
  `id_content_cfl` INT NOT NULL ,
  `id_user_cfl` INT NOT NULL ,
  `flag_cfl` VARCHAR(45) NOT NULL ,
  `created_cfl` VARCHAR(45) NULL ,
  `modified_cfl` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_cfl`) ,
  INDEX `fk_cnt_cfl` (`id_content_cfl` ASC) ,
  INDEX `fk_usr_cfl` (`id_user_cfl` ASC) ,
  CONSTRAINT `fk_cnt_cfl`
    FOREIGN KEY (`id_content_cfl` )
    REFERENCES `oibs`.`contents_cnt` (`id_cnt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usr_cfl`
    FOREIGN KEY (`id_user_cfl` )
    REFERENCES `oibs`.`users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;