#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->createCaseLink();
cid=1
pid=1

测试创建module 1821 的caselink >> title='产品模块1'
测试创建module 1822 的caselink >> title='产品模块2'
测试创建module 1983 branch 0 的caselink >> title='产品模块162'
测试创建module 1983 branch 1 的caselink >> title='产品模块162'
测试创建module 1983 branch 2 的caselink >> title='产品模块162'
测试创建module 1983 branch 0 的caselink >> title='产品模块163'
测试创建module 1983 branch 1 的caselink >> title='产品模块163'
测试创建module 1983 branch 2 的caselink >> title='产品模块163'
测试创建module 1621 的caselink >> title='模块1601'
测试创建module 1622 的caselink >> title='模块1602'
测试创建module 21 的caselink >> title='模块1'
测试创建module 22 的caselink >> title='模块2'

*/

$moduleID = array(1821, 1822, 1982, 1983, 1621, 1622, 21, 22);
$extra    = array(0, 1, 2);

$tree = new treeTest();

r($tree->createCaseLinkTest($moduleID[0]))                                 && p() && e("title='产品模块1'");   // 测试创建module 1821 的caselink
r($tree->createCaseLinkTest($moduleID[1]))                                 && p() && e("title='产品模块2'");   // 测试创建module 1822 的caselink
r($tree->createCaseLinkTest($moduleID[2], array('branchID' => $extra[0]))) && p() && e("title='产品模块162'"); // 测试创建module 1983 branch 0 的caselink
r($tree->createCaseLinkTest($moduleID[2], array('branchID' => $extra[1]))) && p() && e("title='产品模块162'"); // 测试创建module 1983 branch 1 的caselink
r($tree->createCaseLinkTest($moduleID[2], array('branchID' => $extra[2]))) && p() && e("title='产品模块162'"); // 测试创建module 1983 branch 2 的caselink
r($tree->createCaseLinkTest($moduleID[3], array('branchID' => $extra[0]))) && p() && e("title='产品模块163'"); // 测试创建module 1983 branch 0 的caselink
r($tree->createCaseLinkTest($moduleID[3], array('branchID' => $extra[1]))) && p() && e("title='产品模块163'"); // 测试创建module 1983 branch 1 的caselink
r($tree->createCaseLinkTest($moduleID[3], array('branchID' => $extra[2]))) && p() && e("title='产品模块163'"); // 测试创建module 1983 branch 2 的caselink
r($tree->createCaseLinkTest($moduleID[4]))                                 && p() && e("title='模块1601'");    // 测试创建module 1621 的caselink
r($tree->createCaseLinkTest($moduleID[5]))                                 && p() && e("title='模块1602'");    // 测试创建module 1622 的caselink
r($tree->createCaseLinkTest($moduleID[6]))                                 && p() && e("title='模块1'");       // 测试创建module 21 的caselink
r($tree->createCaseLinkTest($moduleID[7]))                                 && p() && e("title='模块2'");       // 测试创建module 22 的caselink