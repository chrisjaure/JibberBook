CREATE TABLE `jb_comments` (
  `id` varchar(30) collate latin1_general_ci NOT NULL,
  `name` varchar(50) collate latin1_general_ci NOT NULL,
  `website` varchar(100) collate latin1_general_ci NOT NULL,
  `comment` text collate latin1_general_ci NOT NULL,
  `date` int(10) NOT NULL,
  `user_ip` varchar(15) collate latin1_general_ci NOT NULL,
  `user_agent` varchar(255) collate latin1_general_ci NOT NULL,
  `spam` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `spam` (`spam`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
