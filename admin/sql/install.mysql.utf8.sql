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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_item`;
CREATE TABLE `#__catalogue_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `alias` varchar(45) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `item_sale` double NOT NULL,
  `rate` double NOT NULL,
  `votes_count` int(11) NOT NULL,
  `comments_count` int(11) NOT NULL,
  `sticker` tinyint(4) NOT NULL DEFAULT '0',
  `item_image` varchar(255) NOT NULL,
  `item_description` text NOT NULL,
  `params` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_section`;
CREATE TABLE `#__catalogue_section` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `supersection_id` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `section_sale` double NOT NULL,
  `published` tinyint(1) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `ordering` int(11) NOT NULL,
  `section_image` varchar(255) NOT NULL,
  `section_description` text NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_field`;
CREATE TABLE `#__catalogue_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `field_type` varchar(255) NOT NULL,
  `field_label` varchar(255) NOT NULL,
  `field_value` varchar(255) NOT NULL,
  `field_placeholder` varchar(255) NOT NULL,
  `field_required` tinyint(1) NOT NULL,
  `field_validate` tinyint(1) NOT NULL,
  `field_regexp` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_form`;
CREATE TABLE `#__catalogue_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_name` varchar(255) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_links`;
CREATE TABLE `#__catalogue_links` (
  `ptype` tinyint(4) NOT NULL,
  `ctype` tinyint(4) NOT NULL,
  `parent` int(11) NOT NULL,
  `child` int(11) NOT NULL,
  PRIMARY KEY (`parent`,`child`)
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `#__catalogue_supersection`;
CREATE TABLE `#__catalogue_supersection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `supersection_name` varchar(255) NOT NULL,
  `supersection_sale` double NOT NULL,
  `published` tinyint(1) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `ordering` int(11) NOT NULL,
  `supersection_image` varchar(255) NOT NULL,
  `supersection_description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;