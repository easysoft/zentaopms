#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->createBugLink();
cid=1
pid=1

测试创建module 1821 的buglink >> title='产品模块1'
测试创建module 1822 的buglink >> title='产品模块2'
测试创建module 1981 的buglink >> title='产品模块161'
测试创建module 1982 的buglink >> title='产品模块162'
测试创建module 1621 的buglink >> title='模块1601'
测试创建module 1622 的buglink >> title='模块1602'
测试创建module 21 的buglink >> title='模块1'
测试创建module 22 的buglink >> title='模块2'

*/

$moduleID = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);

$tree = new treeTest();

r($tree->createBugLinkTest($moduleID[0])) && p() && e("title='产品模块1'");   // 测试创建module 1821 的buglink
r($tree->createBugLinkTest($moduleID[1])) && p() && e("title='产品模块2'");   // 测试创建module 1822 的buglink
r($tree->createBugLinkTest($moduleID[2])) && p() && e("title='产品模块161'"); // 测试创建module 1981 的buglink
r($tree->createBugLinkTest($moduleID[3])) && p() && e("title='产品模块162'"); // 测试创建module 1982 的buglink
r($tree->createBugLinkTest($moduleID[4])) && p() && e("title='模块1601'");    // 测试创建module 1621 的buglink
r($tree->createBugLinkTest($moduleID[5])) && p() && e("title='模块1602'");    // 测试创建module 1622 的buglink
r($tree->createBugLinkTest($moduleID[6])) && p() && e("title='模块1'");       // 测试创建module 21 的buglink
r($tree->createBugLinkTest($moduleID[7])) && p() && e("title='模块2'");       // 测试创建module 22 的buglink