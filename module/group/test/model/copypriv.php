#!/usr/bin/env php
<?php

/**

title=测试 groupModel->copyPriv();
timeout=0
cid=16697

- 复制分组1权限到分组1 @1
- 复制分组1权限到分组2 @1
- 复制分组2权限到分组3 @1
- 复制分组3权限到分组3 @1
- 复制分组0权限到分组3 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

zenData('group')->gen(5);
zenData('grouppriv')->loadYaml('grouppriv')->gen(10);

$group = new groupModelTest();
r($group->copyPrivTest(1, 1)) && p() && e('1'); // 复制分组1权限到分组1
r($group->copyPrivTest(1, 2)) && p() && e('1'); // 复制分组1权限到分组2
r($group->copyPrivTest(2, 3)) && p() && e('1'); // 复制分组2权限到分组3
r($group->copyPrivTest(3, 3)) && p() && e('1'); // 复制分组3权限到分组3
r($group->copyPrivTest(0, 3)) && p() && e('1'); // 复制分组0权限到分组3
