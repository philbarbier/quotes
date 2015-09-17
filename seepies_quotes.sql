# phpMyAdmin MySQL-Dump
# version 2.4.0-rc1
# http://www.phpmyadmin.net/ (download page)
#
# Host: 192.168.56.132
# Generation Time: Jul 28, 2004 at 06:44 PM
# Server version: 3.23.49
# PHP Version: 4.3.4
# Database : `seepies_quotes`
# --------------------------------------------------------

#
# Table structure for table `tbl_moderator`
#

CREATE TABLE tbl_moderator (
  modid tinyint(4) NOT NULL auto_increment,
  username varchar(10) NOT NULL default '',
  qpassword varchar(32) NOT NULL default '',
  email varchar(50) NOT NULL default '',
  PRIMARY KEY  (modid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tbl_quote`
#

CREATE TABLE tbl_quote (
  quoteid int(8) NOT NULL auto_increment,
  visible tinyint(1) NOT NULL default '0',
  qstatus tinyint(1) NOT NULL default '1',
  ipaddress varchar(15) NOT NULL default '',
  datetime int(11) NOT NULL default '0',
  quotetext longtext NOT NULL,
  score int(8) NOT NULL default '0',
  mksuck tinyint(1) NOT NULL default '0',
  datemod int(11) NOT NULL default '0',
  assignedto tinyint(4) default NULL,
  PRIMARY KEY  (quoteid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tbl_status`
#

CREATE TABLE tbl_status (
  statusid tinyint(4) NOT NULL auto_increment,
  status varchar(20) NOT NULL default '',
  PRIMARY KEY  (statusid)
) TYPE=MyISAM;

