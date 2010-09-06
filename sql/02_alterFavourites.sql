 -- Changes favourites to more like subscribing to content
alter table usr_has_fvr drop content_edited;
alter table usr_has_fvr add last_checked datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
