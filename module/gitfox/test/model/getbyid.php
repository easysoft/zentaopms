#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitfox.class.php';
su('admin');

/**

title=测试gitfoxModel->gitById();
timeout=0
cid=1

- 使用存在的ID属性id @1
- 使用空的ID @0
- 使用不存在的ID @0

*/

zdTable('pipeline')->gen(7);

$gitfox = new gitfoxTest();

$gitfoxID = 1;
r($gitfox->getById($gitfoxID)) && p('id') && e('7');    // 使用存在的ID

$gitfoxID = 0;
r($gitfox->getById($gitfoxID)) && p() && e(0);     // 使用空的ID

$gitfoxID = 111;
r($gitfox->getById($gitfoxID)) && p() && e(0);     // 使用不存在的ID
