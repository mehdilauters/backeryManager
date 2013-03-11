SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `acos`;
DROP TABLE IF EXISTS `aros`;
DROP TABLE IF EXISTS `aros_acos`;

DROP TABLE IF EXISTS `medias`;
DROP TABLE IF EXISTS `photos`;
DROP TABLE IF EXISTS `videos`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `product_types`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `event_types`;

SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE IF NOT EXISTS `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------


--
-- Structure de la table `aros`
--


CREATE TABLE IF NOT EXISTS `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Structure de la table `aros_acos`
--


CREATE TABLE IF NOT EXISTS `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

create table if not exists medias (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  name varchar(255),
  description text,
  path varchar(255),
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


create table if not exists photos (
  `id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_photos_medias` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

create table if not exists videos (
  `id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_videos_medias` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


create table if not exists users (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  media_id int(10), 
  email varchar(255) CHARACTER SET utf8 COLLATE utf8_bin not null,
  password varchar(255) CHARACTER SET utf8 COLLATE utf8_bin,
  name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_media` (`media_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- patisserie -vienoiserie --pain.. services...
create table if not exists product_types (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  media_id int(10), 
  name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin not null ,
  description text CHARACTER SET utf8 COLLATE utf8_bin not null ,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_producttypes_media` (`media_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;



create table if not exists products (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  product_types_id int(10) NOT NULL,
  media_id int(10), 
  name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin not null ,
  description text CHARACTER SET utf8 COLLATE utf8_bin not null ,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_products_producttypes` (`product_types_id`),
  KEY `fk_products_media` (`media_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


--
-- Table structure for table `event_types`
--

CREATE TABLE IF NOT EXISTS `event_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `calendar_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

insert `event_types` values (0, 'schedule', 'notdefined');
insert `event_types` values (0, 'news', 'notdefined');
insert `event_types` values (0, 'fabrication', 'notdefined');
insert `event_types` values (0, 'maintenance', 'notdefined');


create table if not exists events (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `event_type_id` int(10) NOT NULL ,
  `gevent_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL,
  media_id int(10), 
  product_id int(10),
  PRIMARY KEY (`id`),
  KEY `fk_events_media` (`media_id`),
  KEY `fk_events_eventTypes` (`event_type_id`),
  KEY `fk_events_product` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


ALTER TABLE `photos`
  ADD CONSTRAINT `fk_photos_medias` FOREIGN KEY (`id`) REFERENCES `medias` (`id`);

ALTER TABLE `videos`
  ADD CONSTRAINT `fk_videos_medias` FOREIGN KEY (`id`) REFERENCES `medias` (`id`);
  
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_medias` FOREIGN KEY (`media_id`) REFERENCES `medias` (`id`);
  
ALTER TABLE `product_types`
  ADD CONSTRAINT `fk_producttypes_media` FOREIGN KEY (`media_id`) REFERENCES `medias` (`id`);  
  
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_medias` FOREIGN KEY (`media_id`) REFERENCES `medias` (`id`);  
  
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_medias` FOREIGN KEY (`media_id`) REFERENCES `medias` (`id`);  

ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_eventTypes` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`id`);