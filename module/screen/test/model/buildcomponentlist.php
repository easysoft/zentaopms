#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildComponentList();
timeout=0
cid=18208

- 执行screen模块的buildComponentListTest方法，参数是array  @2
- 执行screen模块的buildComponentListTest方法，参数是array  @0
- 执行screen模块的buildComponentListTest方法，参数是array  @2
- 执行screen模块的buildComponentListTest方法，参数是array  @0
- 执行screen模块的buildComponentListTest方法，参数是array  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$screen = new screenModelTest();

// 准备测试数据
$validComponent1 = new stdclass();
$validComponent1->id = 'comp1';
$validComponent1->type = 'text';

$validComponent2 = new stdclass();
$validComponent2->id = 'comp2';
$validComponent2->type = 'text';

$groupComponent = new stdclass();
$groupComponent->id = 'group1';
$groupComponent->isGroup = 1;
$groupComponent->groupList = array($validComponent1);
$groupComponent->type = 'group';

r(count($screen->buildComponentListTest(array($validComponent1, $validComponent2)))) && p() && e(2);
r(count($screen->buildComponentListTest(array()))) && p() && e(0);
r(count($screen->buildComponentListTest(array($validComponent1, null, $validComponent2)))) && p() && e(2);
r(count($screen->buildComponentListTest(array(null, false, '', 0)))) && p() && e(0);
r(count($screen->buildComponentListTest(array($validComponent1, $groupComponent, $validComponent2)))) && p() && e(3);
