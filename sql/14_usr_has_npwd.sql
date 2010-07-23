# This will add a new table "usr_has_npwd" for password reset requests.
#

DROP TABLE IF EXISTS `usr_has_npwd`;

CREATE TABLE IF NOT EXISTS `usr_has_npwd` (
    `id_npwd` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `id_usr_npwd` INT( 11 ) NOT NULL ,
    `key_npwd` VARCHAR( 32 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
    `expire_date_npwd` DATETIME NOT NULL
);
