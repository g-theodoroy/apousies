CREATE DATABASE IF NOT EXISTS `apousies_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `apousies_db`;
CREATE TABLE IF NOT EXISTS `apousies` (  `mydate` int(8) NOT NULL,  `apous` varchar(10) NOT NULL,  `dik` int(11) NOT NULL DEFAULT '0',  `from` varchar(1) NOT NULL,  `fh` int(11) NOT NULL,  `mh` int(11) NOT NULL,  `lh` int(11) NOT NULL,  `oa` int(11) NOT NULL,  `da` int(11) NOT NULL,  `user` varchar(50) NOT NULL,  `student_am` varchar(20) NOT NULL,  UNIQUE KEY `mydate` (`mydate`,`user`,`student_am`),  KEY `student_am` (`student_am`),  KEY `user` (`user`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `apousies_pre` (  `mydate` int(8) NOT NULL,  `apous` varchar(20) NOT NULL,  `dik` varchar(20) NOT NULL,  `daysk` int(11) NOT NULL,  `fh` int(11) NOT NULL,  `mh` int(11) NOT NULL,  `lh` int(11) NOT NULL,  `oa` int(11) NOT NULL,  `da` int(11) NOT NULL,  `user` varchar(50) NOT NULL,  `student_am` varchar(20) NOT NULL,  UNIQUE KEY `student_am` (`student_am`,`user`),  KEY `user` (`user`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `dikaiologisi` (  `aa` int(11) NOT NULL AUTO_INCREMENT,  `protokolo` int(11) NOT NULL,  `mydate` int(11) NOT NULL,  `firstday` varchar(20) NOT NULL,  `lastday` varchar(20) NOT NULL,  `countdays` int(11) NOT NULL,  `iat_beb` enum('0','1') NOT NULL DEFAULT '0',  `am` varchar(20) NOT NULL,  `user` varchar(50) NOT NULL,  PRIMARY KEY (`aa`),  UNIQUE KEY `mydate` (`mydate`,`am`,`user`),  KEY `am` (`am`,`user`)) ENGINE=InnoDB AUTO_INCREMENT=554 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `paperhistory` (  `aa` int(11) NOT NULL AUTO_INCREMENT,  `protok` varchar(5) DEFAULT NULL,  `mydate` int(8) NOT NULL,  `am` varchar(20) NOT NULL,  `apous` int(11) NOT NULL,  `user` varchar(20) NOT NULL,  PRIMARY KEY (`aa`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `parameters` (  `key` varchar(20) NOT NULL,  `value` varchar(50) NOT NULL,  `user` varchar(20) NOT NULL,  `tmima` varchar(20) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `students` (  `am` varchar(10) NOT NULL,  `epitheto` varchar(50) NOT NULL,  `onoma` varchar(50) NOT NULL,  `patronimo` varchar(50) NOT NULL,  `ep_kidemona` varchar(50) NOT NULL,  `on_kidemona` varchar(50) NOT NULL,  `dieythinsi` varchar(50) NOT NULL,  `tk` varchar(10) NOT NULL,  `poli` varchar(50) NOT NULL,  `til1` varchar(10) NOT NULL,  `til2` varchar(10) NOT NULL,  `filo` varchar(5) NOT NULL,  `email` varchar(50) NOT NULL,  `user` varchar(50) NOT NULL,  UNIQUE KEY `am` (`am`,`user`),  KEY `epitheto` (`epitheto`),  KEY `onoma` (`onoma`),  KEY `user` (`user`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `studentstmimata` (  `user` varchar(50) NOT NULL,  `student_am` varchar(20) NOT NULL,  `tmima` varchar(20) NOT NULL,  UNIQUE KEY `user` (`user`,`student_am`,`tmima`),  KEY `student_am` (`student_am`),  KEY `tmima` (`tmima`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `tmimata` (  `username` varchar(50) NOT NULL,  `tmima` varchar(15) NOT NULL,  `lastselect` int(15) NOT NULL,  `type` varchar(1) NOT NULL,  UNIQUE KEY `tmima` (`tmima`,`username`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `users` (  `username` varchar(50) NOT NULL,  `password` varchar(50) NOT NULL,  `email` varchar(50) NOT NULL,  `reminder` varchar(50) NOT NULL,  `timestamp` int(15) NOT NULL,  `lastlogin` int(15) NOT NULL,  `apoucheck` varchar(10) NOT NULL DEFAULT '012',  `dikcheck` varchar(10) NOT NULL DEFAULT '012',  `groupname` varchar(50) NOT NULL,  UNIQUE KEY `username` (`username`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `apousies`  ADD CONSTRAINT `apousies_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;