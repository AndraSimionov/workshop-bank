# table schema queries
CREATE DATABASE IF NOT EXISTS `sample_bank`;

USE `sample_bank`;

CREATE TABLE IF NOT EXISTS `users` (
	`IdUser` INT(11) NOT NULL AUTO_INCREMENT,
	`Username` VARCHAR(50) NOT NULL DEFAULT '',
	`FirstName` VARCHAR(50) NOT NULL DEFAULT '',
	`LastName` VARCHAR(50) NOT NULL DEFAULT '',
	`Email` VARCHAR(50) NOT NULL DEFAULT '',
	`Password` VARCHAR(50) NOT NULL DEFAULT '',
	`RegistrationDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`IdUser`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `credit_cards` (
	`IdCreditCard` INT(11) NOT NULL AUTO_INCREMENT,
	`IdUser` INT(11) NOT NULL,
	`CardNumber` VARCHAR(19) NOT NULL,
	`Cvv` INT(4) NOT NULL,
	`ExpirationMonth` INT(2) NOT NULL,
	`ExpirationYear` INT(4) NOT NULL,
	`AddDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`ChangeDate` DATETIME,
	PRIMARY KEY (`IdCreditCard`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `card_amounts` (
	`IdCardAmounts` INT(11) NOT NULL AUTO_INCREMENT,
	`IdCreditCard` INT(11) NOT NULL,
	`Sold` INT(11) NOT NULL,
	`Currency` VARCHAR(3) NOT NULL DEFAULT 'RON',
	`AddDate`DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`ChangeDate` DATETIME,
	PRIMARY KEY (`IdCardAmounts`)
)
	COLLATE='utf8_general_ci'
	ENGINE=InnoDB
	AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `user_credentials` (
	`IdUserCredentials` INT(11) NOT NULL AUTO_INCREMENT,
	`ClientId` VARCHAR(50) NOT NULL DEFAULT '',
	`SecretKey` VARCHAR(50) NOT NULL DEFAULT '',
	`Email` VARCHAR(50) NOT NULL DEFAULT '',
	`AddDate`DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`IdUserCredentials`)
)
	COLLATE='utf8_general_ci'
	ENGINE=InnoDB
	AUTO_INCREMENT=0;
