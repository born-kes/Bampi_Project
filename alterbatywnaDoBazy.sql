ALTER TABLE `sprzedaz`
ADD `dedykowana` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
ADD `dedykowana_top` int(1) NOT NULL,
ADD `dedykowana_cena` FLOAT( 8, 2 ) NOT NULL