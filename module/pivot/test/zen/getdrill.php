#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getDrill();
timeout=0
cid=0

- 执行pivotTest模块的getDrillTest方法，参数是1, '1', 'name', 'published' 属性field @name
- 执行pivotTest模块的getDrillTest方法，参数是1, '1', 'status', 'design' 属性field @status
- 执行pivotTest模块的getDrillTest方法，参数是999, '1', 'name', 'published'  @{}
- 执行pivotTest模块的getDrillTest方法，参数是1, '1', 'nonexistent', 'published'  @{}
- 执行pivotTest模块的getDrillTest方法，参数是1, 'invalid', 'name', 'published'  @{}

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

global $tester;
$tester->dbh->exec("DELETE FROM zt_pivotdrill");
$tester->dbh->exec("
INSERT INTO zt_pivotdrill (`pivot`, `version`, `field`, `object`, `whereSql`, `condition`, `status`, `account`, `type`)
VALUES 
(1, '1', 'name', 'bug', 'status = \"active\"', '{\"field\":\"status\",\"operator\":\"=\",\"value\":\"active\"}', 'published', 'admin', 'manual'),
(1, '1', 'status', 'bug', 'deleted = \"0\"', '{\"field\":\"deleted\",\"operator\":\"=\",\"value\":\"0\"}', 'published', 'admin', 'auto'),
(2, '1', 'category', 'story', 'type = \"story\"', '{\"field\":\"type\",\"operator\":\"=\",\"value\":\"story\"}', 'published', 'user1', 'manual'),
(2, '2', 'name', 'story', 'name IS NOT NULL', '{\"field\":\"name\",\"operator\":\"!=\",\"value\":\"\"}', 'published', 'admin', 'auto'),
(3, '1', 'priority', 'task', 'priority > 0', '{\"field\":\"priority\",\"operator\":\">\",\"value\":\"0\"}', 'design', 'user1', 'manual')
");

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->getDrillTest(1, '1', 'name', 'published')) && p('field') && e('name');
r($pivotTest->getDrillTest(1, '1', 'status', 'design')) && p('field') && e('status');
r($pivotTest->getDrillTest(999, '1', 'name', 'published')) && p() && e('{}');
r($pivotTest->getDrillTest(1, '1', 'nonexistent', 'published')) && p() && e('{}');
r($pivotTest->getDrillTest(1, 'invalid', 'name', 'published')) && p() && e('{}');