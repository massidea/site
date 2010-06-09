USE `oibs`;

-- Drop old table "content has campaign".
DROP TABLE `cnt_has_cmp`;

-- Add new table "campaign has content".
CREATE TABLE `oibs`.`cmp_has_cnt` (
  `id_cmp` INT NOT NULL ,
  `id_cnt` INT NOT NULL ,
  PRIMARY KEY ( `id_cmp` , `id_cnt` ) 
) ENGINE = MYISAM ;

-- Change datetimes to dates.
ALTER TABLE `campaigns_cmp` CHANGE `start_time_cmp` `start_time_cmp` DATE NULL DEFAULT NULL;
ALTER TABLE `campaigns_cmp` CHANGE `end_time_cmp` `end_time_cmp` DATE NULL DEFAULT NULL;