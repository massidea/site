-- Creating follows_flw table to users so they can follow their own contents
-- or follow their favourites. It is used to list options that can be followed when content is set to be followed.
-- For now it only has comment, rating and linking (translation does not exist yet)
-- usr_flw_cnt is meant to hold data of actions that has happened in contents that are followed.
-- bit in followed_cnt refers to follows_flw table's bit so we know what id the id_flw is (is it comment id, or rating id etc.)

CREATE TABLE `follows_flw` (
  `bit` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  PRIMARY KEY (`bit`)
); 

INSERT INTO `follows_flw` (`bit`, `name`) VALUES(1,'comment'),(2,'rating'),(4,'linking'),(8,'translation'),(16,'modified');

ALTER TABLE `cnt_has_usr` add `last_checked` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
