#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildPieChart();
timeout=0
cid=1

- 检查生成的饼图表头信息是否正确
 -  @状态
 - 属性1 @id
- 检查生成的饼图数据是否正确
 - 第0条的状态属性 @未设置
 - 第0条的id属性 @8
 - 第1条的状态属性 @未开始
 - 第1条的id属性 @4
 - 第2条的状态属性 @进行中
 - 第2条的id属性 @4
 - 第3条的状态属性 @已完成
 - 第3条的id属性 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('action')->loadYaml('action_for_pie')->gen(20);
zenData('task')->gen(10);
zenData('user')->gen(10);

$screen = new screenTest();

global $tester;
$chart = $tester->dao->select('*')->from(TABLE_CHART)->where('id')->eq(1010)->fetch();

$component = json_decode($tester->config->screen->chartConfig['pie']);
$component->option = new stdClass();
$component->option->dataset = new stdClass();

$component = $screen->buildPieChart($component, $chart);

r($component->chartKey)                              && p('') && e('VPieCommon'); // 测试组件类型
r(isset($component->option->dataset->dimensions))    && p('') && e('1');          // 判断dimensions存在
r(isset($component->option->dataset->source))        && p('') && e('1');          // 判断source存在
r(is_array($component->option->dataset->dimensions)) && p('') && e('1');          // 判断dimensions是数组
r(is_array($component->option->dataset->source))     && p('') && e('1');          // 判断source是数组