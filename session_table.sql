-- Tabela de sessões para o driver 'database' do CodeIgniter (application/config/config.php).
-- Rode este script uma vez contra o banco de produção (mesmo DB_DATABASE do .env).
-- Referencia: https://codeigniter.com/userguide3/libraries/sessions.html#database

USE novel;

CREATE TABLE IF NOT EXISTS `ci_sessions` (
	`id` varchar(128) NOT NULL,
	`ip_address` varchar(45) NOT NULL,
	`timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
	`data` blob NOT NULL,
	KEY `ci_sessions_timestamp` (`timestamp`)
);

ALTER TABLE `ci_sessions` CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ci_sessions` ADD PRIMARY KEY (`id`);
