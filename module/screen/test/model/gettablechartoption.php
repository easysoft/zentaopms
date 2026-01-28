#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getTableChartOption();
timeout=0
cid=18257

- 测试有SQL的图表情况，检查styles属性属性styles @default-styles
- 测试有SQL的图表情况，检查status属性属性status @default-status
- 测试有SQL的图表情况，检查request属性属性request @default-request
- 测试无SQL的图表情况，检查styles属性属性styles @default-styles
- 测试无SQL的图表情况，检查events属性属性events @default-events

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
zenData('chart')->gen(0);
zenData('pivot')->gen(0);
zenData('screen')->gen(0);

// 登录用户
su('admin');

// 创建测试实例
$screenTest = new screenModelTest();

// 创建有SQL的图表对象
$chartWithSQL = new stdclass();
$chartWithSQL->sql = 'SELECT name, count FROM test_table';
$chartWithSQL->settings = '{"summary":"use"}';
$chartWithSQL->fields = '{"field1":"name","field2":"count"}';
$chartWithSQL->langs = '{}';
$chartWithSQL->filters = '{}';
$chartWithSQL->driver = 'mysql';

// 创建无SQL的图表对象
$chartWithoutSQL = new stdclass();
$chartWithoutSQL->sql = '';
$chartWithoutSQL->settings = '{}';
$chartWithoutSQL->fields = '{}';
$chartWithoutSQL->langs = '{}';
$chartWithoutSQL->filters = '{}';

// 创建组件对象
$component = new stdclass();
$component->chartConfig = new stdclass();
$component->chartConfig->package = 'Tables';

// 测试有SQL的图表情况
r($screenTest->getTableChartOptionTest($component, $chartWithSQL)) && p('styles') && e('default-styles'); // 测试有SQL的图表情况，检查styles属性
r($screenTest->getTableChartOptionTest($component, $chartWithSQL)) && p('status') && e('default-status'); // 测试有SQL的图表情况，检查status属性
r($screenTest->getTableChartOptionTest($component, $chartWithSQL)) && p('request') && e('default-request'); // 测试有SQL的图表情况，检查request属性

// 测试无SQL的图表情况
r($screenTest->getTableChartOptionTest($component, $chartWithoutSQL)) && p('styles') && e('default-styles'); // 测试无SQL的图表情况，检查styles属性
r($screenTest->getTableChartOptionTest($component, $chartWithoutSQL)) && p('events') && e('default-events'); // 测试无SQL的图表情况，检查events属性