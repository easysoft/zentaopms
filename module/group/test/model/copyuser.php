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
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('user')->gen(100);
zdTable('group')->gen(5);
zdTable('usergroup')->config('usergroup')->gen(10);

$fromGroup = 2;
$toGroup   = 3;

$group = new groupTest();

r($group->copyUserTest($fromGroup, $toGroup)) && p() && e('1'); // 复制分组2用户到分组3
r($group->copyUserTest(0,          $toGroup)) && p() && e('1'); // 复制分组0用户到分组3