CREATE TABLE IF NOT EXISTS `daily` (
	`title` varchar(50) NOT NULL,
	`share_url` varchar(100) NOT NULL,
	`id` varchar(50) NOT NULL,
	`body` text NOT NULL,
	`date` varchar(50) NOT NULL,
	`image` varchar(100) NOT NULL,
	`image_source` varchar(50) NOT NULL,
	`date_index` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;