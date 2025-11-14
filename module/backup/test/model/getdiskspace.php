#!/usr/bin/env php
<?php

/**

title=测试 backupModel::getDiskSpace();
timeout=0
cid=15137

- 执行$result1, ',') !== false @1
- 执行$result2, ',') !== false @1
- 执行$result3, ',') !== false @1
- 执行$result1 @2
- 执行$parts[0]) && is_numeric($parts[1] @1
- 执行$parts[0] > 0 @1
- 执行$parts[1] >= 0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

su('admin');

$backupTest = new backupTest();

$result1 = $backupTest->getDiskSpaceTest('/tmp');
$result2 = $backupTest->getDiskSpaceTest('.');
$result3 = $backupTest->getDiskSpaceTest(dirname(__FILE__, 5));

r(strpos($result1, ',') !== false) && p() && e(1);
r(strpos($result2, ',') !== false) && p() && e(1);
r(strpos($result3, ',') !== false) && p() && e(1);
r(count(explode(',', $result1))) && p() && e(2);

$parts = explode(',', $result1);
r(is_numeric($parts[0]) && is_numeric($parts[1])) && p() && e(1);
r($parts[0] > 0) && p() && e(1);
r($parts[1] >= 0) && p() && e(1);