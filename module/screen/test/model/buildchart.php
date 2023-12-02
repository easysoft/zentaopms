#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('project')->config('program')->gen(5);
zdTable('story')->gen(10);

/**
title=测试 screenModel->buildChart();
cid=1
pid=1

判断生成的卡片图表数据是否正确。 >> 1
判断生成的折线图表数据是否正确。 >> 1
判断生成的柱状图表数据是否正确。 >> 1
判断生成的环形图表数据是否正确。 >> 1
判断生成的饼图表数据是否正确。 >> 1
判断生成的雷达图表数据是否正确。 >> 1
目前并没有漏斗图，所以这里应该是false。 >> 0
判断生成的表格图表数据是否正确。 >> 1
判断生成的柱状图表数据是否正确。 >> 1
*/

$screen = new screenTest();

$components = $screen->getAllComponent();

global $tester;
$componentList = array();
foreach($components as $component)
{
    if(isset($component->sourceID) && $component->sourceID)
    {
        $chart = $tester->dao->select('*')->from(TABLE_CHART)->where('id')->eq($component->sourceID)->fetch();

        if(!isset($chart->type)) continue;
        if(isset($chart->settings) && isset($chart->sql))
        {
            if(!isset($componentList['card'])      && $chart->type == 'card')      $componentList['card']      = $component;
            if(!isset($componentList['line'])      && $chart->type == 'line')      $componentList['line']      = $component;
            if(!isset($componentList['bar'])       && $chart->type == 'bar')       $componentList['bar']       = $component;
            if(!isset($componentList['piecircle']) && $chart->type == 'piecircle') $componentList['piecircle'] = $component;
            if(!isset($componentList['pie'])       && $chart->type == 'pie')       $componentList['pie']       = $component;
            if(!isset($componentList['radar'])     && $chart->type == 'radar')     $componentList['radar']     = $component;
            if(!isset($componentList['funnel'])    && $chart->type == 'funnel')    $componentList['funnel']    = $component;
            if(!isset($componentList['table'])     && $chart->type == 'table')     $componentList['table']     = $component;
            if(!isset($componentList['baroption']) && in_array($chart->type, array('cluBarY', 'stackedBarY', 'cluBarX', 'stackedBar'))) $componentList['baroption'] = $component;
        }
    }
}

isset($componentList['card']) && $screen->buildChartTest($componentList['card']);
$card = $componentList['card'] ?? null;
r($card && $card->option->dataset == 5) && p('') && e('1');  //判断生成的卡片图表数据是否正确。

isset($componentList['line']) && $screen->buildChartTest($componentList['line']);
$line = $componentList['line'] ?? null;
r($line && $line->option->dataset->dimensions[0] == '月份') && p('') && e('1');  //判断生成的折线图表数据是否正确。

isset($componentList['bar']) && $screen->buildChartTest($componentList['bar']);
$bar = $componentList['bar'] ?? null;
r($bar && $bar->option->dataset->dimensions[0] == '项目集') && p('') && e('1');  //判断生成的柱状图表数据是否正确。

isset($componentList['piecircle']) && $screen->buildChartTest($componentList['piecircle']);
$piecircle = $componentList['piecircle'] ?? null;
r($piecircle && $piecircle->option->dataset == '0.25') && p('') && e('1');  //判断生成的环形图表数据是否正确。

isset($componentList['pie']) && $screen->buildChartTest($componentList['pie']);
$pie = $componentList['pie'] ?? null;
r($pie && $pie->option->dataset->source[0]['id'] == '3') && p('') && e('1');  //判断生成的饼图表数据是否正确。

isset($componentList['radar']) && $screen->buildChartTest($componentList['radar']);
$radar = $componentList['radar'] ?? null;
r($radar && count($radar->option->dataset->radarIndicator) == '5') && p('') && e('1');  //判断生成的雷达图表数据是否正确。

isset($componentList['funnel']) && $screen->buildChartTest($componentList['funnel']);
$funnel = $componentList['funnel'] ?? null;
r($funnel && $funnel->option->dataset->dimensions[0] == '项目集') && p('') && e('0');  //目前并没有漏斗图，所以这里应该是false。

isset($componentList['table']) && $screen->buildChartTest($componentList['table']);
$table = $componentList['table'] ?? null;
r($table && count($table->option->dataset) == '5') && p('') && e('1');  //判断生成的表格图表数据是否正确。

isset($componentList['baroption']) && $screen->buildChartTest($componentList['baroption']);
$baroption = $componentList['baroption'] ?? null;
r($baroption && $baroption->option->dataset->dimensions[0] == 'name') && p('') && e('1');  //判断生成的柱状图表数据是否正确。
