
CREATE TABLE `api_requests` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(1) unsigned DEFAULT NULL COMMENT 'id uživatele',
  `method` varchar(250) NOT NULL COMMENT 'HTTP metoda',
  `url` varchar(250) NOT NULL COMMENT 'URL',
  `headers` text NOT NULL COMMENT 'hlavičky požadavku',
  `body` text NOT NULL COMMENT 'tělo požadavku',
  `api_version` varchar(250) DEFAULT NULL COMMENT 'verze rozhraní',
  `action` varchar(250) DEFAULT NULL COMMENT 'presenter a akce',
  `response_code` varchar(250) NOT NULL COMMENT 'HTTP kód odpovědi',
  `response_headers` text NOT NULL COMMENT 'hlavičky odpovědi',
  `response_body` text NOT NULL COMMENT 'tělo odpovědi',
  `remote_address` varchar(250) NOT NULL COMMENT 'IP adresa klienta',
  `remote_host` varchar(250) DEFAULT NULL COMMENT 'doménové jméno klienta',
  `created_at` datetime NOT NULL COMMENT 'čas obdržení požadavku',
  PRIMARY KEY (`id`),
  KEY `fk_api_requests_users1` (`user_id`),
  CONSTRAINT `api_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='přijaté rekvesty (pro debugování komunikace)';
