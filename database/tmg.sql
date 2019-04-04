/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
DROP TABLE IF EXISTS clients;
CREATE TABLE `clients` (
  `client_id` varchar(11) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `client_phone` varchar(13) NOT NULL,
  `client_email` varchar(50) DEFAULT NULL,
  `client_priv_value` int(11) unsigned DEFAULT NULL,
  `client_address` varchar(200) DEFAULT NULL,
  `client_mobile` varchar(20) DEFAULT NULL,
  `client_vatno` varchar(20) DEFAULT NULL,
  `client_fax` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE clients DISABLE KEYS */;
INSERT INTO clients VALUES ('3457', 'abcd', '4545644', 'aga@sff.com', NULL, 'jkjhi', '9896866u', '587', '54854854');
INSERT INTO clients VALUES ('58787', 'Time Managemenat Group', '25546565', 'timemanagemenatgroup@gmail.com', NULL, 'Bidhan nagar', '9163196112', '854878448747', '4544548748787');
/*!40000 ALTER TABLE clients ENABLE KEYS */;

DROP TABLE IF EXISTS employees;
CREATE TABLE `employees` (
  `employee_id` varchar(11) NOT NULL,
  `employee_name` varchar(50) NOT NULL,
  `employee_phone` varchar(13) NOT NULL,
  `employee_email` varchar(50) DEFAULT NULL,
  `employee_priv_value` int(11) unsigned DEFAULT NULL,
  `employee_address` varchar(200) DEFAULT NULL,
  `employee_mobile` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE employees DISABLE KEYS */;
INSERT INTO employees VALUES ('123', 'adsfdsafg', 'sdfgsdfg', 'dfgdfgdg@gmail.com', NULL, 'sdfgsdfg', 'sdfgdsfg');
INSERT INTO employees VALUES ('emp0001', 'Sukanta', 'Hui', 'sukantahui@gmail.com', 15, NULL, NULL);
INSERT INTO employees VALUES ('emp002', 'Rajib Das', '89993003-2', 'dfsf@sfsdf.com', 12, NULL, NULL);
/*!40000 ALTER TABLE employees ENABLE KEYS */;

DROP TABLE IF EXISTS users;
CREATE TABLE `users` (
  `user_id` varchar(50) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `employee_id` varchar(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  KEY `emp_id` (`employee_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE users DISABLE KEYS */;
INSERT INTO users VALUES ('biju', '1d683d2f77ebd646f11b4afe50d6f9ca', 'emp0001', '2013-04-17 16:36:57');
INSERT INTO users VALUES ('rajib', 'ceedaf94ac9610031ab5e582eaf36aba', 'emp002', '2013-06-22 14:00:53');
/*!40000 ALTER TABLE users ENABLE KEYS */;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
