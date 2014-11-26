CREATE DATABASE `joroot`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `news`
--

CREATE TABLE IF NOT EXISTS `joroot`.`news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `description` text,
  `image` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `admin`
--

CREATE TABLE IF NOT EXISTS `joroot`.`admin` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(200) NOT NULL,
  `plain_password` varchar(150) NOT NULL,
  `role` varchar(25) NOT NULL,
  `status` enum('A','I') NOT NULL,
  `last_ip` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_last_logged` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `plain_password`, `role`, `status`, `last_ip`, `date_created`, `date_last_logged`) VALUES
(1, 'Administrador', 'admin@joroot.com.br', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'MTIzNDU2', 'ADMIN', 'A', '127.0.0.1', '2014-10-16 17:18:15', NULL);
