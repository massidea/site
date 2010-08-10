# Group types

CREATE  TABLE IF NOT EXISTS `group_types_gtp` (
  `id_gtp` INT NOT NULL UNIQUE AUTO_INCREMENT ,
  `key_gtp` VARCHAR(15) NOT NULL ,
  `name_gtp` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id_gtp`) )
ENGINE = MyISAM;


# Alter to usr_groups_grp

ALTER TABLE usr_groups_grp ADD id_type_grp INT NOT NULL;
ALTER TABLE usr_groups_grp ADD INDEX `fk_id_type_grp` (`id_type_grp` ASC);
ALTER TABLE usr_groups_grp ADD CONSTRAINT `fk_id_type_grp`
                                FOREIGN KEY (`id_type_grp` )
                                REFERENCES `group_types_gtp` (`id_gtp` )
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION;
UPDATE usr_groups_grp SET id_type_grp=1 WHERE id_type_grp=0;


# Insert group types

INSERT INTO group_types_gtp (id_gtp, key_gtp, type_name_gtp) VALUES (1, 'open_grp','Open group');
INSERT INTO group_types_gtp (id_gtp, key_gtp, type_name_gtp) VALUES (2, 'closed_grp','Closed group');

# User has group waiting

CREATE  TABLE IF NOT EXISTS `usr_has_grp_waiting` (
  `id_usr` INT NOT NULL ,
  `id_grp` INT NOT NULL ,
  PRIMARY KEY (`id_grp`, `id_usr`) ,
  INDEX `fk_usr_has_grp_waiting_grp` (`id_grp` ASC) ,
  INDEX `fk_usr_has_grp_waiting_usr` (`id_usr` ASC) ,
  CONSTRAINT `fk_usr_has_grp_waiting_grp`
    FOREIGN KEY (`id_grp` )
    REFERENCES `usr_groups_grp` (`id_grp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usr_has_grp_waiting_usr`
    FOREIGN KEY (`id_usr` )
    REFERENCES `users_usr` (`id_usr` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;