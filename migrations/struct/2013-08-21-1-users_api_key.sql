
ALTER TABLE `users`
ADD `api_key` varchar(250) NULL COMMENT 'klíč API' AFTER `expiration_date`,
ADD `api_key_expiration_date` datetime NULL COMMENT 'platnost klíče API' AFTER `api_key`;

ALTER TABLE `users`
ADD UNIQUE `api_key` (`api_key`);
