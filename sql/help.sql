/*
Creates help table if it does not exist. -- It also creates the help table when
it exists?
*/

DROP TABLE IF EXISTS `help_hlp`;
CREATE TABLE `help_hlp` (
	`id_hlp` int(11) NOT NULL auto_increment,
	`title_hlp` varchar(50),
	`content_hlp` varchar(500),
	`author_hlp` varchar(16),
	`lang_hlp` varchar(3),
	PRIMARY KEY (`id_hlp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Contains helps information';

INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (1, 
		'How to register', 
		'Click "Sign up" button from right upper corner. Enter your info and verif. text in pic. Click "I agree" and "Submit" buttons.', 
		'FrostRose', 
		'en');
	
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (2,
		'Logging in',
		'Click "Login" button from right upper corner. Write your username and password to their fields. Press "Login" button under Submit button',
		'FrostRose',
		'en');
		
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (3,
		'I forgot my password',
		'Click "Login" button from right upper corner. Click "I forgot my password" under "submit" button. Proceed.',
		'FrostRose',
		'en');
		
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (4,
		'Kuinka rekisteroitya',
		'Klikkaa "Rekisteroidy" nappia oikeasta ylakulmasta. Kirjoita tietosi ja verif. teksti kuvasta. Klikkaa "Laheta"',
		'FrostRose',
		'fi');
		
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (5,
		'Kirjautuminen',
		'Klikkaa "Kirjaudu" nappia oikeasta ylakulmasta. Kirjoita kayttajanimi ja salasana omiin kenttiinsa. Klikkaa Kirjaudu nappia',
		'FrostRose',
		'fi');
		
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (6,
		'Unohdit salasanasi',
		'Klikkaa "Kirjaudu" nappia oikeasta ylakulmasta. Klikkaa "Unohdin salasanani" Kirjaudu napin alla. Jatka.',
		'FrostRose',
		'fi');
		
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (7,
		'Search',
		'Write your search word(s) you want to search with to search-field. The search is done to topic and intodusion parts of contents.',
		'FrostRose',
		'en');
		
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (8,
		'Haku',
		'Kirjoita hakusana(t) haku-kenttään. Haku tehdään sisällön otsikkoon ja esittely osiin.',
		'FrostRose',
		'fi');
		
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (9,
		'Browsing',
		'To browse content you can click one of three content buttons over search-field. Then appears same three and "all" to under search-field.',
		'FrostRose',
		'en');
		
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (10,
		'Selaaminen',
		'Selataksesi sisältöä klikkaa jotain kolmesta napista haku-kentän yläpuolella. Silloin tulee toiset kolme ja "kaikki" napit haku-kentän alle. Sisällön voi järjestää otsikon, julkaisijan päivämäärän tai katsotuimpien mukaan. ',
		'FrostRose',
		'fi');
		
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (11,
		'Add content',
		'Click "Add content" from upper right corner. Click the link in picture. Choose the options and start writing the content. Page tells if you havent written enough. To use multiple keywords write ", " before next one.',
		'FrostRose',
		'en');
		
INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (12,
		'Lisää sisältöä',
		'Klikkaa "Lisää sisältöä" oikeasta yläkulmasta. Klikkaa linkkiä kuvassa. Valitse kysytyt asiat, ja ala kirjoittamaan sisältöä. Sivu kertoo jos et ole kirjoittanut tarpeeksi. Käyttääksesi useita avain sanoja kirjoita sanojen väliin ", "',
		'FrostRose',
		'fi');
		
/* INSERT INTO help_hlp (id_hlp, title_hlp, content_hlp, author_hlp, lang_hlp) 
	VALUES (,
		'',
		'',
		'',
		'');
	---malli---
*/
