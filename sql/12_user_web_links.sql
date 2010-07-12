-- -----------------------------------------------------
-- Table `usr_weblinks_uwl`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `usr_weblinks_uwl` (
  `id_uwl` INT NOT NULL AUTO_INCREMENT ,
  `id_usr_uwl` INT NOT NULL ,
  `name_uwl` VARCHAR(45) NOT NULL ,
  `url_uwl` VARCHAR(150) NOT NULL ,
  `count_uwl` INT NOT NULL ,
  `created_uwl` DATETIME NULL ,
  `modified_uwl` DATETIME NULL ,
  PRIMARY KEY (`id_uwl`) ,
  INDEX `fk_usr_uwl` (`id_usr_uwl` ASC) ,
  CONSTRAINT `fk_usr_uwl`
    FOREIGN KEY (`id_usr_uwl` )
    REFERENCES `users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;

-- -----------------------------------------------------
-- Table `grp_weblinks_uwl`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `grp_weblinks_gwl` (
  `id_gwl` INT NOT NULL AUTO_INCREMENT ,
  `id_grp_gwl` INT NOT NULL ,
  `name_gwl` VARCHAR(45) NOT NULL ,
  `url_gwl` VARCHAR(150) NOT NULL ,
  `count_gwl` INT NOT NULL ,
  `created_gwl` DATETIME NULL ,
  `modified_gwl` DATETIME NULL ,
  PRIMARY KEY (`id_gwl`) ,
  INDEX `fk_grp_gwl` (`id_grp_gwl` ASC) ,
  CONSTRAINT `fk_grp_gwl`
    FOREIGN KEY (`id_grp_gwl` )
    REFERENCES `usr_groups_grp` (`id_grp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;

-- -----------------------------------------------------
-- Table `cmp_weblinks_uwl`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `cmp_weblinks_cwl` (
  `id_cwl` INT NOT NULL AUTO_INCREMENT ,
  `id_cmp_cwl` INT NOT NULL ,
  `name_cwl` VARCHAR(45) NOT NULL ,
  `url_cwl` VARCHAR(150) NOT NULL ,
  `count_cwl` INT NOT NULL ,
  `created_cwl` DATETIME NULL ,
  `modified_cwl` DATETIME NULL ,
  PRIMARY KEY (`id_cwl`) ,
  INDEX `fk_cmp_cwl` (`id_cmp_cwl` ASC) ,
  CONSTRAINT `fk_cmp_cwl`
    FOREIGN KEY (`id_cmp_cwl` )
    REFERENCES `campaigns_cmp` (`id_cmp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;