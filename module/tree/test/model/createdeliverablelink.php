#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createDeliverableLink();
timeout=0
cid=1

- 测试获取module 1的链接属性url @createdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=bymodule&param=1
- 测试获取module 2的链接属性url @createdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=bymodule&param=2
- 测试获取module 3的链接属性url @createdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=bymodule&param=3
- 测试获取module 4的链接属性url @createdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=bymodule&param=4
- 测试获取module 5的链接属性url @createdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=bymodule&param=5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$module = zenData('module');
$module->type->range('deliverable');
$module->root->range('1');
$module->gen(20);

global $tester;
$tester->loadModel('tree');
$module = $tester->tree->fetchByID(1, 'module');
r($tester->tree->createDeliverableLink('deliverable', $module, '0', 'bymodule')) && p('url') && e("createdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=bymodule&param=1"); // 测试获取module 1的链接
$module = $tester->tree->fetchByID(2, 'module');
r($tester->tree->createDeliverableLink('deliverable', $module, '0', 'bymodule')) && p('url') && e("createdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=bymodule&param=2"); // 测试获取module 2的链接
$module = $tester->tree->fetchByID(3, 'module');
r($tester->tree->createDeliverableLink('deliverable', $module, '0', 'bymodule')) && p('url') && e("createdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=bymodule&param=3"); // 测试获取module 3的链接
$module = $tester->tree->fetchByID(4, 'module');
r($tester->tree->createDeliverableLink('deliverable', $module, '0', 'bymodule')) && p('url') && e("createdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=bymodule&param=4"); // 测试获取module 4的链接
$module = $tester->tree->fetchByID(5, 'module');
r($tester->tree->createDeliverableLink('deliverable', $module, '0', 'bymodule')) && p('url') && e("createdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=bymodule&param=5"); // 测试获取module 5的链接
