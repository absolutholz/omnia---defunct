



-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'users'
-- 
-- ---

DROP TABLE IF EXISTS `users`;
		
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(25) NOT NULL,
  `password` CHAR(40) NOT NULL,
  `created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'participations'
-- 
-- ---

DROP TABLE IF EXISTS `participations`;
		
CREATE TABLE `participations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `collection_id` INT(11) NOT NULL,
  `completion_count` INT NULL DEFAULT 0,
  `visibility_id` INT(11) NOT NULL DEFAULT 1,
  `created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'collections'
-- 
-- ---

DROP TABLE IF EXISTS `collections`;
		
CREATE TABLE `collections` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` MEDIUMTEXT NULL DEFAULT NULL,
  `collection_item_count` INT NULL DEFAULT 0,
  `visibility_id` INT(11) NOT NULL DEFAULT 1,
  `created` TIMESTAMP NOT NULL,
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'collection_items'
-- 
-- ---

DROP TABLE IF EXISTS `collection_items`;
		
CREATE TABLE `collection_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `notes` MEDIUMTEXT NULL DEFAULT NULL,
  `collection_id` INT(11) NOT NULL,
  `collection_item_status_id` INT(11) NOT NULL DEFAULT 1,
  `created` TIMESTAMP NOT NULL,
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'collection_item_statuses'
-- 
-- ---

DROP TABLE IF EXISTS `collection_item_statuses`;
		
CREATE TABLE `collection_item_statuses` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'collection_item_fields'
-- 
-- ---

DROP TABLE IF EXISTS `collection_item_fields`;
		
CREATE TABLE `collection_item_fields` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `value` MEDIUMTEXT NOT NULL,
  `field_id` INT(11) NOT NULL,
  `collection_item_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'completions'
-- 
-- ---

DROP TABLE IF EXISTS `completions`;
		
CREATE TABLE `completions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `collection_item_id` INT(11) NOT NULL,
  `participation_id` INT(11) NOT NULL,
  `completion_status_id` INT(11) NOT NULL DEFAULT 1,
  `created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'fields'
-- 
-- ---

DROP TABLE IF EXISTS `fields`;
		
CREATE TABLE `fields` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `field_type_id` INT(11) NOT NULL DEFAULT 1,
  `collection_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'completion_statuses'
-- 
-- ---

DROP TABLE IF EXISTS `completion_statuses`;
		
CREATE TABLE `completion_statuses` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'field_types'
-- 
-- ---

DROP TABLE IF EXISTS `field_types`;
		
CREATE TABLE `field_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `description` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'visibilities'
-- 
-- ---

DROP TABLE IF EXISTS `visibilities`;
		
CREATE TABLE `visibilities` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(25) NOT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `participations` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `participations` ADD FOREIGN KEY (collection_id) REFERENCES `collections` (`id`);
ALTER TABLE `participations` ADD FOREIGN KEY (visibility_id) REFERENCES `visibilities` (`id`);
ALTER TABLE `collections` ADD FOREIGN KEY (visibility_id) REFERENCES `visibilities` (`id`);
ALTER TABLE `collections` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `collection_items` ADD FOREIGN KEY (collection_id) REFERENCES `collections` (`id`);
ALTER TABLE `collection_items` ADD FOREIGN KEY (collection_item_status_id) REFERENCES `collection_item_statuses` (`id`);
ALTER TABLE `collection_items` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `collection_item_fields` ADD FOREIGN KEY (field_id) REFERENCES `fields` (`id`);
ALTER TABLE `collection_item_fields` ADD FOREIGN KEY (collection_item_id) REFERENCES `collection_items` (`id`);
ALTER TABLE `completions` ADD FOREIGN KEY (collection_item_id) REFERENCES `collection_items` (`id`);
ALTER TABLE `completions` ADD FOREIGN KEY (participation_id) REFERENCES `participations` (`id`);
ALTER TABLE `completions` ADD FOREIGN KEY (completion_status_id) REFERENCES `completion_statuses` (`id`);
ALTER TABLE `fields` ADD FOREIGN KEY (field_type_id) REFERENCES `field_types` (`id`);
ALTER TABLE `fields` ADD FOREIGN KEY (collection_id) REFERENCES `collections` (`id`);

-- ---
-- Table Properties
-- ---

-- ALTER TABLE `users` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `participations` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `collections` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `collection_items` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `collection_item_statuses` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `collection_item_fields` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `completions` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `fields` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `completion_statuses` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `field_types` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `visibilities` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `users` (`id`,`username`,`password`,`created`) VALUES
-- ('','','','');
-- INSERT INTO `participations` (`id`,`user_id`,`collection_id`,`completion_count`,`visibility_id`,`created`) VALUES
-- ('','','','','','');
-- INSERT INTO `collections` (`id`,`name`,`description`,`collection_item_count`,`visibility_id`,`created`,`user_id`) VALUES
-- ('','','','','','','');
-- INSERT INTO `collection_items` (`id`,`name`,`notes`,`collection_id`,`collection_item_status_id`,`created`,`user_id`) VALUES
-- ('','','','','','','');
-- INSERT INTO `collection_item_statuses` (`id`,`name`) VALUES
-- ('','');
-- INSERT INTO `collection_item_fields` (`id`,`value`,`field_id`,`collection_item_id`) VALUES
-- ('','','','');
-- INSERT INTO `completions` (`id`,`collection_item_id`,`participation_id`,`completion_status_id`,`created`) VALUES
-- ('','','','','');
-- INSERT INTO `fields` (`id`,`name`,`field_type_id`,`collection_id`) VALUES
-- ('','','','');
-- INSERT INTO `completion_statuses` (`id`,`name`) VALUES
-- ('','');
-- INSERT INTO `field_types` (`id`,`name`,`description`) VALUES
-- ('','','');
-- INSERT INTO `visibilities` (`id`,`name`) VALUES
-- ('','');

