#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';
su('admin');

zdTable('project')->gen(50);
zdTable('story')->gen(20);
zdTable('bug')->gen(20);

/**

title=测试 screenModel->getBarChartOption()。
timeout=0
cid=1

- 测试type为cluBarY的图表是否显示正确，生成的指标项和数据项是否正确。 @1
- 测试type为stackedBarY的图表是否显示正确，由于目前系统里没有这种类型的图表，故不作展示。 @1
- 测试type为stackedBar的图表是否显示正确，生成的指标项和数据项是否正确。 @1
- 测试type为bar的图表是否显示正确，由于目前系统里没有这种类型的图表，故不作展示。 @1

*/

$screen = new screenTest();

function getComponetAndChart($screen, $filters = array())
{
    global $tester;
    $componets = $screen->getAllComponent($filters);
    foreach($componets as $componet)
    {
        if(!isset($componet->sourceID)) continue;
        $type  = $componet->chartConfig->package == 'Tables' ? 'pivot' : 'chart';
        $table = $type == 'chart' ? TABLE_CHART : TABLE_PIVOT;
        $chart = $tester->dao->select('*')->from($table)->where('id')->eq($componet->sourceID)->fetch();
        if($chart) return array($componet, $chart);
    }
    return array(null, null);
}

$filter2  = array('type' => 'cluBarY');
$filter3  = array('type' => 'stackedBarY');
$filter4  = array('type' => 'cluBarX');
$filter5  = array('type' => 'stackedBar');
$filter6  = array('type' => 'bar');

list($component2, $chart2) = getComponetAndChart($screen, $filter2);
$screen->getChartOptionTest($chart2, $component2);
r(isset($component2) && $component2->option->dataset && $component2->option->dataset->dimensions[0] == 'name' && count($component2->option->dataset->source) == 5) && p('') && e('1');  //测试type为cluBarY的图表是否显示正确，生成的指标项和数据项是否正确。

list($component3, $chart3) = getComponetAndChart($screen, $filter3);
r(is_null($component3) || is_null($chart3)) && p('') && e(1);  //测试type为stackedBarY的图表是否显示正确，由于目前系统里没有这种类型的图表，故不作展示。

list($component4, $chart4) = getComponetAndChart($screen, $filter4);
$screen->getChartOptionTest($chart4, $component4);
r(
    isset($component4->option->dataset->dimensions[0])
    && $component4->option->dataset->dimensions[0] == 'project'
    && count($component4->option->dataset->source) == 5
) && p('') && e(1);  //测试type为cluBarX的图表是否显示正确，生成的指标项和数据项是否正确。

list($component5, $chart5) = getComponetAndChart($screen, $filter5);
$screen->getChartOptionTest($chart5, $component5);
$dataset = isset($component5) && $component5->option->dataset ? $component5->option->dataset : null;
r($dataset && $dataset->dimensions[0] == '年份' && count($dataset->source) == 1) && p('') && e(1);  // 测试type为stackedBar的图表是否显示正确，生成的指标项和数据项是否正确。

list($component6, $chart6) = getComponetAndChart($screen, $filter6);
r($component6 && $chart6) && p('') && e(1);  //测试type为bar的图表是否显示正确，由于目前系统里没有这种类型的图表，故不作展示。
