DELIMITER $$
CREATE PROCEDURE `login_rehash`(
	IN id INT(11),
	-- IN pass VARCHAR(255),
	IN hash VARCHAR(255)
)
BEGIN
	UPDATE `members_auth_basic`
	SET password = hash
	WHERE memberID = id;-- AND password = pass;
END$$
DELIMITER ;
