/*
Creates help table if it does not exist. 
*/

DROP TABLE IF EXISTS `help_hlp`;
CREATE TABLE `help_hlp` (
	`hlp_id` int(11) NOT NULL auto_increment,
	`hlp_title` varchar(50),
	`hlp_content` varchar(255),
	`hlp_author` varchar(16),
	`hlp_lang` varchar(3),
	PRIMARY KEY (`hlp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Contains helps information';

INSERT INTO help_hlp (hlp_id, hlp_title, hlp_content, hlp_author, hlp_lang) 
	VALUES (1, 
		'How to register', 
		'Click "Sign up" button from ru. corner. Enter your info and verif. text in pic. Click "I agree" and "Submit" buttons.', 
		'FrostRose', 
		'en');
	
INSERT INTO help_hlp (hlp_id, hlp_title, hlp_content, hlp_author, hlp_lang) 
	VALUES (2,
		'Logging in',
		'Click "Login" button from ru. corner. Write your username and password to their fields. Press "Login" button under Submit button',
		'FrostRose',
		'en');
		
INSERT INTO help_hlp (hlp_id, hlp_title, hlp_content, hlp_author, hlp_lang) 
	VALUES (3,
		'I forgot my password',
		'Click "Login" button from ru. corner. Click "I forgot my password" under "submit" button. Proceed.',
		'FrostRose',
		'en');
		
INSERT INTO help_hlp (hlp_id, hlp_title, hlp_content, hlp_author, hlp_lang) 
	VALUES (4,
		'Kuinka rekisteroitya',
		'Klikkaa "Rekisteroidy" nappia oy. kulmasta. Kirjoita tietosi ja verif. teksti kuvasta. Klikkaa "Laheta"',
		'FrostRose',
		'fi');
		
INSERT INTO help_hlp (hlp_id, hlp_title, hlp_content, hlp_author, hlp_lang) 
	VALUES (5,
		'Kirjautuminen',
		'Klikkaa "Kirjaudu" nappia oy. kulmasta. Kirjoita kayttajanimi ja salasana omiin kenttiinsa. Klikkaa Kirjaudu nappia',
		'FrostRose',
		'fi');
		
INSERT INTO help_hlp (hlp_id, hlp_title, hlp_content, hlp_author, hlp_lang) 
	VALUES (6,
		'Unohdit salasanasi',
		'Klikkaa "Kirjaudu" nappia oy. kulmasta. Klikkaa "Unohdin salasanani" Kirjaudu napin alla. Jatka.',
		'FrostRose',
		'fi');
		
