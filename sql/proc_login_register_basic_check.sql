DELIMITER $$
CREATE DEFINER=`bzcomple`@`localhost` PROCEDURE `login_register_basic_check`(
	IN token BINARY(16),
	OUT email VARCHAR(50)-- CHARACTER SET utf8 collate utf8_general_ci,
)
BEGIN
	SELECT mrb.email
	INTO email
	FROM `members_register_basic` mrb
	WHERE mrb.token = token
	LIMIT 1;
END$$
DELIMITER ;
