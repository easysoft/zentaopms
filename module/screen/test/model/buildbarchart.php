#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildBarChart();
timeout=0
cid=1

- 判断生成的柱状图表数据是否正确。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('product')->gen(5);
zenData('project')->loadYaml('program')->gen(5);
zenData('story')->loadYaml('story')->gen(20);
zenData('bug')->loadYaml('bug')->gen(15);

$screen = new screenTest();

global $tester;
$chart = $tester->dao->select('*')->from(TABLE_CHART)->where('id')->eq(1015)->fetch();

$component = json_decode($tester->config->screen->chartConfig['cluBarY']);
$component->option = new stdClass();
$component->option->dataset = new stdClass();

$component = $screen->buildBarChart($component, $chart);

r($component->chartKey)                              && p('') && e('VBarCrossrange'); // 测试组件类型
r(isset($component->option->dataset->dimensions))    && p('') && e('1');              // 判断dimensions存在
r(isset($component->option->dataset->source))        && p('') && e('1');              // 判断source存在
r(is_array($component->option->dataset->dimensions)) && p('') && e('1');              // 判断dimensions是数组
r(is_array($component->option->dataset->source))     && p('') && e('1');              // 判断source是数组