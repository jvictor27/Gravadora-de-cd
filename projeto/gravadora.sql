-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 13-Ago-2016 às 16:37
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gravadora`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ws_cantor`
--

CREATE TABLE IF NOT EXISTS `ws_cantor` (
  `cantor_id` int(11) NOT NULL AUTO_INCREMENT,
  `cantor_title` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `cantor_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cantor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `ws_cantor`
--

INSERT INTO `ws_cantor` (`cantor_id`, `cantor_title`, `cantor_date`) VALUES
(1, 'Raul seixas', NULL),
(2, 'System of a Down', NULL),
(4, 'teste', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ws_cds`
--

CREATE TABLE IF NOT EXISTS `ws_cds` (
  `cd_id` int(11) NOT NULL AUTO_INCREMENT,
  `cd_title` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `cd_cover` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `cd_date` timestamp NULL DEFAULT NULL,
  `cd_author` int(11) DEFAULT NULL,
  `cd_cantor` int(11) DEFAULT NULL,
  `cd_type` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`cd_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `ws_cds`
--

INSERT INTO `ws_cds` (`cd_id`, `cd_title`, `cd_cover`, `cd_date`, `cd_author`, `cd_cantor`, `cd_type`) VALUES
(1, 'O baú do Raul', 'images/2016/08/o-bau-do-raul.jpg', '2016-08-08 01:09:52', NULL, 1, 'post'),
(2, 'Toxicity', 'images/2016/08/toxicity.jpg', '2016-08-08 01:13:42', NULL, 2, 'post'),
(3, 'Toxicity II', 'images/2016/08/toxicity-ii.jpg', '2016-08-08 01:14:44', NULL, 2, 'post'),
(4, 'king', 'images/2016/08/king.png', '2016-08-12 22:45:12', NULL, 4, 'cd'),
(5, 'coringa', 'images/2016/08/coringa.jpg', '2016-08-13 02:15:53', NULL, 4, 'cd');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ws_users`
--

CREATE TABLE IF NOT EXISTS `ws_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_lastname` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_registration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_lastupdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_level` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `ws_users`
--

INSERT INTO `ws_users` (`user_id`, `user_name`, `user_lastname`, `user_email`, `user_password`, `user_registration`, `user_lastupdate`, `user_level`) VALUES
(5, 'João Victor', 'Oliveira Júniro', 'jvictor451@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2016-08-07 17:57:54', '0000-00-00 00:00:00', 2),
(6, 'Teste', 'Testador', 'teste@email.com', '25d55ad283aa400af464c76d713c07ad', '2016-08-07 17:51:54', '0000-00-00 00:00:00', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
