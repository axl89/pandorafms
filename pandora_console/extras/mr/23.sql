START TRANSACTION;

--ALTER TABLE `tagent_custom_fields_filter` ADD COLUMN `module_status` varchar(600) default '';

ALTER TABLE `tagent_custom_fields_filter` ADD COLUMN `module_status` varchar(600) default '';

ALTER TABLE `tagent_custom_fields_filter` ADD COLUMN `recursion` int(1) unsigned default '0';

COMMIT;