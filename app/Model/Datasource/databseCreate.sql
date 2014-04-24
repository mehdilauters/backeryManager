SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `database_version`;
DROP TABLE IF EXISTS `acos`;
DROP TABLE IF EXISTS `aros`;
DROP TABLE IF EXISTS `aros_acos`;

DROP TABLE IF EXISTS `results_entries`;
DROP TABLE IF EXISTS `results`;
DROP TABLE IF EXISTS `sales`;
DROP TABLE IF EXISTS `medias`;
DROP TABLE IF EXISTS `photos`;
DROP TABLE IF EXISTS `videos`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `product_types`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `recursive_events`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `event_types`;
DROP TABLE IF EXISTS `shops`;

DROP TABLE IF EXISTS `ordered_items`;
DROP TABLE IF EXISTS `orders`;


DROP TABLE IF EXISTS `companies`;

SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE IF NOT EXISTS `database_version` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  version int (5),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
insert into database_version (version) values (0);


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
  `rib_on_orders` boolean default TRUE,
  `created` datetime DEFAULT NULL,
  address text CHARACTER SET utf8 COLLATE utf8_bin ,
  phone int ,
  `discount` float(3) default 0 ,
  PRIMARY KEY (`id`),
  KEY `fk_users_media` (`media_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- patisserie -vienoiserie --pain.. services...
create table if not exists product_types (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  media_id int(10), 
  name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin not null ,
  description text CHARACTER SET utf8 COLLATE utf8_bin not null ,
  `customer_display` boolean default TRUE,
  `tva` float(3) not null ,
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
  `price` float(3) NOT NULL,
  `unity` boolean default TRUE,
  `customer_display` boolean default TRUE,
  `production_display` boolean default TRUE,
  `depends_on_production` boolean default TRUE,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_products_producttypes` (`product_types_id`),
  KEY `fk_products_media` (`media_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


create table if not exists shops (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  media_id int(10),
  `event_type_id` int(10) NOT NULL ,
  name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin not null ,
  phone int not null ,
  address text CHARACTER SET utf8 COLLATE utf8_bin not null ,
  description text CHARACTER SET utf8 COLLATE utf8_bin not null ,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_shops_eventTypes` (`event_type_id`),
  KEY `fk_shops_media` (`media_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Table structure for table `event_types`
--

CREATE TABLE IF NOT EXISTS `event_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `recursive_start` datetime,
  `recursive_end` datetime ,
  `recursive` enum('day','week','month', 'year'),
  `all_day` tinyint(1) NOT NULL DEFAULT '1',
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Scheduled',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` date DEFAULT NULL,
  `modified` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_events_event_type` (`event_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


-- create table if not exists events (
--  `id` int(10) NOT NULL AUTO_INCREMENT,
--  `event_type_id` int(10) NOT NULL ,
--  `gevent_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL,
--  media_id int(10), 
--  product_id int(10),
--  PRIMARY KEY (`id`),
--  KEY `fk_events_media` (`media_id`),
--  KEY `fk_events_eventTypes` (`event_type_id`),
--  KEY `fk_events_product` (`product_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;



create table if not exists sales (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `product_id` int(10) NOT NULL ,
  `price` float(3) NOT NULL,
  `unity` boolean default TRUE,
  `shop_id` int(10) NOT NULL ,
  `produced` int(10) ,
  `lost` int(10) ,
  `comment` text CHARACTER SET utf8 COLLATE utf8_bin not null ,
  PRIMARY KEY (`id`),
  KEY `fk_sales_products` (`product_id`),
  KEY `fk_sales_shops` (`shop_id`),
  UNIQUE KEY `unique_sales` (`date`,`shop_id`, `product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


create table if not exists results (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) NOT NULL ,
  `date` datetime NOT NULL,
  `cash` float(10) NOT NULL,
  `check` float(10) NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_bin not null,
  PRIMARY KEY (`id`),
  KEY `fk_results_shops` (`shop_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

create table if not exists results_entries (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `result_id` int(10) NOT NULL,
  `product_types_id` int(10) NOT NULL ,
  `shop_id` int(10) NOT NULL ,
  `date` datetime NOT NULL,
  `result` float(10) ,
  PRIMARY KEY (`id`),
  KEY `fk_results_entries_results` (`result_id`),
  KEY `fk_results_entries_productsTypes` (`product_types_id`),
  KEY `fk_results_entries_shops` (`shop_id`),
  UNIQUE KEY `unique_results` (`result_id`, `product_types_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

	
create table if not exists orders (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) NOT NULL,
  `created` datetime DEFAULT NULL,
  `user_id` int(10) NOT NULL,
  `status` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin not null ,
  `legals_mentions` text CHARACTER SET utf8 COLLATE utf8_bin,
  `delivery_date` datetime,
  `comment` text CHARACTER SET utf8 COLLATE utf8_bin,
  `discount` float(3) default 0 ,
  PRIMARY KEY (`id`),
  KEY `fk_orders_shops` (`shop_id`),
  KEY `fk_orders_users` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


create table if not exists ordered_items (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL ,
  `created` datetime NOT NULL,
  `tva` float(3),
  `price` float(3),
  `unity` boolean default TRUE,
  `quantity` float(5) ,
  `comment` text CHARACTER SET utf8 COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  KEY `fk_ordered_items_orders` (`order_id`),
  KEY `fk_ordered_items_products` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

create table if not exists companies (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rib` int(10) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin not null ,
  `address` text CHARACTER SET utf8 COLLATE utf8_bin not null ,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin not null,
  `phone` int not null ,
  `capital` int not null ,
  `siret` bigint(20) not null ,
  PRIMARY KEY (`id`),
  KEY `fk_companies_media` (`rib`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


ALTER TABLE `companies`
  ADD CONSTRAINT `fk_companies_media` FOREIGN KEY (`rib`) REFERENCES `medias` (`id`);

ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_shops` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`),
  ADD CONSTRAINT `fk_orders_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `ordered_items`
  ADD CONSTRAINT `fk_ordered_items_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `fk_ordered_items_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

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
  
ALTER TABLE `shops`
  ADD CONSTRAINT `fk_shops_media` FOREIGN KEY (`media_id`) REFERENCES `medias` (`id`),
  ADD CONSTRAINT `fk_shops_eventTypes` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`id`);  

-- ALTER TABLE `events`
--   ADD CONSTRAINT `fk_events_medias` FOREIGN KEY (`media_id`) REFERENCES `medias` (`id`),
--   ADD CONSTRAINT `fk_events_eventTypes` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`id`);

ALTER TABLE `events`
   ADD CONSTRAINT `fk_events_event_types` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`id`);

ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_sales_shops` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`);

ALTER TABLE `results_entries`
  ADD CONSTRAINT `fk_results_entries_productsTypes` FOREIGN KEY (`product_types_id`) REFERENCES `product_types` (`id`),
  ADD CONSTRAINT `fk_results_entries_shops` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`),
  ADD CONSTRAINT `fk_results_entries_results` FOREIGN KEY (`result_id`) REFERENCES `results` (`id`);

ALTER TABLE `results`
  ADD CONSTRAINT `fk_results_shops` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`);

