SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `prefkey` varchar(15) collate latin1_general_ci NOT NULL,
  `preftype` enum('record','normal') collate latin1_general_ci NOT NULL,
  `prefval` varchar(255) collate latin1_general_ci default NULL,
  UNIQUE KEY `prefkey` (`prefkey`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`prefkey`, `preftype`, `prefval`) VALUES
('A', 'record', 'on'),
('NS', 'record', 'on'),
('CNAME', 'record', 'on'),
('PTR', 'record', 'off'),
('MX', 'record', 'on'),
('AAAA', 'record', 'on'),
('WKS', 'record', 'off'),
('HINFO', 'record', 'off'),
('MINFO', 'record', 'off'),
('TXT', 'record', 'off'),
('RP', 'record', 'off'),
('AFSDB', 'record', 'off'),
('X25', 'record', 'off'),
('ISDN', 'record', 'off'),
('RT', 'record', 'off'),
('NSAP', 'record', 'off'),
('NSAP-PTR', 'record', 'off'),
('SIG', 'record', 'off'),
('KEY', 'record', 'off'),
('PX', 'record', 'off'),
('GPOS', 'record', 'off'),
('LOC', 'record', 'off'),
('NXT', 'record', 'off'),
('EID', 'record', 'off'),
('NIMLOC', 'record', 'off'),
('SRV', 'record', 'off'),
('ATMA', 'record', 'off'),
('NAPTR', 'record', 'off'),
('KX', 'record', 'off'),
('CERT', 'record', 'off'),
('A6', 'record', 'off'),
('DNAME', 'record', 'off'),
('SINK', 'record', 'off'),
('OPT', 'record', 'off'),
('APL', 'record', 'off'),
('DS', 'record', 'off'),
('SSHFP', 'record', 'off'),
('RRSIG', 'record', 'off'),
('NSEC', 'record', 'off'),
('DNSKEY', 'record', 'off'),
('TKEY', 'record', 'off'),
('TSIG', 'record', 'off'),
('IXFR', 'record', 'off'),
('AXFR', 'record', 'off'),
('MAILB', 'record', 'off'),
('prins', 'normal', 'ns1.domain.tdl'),
('secns', 'normal', 'ns2.domain.tdl'),
('hostmaster', 'normal', 'hostmaster.domain.tdl'),
('range', 'normal', '10');

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE IF NOT EXISTS `records` (
  `id` int(11) NOT NULL auto_increment,
  `zone` int(11) NOT NULL default '0',
  `host` varchar(255) collate latin1_general_ci NOT NULL,
  `type` varchar(10) collate latin1_general_ci NOT NULL,
  `pri` int(11) NOT NULL default '0',
  `destination` varchar(255) collate latin1_general_ci NOT NULL,
  `valid` enum('unknown','yes','no') collate latin1_general_ci NOT NULL default 'unknown',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `records`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) collate latin1_general_ci NOT NULL,
  `password` varchar(40) collate latin1_general_ci NOT NULL,
  `admin` enum('no','yes') collate latin1_general_ci NOT NULL default 'no',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--


-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE IF NOT EXISTS `zones` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate latin1_general_ci NOT NULL,
  `pri_dns` varchar(100) collate latin1_general_ci default NULL,
  `sec_dns` varchar(100) collate latin1_general_ci default NULL,
  `serial` int(10) NOT NULL default '0',
  `refresh` int(11) NOT NULL default '604800',
  `retry` int(11) NOT NULL default '86400',
  `expire` int(11) NOT NULL default '2419200',
  `ttl` int(11) NOT NULL default '604800',
  `valid` enum('unknown','no','yes') collate latin1_general_ci NOT NULL default 'unknown',
  `owner` int(11) NOT NULL default '1',
  `updated` enum('yes','no') collate latin1_general_ci NOT NULL default 'yes',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `zones`
--
