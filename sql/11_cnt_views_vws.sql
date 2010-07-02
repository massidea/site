# This will add modified_vws column to cnt_views_vws table
#

USE OIBS;

ALTER TABLE cnt_views_vws ADD COLUMN modified_vws datetime;
