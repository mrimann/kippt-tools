CREATE TABLE `clips` (
	`id` int(11) unsigned NOT NULL,
	`title` varchar(255) DEFAULT NULL,
	`url` varchar(255) DEFAULT NULL,
	`list` varchar(255) DEFAULT NULL,
	`notes` text,
	`is_starred` tinyint(4) DEFAULT NULL,
	`url_domain` tinytext,
	`created` int(11) DEFAULT NULL,
	`updated` int(11) DEFAULT NULL,
	`resource_uri` tinytext,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;