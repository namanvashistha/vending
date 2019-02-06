
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `user` (
  `userid` int(12) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `location` varchar(25) NOT NULL,
  `token` varchar(15) NOT NULL,
  `isemail` int(1) NOT NULL DEFAULT '0',
  `otp` int(6) NOT NULL,
  `isphone` int(1) NOT NULL DEFAULT '0',
  `weddingweb` varchar(70) NOT NULL DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `vendor` (
  `vendorid` int(12) NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `city` varchar(20) NOT NULL,
  `postal` varchar(8) NOT NULL,
  `address` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `displaypicture` varchar(60) NOT NULL DEFAULT 'default.jpg',
  `category` varchar(25) NOT NULL,
  `token` varchar(15) NOT NULL,
  `isemail` int(1) NOT NULL DEFAULT '0',
  `otp` int(6) NOT NULL,
  `isphone` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);


ALTER TABLE `vendor`
  ADD PRIMARY KEY (`vendorid`);


ALTER TABLE `user`
  MODIFY `userid` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;


ALTER TABLE `vendor`
  MODIFY `vendorid` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100022;
COMMIT;

