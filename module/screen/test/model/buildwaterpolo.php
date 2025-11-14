#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildWaterPolo()。
timeout=0
cid=18220

- 测试key @WaterPolo
- 测试chartKey，conKey以及标题。
 - 属性chartKey @VWaterPolo
 - 属性conKey @VCWaterPolo
 - 属性title @Bug密度
- 判断生成的水球图数据是否正确。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('product')->gen(5);
zenData('project')->loadYaml('program')->gen(5);
zenData('story')->loadYaml('story_waterpolo')->gen(20);
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
            if(!isset($componentList['waterpolo']) && $chart->type == 'waterpolo')
            {
                $componentList['waterpolo'] = $component;
                break;
            }
        }
    }
}

isset($componentList['waterpolo']) && $screen->buildWaterPolo($componentList['waterpolo'], $chart);
$waterpolo = $componentList['waterpolo'] ?? null;
r($waterpolo->key) && p('') && e('WaterPolo'); // 测试key
r($waterpolo->chartConfig) && p('chartKey,conKey,title') && e('VWaterPolo,VCWaterPolo,Bug密度'); // 测试chartKey，conKey以及标题。
r($waterpolo && round($waterpolo->option->dataset, 3) == '0.176') && p('') && e('1');  //判断生成的水球图数据是否正确。