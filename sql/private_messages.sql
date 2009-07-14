-- -----------------------------------------------------
-- Table `oibs`.`private_messages_pmg`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `oibs`.`private_messages_pmg` (
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