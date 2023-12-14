#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->gitById();
timeout=0
cid=1

- 使用存在的ID属性id @1
- 使用空的ID @0
- 使用不存在的ID @0

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$gitlabID = 1;
r($gitlab->getById($gitlabID)) && p('id') && e('1');    // 使用存在的ID

$gitlabID = 0;
r($gitlab->getById($gitlabID)) && p() && e(0);     // 使用空的ID

$gitlabID = 111;
r($gitlab->getById($gitlabID)) && p() && e(0);     // 使用不存在的ID