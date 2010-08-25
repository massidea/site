-- Adds created time for linked contents so that they can be sorted out by date
alter table cnt_has_cnt add column `created_cnt` datetime NOT NULL DEFAULT '0000-0-0 00:00:00';
