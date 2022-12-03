DROP TABLE IF EXISTS `apk_dmu_rapportage`;
CREATE TABLE IF NOT EXISTS `apk_dmu_rapportage` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `fk_fma_excel` int(255) NOT NULL,
  `unixdate` int(255) NOT NULL,
  `ordernummer` varchar(1024) COLLATE utf8_bin NOT NULL,
  `status_excel` int(255) NOT NULL DEFAULT 0,
  `beschrijving` varchar(1024) COLLATE utf8_bin NOT NULL,
  `status` int(255) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_fma_excel` (`fk_fma_excel`)
) ENGINE=MyISAM AUTO_INCREMENT=4532 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;