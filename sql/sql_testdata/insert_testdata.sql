--
-- Table `users_usr`
-- Username: Admin  Password: admin
-- Username: User   Password: user
--
INSERT INTO `users_usr` (`id_lng_usr`, `login_name_usr`, `password_usr`, `password_salt_usr`, `email_usr`, `gravatar_usr`, `first_name_usr`, `surname_usr`, `gender_usr`, `last_login_usr`, `created_usr`, `modified_usr`) VALUES
(12, 'Admin', 'e69d45f250a349b83025bbcec46ac873', 'Aic5I46H9FV3x7lyQ0NAnlICli0AZjSgu97p0nyCb2TtthbUb8p6wB6IeCuCw0EOVxVxbuiUvCK6heJNed9PyzmmQ2hbBesSz9lR4OKA8j3hOztdZWL5sALd3GMNJfOu', 'admin@massidea.com', 0, NULL, NULL, NULL, '2012-11-13 16:42:48', '2012-11-13 16:42:48', '2012-11-13 16:42:48'),
(12, 'User', '062b2817fe26ec1cf9de584425690750', 'SqHj88xrkqN4LCRMYuRASBGTdlDkCrAxkBRZMxuZcttOeEE22fIxqRt7iFP0hKeZPNyIwdE9tolaL8PatJVcdYve9by8ZfV4SStuuCwbWc1eOux17p69mYJ2yqu9oskR', 'user@massidea.com', 0, NULL, NULL, NULL, '2012-11-13 16:44:49', '2012-11-13 16:44:49', '2012-11-13 16:44:49');


--
-- Table `contents_cnt`
-- Adds 1 Challenge, 1 Vision, 1 Idea
--
INSERT INTO `contents_cnt` (`id_cty_cnt`, `title_cnt`, `lead_cnt`, `language_cnt`, `body_cnt`, `research_question_cnt`, `opportunity_cnt`, `threat_cnt`, `solution_cnt`, `references_cnt`, `views_cnt`, `published_cnt`, `created_cnt`, `modified_cnt`) VALUES
(1, 'Test - Vision', 'This is the lead chapter of a test vision', '23', 'This is the body of the test vision', NULL, NULL, NULL, NULL, NULL, 0, 1, '2012-11-13 15:31:41', '2012-11-13 15:31:45'),
(3, 'Test - Challenge', 'Lead text of this challenge', '23', 'body text of this challenge', NULL, NULL, NULL, NULL, NULL, 0, 1, '2012-11-13 15:49:48', '2012-11-13 15:49:51'),
(2, 'Test - Idea', 'This is the lead charpter of this test idea .....', '23', 'This is another test for this idea', NULL, NULL, NULL, NULL, NULL, 0, 1, '2012-11-13 15:50:26', '2012-11-13 15:50:29');


--
-- Table `usr_groups_grp`
-- Creates two groups:
-- 'FH-Hagenberg Group' -> closed Group
-- 'Test Open Group'    -> open Group
--
INSERT INTO `usr_groups_grp` (`id_sth_grp`, `group_name_grp`, `description_grp`, `body_grp`, `has_blog_grp`, `image_grp`, `created_grp`, `modified_grp`, `id_type_grp`) VALUES
(0, 'FH-Hagenberg Group', 'Hagenberg Group', 'Development group from Fh-Hagenberg', 0, NULL, '2012-11-13 16:08:15', '2012-11-13 16:08:18', 2),
(0, 'Test Open Group', 'This is an open Group', 'Body text of the open test group', 0, NULL, '2012-11-13 16:10:16', '2012-11-13 16:10:18', 1);


--
-- Table `campaigns_cmp`
-- Creates a test project
--
INSERT INTO `campaigns_cmp` (`id_grp_cmp`, `id_cty_cmp`, `name_cmp`, `ingress_cmp`, `description_cmp`, `image_cmp`, `start_time_cmp`, `end_time_cmp`, `created_cmp`, `modified_cmp`) VALUES
(1, 0, 'Test Project', 'this is a project for testing', 'test description', NULL, '2012-11-13', '2015-11-13', '2012-11-13 16:25:16', '2012-11-13 16:25:23');


--
-- Table `cmp_has_cnt`
-- adds content with id '2' to project with id '1'
--
INSERT INTO `cmp_has_cnt` (`id_cmp`, `id_cnt`) VALUES
(1, 2);


--
-- Table `cnt_has_grp`
-- adds content (id 2) to group (id 1)
--
INSERT INTO `cnt_has_grp` (`id_cnt`, `id_grp`, `owner_cnt_grp`) VALUES
(2, 1, 1);


--
-- Table 'comments_cmt'
-- adds a comment to content with id (2)
--
INSERT INTO `massidea`.`comments_cmt` (`id_target_cmt`, `id_usr_cmt`, `id_parent_cmt`, `title_cmt`, `body_cmt`, `created_cmt`, `modified_cmt`, `type_cmt`) VALUES
('2', '1', '0', 'Test Comment', 'This is a test comment', '2012-11-13 16:36:29', '2012-11-13 16:36:32', '2');


--
-- Table `grp_has_admin_usr`
-- user 'Admin' (id 1) is set as group admin for groups 'FH-Hagenberg Group' and 'Test Open Group'
--
INSERT INTO `grp_has_admin_usr` (`id_usr`, `id_grp`) VALUES
(1, 1),
(1, 2);


--
-- Table `usr_has_grp`
-- adds user 'User' to group 'Test Open Group'
--
INSERT INTO `usr_has_grp` (`id_usr`, `id_grp`) VALUES
(2, 1);


--
-- Table `usr_profiles_usp`
-- adds profile data for the users 'Admin' and 'User'
--
INSERT INTO `usr_profiles_usp` (`id_usr_usp`, `profile_key_usp`, `profile_value_usp`, `public_usp`, `created_usp`, `modified_usp`) VALUES
(1, 'employment', 'student', 0, NULL, '2012-11-13 16:42:48'),
(1, 'city', 'Hagenberg', 1, NULL, '2012-11-13 16:42:48'),
(2, 'employment', 'student', 0, NULL, '2012-11-13 16:44:49'),
(2, 'city', 'Hagenberg', 1, NULL, '2012-11-13 16:44:49');
