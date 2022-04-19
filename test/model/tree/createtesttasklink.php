#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->createTestTaskLink();
cid=1
pid=1

测试创建module 1821 的TestTasklink >> title='产品模块1'
测试创建module 1822 的TestTasklink >> title='产品模块2'
测试创建module 1981 的TestTasklink >> title='产品模块161'
测试创建module 1982 的TestTasklink >> title='产品模块162'
测试创建module 1621 的TestTasklink >> title='模块1601'
测试创建module 1622 的TestTasklink >> title='模块1602'
测试创建module 21 的TestTasklink >> title='模块1'
测试创建module 22 的TestTasklink >> title='模块2'

*/
$moduleID    = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);
$extra       = 1;

$tree = new treeTest();

r($tree->createTestTaskLinkTest($moduleID[0], $extra)) && p() && e("title='产品模块1'");   // 测试创建module 1821 的TestTasklink
r($tree->createTestTaskLinkTest($moduleID[1], $extra)) && p() && e("title='产品模块2'");   // 测试创建module 1822 的TestTasklink
r($tree->createTestTaskLinkTest($moduleID[2], $extra)) && p() && e("title='产品模块161'"); // 测试创建module 1981 的TestTasklink
r($tree->createTestTaskLinkTest($moduleID[3], $extra)) && p() && e("title='产品模块162'"); // 测试创建module 1982 的TestTasklink
r($tree->createTestTaskLinkTest($moduleID[4], $extra)) && p() && e("title='模块1601'");    // 测试创建module 1621 的TestTasklink
r($tree->createTestTaskLinkTest($moduleID[5], $extra)) && p() && e("title='模块1602'");    // 测试创建module 1622 的TestTasklink
r($tree->createTestTaskLinkTest($moduleID[6], $extra)) && p() && e("title='模块1'");       // 测试创建module 21 的TestTasklink
r($tree->createTestTaskLinkTest($moduleID[7], $extra)) && p() && e("title='模块2'");       // 测试创建module 22 的TestTasklink