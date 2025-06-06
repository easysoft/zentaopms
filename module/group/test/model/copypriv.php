#!/usr/bin/env php
<?php

/**

title=测试 groupModel->copyPriv();
timeout=0
cid=1

- 复制分组1权限到分组5 @1
- 复制分组2权限到分组5 @1
- 复制分组3权限到分组5 @1
- 复制分组4权限到分组5 @1
- 复制分组0权限到分组5 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

su('admin');

zenData('group')->gen(5);
zenData('grouppriv')->loadYaml('grouppriv')->gen(10);

$fromGroup = array(1, 2, 3, 4);
$toGroup   = 5;

$group = new groupTest();
r($group->copyPrivTest($fromGroup[0], $toGroup)) && p() && e('1'); // 复制分组1权限到分组5
r($group->copyPrivTest($fromGroup[1], $toGroup)) && p() && e('1'); // 复制分组2权限到分组5
r($group->copyPrivTest($fromGroup[2], $toGroup)) && p() && e('1'); // 复制分组3权限到分组5
r($group->copyPrivTest($fromGroup[3], $toGroup)) && p() && e('1'); // 复制分组4权限到分组5
r($group->copyPrivTest(0,             $toGroup)) && p() && e('1'); // 复制分组0权限到分组5