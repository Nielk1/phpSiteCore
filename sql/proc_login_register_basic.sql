DELIMITER $$
CREATE DEFINER=`bzcomple`@`localhost` PROCEDURE `login_register_basic`(
	IN email VARCHAR(50),-- CHARACTER SET utf8 collate utf8_general_ci,
	OUT token BINARY(16),
	OUT sendMail BIT
)
BEGIN
	DECLARE tokenraw BINARY(16);
	DECLARE created DATETIME;
	DECLARE lastmailed DATETIME;

	SET sendMail = 0;

	IF NOT EXISTS(SELECT email FROM `members` m WHERE m.email = email LIMIT 1) THEN
		IF NOT EXISTS(SELECT mrb.email from `members_register_basic` mrb WHERE mrb.email = email LIMIT 1) THEN
			SET tokenraw = UNHEX(REPLACE(UUID(),'-',''));
			INSERT INTO `members_register_basic`(`email`,`token`,`created`,`lastmailed`)
			VALUES(email,tokenraw,NOW(),NOW());
			SET token = tokenraw;
			SET sendMail = 1;
		ELSE
			SELECT mrb.token, mrb.created, mrb.lastmailed
			INTO token, created, lastmailed
			FROM `members_register_basic` mrb
			WHERE mrb.email = email
			LIMIT 1;
			IF (lastmailed + INTERVAL 5 HOUR) < NOW() THEN
				UPDATE `members` m SET lastmailed = NOW() WHERE m.email = email;
				SET sendMail = 1;
			END IF;
		END IF;
	END IF;

	-- implement lock checks
	-- SET locked = 0;
END$$
DELIMITER ;
