DELIMITER $$
CREATE PROCEDURE `login_gethash`(
	IN id INT(11),
	OUT hash VARCHAR(255)
)
BEGIN
	SELECT mab.password
	INTO hash
	FROM `members_auth_basic` mab
	WHERE mab.memberId = id
	LIMIT 1;
END$$
DELIMITER ;