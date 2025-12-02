#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getControlOptions();
timeout=0
cid=17085

- 测试product选项：获取产品对象列表 @5
- 测试sprint选项：获取执行对象列表 @0
- 测试user选项：获取用户对象列表 @16
- 测试语言包选项bug.pri：获取优先级选项列表 @4
- 测试语言包选项task.type：获取任务类型选项列表 @9
- 测试不存在选项：返回默认空数组 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';
su('admin');

$metric = new metricTest();

zenData('user')->loadYaml('user', true)->gen(30);
zenData('product')->loadYaml('product', true)->gen(10);

r(count($metric->getControlOptions('product')))      && p('') && e('5');   // 测试product选项：获取产品对象列表
r(count($metric->getControlOptions('sprint')))       && p('') && e('0');   // 测试sprint选项：获取执行对象列表
r(count($metric->getControlOptions('user')))         && p('') && e('16');  // 测试user选项：获取用户对象列表
r(count($metric->getControlOptions('bug.pri')))      && p('') && e('4');   // 测试语言包选项bug.pri：获取优先级选项列表
r(count($metric->getControlOptions('task.type')))    && p('') && e('9');   // 测试语言包选项task.type：获取任务类型选项列表
r(count($metric->getControlOptions('invalidtype')))  && p('') && e('1');   // 测试不存在选项：返回默认空数组