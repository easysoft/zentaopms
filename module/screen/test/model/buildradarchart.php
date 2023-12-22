#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildRadarChart();
timeout=0
cid=1

- 判断生成的雷达图表数据是否正确。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('product')->gen(5);
zdTable('project')->config('program')->gen(5);
zdTable('story')->config('story')->gen(20);
zdTable('bug')->config('bug')->gen(15);

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
            if(!isset($componentList['radar'])     && $chart->type == 'radar')
            {
                $componentList['radar']     = $component;
                break;
            }
        }
    }
}

isset($componentList['radar']) && $screen->buildRadarChart($componentList['radar'], $chart);
$radar = $componentList['radar'] ?? null;
$condition = $radar && $radar->option->dataset->radarIndicator[0]['name'] == '产品管理' && $radar->option->dataset->radarIndicator[4]['name'] == '其他';
r($condition) && p('') && e('1');  //判断生成的雷达图表数据是否正确。
