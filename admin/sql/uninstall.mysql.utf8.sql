<<<<<<< HEAD
DROP TABLE `#__catalogue_category`, `#__catalogue_item`, `#__catalogue_section`,`#__catalogue_country`,`#__catalogue_manufacturer`,`#__catalogue_supersection`;
=======
DELETE FROM `#__categories` WHERE `extension` = 'com_catalogue';
DROP TABLE IF EXISTS `#__catalogue_item`;
DROP TABLE IF EXISTS `#__catalogue_country`;
DROP TABLE IF EXISTS `#__catalogue_manufacturer`;
>>>>>>> ce058dac706f2a994396c02e2947681e8f00335b
