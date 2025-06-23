#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildRadarChart();
timeout=0
cid=1

- 测试组件的key @Radar
- 测试series的name和type
 - 属性name @radar
 - 属性type @radar
- 测试雷达图的shape属性shape @polygon
- 测试雷达图存在数据 @1

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
            if(!isset($componentList['radar']) && $chart->type == 'radar')
            {
                $componentList['radar'] = $component;
                break;
            }
        }
    }
}

isset($componentList['radar']) && $screen->buildRadarChart($componentList['radar'], $chart);
$radar = $componentList['radar'] ?? null;

r($radar->chartConfig->key) && p('') && e('Radar');                 // 测试组件的key
r($radar->option->series[0]) && p('name,type') && e('radar,radar'); // 测试series的name和type
r($radar->option->radar) && p('shape') && e('polygon');             // 测试雷达图的shape
r(isset($radar->option->radar->indicator)) && p('') && e('1');      // 测试雷达图存在数据
