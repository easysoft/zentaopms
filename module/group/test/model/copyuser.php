#!/usr/bin/env php
<?php

/**

title=测试 groupModel->copyUser();
timeout=0
cid=16698

- 复制分组1用户到分组2 @1
- 复制分组2用户到分组3 @1
- 复制分组3用户到分组4 @1
- 复制分组4用户到分组5 @1
- 复制分组0用户到分组5 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

su('admin');

zenData('user')->gen(100);
zenData('group')->gen(5);
zenData('usergroup')->loadYaml('usergroup')->gen(10);

$group = new groupTest();

r($group->copyUserTest(1, 2)) && p() && e('1'); // 复制分组1用户到分组2
r($group->copyUserTest(2, 3)) && p() && e('1'); // 复制分组2用户到分组3
r($group->copyUserTest(3, 4)) && p() && e('1'); // 复制分组3用户到分组4
r($group->copyUserTest(4, 5)) && p() && e('1'); // 复制分组4用户到分组5
r($group->copyUserTest(0, 5)) && p() && e('1'); // 复制分组0用户到分组5
