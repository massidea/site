-- -----------------------------------------------------
-- Table `cmp_has_cmp`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `cmp_has_cmp` (
  `id_parent_cmp` INT NOT NULL ,
  `id_child_cmp` INT NOT NULL ,
  PRIMARY KEY (`id_parent_cmp`, `id_child_cmp`) ,
  INDEX `fk_cmp_has_cmp_parent_cmp` (`id_parent_cmp` ASC) ,
  INDEX `fk_cmp_has_cmp_child_cmp` (`id_child_cmp` ASC) ,
  CONSTRAINT `fk_cmp_has_cmp_parent_cmp`
    FOREIGN KEY (`id_parent_cmp` )
    REFERENCES `campaigns_cmp` (`id_cmp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cmp_has_cmp_child_cmp`
    FOREIGN KEY (`id_child_cmp` )
    REFERENCES `campaigns_cmp` (`id_cmp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;

-- -----------------------------------------------------
-- Table `grp_has_grp`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `grp_has_grp` (
  `id_parent_grp` INT NOT NULL ,
  `id_child_grp` INT NOT NULL ,
  PRIMARY KEY (`id_parent_grp`, `id_child_grp`) ,
  INDEX `fk_grp_has_grp_parent_grp` (`id_parent_grp` ASC) ,
  INDEX `fk_grp_has_grp_child_grp` (`id_child_grp` ASC) ,
  CONSTRAINT `fk_grp_has_grp_parent_grp`
    FOREIGN KEY (`id_parent_grp` )
    REFERENCES `usr_groups_grp` (`id_grp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_grp_has_grp_child_grp`
    FOREIGN KEY (`id_child_grp` )
    REFERENCES `usr_groups_grp` (`id_grp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;