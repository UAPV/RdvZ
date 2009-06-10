SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de données: `rdvz-revisited`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `meeting`
-- 

CREATE TABLE IF NOT EXISTS `meeting` (
  `mid` varchar(8) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `uid` varchar(50) NOT NULL,
  `date_del` date NOT NULL,
  `date_end` date NOT NULL,
  `closed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `meeting_date`
-- 

CREATE TABLE IF NOT EXISTS `meeting_date` (
  `pollid` int(11) NOT NULL auto_increment,
  `mid` varchar(8) NOT NULL,
  `date` date NOT NULL,
  `comment` varchar(255) default NULL,
  PRIMARY KEY  (`pollid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=203 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `meeting_poll`
-- 

CREATE TABLE IF NOT EXISTS `meeting_poll` (
  `uid` varchar(50) default NULL,
  `pollid` int(11) NOT NULL,
  `poll` tinyint(1) NOT NULL,
  `participant_name` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

