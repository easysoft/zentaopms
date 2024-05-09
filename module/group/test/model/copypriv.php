#!/usr/bin/env php
<?php

/**

title=测试 groupModel->copyPriv();
timeout=0
cid=1

- 复制分组2权限到分组3 @1
- 复制分组0权限到分组3 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

su('admin');

zenData('group')->gen(5);
zenData('grouppriv')->loadYaml('grouppriv')->gen(10);

$fromGroup = 2;
$toGroup   = 3;

$group = new groupTest();
r($group->copyPrivTest($fromGroup, $toGroup)) && p() && e('1'); // 复制分组2权限到分组3
r($group->copyPrivTest(0,          $toGroup)) && p() && e('1'); // 复制分组0权限到分组3