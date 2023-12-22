#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildLineChart();
timeout=0
cid=1

- 判断生成的折线图表数据是否正确。 @1

*/


include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('product')->gen(5);
zdTable('project')->config('program')->gen(5);
zdTable('story')->config('story')->gen(20);
zdTable('bug')->config('bug')->gen(15);

$screen = new screenTest();

$components = $screen->getAllComponent();

$componentList = array();
foreach($components as $component)
{
    if(isset($component->sourceID) && $component->sourceID)
    {
        $chart = $tester->dao->select('*')->from(TABLE_CHART)->where('id')->eq($component->sourceID)->fetch();

        if(!isset($chart->type)) continue;
        if(isset($chart->settings) && isset($chart->sql))
        {
            if(!isset($componentList['line']) && $chart->type == 'line')
            {
                $componentList['line']      = $component;
                break;
            }
        }
    }
}

isset($componentList['line']) && $screen->buildLineChart($componentList['line'], $chart);
$line = $componentList['line'] ?? null;
$condition1 = $line && $line->option->dataset->dimensions[0] == 'product' && $line->option->dataset->source[0]->product == 'Mon';
$condition2 = $line && $line->option->dataset->dimensions[1] == 'data1' && $line->option->dataset->source[0]->{$line->option->dataset->dimensions[1]} == 120;
$condition3 = $line && $line->option->dataset->dimensions[2] == 'data2' && $line->option->dataset->source[6]->{$line->option->dataset->dimensions[2]} == 160;
r($condition1 && $condition2 && $condition3) && p('') && e('1');  //判断生成的折线图表数据是否正确。
