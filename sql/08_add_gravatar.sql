USE `oibs`;

ALTER TABLE `users_usr` ADD `gravatar_usr` TINYINT(2) NOT NULL DEFAULT 0 AFTER `email_usr`;
