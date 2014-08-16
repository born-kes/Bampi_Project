<?php
/**
 * pierwsze uruchoienie
 */
$db->query(
    "CREATE TABLE IF NOT EXISTS `sprzedaz` (
`id` int(10) unsigned NOT NULL DEFAULT '0',
`top1` varchar(255) DEFAULT NULL,
`top1_cena` float(8,2) unsigned DEFAULT NULL,
`top2` varchar(255) DEFAULT NULL,
`top2_cena` float(8,2) unsigned DEFAULT NULL,
`top3` varchar(255) DEFAULT NULL,
`top3_cena` float(8,2) unsigned DEFAULT NULL,
`top4` varchar(255) DEFAULT NULL,
`top4_cena` float(8,2) unsigned DEFAULT NULL,
UNIQUE KEY `top_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);