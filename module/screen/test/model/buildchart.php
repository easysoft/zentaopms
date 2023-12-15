#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('product')->gen(5);
zdTable('project')->config('program')->gen(5);
zdTable('story')->config('story')->gen(20);
zdTable('bug')->config('bug')->gen(15);

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
$condition1 = $line && $line->option->dataset->dimensions[0] == 'product' && $line->option->dataset->source[0]->product == 'Mon';
$condition2 = $line && $line->option->dataset->dimensions[1] == 'data1' && $line->option->dataset->source[0]->{$line->option->dataset->dimensions[1]} == 120;
$condition3 = $line && $line->option->dataset->dimensions[2] == 'data2' && $line->option->dataset->source[6]->{$line->option->dataset->dimensions[2]} == 160;
r($condition1 && $condition2 && $condition3) && p('') && e('1');  //判断生成的折线图表数据是否正确。

isset($componentList['bar']) && $screen->buildChartTest($componentList['bar']);
$bar = $componentList['bar'] ?? null;
r($bar && $bar->option->dataset->dimensions[0] == '对象类型' && $bar->option->dataset->dimensions[1] == '创建' && $bar->option->dataset->dimensions[2] == '编辑') && p('') && e('1');  //判断生成的柱状图表数据是否正确。

isset($componentList['piecircle']) && $screen->buildChartTest($componentList['piecircle']);
$piecircle = $componentList['piecircle'] ?? null;
r($piecircle && $piecircle->option->dataset == '1') && p('') && e('1');  //判断生成的环形图表数据是否正确。

isset($componentList['pie']) && $screen->buildChartTest($componentList['pie']);
$pie = $componentList['pie'] ?? null;
$condition1 = $pie && $pie->option->dataset->dimensions[0] == 'status' && $pie->option->dataset->dimensions[1] == 'id';
$condition1 = $pie->option->dataset->source[0]->status = '进行中' && $pie->option->dataset->source[0]->id = '3';
$condition2 = $pie->option->dataset->source[1]->status = '未开始' && $pie->option->dataset->source[1]->id = '2';
r($pie && $pie->option->dataset->source[0]->id == '3') && p('') && e('1');  //判断生成的饼图表数据是否正确。

isset($componentList['radar']) && $screen->buildChartTest($componentList['radar']);
$radar = $componentList['radar'] ?? null;
$condition = $radar && $radar->option->dataset->radarIndicator[0]['name'] == '产品管理' && $radar->option->dataset->radarIndicator[4]['name'] == '其他';
r($condition) && p('') && e('1');  //判断生成的雷达图表数据是否正确。

isset($componentList['funnel']) && $screen->buildChartTest($componentList['funnel']);
$funnel = $componentList['funnel'] ?? null;
r($funnel && $funnel->option->dataset->dimensions[0] == '项目集') && p('') && e('0');  //目前并没有漏斗图，所以这里应该是false。

isset($componentList['table']) && $screen->buildChartTest($componentList['table']);
$table = $componentList['table'] ?? null;
$condition1 = $table && $table->option->header[0] == '一级项目集' && $table->option->header[8] == '任务数';
$condition2 = $table && $table->option->dataset[0][0] == '项目集1' && $table->option->dataset[0][3] == '4';
$condition3 = $table && $table->option->dataset[4][0] == '项目集5' && $table->option->dataset[4][3] == '0';
r($condition1 && $condition2 && $condition3) && p('') && e('1');  //判断生成的表格图表数据是否正确。

isset($componentList['baroption']) && $screen->buildChartTest($componentList['baroption']);
$baroption = $componentList['baroption'] ?? null;
$condition1 = $baroption && $baroption->option->dataset->dimensions[0] == 'topProgram' && $baroption->option->dataset->source[0]->topProgram === '项目集4';
$condition2 = $baroption && $baroption->option->dataset->dimensions[1] == '需求完成率(求和)' && $baroption->option->dataset->source[0]->{$baroption->option->dataset->dimensions[1]} === '0.00';
$condition3 = $baroption && $baroption->option->dataset->dimensions[2] == 'bug修复率(求和)'  && $baroption->option->dataset->source[1]->{$baroption->option->dataset->dimensions[2]} === '0.00';
r($condition1 && $condition2 && $condition3) && p('') && e('1');  //判断生成的柱状图表数据是否正确。
