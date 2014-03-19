CREATE TABLE `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30 )UNIQUE NOT NULL,
  `email` varchar(50) UNIQUE NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE `members_auth_basic` (
  `memberId` int(11) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`memberId`),
  CONSTRAINT `fk__member_auth_basic__members__memberId` FOREIGN KEY (`memberId`) REFERENCES `members` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `members` (`username`,`email`)
VALUES('admin','root@admin');

INSERT INTO `members_auth_basic` (`memberId`,`password`)
VALUES(LAST_INSERT_ID(),'$2y$10$Cc0yO1uafMSg3MwZ.oGa5OJLvebwnTEocXxprVaOLvvhgVHWqAHMK'); -- "admin"

CREATE TABLE `members_register_basic` (
  `email` VARCHAR(50) UNIQUE NOT NULL,
  `token` BINARY(16) UNIQUE NOT NULL,
  `created` DATETIME NOT NULL,
  `lastmailed` DATETIME NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;








CREATE TABLE `permission_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE `member_permissions` (
  `memberId` int(11) NOT NULL,
  `permissionId` int(11) NOT NULL,
  PRIMARY KEY (`memberId`),
  KEY `fk__member_permissions__permission_types__permissionId__idx` (`permissionId`),
  CONSTRAINT `fk__member_permissions__members__memberId` FOREIGN KEY (`memberId`) REFERENCES `members` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk__member_permissions__permission_types__permissionId` FOREIGN KEY (`permissionId`) REFERENCES `permission_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `login_attempts` (
  `user_id` int(11) NOT NULL,
  `time` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;