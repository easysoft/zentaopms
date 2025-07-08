#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('product')->gen(5);
zenData('project')->loadYaml('program')->gen(5);
zenData('story')->loadYaml('story')->gen(20);
zenData('bug')->loadYaml('bug')->gen(15);

/**

title=测试 screenModel->buildChart();
timeout=0
cid=1

- 检查组件是否都被修改。
 - 属性card @1
 - 属性line @1
 - 属性piecircle @1
 - 属性pie @1
 - 属性table @1
 - 属性baroption @1

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

$checkList = array();
foreach($componentList as $type => $component)
{
    $clone_componet = clone $component;
    $screen->buildChartTest($clone_componet);
    $checkList[$type] = serialize($clone_componet) != serialize($component);
}

r($checkList) && p('card,line,piecircle,pie,table,baroption') && e('1,1,1,1,1,1');     //检查组件是否都被修改。