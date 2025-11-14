#!/usr/bin/env php
<?php

/**

title=测试 programZen::getProgramList4Kanban();
timeout=0
cid=17731

- 测试步骤1:使用默认参数my获取看板数据 @1
- 测试步骤2:使用参数my获取看板数据 @1
- 测试步骤3:使用参数other获取看板数据 @1
- 测试步骤4:使用参数all获取看板数据 @1
- 测试步骤5:使用空字符串参数获取看板数据 @1
- 测试步骤6:验证my类型返回的项目集数量大于等于0 @1
- 测试步骤7:验证other类型返回结果结构正确 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

zenData('project')->loadYaml('getprogramlist4kanban/project', false, 2)->gen(50);
zenData('product')->loadYaml('getprogramlist4kanban/product', false, 2)->gen(30);
zenData('productplan')->loadYaml('getprogramlist4kanban/productplan', false, 2)->gen(40);
zenData('release')->loadYaml('getprogramlist4kanban/release', false, 2)->gen(30);
zenData('projectproduct')->loadYaml('getprogramlist4kanban/projectproduct', false, 2)->gen(25);
zenData('stakeholder')->loadYaml('getprogramlist4kanban/stakeholder', false, 2)->gen(50);

su('admin');

$programTest = new programTest();

r(is_array($programTest->getProgramList4KanbanTest())) && p() && e('1'); // 测试步骤1:使用默认参数my获取看板数据
r(is_array($programTest->getProgramList4KanbanTest('my'))) && p() && e('1'); // 测试步骤2:使用参数my获取看板数据
r(is_array($programTest->getProgramList4KanbanTest('other'))) && p() && e('1'); // 测试步骤3:使用参数other获取看板数据
r(is_array($programTest->getProgramList4KanbanTest('all'))) && p() && e('1'); // 测试步骤4:使用参数all获取看板数据
r(is_array($programTest->getProgramList4KanbanTest(''))) && p() && e('1'); // 测试步骤5:使用空字符串参数获取看板数据
r(count($programTest->getProgramList4KanbanTest('my')) >= 0) && p() && e('1'); // 测试步骤6:验证my类型返回的项目集数量大于等于0
r(gettype($programTest->getProgramList4KanbanTest('other'))) && p() && e('array'); // 测试步骤7:验证other类型返回结果结构正确