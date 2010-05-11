-- This update comes a bit late, sorry for inconvenience :p
-- If you already havent, this updates usr_favourites table to usr_has_fvr
-- usr_favourites_fvr will be recreated with favourites setup.

USE `oibs`;

RENAME TABLE usr_favourites_fvr TO usr_has_fvr;
ALTER TABLE usr_has_fvr ADD content_edited TINYINT(1) NOT NULL AFTER id_usr_fvr;
ALTER TABLE usr_has_fvr CHANGE id_usr_fvr id_usr INT(11) NOT NULL;
ALTER TABLE usr_has_fvr CHANGE id_cnt_fvr id_cnt INT(11) NOT NULL;

