#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getRiskCardMenu();
timeout=0
cid=16984

- 执行kanbanTest模块的getRiskCardMenuTest方法，参数是$emptyRisks  @0
- 执行$activeMenus[$activeRisk->id] @5
- 执行$hangupMenus[$hangupRisk->id] @4
- 执行$canceledMenus[$canceledRisk->id] @2
- 执行$closedMenus[$closedRisk->id] @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

zenData('user')->gen(3);
zenData('company')->gen(1);
zenData('risk')->gen(10);

$kanbanTest = new kanbanTest();

$emptyRisks = array();

$activeRisk = new stdClass();
$activeRisk->id = 1;
$activeRisk->status = 'active';

$hangupRisk = new stdClass();
$hangupRisk->id = 2;
$hangupRisk->status = 'hangup';

$canceledRisk = new stdClass();
$canceledRisk->id = 3;
$canceledRisk->status = 'canceled';

$closedRisk = new stdClass();
$closedRisk->id = 4;
$closedRisk->status = 'closed';

// 测试步骤：检测返回的菜单数量是否正确
r(count($kanbanTest->getRiskCardMenuTest($emptyRisks))) && p() && e('0');
$activeMenus = $kanbanTest->getRiskCardMenuTest(array($activeRisk));
r(count($activeMenus[$activeRisk->id])) && p() && e('5');
$hangupMenus = $kanbanTest->getRiskCardMenuTest(array($hangupRisk));
r(count($hangupMenus[$hangupRisk->id])) && p() && e('4');
$canceledMenus = $kanbanTest->getRiskCardMenuTest(array($canceledRisk));
r(count($canceledMenus[$canceledRisk->id])) && p() && e('2');
$closedMenus = $kanbanTest->getRiskCardMenuTest(array($closedRisk));
r(count($closedMenus[$closedRisk->id])) && p() && e('2');