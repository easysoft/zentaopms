#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';
su('admin');

zdTable('project')->gen(50);
zdTable('story')->gen(20);
zdTable('bug')->gen(20);

/**
title=测试 screenModel->getchartoption();
cid=1
pid=1

测试获取折线图配置中dimensions是否正确，值为年份。                                      >> 1
测试type为cluBarY的图表是否显示正确，生成的指标项和数据项是否正确。                     >> 1
测试type为stackedBarY的图表是否显示正确，由于目前系统里没有这种类型的图表，故不作展示。 >> 1
测试type为cluBarX的图表是否显示正确，生成的指标项和数据项是否正确。                     >> 1
测试type为stackedBar的图表是否显示正确，生成的指标项和数据项是否正确。                  >> 1
测试type为bar的图表是否显示正确，由于目前系统里没有这种类型的图表，故不作展示。         >> 0
测试type为piecircle的图表是否显示正确，生成的指标项和数据项是否正确。                   >> 1
测试type为pie的图表是否显示正确，生成的指标项和数据项是否正确。                         >> 1
测试type为table的图表是否显示正确，生成的header指标项数量是否正确。                     >> 1
测试type为table的图表是否显示正确，生成的dataset数据项数量是否正确。                    >> 1
测试配置错误的水球图是否能正常生成，此为默认配置。                                      >> 1
测试配置正确的水球图是否能正常生成，生成的比例为0.000。                                 >> 1
测试type为radar的图表是否显示正确，由于目前系统里没有这种类型的图表，故不作展示。       >> 0
测试type为card的图表是否显示正确，生成的指标项和数据项是否正确。                        >> 1

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

$filter1  = array('type' => 'line');
$filter2  = array('type' => 'cluBarY');
$filter3  = array('type' => 'stackedBarY');
$filter4  = array('type' => 'cluBarX');
$filter5  = array('type' => 'stackedBar');
$filter6  = array('type' => 'bar');
$filter7  = array('type' => 'piecircle');
$filter8  = array('type' => 'pie');
$filter9  = array('type' => 'table');
$filter10 = array('type' => 'waterpolo');
$filter11 = array('type' => 'radar');
$filter12 = array('type' => 'card');

list($component1, $chart1) = getComponetAndChart($screen, $filter1);
$screen->getChartOptionTest($chart1, $component1);
$dimension_0 = $component1->option->dataset->dimensions[0] ?? null;
$dimension_1 = $component1->option->dataset->dimensions[1] ?? null;
$year = date('Y');
$source_0 = $component1->option->dataset->source[0];
r($dimension_0 && $dimension_0 == 'year') && p('') && e('1');                                                                                       //测试获取折线图配置中dimensions是否正确，值为年份。

list($component2, $chart2) = getComponetAndChart($screen, $filter2);
$screen->getChartOptionTest($chart2, $component2);
r( isset($component2) && $component2->option->dataset && $component2->option->dataset->dimensions[0] == 'name' && count($component2->option->dataset->source) == 5) && p('') && e('1');     //测试type为cluBarY的图表是否显示正确，生成的指标项和数据项是否正确。

list($component3, $chart3) = getComponetAndChart($screen, $filter3);
r(is_null($component3) || is_null($chart3)) && p('') && e(1);                                                      //测试type为stackedBarY的图表是否显示正确，由于目前系统里没有这种类型的图表，故不作展示。

list($component4, $chart4) = getComponetAndChart($screen, $filter4);
$screen->getChartOptionTest($chart4, $component4);
r(
    isset($component4->option->dataset->dimensions[0])
    && $component4->option->dataset->dimensions[0] == 'project'
    && count($component4->option->dataset->source) == 5
) && p('') && e(1);                                                                                //测试type为cluBarX的图表是否显示正确，生成的指标项和数据项是否正确。

list($component5, $chart5) = getComponetAndChart($screen, $filter5);
$screen->getChartOptionTest($chart5, $component5);
$dataset = isset($component5) && $component5->option->dataset ? $component5->option->dataset : null;
r($dataset && $dataset->dimensions[0] == '年份' && count($dataset->source) == 1) && p('') && e(1); // 测试type为stackedBar的图表是否显示正确，生成的指标项和数据项是否正确。

list($component6, $chart6) = getComponetAndChart($screen, $filter6);
r($component6 && $chart6) && p('') && e(1);                                                        //测试type为bar的图表是否显示正确，由于目前系统里没有这种类型的图表，故不作展示。

list($component7, $chart7) = getComponetAndChart($screen, $filter7);
$screen->getChartOptionTest($chart7, $component7);
r(
    isset($component7->option->series[0]->data[0]->value[0]) 
    && isset($component7->option->dataset)
    && $component7->option->dataset === $component7->option->series[0]->data[0]->value[0]
) && p('') && e(1);                                                                                // 测试type为piecircle的图表是否显示正确，生成的指标项和数据项是否正确。

list($component8, $chart8) = getComponetAndChart($screen, $filter8);
$screen->getChartOptionTest($chart8, $component8);
$dataset = $component8->option->dataset ?? null;
r($dataset->dimensions[0] == 'completeStatus' && $dataset->source[0]->completeStatus == '延期完成项目') && p('') && e(0);               //测试type为pie的图表是否显示正确，生成的指标项和数据项是否正确。

list($component9, $chart9) = getComponetAndChart($screen, $filter9);
$screen->getChartOptionTest($chart9, $component9);
$option = $component9->option;
r(isset($option->header[0])  && count($option->header[0]) == 10) && p('') && e(1);                 //测试type为table的图表是否显示正确，生成的header指标项数量是否正确。
r(isset($option->dataset[0]) && count($option->dataset[0]) == 10) && p('') && e(1);                //测试type为table的图表是否显示正确，生成的dataset数据项数量是否正确。

list($component10, $chart10) = getComponetAndChart($screen, $filter10);
$screen->getChartOptionTest($chart10, $component10);
r(is_float($component10->option->dataset)) && p('') && e('1');                                     //测试配置错误的水球图是否能正常生成，此为默认配置。

$component11_all = array_filter($screen->componentList, function($item){ return isset($item->type) && $item->type == 'waterpolo' && $item->id != '58c9hdcwi5s000'; });
$component11 = current($component11_all);
$chart11 = $tester->dao->select('*')->from(TABLE_CHART)->where('id')->eq($component11->sourceID)->fetch();
$screen->getChartOptionTest($chart11, $component11);
r($component11->option->dataset == "0.000") && p('') && e(1);                                       //测试配置正确的水球图是否能正常生成，生成的比例为0.000。

list($component12, $chart12) = getComponetAndChart($screen, $filter11);
r($component12 && $chart12) && p('') && e(1);                                                        //测试type为radar的图表是否显示正确，由于目前系统里没有这种类型的图表，故不作展示。

list($component13, $chart13) = getComponetAndChart($screen, $filter12); 
$screen->getChartOptionTest($chart13, $component13);
r($component13->option->dataset === '0') && p('') && e(1);                                           //测试type为card的图表是否显示正确，生成的指标项和数据项是否正确。
