USE `oibs`;

-- Fix a typo.
ALTER TABLE `campaigns_cmp` CHANGE `descsiption_cmp` `description_cmp` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- Move body_grp after description_grp.
ALTER TABLE `usr_groups_grp` CHANGE `body_grp` `body_grp` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci  NOT NULL AFTER `description_grp`;
ALTER TABLE `usr_groups_grp` CHANGE `has_blog_grp` `has_blog_grp` TINYINT( 1 ) NOT NULL AFTER body_grp;