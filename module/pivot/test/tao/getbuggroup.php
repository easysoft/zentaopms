#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getBugGroup();
timeout=0
cid=17440

- 执行pivotTest模块的getBugGroupTest方法，参数是'2025-01-01', '2025-12-31', 0, 0  @1
- 执行pivotTest模块的getBugGroupTest方法，参数是'2025-01-01', '2025-12-31', 0, 0)['admin']  @90
- 执行pivotTest模块的getBugGroupTest方法，参数是'2025-01-01', '2025-12-31', 1, 0)['admin']  @3
- 执行pivotTest模块的getBugGroupTest方法，参数是'2025-01-01', '2025-12-31', 0, 101)['admin']  @3
- 执行getBugGroupTest('2025-01-01', '2025-12-31', 0, 0)['admin'][0]模块的openedBy方法  @admin
- 执行getBugGroupTest('2025-01-01', '2025-12-31', 0, 0)['admin'][0]模块的status方法  @active
- 执行getBugGroupTest('2025-01-01', '2025-12-31', 0, 0)['admin'][39]模块的resolution方法  @unResolved

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('bug')->gen(5);

global $tester;
$tester->dao->update(TABLE_BUG)->set('product')->eq(1)->set('execution')->eq(101)->set('openedBy')->eq('admin')->set('openedDate')->eq('2025-01-10 00:00:00')->set('status')->eq('active')->set('resolution')->eq('')->where('id')->eq(1)->exec();
$tester->dao->update(TABLE_BUG)->set('product')->eq(1)->set('execution')->eq(101)->set('openedBy')->eq('admin')->set('openedDate')->eq('2025-02-10 00:00:00')->set('status')->eq('resolved')->set('resolution')->eq('fixed')->where('id')->eq(2)->exec();
$tester->dao->update(TABLE_BUG)->set('product')->eq(2)->set('execution')->eq(102)->set('openedBy')->eq('admin')->set('openedDate')->eq('2025-03-10 00:00:00')->set('status')->eq('closed')->set('resolution')->eq('postponed')->where('id')->eq(3)->exec();
$tester->dao->update(TABLE_BUG)->set('product')->eq(3)->set('execution')->eq(102)->set('openedBy')->eq('user1')->set('openedDate')->eq('2025-04-10 00:00:00')->set('status')->eq('active')->set('resolution')->eq('')->where('id')->eq(4)->exec();
$tester->dao->update(TABLE_BUG)->set('product')->eq(1)->set('execution')->eq(101)->set('openedBy')->eq('user1')->set('openedDate')->eq('2024-12-10 00:00:00')->set('status')->eq('resolved')->set('resolution')->eq('fixed')->where('id')->eq(5)->exec();

su('admin');

$pivotTest = new pivotTaoTest();
$allGroups       = $pivotTest->getBugGroupTest('2025-01-01', '2025-12-31', 0, 0);
$adminGroups     = $allGroups['admin'] ?? array();
$product1Groups  = $pivotTest->getBugGroupTest('2025-01-01', '2025-12-31', 1, 0);
$executionGroups = $pivotTest->getBugGroupTest('2025-01-01', '2025-12-31', 0, 101);

r(count($allGroups)) && p() && e('2');
r(count($adminGroups)) && p() && e('3');
r(count($product1Groups['admin'] ?? array())) && p() && e('2');
r(count($executionGroups['admin'] ?? array())) && p() && e('2');
r(count(array_unique(array_column($adminGroups, 'openedBy')))) && p() && e('1');
r(in_array('active', array_column($adminGroups, 'status')) ? 1 : 0) && p() && e('1');
r(in_array('unResolved', array_column($adminGroups, 'resolution')) ? 1 : 0) && p() && e('1');
