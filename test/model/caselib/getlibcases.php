#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->getLibCases();
cid=1
pid=1

所有状态下，用例库下用例数量 >> 10
搜索状态下，用例库下用例数量 >> 10
分页状态下，用例库下用例数量 >> 5

*/
global $tester;
$tester->app->loadClass('pager', $static = true);

$pager   = new pager(0, 5, 1);
$caselib = new caselibTest();

$list1 = $caselib->getLibCasesTest(201, 'all');
$list2 = $caselib->getLibCasesTest(201, 'bysearch');
$list3 = $caselib->getLibCasesTest(201, 'all', '0', '0', 'id_desc', $pager);

r(count($list1)) && p() && e('10'); //所有状态下，用例库下用例数量
r(count($list2)) && p() && e('10'); //搜索状态下，用例库下用例数量
r(count($list3)) && p() && e('5');  //分页状态下，用例库下用例数量