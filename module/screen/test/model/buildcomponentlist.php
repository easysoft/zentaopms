#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildComponentList();
timeout=0
cid=0

- 执行screen模块的buildComponentListTest方法，参数是array  @2
- 执行screen模块的buildComponentListTest方法，参数是array  @0
- 执行screen模块的buildComponentListTest方法，参数是array  @2
- 执行screen模块的buildComponentListTest方法，参数是array  @0
- 执行screen模块的buildComponentListTest方法，参数是array  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

$screen = new screenTest();

// 准备测试数据
$validComponent1 = new stdclass();
$validComponent1->id = 'comp1';
$validComponent1->chartConfig = new stdclass();
$validComponent1->chartConfig->chartKey = 'bar';

$validComponent2 = new stdclass();
$validComponent2->id = 'comp2';
$validComponent2->chartConfig = new stdclass();
$validComponent2->chartConfig->chartKey = 'line';

$groupComponent = new stdclass();
$groupComponent->id = 'group1';
$groupComponent->isGroup = 1;
$groupComponent->groupList = array($validComponent1);
$groupComponent->chartConfig = new stdclass();
$groupComponent->chartConfig->chartKey = 'group';

r(count($screen->buildComponentListTest(array($validComponent1, $validComponent2)))) && p() && e(2);
r(count($screen->buildComponentListTest(array()))) && p() && e(0);
r(count($screen->buildComponentListTest(array($validComponent1, null, $validComponent2)))) && p() && e(2);
r(count($screen->buildComponentListTest(array(null, false, '', 0)))) && p() && e(0);
r(count($screen->buildComponentListTest(array($validComponent1, $groupComponent, $validComponent2)))) && p() && e(3);