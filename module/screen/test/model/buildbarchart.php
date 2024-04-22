#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildBarChart();
timeout=0
cid=1

- 判断生成的柱状图表数据是否正确。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('product')->gen(5);
zenData('project')->loadYaml('program')->gen(5);
zenData('story')->loadYaml('story')->gen(20);
zenData('bug')->loadYaml('bug')->gen(15);

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
            if(!isset($componentList['bar']) && $chart->type == 'bar')
            {
                $componentList['bar'] = $component;
                break;
            }
        }
    }
}

isset($componentList['bar']) && $screen->buildBarChart($componentList['bar'], $chart);
$bar = $componentList['bar'] ?? null;
r($bar && $bar->option->dataset->dimensions[0] == '对象类型' && $bar->option->dataset->dimensions[1] == '创建' && $bar->option->dataset->dimensions[2] == '编辑') && p('') && e('1');  //判断生成的柱状图表数据是否正确。