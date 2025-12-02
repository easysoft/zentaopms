#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWaterfallProgressBlock();
timeout=0
cid=15312

- 执行blockTest模块的printWaterfallProgressBlockTest方法，参数是1
 - 属性hasCharts @1
 - 属性hasPV @1
 - 属性hasEV @1
 - 属性hasAC @1
 - 属性pvCount @5
 - 属性evCount @5
 - 属性acCount @5
- 执行blockTest模块的printWaterfallProgressBlockTest方法，参数是2
 - 属性hasCharts @1
 - 属性hasPV @1
 - 属性hasEV @1
 - 属性hasAC @1
 - 属性pvCount @3
 - 属性evCount @3
 - 属性acCount @3
- 执行blockTest模块的printWaterfallProgressBlockTest方法，参数是3
 - 属性hasCharts @1
 - 属性hasPV @1
 - 属性hasEV @1
 - 属性hasAC @1
 - 属性pvCount @2
 - 属性evCount @2
 - 属性acCount @2
- 执行blockTest模块的printWaterfallProgressBlockTest方法，参数是4
 - 属性hasCharts @1
 - 属性hasPV @1
 - 属性hasEV @1
 - 属性hasAC @1
 - 属性pvCount @0
 - 属性evCount @0
 - 属性acCount @0
- 执行blockTest模块的printWaterfallProgressBlockTest方法，参数是5
 - 属性hasCharts @1
 - 属性hasPV @1
 - 属性hasEV @1
 - 属性hasAC @1
 - 属性pvCount @0
 - 属性evCount @0
 - 属性acCount @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('Project1,Project2,Project3,Project4,Project5,Project6,Project7,Project8,Project9,Project10');
$project->type->range('project');
$project->model->range('waterfall');
$project->status->range('doing');
$project->gen(10);

$dao = $tester->app->loadClass('dao');
$dao->delete()->from(TABLE_WEEKLYREPORT)->exec();

$weeklydata = array(
    array('project' => 1, 'weekStart' => '2024-01-01', 'pv' => 100.50, 'ev' => 80.00, 'ac' => 90.00, 'sv' => 10.00, 'cv' => -10.00, 'staff' => 5, 'progress' => '', 'workload' => ''),
    array('project' => 1, 'weekStart' => '2024-01-08', 'pv' => 150.00, 'ev' => 120.00, 'ac' => 140.00, 'sv' => -5.00, 'cv' => 5.00, 'staff' => 6, 'progress' => '', 'workload' => ''),
    array('project' => 1, 'weekStart' => '2024-01-15', 'pv' => 200.00, 'ev' => 180.00, 'ac' => 190.00, 'sv' => 20.00, 'cv' => -20.00, 'staff' => 7, 'progress' => '', 'workload' => ''),
    array('project' => 1, 'weekStart' => '2024-01-22', 'pv' => 250.00, 'ev' => 220.00, 'ac' => 240.00, 'sv' => -8.00, 'cv' => 8.00, 'staff' => 8, 'progress' => '', 'workload' => ''),
    array('project' => 1, 'weekStart' => '2024-01-29', 'pv' => 300.00, 'ev' => 280.00, 'ac' => 290.00, 'sv' => 15.00, 'cv' => -15.00, 'staff' => 9, 'progress' => '', 'workload' => ''),
    array('project' => 2, 'weekStart' => '2024-02-05', 'pv' => 350.00, 'ev' => 320.00, 'ac' => 340.00, 'sv' => -3.00, 'cv' => 3.00, 'staff' => 10, 'progress' => '', 'workload' => ''),
    array('project' => 2, 'weekStart' => '2024-02-12', 'pv' => 400.00, 'ev' => 380.00, 'ac' => 390.00, 'sv' => 25.00, 'cv' => -25.00, 'staff' => 11, 'progress' => '', 'workload' => ''),
    array('project' => 2, 'weekStart' => '2024-02-19', 'pv' => 450.00, 'ev' => 420.00, 'ac' => 440.00, 'sv' => -10.00, 'cv' => 10.00, 'staff' => 12, 'progress' => '', 'workload' => ''),
    array('project' => 3, 'weekStart' => '2024-02-26', 'pv' => 500.00, 'ev' => 480.00, 'ac' => 490.00, 'sv' => 30.00, 'cv' => -30.00, 'staff' => 13, 'progress' => '', 'workload' => ''),
    array('project' => 3, 'weekStart' => '2024-03-04', 'pv' => 550.00, 'ev' => 520.00, 'ac' => 540.00, 'sv' => -12.00, 'cv' => 12.00, 'staff' => 14, 'progress' => '', 'workload' => ''),
);

foreach($weeklydata as $data)
{
    $dao->insert(TABLE_WEEKLYREPORT)->data($data)->exec();
}

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printWaterfallProgressBlockTest(1)) && p('hasCharts,hasPV,hasEV,hasAC,pvCount,evCount,acCount') && e('1,1,1,1,5,5,5');
r($blockTest->printWaterfallProgressBlockTest(2)) && p('hasCharts,hasPV,hasEV,hasAC,pvCount,evCount,acCount') && e('1,1,1,1,3,3,3');
r($blockTest->printWaterfallProgressBlockTest(3)) && p('hasCharts,hasPV,hasEV,hasAC,pvCount,evCount,acCount') && e('1,1,1,1,2,2,2');
r($blockTest->printWaterfallProgressBlockTest(4)) && p('hasCharts,hasPV,hasEV,hasAC,pvCount,evCount,acCount') && e('1,1,1,1,0,0,0');
r($blockTest->printWaterfallProgressBlockTest(5)) && p('hasCharts,hasPV,hasEV,hasAC,pvCount,evCount,acCount') && e('1,1,1,1,0,0,0');