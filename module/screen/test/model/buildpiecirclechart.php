#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 screenModel->buildPieCircleChart();
timeout=0
cid=1

- 判断生成的环形图表数据是否正确。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('product')->gen(5);
zenData('project')->loadYaml('program')->gen(5);
zenData('story')->loadYaml('story')->gen(20);
zenData('bug')->loadYaml('bug')->gen(15);

$screen = new screenTest();

global $tester;
$chart = $tester->dao->select('*')->from(TABLE_CHART)->where('id')->eq(1031)->fetch();

$component = json_decode($tester->config->screen->chartConfig['piecircle']);
$component->option = (object)array('dataset' => 0);

$component = $screen->buildPieCircleChart($component, $chart);

r($component->chartKey)                  && p('') && e('VPieCircle'); // 测试组件类型
r($component->option->dataset)           && p('') && e('1');           // 测试dataset数据
r(is_array($component->option->series))  && p('') && e('1');           // 测试series数据
r($component->option->series[0]->type)   && p('') && e('pie');         // 测试series数据
r($component->option->series[0]->radius) && p('') && e('70%');         // 测试series数据