SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `accounts`;
DROP TABLE IF EXISTS `account_entries`;

SET FOREIGN_KEY_CHECKS = 1; 



create table if not exists accounts (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

create table if not exists account_entries (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `account_id` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(255) not null,
  `value` float(10),
  PRIMARY KEY (`id`),
  KEY `fk_account_entries_account` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

ALTER TABLE `account_entries`
  ADD CONSTRAINT `fk_account_entries_account` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);