DROP TABLE IF EXISTS `#__catalogue_supersection`;
CREATE TABLE `#__catalogue_supersection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `supersection_name` varchar(255) NOT NULL,
  `supersection_sale` double NOT NULL,
  `published` tinyint(1) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `metadata` text NOT NULL,
  `params` text NOT NULL,
  `supersection_image` varchar(255) NOT NULL,
  `supersection_description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_section`;
CREATE TABLE `#__catalogue_section` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `supersection_id` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `section_sale` double NOT NULL,
  `created_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `metadata` text NOT NULL,
  `params` text NOT NULL,
  `published` tinyint(1) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `ordering` int(11) NOT NULL,
  `section_image` varchar(255) NOT NULL,
  `section_description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_category`;
CREATE TABLE `#__catalogue_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_sale` double NOT NULL,
  `category_image` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `params` text NOT NULL,
  `metadata` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_item`;
CREATE TABLE `#__catalogue_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `alias` varchar(45) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_art` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `item_sale` double NOT NULL,
  `sticker` tinyint(4) NOT NULL DEFAULT '0',
  `item_count` int(11) NOT NULL,
  `item_image` varchar(255) NOT NULL,
  `item_image_2` varchar(255) NOT NULL,
  `item_image_3` varchar(255) NOT NULL,
  `item_image_4` varchar(255) NOT NULL,
  `item_image_5` varchar(255) NOT NULL,
  `item_description` text NOT NULL,
  `params` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `metadata` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_country`;
CREATE TABLE `#__catalogue_country` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `country_image` varchar(255) NOT NULL,
  `country_description` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `params` text NOT NULL,
  `metadata` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_manufacturer`;
CREATE TABLE `#__catalogue_manufacturer` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `manufacturer_name` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `ordering` int(11) NOT NULL,
  `manufacturer_image` varchar(255) NOT NULL,
  `manufacturer_description` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `params` text NOT NULL,
  `metadata` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;