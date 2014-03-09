DELIMITER $$
CREATE PROCEDURE `login`(
	IN email VARCHAR(50),-- CHARACTER SET utf8 collate utf8_general_ci,
	IN password CHAR(128),-- CHARACTER SET utf8 collate utf8_general_ci
	OUT id INT(11),
	OUT username VARCHAR(30),
	OUT success BIT(1),
	OUT locked BIT(1),
	OUT pass CHAR(128)
)
BEGIN
	DECLARE inputPass CHAR(128);
	-- DECLARE pass CHAR(128);

	SELECT m.id, m.username, SHA2(CONCAT(password,m.salt),512), m.password
	INTO id, username, inputPass, pass
	FROM `members` m
		INNER JOIN `member_auth_basic` mab ON (mab.mmemberId = m.id)
	WHERE m.email = email
	  -- AND BINARY m.password = BINARY SHA2(CONCAT(password,m.salt),512)
	LIMIT 1;

	IF BINARY inputPass = BINARY pass THEN
		SET success = 1;
		SET locked = 0;
	ELSE
		SET success = 0;
		SET locked = 0;
	END IF;
END$$
DELIMITER ;
