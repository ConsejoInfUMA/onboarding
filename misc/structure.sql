-- onboarding.invites definition
CREATE TABLE `invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(156) NOT NULL,
  `firstName` varchar(156) NOT NULL,
  `lastName` varchar(156) NOT NULL,
  `email` varchar(156) NOT NULL,
  `token` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invites_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
