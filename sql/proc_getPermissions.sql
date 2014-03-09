DELIMITER $$
CREATE PROCEDURE `getPermissions`(
	id INT(11)
)
BEGIN
	SELECT pt.name
	FROM member_permissions mp
	INNER JOIN permission_types pt ON (mp.permissionId = pt.id)
	WHERE mp.memberId = id;
END$$
DELIMITER ;
