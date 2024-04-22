#!/usr/bin/env php
<?php

/**

title=测试 groupModel->copyUser();
timeout=0
cid=1

- 复制分组2用户到分组3 @1
- 复制分组0用户到分组3 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

su('admin');

zenData('user')->gen(100);
zenData('group')->gen(5);
zenData('usergroup')->loadYaml('usergroup')->gen(10);

$fromGroup = 2;
$toGroup   = 3;

$group = new groupTest();

r($group->copyUserTest($fromGroup, $toGroup)) && p() && e('1'); // 复制分组2用户到分组3
r($group->copyUserTest(0,          $toGroup)) && p() && e('1'); // 复制分组0用户到分组3