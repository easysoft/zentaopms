UPDATE `zt_chart` SET `sql` = 'SELECT IF(t3.id IS NOT NULL, t3.`name`, \"空\") AS deptName,count(1) as count, \r\nIF(t3.id IS NOT NULL, t3.`order`, 9999) AS deptOrder \r\nFROM zt_user AS t1 \r\nLEFT JOIN zt_dept AS t2 ON t1.dept = t2.id\r\nLEFT JOIN zt_dept AS t3 ON FIND_IN_SET(TRIM(\',\' FROM t3.path), TRIM(\',\' FROM t2.path)) AND t3.grade = \'1\'\r\nWHERE t1.deleted = \'0\'\r\nGROUP BY deptName, deptOrder \r\nORDER BY deptOrder  ASC'
WHERE `id` = 1049;

ALTER TABLE `zt_privrelation` MODIFY `priv` VARCHAR(100) NOT NULL;
ALTER TABLE `zt_privrelation` MODIFY `relationPriv` VARCHAR(100) NOT NULL;
