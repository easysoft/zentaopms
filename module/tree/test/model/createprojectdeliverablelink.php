#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createDeliverableLink();
timeout=0
cid=1

- 测试获取module 1的链接属性url @createprojectdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=1&param=1
- 测试获取module 2的链接属性url @createprojectdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=2&param=2
- 测试获取module 3的链接属性url @createprojectdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=3&param=3
- 测试获取module 4的链接属性url @createprojectdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=4&param=4
- 测试获取module 5的链接属性url @createprojectdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=5&param=5

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
r($tester->tree->createDeliverableLink('deliverable', $module, '0', '1')) && p('url') && e("createprojectdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=1&param=1"); // 测试获取module 1的链接
$module = $tester->tree->fetchByID(2, 'module');
r($tester->tree->createDeliverableLink('deliverable', $module, '0', '2')) && p('url') && e("createprojectdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=2&param=2"); // 测试获取module 2的链接
$module = $tester->tree->fetchByID(3, 'module');
r($tester->tree->createDeliverableLink('deliverable', $module, '0', '3')) && p('url') && e("createprojectdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=3&param=3"); // 测试获取module 3的链接
$module = $tester->tree->fetchByID(4, 'module');
r($tester->tree->createDeliverableLink('deliverable', $module, '0', '4')) && p('url') && e("createprojectdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=4&param=4"); // 测试获取module 4的链接
$module = $tester->tree->fetchByID(5, 'module');
r($tester->tree->createDeliverableLink('deliverable', $module, '0', '5')) && p('url') && e("createprojectdeliverablelink.php?m=deliverable&f=browse&groupID=1&browseType=5&param=5"); // 测试获取module 5的链接