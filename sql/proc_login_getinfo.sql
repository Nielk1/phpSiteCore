DELIMITER $$
CREATE DEFINER=`bzcomple`@`localhost` PROCEDURE `login_getinfo`(
	IN email VARCHAR(50),-- CHARACTER SET utf8 collate utf8_general_ci,
	OUT id INT(11),
	OUT username VARCHAR(30),
	-- OUT locked BIT(1),
	OUT pass VARCHAR(255)
)
BEGIN
	SELECT m.id, m.username, mab.password
	INTO id, username,  pass
	FROM `members` m
	INNER JOIN `members_auth_basic` mab ON (mab.memberId = m.id)
	WHERE m.email = email
	LIMIT 1;

	-- implement lock checks
	-- SET locked = 0;
END$$
DELIMITER ;
