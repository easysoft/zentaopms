#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printExecutionStatisticBlock();
timeout=0
cid=15263

- 执行blockTest模块的printExecutionStatisticBlockTest方法，参数是$block1
 - 属性executionsCount @0
 - 属性projectsCount @3
 - 属性hasChartData @1
- 执行blockTest模块的printExecutionStatisticBlockTest方法，参数是$block2
 - 属性executionsCount @0
 - 属性projectsCount @3
 - 属性hasChartData @1
- 执行blockTest模块的printExecutionStatisticBlockTest方法，参数是$block3
 - 属性executionsCount @0
 - 属性projectsCount @3
 - 属性hasChartData @1
- 执行blockTest模块的printExecutionStatisticBlockTest方法，参数是$block4
 - 属性executionsCount @0
 - 属性projectsCount @3
 - 属性hasChartData @1
 - 属性currentProjectID @0
- 执行blockTest模块的printExecutionStatisticBlockTest方法，参数是$block5
 - 属性executionsCount @0
 - 属性projectsCount @3
 - 属性hasChartData @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(20);
zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('projectproduct')->loadYaml('projectproduct', false, 2)->gen(10);

su('admin');

$blockTest = new blockZenTest();

$block1 = new stdClass();
$block1->id = 1;
$block1->dashboard = 'my';
$block1->params = new stdClass();
$block1->params->type = 'undone';
$block1->params->count = 5;

$block2 = new stdClass();
$block2->id = 2;
$block2->dashboard = 'my';
$block2->params = new stdClass();
$block2->params->type = 'done';
$block2->params->count = 5;

$block3 = new stdClass();
$block3->id = 3;
$block3->dashboard = 'my';
$block3->params = new stdClass();
$block3->params->type = 'all';
$block3->params->count = 10;

$block4 = new stdClass();
$block4->id = 4;
$block4->dashboard = 'project';
$block4->params = new stdClass();
$block4->params->type = 'undone';
$block4->params->count = 5;

$block5 = new stdClass();
$block5->id = 5;
$block5->dashboard = 'my';
$block5->params = new stdClass();
$block5->params->type = 'undone';
$block5->params->count = 0;

r($blockTest->printExecutionStatisticBlockTest($block1)) && p('executionsCount,projectsCount,hasChartData') && e('0,3,1');
r($blockTest->printExecutionStatisticBlockTest($block2)) && p('executionsCount,projectsCount,hasChartData') && e('0,3,1');
r($blockTest->printExecutionStatisticBlockTest($block3)) && p('executionsCount,projectsCount,hasChartData') && e('0,3,1');
r($blockTest->printExecutionStatisticBlockTest($block4)) && p('executionsCount,projectsCount,hasChartData,currentProjectID') && e('0,3,1,0');
r($blockTest->printExecutionStatisticBlockTest($block5)) && p('executionsCount,projectsCount,hasChartData') && e('0,3,1');