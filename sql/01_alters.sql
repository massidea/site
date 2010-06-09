USE `oibs`;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns_cmp`
--
ALTER TABLE `campaigns_cmp` DROP `information_cmp`;
ALTER TABLE `campaigns_cmp` CHANGE `name_cmp` `name_cmp` VARCHAR( 140 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `campaigns_cmp` CHANGE `ingress_cmp` `ingress_cmp` VARCHAR( 320 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- --------------------------------------------------------

--
-- Table structure for table `usr_groups_grp`
--

ALTER TABLE `usr_groups_grp` CHANGE `group_name_grp`  `group_name_grp`  VARCHAR( 140 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `usr_groups_grp` CHANGE `description_grp` `description_grp` VARCHAR( 320 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `usr_groups_grp` ADD `body_grp` TEXT NOT NULL;
ALTER TABLE `usr_groups_grp` ADD `has_blog_grp` BOOL NOT NULL;
