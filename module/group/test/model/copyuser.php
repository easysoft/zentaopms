#!/usr/bin/env php
<?php

/**

title=测试 groupModel->copyUser();
timeout=0
cid=1

- 复制分组1用户到分组5 @1
- 复制分组2用户到分组5 @1
- 复制分组3用户到分组5 @1
- 复制分组4用户到分组5 @1
- 复制分组0用户到分组5 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

su('admin');

zenData('user')->gen(100);
zenData('group')->gen(5);
zenData('usergroup')->loadYaml('usergroup')->gen(10);

$fromGroup = array(1, 2, 3, 4);
$toGroup   = 5;

$group = new groupTest();

r($group->copyUserTest($fromGroup[0], $toGroup)) && p() && e('1'); // 复制分组1用户到分组5
r($group->copyUserTest($fromGroup[1], $toGroup)) && p() && e('1'); // 复制分组2用户到分组5
r($group->copyUserTest($fromGroup[2], $toGroup)) && p() && e('1'); // 复制分组3用户到分组5
r($group->copyUserTest($fromGroup[3], $toGroup)) && p() && e('1'); // 复制分组4用户到分组5
r($group->copyUserTest(0,             $toGroup)) && p() && e('1'); // 复制分组0用户到分组5