#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 screenModel->getchartoption();
timeout=0
cid=0

- 测试type为pie的图表是否显示正确，生成的指标项是否正确和数据项是否正确。 @1
- 测试type为pie的图表是否显示正确，生成的数据项是否正确。 @1
- 测试饼图类型。 @pie
- 测试饼图半径。 @70%
- 测试饼图内圈和外圈。
 -  @50%
 - 属性1 @60%

*/

$screen = new screenModelTest();

$component = new stdclass();
$component->option = new stdclass();
$component->option->dataset = new stdclass();
$component->option->series = array((object)array('type' => 'pie', 'radius' => '70%', 'center' => array('50%', '60%')));

$chart = new stdclass();
$chart->sql = "SELECT '延期完成项目' AS completeStatus, 1 AS count";
$chart->settings = json_encode(array(array(
    'group'  => array(array('field' => 'completeStatus')),
    'metric' => array(array('field' => 'count'))
)));

$result  = $screen->getPieChartOptionTest($component, $chart, array());
$dataset = $result->option->dataset ?? null;
$series  = $result->option->series ?? null;
$pieItem = $series[0] ?? null;

r($dataset && $dataset->dimensions[0] == 'completeStatus' && $dataset->source[0]->completeStatus == 'Active') && p('') && e(1);
r($pieItem && $pieItem->type == 'pie' && $pieItem->radius == '70%' && $pieItem->center[0] == '50%' && $pieItem->center[1] == '60%') && p('') && e(1);
r($pieItem->type)   && p('') && e('pie');
r($pieItem->radius) && p('') && e('70%');
r($pieItem->center) && p('0,1') && e('50%,60%');
