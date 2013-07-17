CREATE TABLE `job_info` (
  `job_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_title` text NOT NULL,
  `salary` varchar(10) NOT NULL COMMENT 'Assumes max salary of $9,999,999. Ideally this should be purely numeric, with all formatting stripped on input and applied on output. MEDIUMINT, unsigned would handle a max salary of approx. $8million',
  `start_date` date NOT NULL,
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `employer_info` (
  `employer_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(128) NOT NULL,
  `company_phone` char(8) NOT NULL COMMENT 'Bumped down from 11, as all of the sample data had only 7 digits + 1 separator. Ideally this would be pure numeric, and slightly longer (15?), with formatting being stripped on input and applied on output.',
  `company_address` varchar(128) NOT NULL,
  PRIMARY KEY (`employer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `employee_info` (
  `employee_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `email1` varchar(64) NOT NULL,
  `email2` varchar(128) DEFAULT NULL,
  `permission_level` enum('admin','user') NOT NULL DEFAULT 'user',
  `is_permission_level_active` enum('true','false') NOT NULL DEFAULT 'false' COMMENT 'Consider using tinyint(1) or boolean here. Actual TRUE and FALSE, instead of interpretting strings.',
  `password` char(32) NOT NULL COMMENT 'this assumes keeping the current password encryption, and encrypting/decrypting external to the database.',
  `employer_id` int(10) unsigned NOT NULL,
  `job_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`employee_id`),
  KEY `job_id_idx` (`job_id`),
  KEY `employer_info_idx` (`employer_id`),
  CONSTRAINT `employer_info` FOREIGN KEY (`employer_id`) REFERENCES `employer_info` (`employer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `job_id` FOREIGN KEY (`job_id`) REFERENCES `job_info` (`job_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


