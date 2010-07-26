-- -----------------------------------------------------
-- Table `oibs`.`files_fil`
-- -----------------------------------------------------

ALTER TABLE comments_cmt ADD type_cmt INT NOT NULL;
UPDATE comments_cmt SET type_cmt=1 WHERE type_cmt=NULL;
ALTER TABLE comments_cmt DROP foreign key fk_cnt_cmt;
ALTER TABLE comments_cmt CHANGE id_cnt_cmt id_target_cmt int not null;


CREATE  TABLE IF NOT EXISTS `oibs`.`comment_types_ctp` (
  `id_ctp` INT NOT NULL UNIQUE AUTO_INCREMENT ,
  `type_ctp` INT NOT NULL UNIQUE ,
  `type_name_ctp` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id_ctp`) ,
  INDEX `fk_type_ctp` (`type_ctp` ASC) ,
  CONSTRAINT `fk_type_ctp`
    FOREIGN KEY (`type_ctp` )
    REFERENCES `oibs`.`comments_cmt` (`type_cmt` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = MyISAM;
