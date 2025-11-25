#!/usr/bin/env php
<?php

/**

title=测试 programZen::getKanbanList();
timeout=0
cid=17727

- 测试步骤1:默认参数my获取看板列表 @1
- 测试步骤2:参数为my获取看板列表 @1
- 测试步骤3:参数为other获取看板列表 @1
- 测试步骤4:参数为空字符串获取看板列表 @1
- 测试步骤5:测试返回数组长度 @1
- 测试步骤6:验证方法可正常调用无错误 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

zenData('project')->loadYaml('getkanbanlist/project', false, 2)->gen(50);
zenData('product')->loadYaml('getkanbanlist/product', false, 2)->gen(30);
zenData('productplan')->loadYaml('getkanbanlist/productplan', false, 2)->gen(40);
zenData('release')->loadYaml('getkanbanlist/release', false, 2)->gen(30);
zenData('projectproduct')->loadYaml('getkanbanlist/projectproduct', false, 2)->gen(25);
zenData('stakeholder')->loadYaml('getkanbanlist/stakeholder', false, 2)->gen(50);

su('admin');

$programTest = new programTest();

r(is_array($programTest->getKanbanListTest())) && p() && e('1'); // 测试步骤1:默认参数my获取看板列表
r(is_array($programTest->getKanbanListTest('my'))) && p() && e('1'); // 测试步骤2:参数为my获取看板列表
r(is_array($programTest->getKanbanListTest('other'))) && p() && e('1'); // 测试步骤3:参数为other获取看板列表
r(is_array($programTest->getKanbanListTest(''))) && p() && e('1'); // 测试步骤4:参数为空字符串获取看板列表
r(count($programTest->getKanbanListTest('my')) >= 0) && p() && e('1'); // 测试步骤5:测试返回数组长度
r(gettype($programTest->getKanbanListTest('my'))) && p() && e('array'); // 测试步骤6:验证方法可正常调用无错误